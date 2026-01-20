<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cropped_image' => 'nullable|string', // Base64 image
            'is_active' => 'sometimes|boolean',
            'order' => 'required|integer|min:0'
        ]);

        try {
            $validated['modal_id'] = 'modal_' . Str::random(8);

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
                    
                    // Guardar imagen
                    $imagePath = 'announcements/' . $imageName;
                    Storage::disk('public')->put($imagePath, base64_decode($imageData));
                    $validated['image'] = $imagePath;
                    
                    Log::info('Imagen recortada guardada:', ['path' => $imagePath, 'type' => $mime]);
                }
                
            } elseif ($request->hasFile('image')) {
                // Manejar imagen normal sin recortar
                if (!Storage::disk('public')->exists('announcements')) {
                    Storage::disk('public')->makeDirectory('announcements');
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('announcements', $imageName, 'public');
                $validated['image'] = $imagePath;
                
                Log::info('Imagen normal guardada:', ['path' => $imagePath]);
            }

            // Establecer valor por defecto para is_active
            $validated['is_active'] = $request->has('is_active') ? true : false;

            $announcement = Announcement::create($validated);

            Log::info('Anuncio creado:', [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'image_path' => $announcement->getRawImagePath(),
                'image_url' => $announcement->image_url
            ]);

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Anuncio creado exitosamente');

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
            // Manejar la carga de nueva imagen (priorizar imagen recortada)
            if ($request->has('cropped_image') && !empty($request->cropped_image)) {
                // Eliminar imagen anterior si existe
                if ($announcement->getRawImagePath() && Storage::disk('public')->exists($announcement->getRawImagePath())) {
                    Storage::disk('public')->delete($announcement->getRawImagePath());
                }
                
                // Procesar imagen recortada en base64
                $imageData = $request->cropped_image;
                
                // Determinar el tipo MIME y extensión
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
                    
                    // Guardar imagen
                    $imagePath = 'announcements/' . $imageName;
                    Storage::disk('public')->put($imagePath, base64_decode($imageData));
                    $validated['image'] = $imagePath;
                    
                    Log::info('Imagen recortada actualizada:', ['path' => $imagePath, 'type' => $mime]);
                }
                
            } elseif ($request->hasFile('image')) {
                // Eliminar imagen anterior si existe
                if ($announcement->getRawImagePath() && Storage::disk('public')->exists($announcement->getRawImagePath())) {
                    Storage::disk('public')->delete($announcement->getRawImagePath());
                }
                
                // Asegurar que la carpeta existe
                if (!Storage::disk('public')->exists('announcements')) {
                    Storage::disk('public')->makeDirectory('announcements');
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('announcements', $imageName, 'public');
                $validated['image'] = $imagePath;
                
                Log::info('Imagen normal actualizada:', ['path' => $imagePath]);
            } else {
                // Mantener la imagen actual si no se sube una nueva
                $validated['image'] = $announcement->getRawImagePath();
            }

            // Establecer valor para is_active
            $validated['is_active'] = $request->has('is_active') ? true : false;

            $announcement->update($validated);

            Log::info('Anuncio actualizado:', [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'image_path' => $announcement->getRawImagePath(),
                'image_url' => $announcement->image_url
            ]);

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Anuncio actualizado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al actualizar anuncio:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error al actualizar el anuncio: ' . $e->getMessage())
                ->withInput();
        }
    }

    
    public function destroy(Announcement $announcement)
    {
        try {
            // Eliminar imagen si existe
            if ($announcement->getRawImagePath() && Storage::disk('public')->exists($announcement->getRawImagePath())) {
                Storage::disk('public')->delete($announcement->getRawImagePath());
            }

            $announcement->delete();

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Anuncio eliminado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al eliminar anuncio:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error al eliminar el anuncio: ' . $e->getMessage());
        }
    }

    // Eliminar todos los anuncios
    public function deleteAll()
    {
        try {
            // Obtener todos los anuncios
            $announcements = Announcement::all();
            $deletedCount = $announcements->count();
            
            if ($deletedCount === 0) {
                return redirect()->route('admin.announcements.index')
                    ->with('info', 'No hay anuncios para eliminar');
            }

            // Eliminar las imágenes de storage
            foreach ($announcements as $announcement) {
                if ($announcement->getRawImagePath() && Storage::disk('public')->exists($announcement->getRawImagePath())) {
                    Storage::disk('public')->delete($announcement->getRawImagePath());
                }
            }
            
            // Eliminar todos los registros
            Announcement::truncate();
            
            return redirect()->route('admin.announcements.index')
                ->with('success', "Se eliminaron {$deletedCount} anuncios exitosamente");

        } catch (\Exception $e) {
            Log::error('Error al eliminar todos los anuncios:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error al eliminar los anuncios: ' . $e->getMessage());
        }
    }


    public function toggleStatus(Announcement $announcement)
    {
        try {
            $announcement->update([
                'is_active' => !$announcement->is_active
            ]);

            $status = $announcement->is_active ? 'activado' : 'desactivado';
            
            return redirect()->back()
                ->with('success', "Anuncio {$status} exitosamente");

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del anuncio:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error al cambiar el estado del anuncio: ' . $e->getMessage());
        }
    }

    /**
     * Update the order of announcements.
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

            return response()->json(['success' => true, 'message' => 'Orden actualizado exitosamente']);

        } catch (\Exception $e) {
            Log::error('Error al actualizar orden:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error al actualizar el orden'], 500);
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

            switch ($request->action) {
                case 'activate':
                    $announcements->update(['is_active' => true]);
                    $message = 'Anuncios activados exitosamente';
                    break;

                case 'deactivate':
                    $announcements->update(['is_active' => false]);
                    $message = 'Anuncios desactivados exitosamente';
                    break;

                case 'delete':
                    // Eliminar imágenes antes de borrar los anuncios
                    $announcementsToDelete = $announcements->get();
                    foreach ($announcementsToDelete as $announcement) {
                        if ($announcement->getRawImagePath() && Storage::disk('public')->exists($announcement->getRawImagePath())) {
                            Storage::disk('public')->delete($announcement->getRawImagePath());
                        }
                    }
                    $announcements->delete();
                    $message = 'Anuncios eliminados exitosamente';
                    break;
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error en bulk actions:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al realizar la acción: ' . $e->getMessage());
        }
    }
}