document.addEventListener('DOMContentLoaded', function() {
    const amountOptions = document.querySelectorAll('.amount-option');
    const customAmountInput = document.getElementById('customAmount');
    const frequencyOptions = document.querySelectorAll('input[name="frequency"]');
    const cardForm = document.getElementById('cardForm');
    const confirmationModal = document.getElementById('confirmationModal');
    const statusModal = document.getElementById('statusModal');

    let selectedAmount = 0;
    let selectedFrequency = 'once';

    // Función para mostrar el modal de estado (Éxito/Error)
    function showStatusModal(title, message, isSuccess = true) {
        const titleEl = document.getElementById('statusModalTitle');
        const messageEl = document.getElementById('statusModalMessage');
        const iconContainer = document.getElementById('statusModalIcon');
        
        titleEl.textContent = title;
        messageEl.textContent = message;
        
        if (isSuccess) {
            iconContainer.innerHTML = `
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>`;
        } else {
            iconContainer.innerHTML = `
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>`;
        }

        statusModal.classList.remove('hidden');
        statusModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    // Función para cerrar todos los modales
    window.closeDonationModals = function() {
        confirmationModal.classList.add('hidden');
        confirmationModal.classList.remove('flex');
        // También ocultar por inline style si se usó .style.display
        confirmationModal.style.display = 'none';
        
        statusModal.classList.add('hidden');
        statusModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Manejar selección de montos
    function handleAmountSelection(selectedOption) {
        amountOptions.forEach(option => {
            option.classList.remove('bg-button', 'text-white');
            option.classList.add('bg-white', 'text-button', 'border-button');
        });
        selectedOption.classList.remove('bg-white', 'text-button');
        selectedOption.classList.add('bg-button', 'text-white');
        customAmountInput.value = '';
        selectedAmount = parseInt(selectedOption.dataset.amount);
    }

    amountOptions.forEach(option => {
        option.addEventListener('click', function() {
            handleAmountSelection(this);
        });
    });

    customAmountInput.addEventListener('input', function() {
        if (this.value) {
            amountOptions.forEach(option => {
                option.classList.remove('bg-button', 'text-white');
                option.classList.add('bg-white', 'text-button', 'border-button');
            });
            selectedAmount = parseInt(this.value) || 0;
        }
    });

    frequencyOptions.forEach(option => {
        option.addEventListener('change', function() {
            selectedFrequency = this.value;
        });
    });

    // Formateo de Tarjeta
    const cardNumberInput = document.getElementById('cardNumber');
    if(cardNumberInput) {
        cardNumberInput.addEventListener('input', function() {
            let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formattedValue += ' ';
                formattedValue += value[i];
            }
            this.value = formattedValue;
        });
    }

    // Formateo de Vencimiento
    const expiryDateInput = document.getElementById('expiryDate');
    if(expiryDateInput) {
        expiryDateInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 2) {
                this.value = value.substring(0, 2) + '/' + value.substring(2, 4);
            } else {
                this.value = value;
            }
        });
    }

    // Envío del Formulario
    cardForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (selectedAmount === 0 || selectedAmount < 100) {
            showStatusModal('Monto Inválido', 'Por favor, seleccione un monto mínimo de $100.', false);
            return;
        }

        const cardHolder = document.getElementById('cardHolder').value;
        const cardNumber = document.getElementById('cardNumber').value;
        const email = document.getElementById('email').value;

        if (!cardHolder || !cardNumber || !email) {
            showStatusModal('Datos Incompletos', 'Por favor, complete todos los campos de la tarjeta.', false);
            return;
        }

        // Mostrar Confirmación
        document.getElementById('confirmAmount').textContent = `$${selectedAmount.toLocaleString()}`;
        const frequencyText = { 'once': 'Única', 'weekly': 'Semanal', 'biweekly': 'Quincenal', 'monthly': 'Mensual' };
        document.getElementById('confirmFrequency').textContent = frequencyText[selectedFrequency];
        const lastFour = cardNumber.replace(/\s/g, '').slice(-4);
        document.getElementById('confirmCard').textContent = `**** **** **** ${lastFour}`;
        document.getElementById('confirmEmail').textContent = email;

        confirmationModal.style.display = 'flex';
        confirmationModal.classList.remove('hidden');
        confirmationModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    });

    // Botón Confirmar del Modal
    document.getElementById('confirmDonation').addEventListener('click', async function() {
        const btn = this;
        const originalText = btn.textContent;
        btn.textContent = 'Procesando...';
        btn.disabled = true;

        try {
            const response = await fetch('/donaciones/procesar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    amount: selectedAmount,
                    frequency: selectedFrequency,
                    card_holder: document.getElementById('cardHolder').value,
                    card_number: document.getElementById('cardNumber').value.replace(/\s/g, ''),
                    email: document.getElementById('email').value
                })
            });

            const result = await response.json();

            closeDonationModals();

            if (result.success) {
                showStatusModal('¡Muchas Gracias!', 'Tu donación ha sido procesada exitosamente. Gracias por tu generosidad.');
                cardForm.reset();
                selectedAmount = 0;
                amountOptions.forEach(opt => opt.classList.remove('bg-button', 'text-white'));
            } else {
                showStatusModal('Error en el Pago', result.message || 'No se pudo procesar la transacción.', false);
            }
        } catch (error) {
            closeDonationModals();
            showStatusModal('Error de Conexión', 'Hubo un problema al conectar con el servidor. Intente nuevamente.', false);
        } finally {
            btn.textContent = originalText;
            btn.disabled = false;
        }
    });

    // Eventos de cierre
    const closeElements = [
        document.getElementById('cancelDonation'),
        document.getElementById('closeStatusModal'),
        confirmationModal.querySelector('.modal-close')
    ];

    closeElements.forEach(el => {
        if(el) el.addEventListener('click', closeDonationModals);
    });

    confirmationModal.addEventListener('click', (e) => { if (e.target === confirmationModal) closeDonationModals(); });
    statusModal.addEventListener('click', (e) => { if (e.target === statusModal) closeDonationModals(); });
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDonationModals();
    });
});