document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const intentionForm = document.getElementById('intentionForm');
    const confirmationModalIntention = document.getElementById('confirmationModalIntention');
    
    console.log('Intention form:', intentionForm);
    console.log('Confirmation modal:', confirmationModalIntention);
    
    if (intentionForm && confirmationModalIntention) {
        const modalClose = confirmationModalIntention.querySelector('.modal-close');
        const cancelBtn = document.getElementById('cancelIntention');
        const confirmBtn = document.getElementById('confirmIntention');

        // Mapeo de tipos de intención para mostrar texto amigable
        const intentionTypeMap = {
            'salud': 'Salud',
            'intenciones': 'Intenciones',
            'accion-gracias': 'Acción de gracias',
            'difuntos': 'Difuntos'
        };

        // Validar y enviar formulario
        intentionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted');

            // Obtener valores del formulario
            const intentionType = document.getElementById('intentionType').value;
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;

            console.log('Form values:', { intentionType, name, email });

            // Validaciones básicas
            if (!intentionType) {
                alert('Por favor, seleccione el tipo de intención');
                return;
            }

            if (!name || !email) {
                alert('Por favor, complete todos los campos');
                return;
            }

            // Mostrar modal de confirmación
            showConfirmationModal(intentionType, name, email);
        });

        // Mostrar modal de confirmación
        function showConfirmationModal(intentionType, name, email) {
            console.log('Showing confirmation modal');
            
            // Actualizar información en el modal
            document.getElementById('confirmIntentionType').textContent = intentionTypeMap[intentionType] || intentionType;
            document.getElementById('confirmName').textContent = name;
            document.getElementById('confirmEmailIntention').textContent = email;

            // Mostrar modal
            confirmationModalIntention.classList.remove('hidden');
            confirmationModalIntention.classList.add('flex');
            document.body.style.overflow = 'hidden';
            console.log('Modal should be visible now');
        }

        // Cerrar modal
        function closeModal() {
            confirmationModalIntention.classList.add('hidden');
            confirmationModalIntention.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Event listeners para el modal
        if (modalClose) {
            modalClose.addEventListener('click', closeModal);
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeModal);
        }

        // Confirmar intención
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                // Enviar datos al servidor
                const formData = new FormData(intentionForm);
                
                // Usar la ruta correcta: /intenciones/guardar
                fetch('/intenciones/guardar', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('¡Intención enviada exitosamente! Será incluida en nuestras oraciones.');
                        closeModal();
                        intentionForm.reset();
                    } else {
                        alert('Error al enviar la intención: ' + (data.message || 'Intente nuevamente'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al enviar la intención. Intente nuevamente.');
                });
            });
        }

        // Cerrar modal al hacer click fuera
        confirmationModalIntention.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && confirmationModalIntention.classList.contains('flex')) {
                closeModal();
            }
        });

    } else {
        console.error('No se encontraron elementos del formulario de intenciones');
    }
});