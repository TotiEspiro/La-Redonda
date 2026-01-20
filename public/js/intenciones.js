document.addEventListener('DOMContentLoaded', function() {
    const intentionForm = document.getElementById('intentionForm');
    const confirmationModalIntention = document.getElementById('confirmationModalIntention');
    
    
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

        intentionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            

            const intentionType = document.getElementById('intentionType').value;
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;

            

            if (!intentionType) {
                alert('Por favor, seleccione el tipo de intención');
                return;
            }

            if (!name || !email) {
                alert('Por favor, complete todos los campos');
                return;
            }

            showConfirmationModal(intentionType, name, email);
        });

        function showConfirmationModal(intentionType, name, email) {
            
            
            document.getElementById('confirmIntentionType').textContent = intentionTypeMap[intentionType] || intentionType;
            document.getElementById('confirmName').textContent = name;
            document.getElementById('confirmEmailIntention').textContent = email;

            confirmationModalIntention.classList.remove('hidden');
            confirmationModalIntention.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
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
                const formData = new FormData(intentionForm);
                
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