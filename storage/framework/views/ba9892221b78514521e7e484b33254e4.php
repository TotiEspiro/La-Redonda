<?php if(auth()->guard()->check()): ?>
<?php if(!Auth::user()->onboarding_completed): ?>
<div id="onboardingModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
        
        <!-- Paso 1: Bienvenida -->
        <div id="ob-step-1" class="p-8 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">¡Bienvenido a la Parroquia!</h2>
            <p class="text-gray-500 mb-8 text-sm">¿Te gustaría unirte a algún grupo parroquial para participar de nuestra comunidad?</p>
            <div class="flex gap-3">
                <button onclick="finishOnboarding()" class="flex-1 py-3 border border-gray-100 rounded-xl text-gray-400 font-bold hover:bg-gray-50">Ahora no</button>
                <button onclick="showStep(2)" class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-200">¡Sí, claro!</button>
            </div>
        </div>

        <!-- Paso 2: Edad -->
        <div id="ob-step-2" class="hidden p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">Cuéntanos sobre ti</h3>
            <p class="text-gray-500 text-xs mb-6 text-center">Ingresa tu edad para ver qué grupos se adaptan mejor a tu etapa de vida.</p>
            <input type="number" id="ob-age-input" class="w-full px-4 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-blue-500 mb-6 text-center text-lg font-bold" placeholder="Ej: 22">
            <button onclick="loadRecommendations()" id="ob-search-btn" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-lg transition-transform active:scale-95">Buscar Grupos</button>
        </div>

        <!-- Paso 3: Recomendaciones -->
        <div id="ob-step-3" class="hidden p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span> Grupos para tu edad:
            </h3>
            <div id="ob-list" class="space-y-3 max-h-60 overflow-y-auto pr-2">
                <!-- Se llena con JavaScript -->
            </div>
            <button onclick="finishOnboarding()" class="w-full mt-6 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-gray-600">Cerrar y continuar</button>
        </div>
    </div>
</div>

<script>
    /**
     * Cambia entre los pasos del modal
     */
    function showStep(s) {
        document.querySelectorAll('[id^="ob-step-"]').forEach(el => el.classList.add('hidden'));
        const nextStep = document.getElementById(`ob-step-${s}`);
        if (nextStep) nextStep.classList.remove('hidden');
    }

    /**
     * Carga las recomendaciones evitando errores de tipo
     */
    function loadRecommendations() {
        const age = document.getElementById('ob-age-input').value;
        if (!age) return;

        const btn = document.getElementById('ob-search-btn');
        const list = document.getElementById('ob-list');
        
        btn.innerHTML = 'Buscando...';
        btn.disabled = true;

        fetch(`/onboarding/recommended?age=${age}`)
            .then(res => {
                // Si el servidor falla (500), devolvemos un array vacío para que el JS no explote
                if (!res.ok) return [];
                return res.json();
            })
            .then(groups => {
                list.innerHTML = '';

                // Verificamos que 'groups' sea realmente una lista
                if (Array.isArray(groups) && groups.length > 0) {
                    groups.forEach(g => {
                        list.innerHTML += `
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-blue-400 transition-colors">
                                <div class="min-w-0">
                                    <span class="font-bold text-gray-800 text-sm block truncate">${g.name}</span>
                                    <p class="text-[10px] text-gray-400">Rango: ${g.min_age}-${g.max_age} años</p>
                                </div>
                                <form action="/grupos/${g.category}/solicitar" method="POST">
                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                    <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold shadow-sm hover:bg-blue-700 transition-colors">UNIRME</button>
                                </form>
                            </div>
                        `;
                    });
                } else {
                    list.innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-gray-400 text-xs italic">No encontramos grupos específicos para tu edad en este momento.</p>
                        </div>
                    `;
                }
                
                // Guardamos la edad en segundo plano y pasamos al paso 3
                saveOnboardingData(age);
                showStep(3);
            })
            .catch(err => {
                console.error("Error en la petición:", err);
                list.innerHTML = '<p class="text-center text-red-400 text-xs py-4">Error al conectar con el servidor.</p>';
                showStep(3);
            })
            .finally(() => {
                btn.innerHTML = 'Buscar Grupos';
                btn.disabled = false;
            });
    }

    function saveOnboardingData(age = null) {
        fetch('/onboarding/complete', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ age: age })
        });
    }

    function finishOnboarding() {
        saveOnboardingData();
        const modal = document.getElementById('onboardingModal');
        if (modal) {
            modal.classList.add('opacity-0');
            modal.style.transition = 'opacity 0.3s ease';
            setTimeout(() => modal.remove(), 300);
        }
    }
</script>
<?php endif; ?>
<?php endif; ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/partials/onboarding-modal.blade.php ENDPATH**/ ?>