<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use App\Notifications\AvisoComunidad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    /**
     * Listado de anuncios con orden personalizado.
     */
    public function index()
    {
        $announcements = Announcement::orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Guarda el anuncio en Supabase/S3.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360',
            'cropped_image' => 'nullable|string', 
            'is_active' => 'sometimes|boolean',
            'order' => 'required|integer|min:0'
        ], [
            'image.max' => 'La imagen es muy pesada. Máximo 15MB.',
        ]);

        try {
            $validated['modal_id'] = 'modal_' . Str::random(8);
            $disk = config('filesystems.default'); // Usa S3 (Supabase)

            // Manejo de imagen (Cropped o Normal)
            if ($request->filled('cropped_image')) {
                $imageData = $request->cropped_image;
                $extension = 'jpg';
                
                if (str_contains($imageData, 'data:image/png')) $extension = 'png';
                elseif (str_contains($imageData, 'data:image/webp')) $extension = 'webp';
                
                $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageData = str_replace(' ', '+', $imageData);
                $imagePath = 'announcements/' . time() . '_ann.' . $extension;

                // Guardar en la nube con visibilidad pública
                Storage::disk($disk)->put($imagePath, base64_decode($imageData), 'public');
                $validated['image'] = $imagePath;

            } elseif ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('announcements', $imageName, $disk);
                $validated['image'] = $imagePath;
            }

            $validated['is_active'] = $request->has('is_active');
            $announcement = Announcement::create($validated);

            // Notificación Push
            if ($announcement->is_active) {
                $users = User::where('notify_announcements', true)->get();
                if ($users->isNotEmpty()) {
                    try {
                        Notification::send($users, new AvisoComunidad(
                            'Nuevo Aviso Parroquial',
                            $announcement->title,
                            route('home') 
                        ));
                    } catch (\Exception $e) { Log::warning("Fallo notificación: " . $e->getMessage()); }
                }
            }

            return redirect()->route('admin.announcements.index')->with('success', 'Anuncio publicado en la nube');

        } catch (\Exception $e) {
            Log::error('Error store:', ['err' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Actualiza y limpia archivos antiguos de la nube.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'full_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cropped_image' => 'nullable|string', 
            'is_active' => 'sometimes|boolean',
            'order' => 'required|integer|min:0'
        ]);

        try {
            $disk = config('filesystems.default');
            $oldPath = $announcement->getRawImagePath();

            if ($request->filled('cropped_image')) {
                if ($oldPath && Storage::disk($disk)->exists($oldPath)) Storage::disk($disk)->delete($oldPath);
                
                $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $request->cropped_image);
                $imagePath = 'announcements/' . time() . '_upd.jpg';
                Storage::disk($disk)->put($imagePath, base64_decode($imageData), 'public');
                $validated['image'] = $imagePath;

            } elseif ($request->hasFile('image')) {
                if ($oldPath && Storage::disk($disk)->exists($oldPath)) Storage::disk($disk)->delete($oldPath);
                $validated['image'] = $request->file('image')->storeAs('announcements', time().'_upd.'.$request->file('image')->extension(), $disk);
            } else {
                $validated['image'] = $oldPath;
            }

            $validated['is_active'] = $request->has('is_active');
            $announcement->update($validated);

            return redirect()->route('admin.announcements.index')->with('success', 'Actualizado correctamente');
        } catch (\Exception $e) { return back()->with('error', 'Error: ' . $e->getMessage()); }
    }

    /**
     * Borrado físico de la nube.
     */
    public function destroy(Announcement $announcement)
    {
        try {
            $disk = config('filesystems.default');
            $path = $announcement->getRawImagePath();
            if ($path && Storage::disk($disk)->exists($path)) Storage::disk($disk)->delete($path);
            $announcement->delete();
            return redirect()->route('admin.announcements.index')->with('success', 'Eliminado');
        } catch (\Exception $e) { return back()->with('error', 'No se pudo eliminar'); }
    }

    /**
     * Limpieza masiva.
     */
    public function deleteAll()
    {
        try {
            $disk = config('filesystems.default');
            $announcements = Announcement::all();
            foreach ($announcements as $ann) {
                $path = $ann->getRawImagePath();
                if ($path && Storage::disk($disk)->exists($path)) Storage::disk($disk)->delete($path);
            }
            Announcement::query()->delete();
            return redirect()->route('admin.announcements.index')->with('success', "Todo borrado");
        } catch (\Exception $e) { return back()->with('error', 'Error masivo'); }
    }

    public function toggleStatus(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);
        return back()->with('success', 'Estado cambiado');
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->announcements as $item) {
            Announcement::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        return response()->json(['success' => true]);
    }

    /**
     * Acciones por lote (bulk).
     */
    public function bulkActions(Request $request)
    {
        $disk = config('filesystems.default');
        $announcements = Announcement::whereIn('id', $request->ids);

        if ($request->action === 'delete') {
            foreach ($announcements->get() as $ann) {
                $path = $ann->getRawImagePath();
                if ($path && Storage::disk($disk)->exists($path)) Storage::disk($disk)->delete($path);
            }
            $announcements->delete();
        } else {
            $announcements->update(['is_active' => ($request->action === 'activate')]);
        }
        return back()->with('success', 'Operación masiva completada');
    }
}