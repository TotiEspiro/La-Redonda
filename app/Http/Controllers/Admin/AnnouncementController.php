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
     * Lista todos los anuncios para el administrador.
     */
    public function index()
    {
        $announcements = Announcement::orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Guarda un nuevo anuncio en la nube (Supabase/S3).
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
            'image.max' => 'La imagen es muy pesada. Intenta con una de menos de 15MB.',
        ]);

        try {
            $validated['modal_id'] = 'modal_' . Str::random(8);
            $disk = config('filesystems.default'); // Usará 's3' (Supabase) automáticamente

            // 1. Manejo de imagen (Prioriza la recortada/cropped)
            if ($request->filled('cropped_image')) {
                $imageData = $request->cropped_image;
                $extension = 'jpg';
                
                // Detectar formato Base64
                if (str_contains($imageData, 'data:image/png')) $extension = 'png';
                elseif (str_contains($imageData, 'data:image/webp')) $extension = 'webp';
                
                $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageData = str_replace(' ', '+', $imageData);
                $imageName = time() . '_announcement.' . $extension;
                $imagePath = 'announcements/' . $imageName;

                // Guardar directamente en el disco configurado (Supabase)
                Storage::disk($disk)->put($imagePath, base64_decode($imageData), 'public');
                $validated['image'] = $imagePath;

            } elseif ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
                
                // storeAs ya usa el disco configurado
                $imagePath = $image->storeAs('announcements', $imageName, $disk);
                $validated['image'] = $imagePath;
            }

            $validated['is_active'] = $request->has('is_active');

            // 2. Crear el registro en la base de datos
            $announcement = Announcement::create($validated);

            // 3. Notificar a la comunidad si está activo
            if ($announcement->is_active) {
                $usersToNotify = User::where('notify_announcements', true)->get();
                if ($usersToNotify->isNotEmpty()) {
                    try {
                        Notification::send($usersToNotify, new AvisoComunidad(
                            'Nuevo Aviso Parroquial',
                            'Se ha publicado: ' . $announcement->title,
                            route('home') 
                        ));
                    } catch (\Exception $e) {
                        Log::warning("Anuncio {$announcement->id} creado, pero falló notificación Push: " . $e->getMessage());
                    }
                }
            }

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Anuncio publicado exitosamente en la nube');

        } catch (\Exception $e) {
            Log::error('Error al crear anuncio:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error técnico: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Formulario de edición.
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Actualiza el anuncio y gestiona archivos en la nube.
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
            $oldImagePath = $announcement->getRawImagePath(); // Asegúrate de tener este método en el modelo

            if ($request->filled('cropped_image')) {
                // Borrar anterior de la nube
                if ($oldImagePath && Storage::disk($disk)->exists($oldImagePath)) {
                    Storage::disk($disk)->delete($oldImagePath);
                }
                
                $imageData = $request->cropped_image;
                $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageName = time() . '_update.' . 'jpg';
                $imagePath = 'announcements/' . $imageName;
                
                Storage::disk($disk)->put($imagePath, base64_decode($imageData), 'public');
                $validated['image'] = $imagePath;

            } elseif ($request->hasFile('image')) {
                // Borrar anterior
                if ($oldImagePath && Storage::disk($disk)->exists($oldImagePath)) {
                    Storage::disk($disk)->delete($oldImagePath);
                }

                $image = $request->file('image');
                $imageName = time() . '_upd_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('announcements', $imageName, $disk);
                $validated['image'] = $imagePath;
            } else {
                // Mantener imagen actual si no se sube nada
                $validated['image'] = $oldImagePath;
            }

            $validated['is_active'] = $request->has('is_active');
            $announcement->update($validated);

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Anuncio actualizado correctamente');

        } catch (\Exception $e) {
            Log::error('Error al actualizar:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Elimina el anuncio y el archivo físico de Supabase.
     */
    public function destroy(Announcement $announcement)
    {
        try {
            $disk = config('filesystems.default');
            $imagePath = $announcement->getRawImagePath();

            if ($imagePath && Storage::disk($disk)->exists($imagePath)) {
                Storage::disk($disk)->delete($imagePath);
            }
            
            $announcement->delete();
            return redirect()->route('admin.announcements.index')->with('success', 'Anuncio eliminado de la nube');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar anuncio');
        }
    }

    /**
     * Elimina todos los anuncios y sus imágenes.
     */
    public function deleteAll()
    {
        try {
            $disk = config('filesystems.default');
            $announcements = Announcement::all();

            foreach ($announcements as $announcement) {
                $path = $announcement->getRawImagePath();
                if ($path && Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }
            }

            Announcement::query()->delete();
            return redirect()->route('admin.announcements.index')->with('success', "Todos los anuncios han sido borrados");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al realizar la limpieza masiva');
        }
    }

    /**
     * Activa/Desactiva rápidamente un aviso.
     */
    public function toggleStatus(Announcement $announcement)
    {
        try {
            $announcement->update(['is_active' => !$announcement->is_active]);
            $msg = $announcement->is_active ? 'activado' : 'desactivado';
            return redirect()->back()->with('success', "Anuncio {$msg} correctamente");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cambiar estado');
        }
    }

    /**
     * Actualiza el orden de los anuncios (vía AJAX).
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'announcements' => 'required|array',
            'announcements.*.id' => 'required|exists:announcements,id',
            'announcements.*.order' => 'required|integer'
        ]);

        try {
            foreach ($request->announcements as $item) {
                Announcement::where('id', $item['id'])->update(['order' => $item['order']]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Acciones masivas sobre avisos seleccionados.
     */
    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:announcements,id'
        ]);

        try {
            $disk = config('filesystems.default');
            $announcements = Announcement::whereIn('id', $request->ids);

            if ($request->action === 'delete') {
                $list = $announcements->get();
                foreach ($list as $ann) {
                    $path = $ann->getRawImagePath();
                    if ($path && Storage::disk($disk)->exists($path)) {
                        Storage::disk($disk)->delete($path);
                    }
                }
                $announcements->delete();
            } else {
                $announcements->update(['is_active' => ($request->action === 'activate')]);
            }

            return redirect()->back()->with('success', 'Acción masiva completada');
        } catch (\Exception $e) {
            Log::error("Error Bulk: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error en la operación masiva');
        }
    }
}