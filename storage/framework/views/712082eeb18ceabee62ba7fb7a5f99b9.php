

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 md:bg-white md:min-h-0 pb-20 md:pb-0">
    
    <div class="bg-button text-white shadow-md md:shadow-none md:rounded-t-xl sticky top-0 z-20 md:static">
        <div class="container mx-auto px-4 py-4 md:px-6 md:py-5 md:mt-8">
            <h1 class="text-xl md:text-2xl font-bold">Editar Anuncio</h1>
            <p class="text-blue-100 text-xs md:text-sm hidden md:block">Modifica la información del aviso seleccionado</p>
        </div>
    </div>

    <div class="container mx-auto px-0 md:px-0 md:border md:border-t-0 md:border-gray-100 md:rounded-b-xl md:shadow-lg md:mb-8 bg-white">
        <form action="<?php echo e(route('admin.announcements.update', $announcement)); ?>" method="POST" enctype="multipart/form-data" id="editForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="p-4 md:p-8 space-y-6">
                
                <?php if($announcement->image_url): ?>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-3 flex items-center gap-4">
                    <img src="<?php echo e($announcement->image_url); ?>" alt="Actual" class="h-20 w-20 object-cover rounded-lg bg-gray-100 flex-shrink-0">
                    <div>
                        <p class="text-sm font-bold text-gray-800">Imagen Actual</p>
                        <p class="text-xs text-gray-500 mt-1 leading-snug">Esta imagen se mantendrá a menos que subas una nueva abajo.</p>
                    </div>
                </div>
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" required 
                           class="block w-full rounded-lg border-gray-300 bg-gray-50 border focus:bg-white focus:border-button focus:ring-button transition-colors p-3"
                           value="<?php echo e(old('title', $announcement->title)); ?>">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Descripción Corta *</label>
                    <textarea name="short_description" required rows="2" 
                              class="block w-full rounded-lg border-gray-300 bg-gray-50 border focus:bg-white focus:border-button focus:ring-button transition-colors p-3"><?php echo e(old('short_description', $announcement->short_description)); ?></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Descripción Completa *</label>
                    <textarea name="full_description" required rows="6" 
                              class="block w-full rounded-lg border-gray-300 bg-gray-50 border focus:bg-white focus:border-button focus:ring-button transition-colors p-3"><?php echo e(old('full_description', $announcement->full_description)); ?></textarea>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <label class="block text-sm font-bold text-gray-800 mb-3">Reemplazar Imagen</label>
                    
                    <div id="uploadContainer" class="border-2 border-dashed border-blue-200 rounded-xl p-6 text-center bg-white hover:bg-blue-50 transition-colors cursor-pointer relative">
                        <input type="file" id="imageInput" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="text-blue-400 mb-2">
                            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Toca para seleccionar imagen</p>
                        <p class="text-xs text-gray-400 mt-1">JPG o PNG. Se recomienda horizontal.</p>
                    </div>
                    
                    <input type="hidden" id="croppedImage" name="cropped_image">

                    <div id="cropArea" class="hidden mt-4 bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                        <div class="mb-3 text-sm font-bold text-gray-700 text-center">Recortar Nueva Imagen (16:9 Panorámico)</div>
                        <div class="relative w-full h-64 md:h-96 bg-black rounded-lg overflow-hidden">
                            <img id="imageToCrop" class="max-w-full block">
                        </div>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <button type="button" id="cancelBtn" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg font-bold hover:bg-gray-200 transition text-sm">
                                Cancelar
                            </button>
                            <button type="button" id="cropBtn" class="bg-green-600 text-white px-4 py-2.5 rounded-lg font-bold hover:bg-green-700 transition text-sm shadow-sm">
                                Confirmar
                            </button>
                        </div>
                    </div>

                    <div id="previewArea" class="hidden mt-4 text-center">
                        <div class="relative inline-block">
                            <img id="finalPreview" class="h-48 w-auto rounded-lg border-4 border-green-500 shadow-md">
                            <button type="button" id="changeImageBtn" class="absolute -bottom-3 -right-3 bg-white text-blue-600 p-2 rounded-full shadow-lg border border-gray-100 hover:bg-blue-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </div>
                        <p class="text-xs text-green-600 font-bold mt-2">¡Nueva imagen lista!</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Orden</label>
                        <input type="number" name="order" value="<?php echo e(old('order', $announcement->order)); ?>" class="block w-full rounded-lg border-gray-300 bg-gray-50 p-2.5">
                    </div>
                    <div class="flex items-center justify-center bg-gray-50 rounded-lg border border-gray-200 mt-6 md:mt-0 h-[46px] self-end">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" <?php echo e($announcement->is_active ? 'checked' : ''); ?> class="w-5 h-5 rounded border-gray-300 text-button focus:ring-button">
                            <span class="ml-2 text-gray-700 font-medium select-none">Activo</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 md:static md:bg-gray-50 md:p-6 md:flex md:justify-end md:gap-4 z-50">
                <div class="flex gap-3">
                    <a href="<?php echo e(route('admin.announcements.index')); ?>" class="flex-1 md:flex-none text-center bg-white border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="flex-1 md:flex-none bg-button text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-600 shadow-md transition-colors">
                        Actualizar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const uploadContainer = document.getElementById('uploadContainer');
    const cropArea = document.getElementById('cropArea');
    const imageToCrop = document.getElementById('imageToCrop');
    const previewArea = document.getElementById('previewArea');
    const finalPreview = document.getElementById('finalPreview');
    const croppedImageInput = document.getElementById('croppedImage');
    const cropBtn = document.getElementById('cropBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const changeImageBtn = document.getElementById('changeImageBtn');
    
    let cropper = null;

    if (typeof Cropper === 'undefined') {
        console.error('ERROR: Cropper.js no cargado.');
        return;
    }

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imageToCrop.src = e.target.result;
                
                cropArea.classList.remove('hidden');
                previewArea.classList.add('hidden');
                uploadContainer.classList.add('hidden'); 

                if (cropper) cropper.destroy();
                
                imageToCrop.onload = () => {
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 16 / 9, 
                        viewMode: 1,
                        autoCropArea: 1,
                        responsive: true,
                    });
                };
            };
            reader.readAsDataURL(file);
        }
    });

    cropBtn.addEventListener('click', () => {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({ width: 1280, height: 720 });
            const base64Image = canvas.toDataURL('image/jpeg', 0.85);
            
            croppedImageInput.value = base64Image;
            finalPreview.src = base64Image;
            
            cropArea.classList.add('hidden');
            previewArea.classList.remove('hidden');
        }
    });

    const reset = () => {
        imageInput.value = '';
        croppedImageInput.value = '';
        cropArea.classList.add('hidden');
        previewArea.classList.add('hidden');
        uploadContainer.classList.remove('hidden'); 
        
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    };

    cancelBtn.addEventListener('click', reset);
    changeImageBtn.addEventListener('click', reset);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/admin/announcements/edit.blade.php ENDPATH**/ ?>