document.addEventListener('DOMContentLoaded', function() {
    const intentionForm = document.getElementById('intentionForm');
    const confirmationModalIntention = document.getElementById('confirmationModalIntention');
    const statusModal = document.getElementById('statusModal');
    
    if (intentionForm && confirmationModalIntention) {
        const modalClose = confirmationModalIntention.querySelector('.modal-close');
        const cancelBtn = document.getElementById('cancelIntention');
        const confirmBtn = document.getElementById('confirmIntention');

        const intentionTypeMap = {
            'salud': 'Salud',
            'intenciones': 'Intenciones',
            'accion-gracias': 'Acción de gracias',
            'difuntos': 'Difuntos'
        };

        // Función para mostrar el modal de estado (Éxito/Error)
        function showStatusModal(title, message, isSuccess = true) {
            const titleEl = document.getElementById('statusModalTitle');
            const messageEl = document.getElementById('statusModalMessage');
            const iconContainer = document.getElementById('statusModalIcon');
            
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            if (isSuccess) {
                iconContainer.innerHTML = `<div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>`;
            } else {
                iconContainer.innerHTML = `<div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>`;
            }

            statusModal.classList.remove('hidden');
            statusModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        intentionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const intentionType = document.getElementById('intentionType').value;
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;

            if (!intentionType || !name || !email) {
                showStatusModal('Atención', 'Por favor, complete todos los campos obligatorios.', false);
                return;
            }

            // Mostrar confirmación
            document.getElementById('confirmIntentionType').textContent = intentionTypeMap[intentionType] || intentionType;
            document.getElementById('confirmName').textContent = name;
            document.getElementById('confirmEmailIntention').textContent = email;

            confirmationModalIntention.classList.remove('hidden');
            confirmationModalIntention.classList.add('flex');
            document.body.style.overflow = 'hidden';
        });

        // Cerrar modales
        window.closeIntentionModals = function() {
            confirmationModalIntention.classList.add('hidden');
            confirmationModalIntention.classList.remove('flex');
            statusModal.classList.add('hidden');
            statusModal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        modalClose.addEventListener('click', closeIntentionModals);
        cancelBtn.addEventListener('click', closeIntentionModals);
        document.getElementById('closeStatusModal').addEventListener('click', closeIntentionModals);

        // Confirmar y Enviar
        confirmBtn.addEventListener('click', function() {
            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Enviando...';
            
            const formData = new FormData(intentionForm);
            
            fetch('/intenciones/guardar', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                confirmationModalIntention.classList.add('hidden');
                if (data.success) {
                    showStatusModal('¡Registrada!', 'Tu intención ha sido enviada exitosamente y será incluida en nuestras oraciones.');
                    intentionForm.reset();
                } else {
                    showStatusModal('Error', data.message || 'No se pudo procesar la solicitud.', false);
                }
            })
            .catch(error => {
                confirmationModalIntention.classList.add('hidden');
                showStatusModal('Error de conexión', 'Hubo un problema al conectar con el servidor.', false);
            })
            .finally(() => {
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Confirmar';
            });
        });
    }
});