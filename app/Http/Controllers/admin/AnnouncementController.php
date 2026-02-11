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
            ],
            [ 'image.max' => 'La imagen es muy pesada para el servidor. Intenta con una de menos de 15MB.',
        ]);

        try {
            $validated['modal_id'] = 'modal_' . Str::random(8);

            // Manejo de imagen recortada o normal
            if ($request->has('cropped_image') && !empty($request->cropped_image)) {
                $imageData = $request->cropped_image;
                $mime = null;
                $extension = 'jpg';
                
                if (strpos($imageData, 'data:image/jpeg;base64,') === 0) {
                    $mime = 'image/jpeg';
                    $extension = 'jpg';
                } elseif (strpos($imageData, 'data:image/png;base64,') === 0) {
                    $mime = 'image/png';
                    $extension = 'png';
                } elseif (strpos($imageData, 'data:image/gif;base64,') === 0) {
                    $mime = 'image/gif';
                    $extension = 'gif';
                } elseif (strpos($imageData, 'data:image/webp;base64,') === 0) {
                    $mime = 'image/webp';
                    $extension = 'webp';
                }
                
                if ($mime) {
                    $imageData = str_replace("data:{$mime};base64,", '', $imageData);
                    $imageData = str_replace(' ', '+', $imageData);
                    $imageName = time() . '_cropped.' . $extension;
                    $imagePath = 'announcements/' . $imageName;
                    Storage::disk('public')->put($imagePath, base64_decode($imageData));
                    $validated['image'] = $imagePath;
                }
            } elseif ($request->hasFile('image')) {
                if (!Storage::disk('public')->exists('announcements')) {
                    Storage::disk('public')->makeDirectory('announcements');
                }
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('announcements', $imageName, 'public');
                $validated['image'] = $imagePath;
            }

            $validated['is_active'] = $request->has('is_active') ? true : false;

            // Crear el anuncio
            $announcement = Announcement::create($validated);

            // --- LÓGICA DE NOTIFICACIÓN CORREGIDA ---
            if ($announcement->is_active) {
                // Buscamos usuarios que quieran recibir anuncios
                $usersToNotify = User::where('notify_announcements', true)->get();

                if ($usersToNotify->isNotEmpty()) {
                    try {
                        // Usamos el sistema nativo de Laravel. 
                        // Esto llena automáticamente notifiable_id, notifiable_type y la columna 'data' en JSON.
                        Notification::send($usersToNotify, new AvisoComunidad(
                            'Nuevo Aviso Parroquial',
                            'Se ha publicado: ' . $announcement->title,
                            route('home') 
                        ));
                    } catch (\Exception $e) {
                        // "Degradación graciosa": Si fallan las llaves de notificación Push (Windows/OpenSSL),
                        // el anuncio se guarda igual pero se loguea el aviso.
                        Log::warning("Anuncio ID {$announcement->id} creado, pero falló el envío Push: " . $e->getMessage());
                    }
                }
            }

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Anuncio publicado y notificaciones enviadas exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al crear anuncio:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error al crear el anuncio: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

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
            if ($request->has('cropped_image') && !empty($request->cropped_image)) {
                if ($announcement->getRawImagePath() && Storage::disk('public')->exists($announcement->getRawImagePath())) {
                    Storage::disk('public')->delete($announcement->getRawImagePath());
                }
                
                $imageData = $request->cropped_image;
                $mime = null;
                $extension = 'jpg';
                
                if (strpos($imageData, 'data:image/jpeg;base64,') === 0) { $mime = 'image/jpeg'; $extension = 'jpg'; }
                elseif (strpos($imageData, 'data:image/png;base64,') === 0) { $mime = 'image/png'; $extension = 'png'; }
                elseif (strpos($imageData, 'data:image/gif;base64,') === 0) { $mime = 'image/gif'; $extension = 'gif'; }
                elseif (strpos($imageData, 'data:image/webp;base64,') === 0) { $mime = 'image/webp'; $extension = 'webp'; }
                
                if ($mime) {
                    $imageData = str_replace("data:{$mime};base64,", '', $imageData);
                    $imageData = str_replace(' ', '+', $imageData);
                    $imageName = time() . '_cropped.' . $extension;
                    $imagePath = 'announcements/' . $imageName;
                    Storage::disk('public')->put($imagePath, base64_decode($imageData));
                    $validated['image'] = $imagePath;
                }
            } elseif ($request->hasFile('image')) {
                if ($announcement->getRawImagePath() && Storage::disk('public')->exists($announcement->getRawImagePath())) {
                    Storage::disk('public')->delete($announcement->getRawImagePath());
                }
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('announcements', $imageName, 'public');
                $validated['image'] = $imagePath;
            } else {
                $validated['image'] = $announcement->getRawImagePath();
            }

            $validated['is_active'] = $request->has('is_active') ? true : false;

            $announcement->update($validated);

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Anuncio actualizado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al actualizar anuncio:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Announcement $announcement)
    {
        try {
            if ($announcement->getRawImagePath() && Storage::disk('public')->exists($announcement->getRawImagePath())) {
                Storage::disk('public')->delete($announcement->getRawImagePath());
            }
            $announcement->delete();
            return redirect()->route('admin.announcements.index')->with('success', 'Anuncio eliminado');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar');
        }
    }

    public function deleteAll()
    {
        try {
            $announcements = Announcement::all();
            foreach ($announcements as $announcement) {
                if ($announcement->getRawImagePath() && Storage::disk('public')->exists($announcement->getRawImagePath())) {
                    Storage::disk('public')->delete($announcement->getRawImagePath());
                }
            }
            Announcement::truncate();
            return redirect()->route('admin.announcements.index')->with('success', "Anuncios eliminados");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar');
        }
    }

    public function toggleStatus(Announcement $announcement)
    {
        try {
            $announcement->update(['is_active' => !$announcement->is_active]);
            $status = $announcement->is_active ? 'activado' : 'desactivado';
            return redirect()->back()->with('success', "Anuncio {$status}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cambiar estado');
        }
    }

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

    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:announcements,id'
        ]);

        try {
            $announcements = Announcement::whereIn('id', $request->ids);
            if ($request->action === 'delete') {
                $announcementsToDelete = $announcements->get();
                foreach ($announcementsToDelete as $announcement) {
                    if ($announcement->getRawImagePath() && Storage::disk('public')->exists($announcement->getRawImagePath())) {
                        Storage::disk('public')->delete($announcement->getRawImagePath());
                    }
                }
                $announcements->delete();
            } else {
                $announcements->update(['is_active' => ($request->action === 'activate')]);
            }
            return redirect()->back()->with('success', 'Acción masiva realizada');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error en acción masiva');
        }
    }
}