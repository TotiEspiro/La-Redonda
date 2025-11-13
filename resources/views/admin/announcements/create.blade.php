@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Anuncio</h2>
            <p class="text-gray-600">Agregar un nuevo aviso parroquial</p>
        </div>
        <a href="{{ route('admin.announcements.index') }}" 
           class="bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
            ← Volver
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Información del Anuncio</h3>
    </div>
    
    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" class="p-6" id="announcementForm">
        @csrf
        
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Título *</label>
                <input type="text" id="title" name="title" required 
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-button focus:border-button"
                       value="{{ old('title') }}">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="short_description" class="block text-sm font-medium text-gray-700">Descripción Corta *</label>
                <textarea id="short_description" name="short_description" required rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-button focus:border-button"
                          placeholder="Descripción breve que aparece en la tarjeta">{{ old('short_description') }}</textarea>
                @error('short_description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="full_description" class="block text-sm font-medium text-gray-700">Descripción Completa *</label>
                <textarea id="full_description" name="full_description" required rows="6"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-button focus:border-button"
                          placeholder="Contenido completo que aparece en el modal">{{ old('full_description') }}</textarea>
                @error('full_description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Sección de imagen con recorte -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Imagen</label>
                <input type="file" id="image" name="image" accept="image/*" 
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                <p class="mt-1 text-sm text-gray-500">Formatos: JPEG, PNG, JPG, GIF. Máx: 2MB</p>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                <!-- Preview y área de recorte -->
                <div id="imagePreview" class="mt-4 hidden">
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Recortar Imagen (Relación 4:3)</h4>
                        <div class="bg-gray-100 p-4 rounded-lg max-w-2xl mx-auto">
                            <div id="cropContainer" class="max-w-full">
                                <img id="imageToCrop" class="max-w-full">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-4 mb-4">
                        <button type="button" id="cropImage" class="bg-button text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                            Aplicar Recorte
                        </button>
                        <button type="button" id="cancelCrop" class="bg-gray-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                            Cancelar
                        </button>
                    </div>
                    
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 hidden" id="cropSuccess">
                        Imagen recortada correctamente. Puedes continuar con el formulario.
                    </div>
                    
                    <!-- Preview de imagen recortada -->
                    <div id="croppedPreview" class="hidden mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Vista previa de imagen recortada:</h4>
                        <img id="croppedImagePreview" class="max-w-xs border rounded-lg">
                    </div>
                </div>
                
                <input type="hidden" id="croppedImageData" name="cropped_image">
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700">Orden</label>
                    <input type="number" id="order" name="order" value="{{ old('order', 0) }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-button focus:border-button">
                    @error('order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked 
                                   class="rounded border-gray-300 text-button focus:ring-button">
                            <span class="ml-2">Activo</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end gap-4">
            <a href="{{ route('admin.announcements.index') }}" 
               class="bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-button text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                Crear Anuncio
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cropper;
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imageToCrop = document.getElementById('imageToCrop');
    const cropImageBtn = document.getElementById('cropImage');
    const cancelCropBtn = document.getElementById('cancelCrop');
    const cropSuccess = document.getElementById('cropSuccess');
    const croppedPreview = document.getElementById('croppedPreview');
    const croppedImagePreview = document.getElementById('croppedImagePreview');
    const croppedImageData = document.getElementById('croppedImageData');
    
    // Configuración del cropper
    const cropperOptions = {
        aspectRatio: 4/3,
        viewMode: 1,
        autoCropArea: 0.8,
        responsive: true,
        restore: false,
        checkCrossOrigin: false,
        guides: true,
        center: true,
        highlight: false,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false,
        ready: function() {
            console.log('Cropper listo');
        }
    };

    // Cuando se selecciona una imagen
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validar tipo de archivo
        if (!file.type.match('image.*')) {
            alert('Por favor, selecciona un archivo de imagen válido.');
            this.value = '';
            return;
        }

        // Validar tamaño (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('La imagen es demasiado grande. Máximo 2MB permitidos.');
            this.value = '';
            return;
        }

        const reader = new FileReader();
        
        reader.onload = function(event) {
            // Ocultar preview anterior si existe
            croppedPreview.classList.add('hidden');
            cropSuccess.classList.add('hidden');
            
            // Mostrar imagen para recortar
            imageToCrop.src = event.target.result;
            imagePreview.classList.remove('hidden');
            
            // Destruir cropper anterior si existe
            if (cropper) {
                cropper.destroy();
            }
            
            // Inicializar cropper después de que la imagen se cargue
            imageToCrop.onload = function() {
                cropper = new Cropper(imageToCrop, cropperOptions);
            };
        };
        
        reader.onerror = function() {
            alert('Error al leer el archivo de imagen.');
            imageInput.value = '';
        };
        
        reader.readAsDataURL(file);
    });

    // Aplicar recorte
    cropImageBtn.addEventListener('click', function() {
        if (!cropper) {
            alert('Primero selecciona una imagen.');
            return;
        }
        
        try {
            // Obtener canvas con imagen recortada
            const canvas = cropper.getCroppedCanvas({
                width: 800,
                height: 600,
                fillColor: '#fff',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });
            
            if (!canvas) {
                alert('Error al recortar la imagen.');
                return;
            }
            
            // Convertir canvas a Data URL
            const croppedDataURL = canvas.toDataURL('image/jpeg', 0.9);
            
            // Guardar en el input hidden
            croppedImageData.value = croppedDataURL;
            
            // Mostrar preview de la imagen recortada
            croppedImagePreview.src = croppedDataURL;
            croppedPreview.classList.remove('hidden');
            
            // Mostrar mensaje de éxito
            cropSuccess.classList.remove('hidden');
            
            // Scroll hacia el mensaje de éxito
            cropSuccess.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            
        } catch (error) {
            console.error('Error al recortar:', error);
            alert('Error al recortar la imagen: ' + error.message);
        }
    });

    // Cancelar recorte
    cancelCropBtn.addEventListener('click', function() {
        // Limpiar todo
        imagePreview.classList.add('hidden');
        croppedPreview.classList.add('hidden');
        cropSuccess.classList.add('hidden');
        imageInput.value = '';
        croppedImageData.value = '';
        
        // Destruir cropper
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    // Manejar envío del formulario
    document.getElementById('announcementForm').addEventListener('submit', function(e) {
        // Si hay una imagen recortada, reemplazar el archivo original
        if (croppedImageData.value) {
            // Convertir base64 a blob
            const base64Data = croppedImageData.value.split(',')[1];
            const blob = base64ToBlob(base64Data, 'image/jpeg');
            
            // Crear archivo desde el blob
            const file = new File([blob], 'announcement_cropped.jpg', { 
                type: 'image/jpeg',
                lastModified: new Date().getTime()
            });
            
            // Crear DataTransfer y reemplazar el archivo
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            imageInput.files = dataTransfer.files;
        }
        
        // El formulario se enviará normalmente
    });

    // Función auxiliar para convertir base64 a blob
    function base64ToBlob(base64, mimeType) {
        const byteCharacters = atob(base64);
        const byteArrays = [];
        
        for (let offset = 0; offset < byteCharacters.length; offset += 512) {
            const slice = byteCharacters.slice(offset, offset + 512);
            const byteNumbers = new Array(slice.length);
            
            for (let i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }
            
            const byteArray = new Uint8Array(byteNumbers);
            byteArrays.push(byteArray);
        }
        
        return new Blob(byteArrays, { type: mimeType });
    }
});
</script>
@endpush