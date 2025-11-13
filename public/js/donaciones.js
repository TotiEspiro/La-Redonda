// JavaScript actualizado para donaciones que guarda en BD
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const amountOptions = document.querySelectorAll('.amount-option');
    const customAmountInput = document.getElementById('customAmount');
    const frequencyOptions = document.querySelectorAll('input[name="frequency"]');
    const cardForm = document.getElementById('cardForm');
    const confirmationModal = document.getElementById('confirmationModal');

    let selectedAmount = 0;
    let selectedFrequency = 'once';

    // Función para manejar selección de montos
    function handleAmountSelection(selectedOption) {
        // Remover clases de active de todos los botones
        amountOptions.forEach(option => {
            option.classList.remove('bg-button', 'text-white');
            option.classList.add('bg-white', 'text-button', 'border-button');
        });
        
        // Agregar clases de active al botón seleccionado
        selectedOption.classList.remove('bg-white', 'text-button');
        selectedOption.classList.add('bg-button', 'text-white');
        
        // Limpiar input personalizado
        customAmountInput.value = '';
        // Guardar monto seleccionado
        selectedAmount = parseInt(selectedOption.dataset.amount);
    }

    // Manejar selección de monto
    amountOptions.forEach(option => {
        option.addEventListener('click', function() {
            handleAmountSelection(this);
        });
    });

    // Manejar monto personalizado
    customAmountInput.addEventListener('input', function() {
        if (this.value) {
            // Remover selección de botones cuando se usa input personalizado
            amountOptions.forEach(option => {
                option.classList.remove('bg-button', 'text-white');
                option.classList.add('bg-white', 'text-button', 'border-button');
            });
            selectedAmount = parseInt(this.value) || 0;
        }
    });

    // Manejar frecuencia
    frequencyOptions.forEach(option => {
        option.addEventListener('change', function() {
            selectedFrequency = this.value;
        });
    });

    // Formatear número de tarjeta
    const cardNumberInput = document.getElementById('cardNumber');
    cardNumberInput.addEventListener('input', function() {
        let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = '';

        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }

        this.value = formattedValue;
    });

    // Formatear fecha de vencimiento
    const expiryDateInput = document.getElementById('expiryDate');
    expiryDateInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');

        if (value.length >= 2) {
            this.value = value.substring(0, 2) + '/' + value.substring(2, 4);
        } else {
            this.value = value;
        }
    });

    // Validar y enviar formulario
    cardForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validaciones básicas
        if (selectedAmount === 0 || selectedAmount < 100) {
            alert('Por favor, seleccione un monto válido (mínimo $100)');
            return;
        }

        // Obtener datos del formulario
        const cardHolder = document.getElementById('cardHolder').value;
        const cardNumber = document.getElementById('cardNumber').value;
        const email = document.getElementById('email').value;

        if (!cardHolder || !cardNumber || !email) {
            alert('Por favor, complete todos los campos de la tarjeta');
            return;
        }

        // Mostrar loading
        const submitBtn = cardForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Procesando...';
        submitBtn.disabled = true;

        try {
            // Enviar a la base de datos
            const response = await fetch('/donaciones/procesar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    amount: selectedAmount,
                    frequency: selectedFrequency,
                    card_holder: cardHolder,
                    card_number: cardNumber.replace(/\s/g, ''),
                    email: email
                })
            });

            const result = await response.json();

            if (result.success) {
                // Mostrar modal de confirmación
                showConfirmationModal(cardHolder, cardNumber, email);
            } else {
                alert('Error: ' + result.message);
            }

        } catch (error) {
            console.error('Error:', error);
            alert('Error al procesar la donación. Por favor, intente nuevamente.');
        } finally {
            // Restaurar botón
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });

    // Mostrar modal de confirmación
    function showConfirmationModal(cardHolder, cardNumber, email) {
        // Actualizar información en el modal
        document.getElementById('confirmAmount').textContent = `$${selectedAmount.toLocaleString()}`;

        const frequencyText = {
            'once': 'Única',
            'weekly': 'Semanal',
            'biweekly': 'Quincenal',
            'monthly': 'Mensual'
        };
        document.getElementById('confirmFrequency').textContent = frequencyText[selectedFrequency];

        // Mostrar últimos 4 dígitos de la tarjeta
        const lastFour = cardNumber.slice(-4);
        document.getElementById('confirmCard').textContent = `**** **** **** ${lastFour}`;

        document.getElementById('confirmEmail').textContent = email;

        // Mostrar modal
        confirmationModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    // Cerrar modal
    function closeModal() {
        confirmationModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Event listeners para el modal
    confirmationModal.querySelector('.modal-close').addEventListener('click', closeModal);
    document.getElementById('cancelDonation').addEventListener('click', closeModal);

    // Confirmar donación
    document.getElementById('confirmDonation').addEventListener('click', function() {
        alert('¡Donación procesada exitosamente! Gracias por su contribución.');
        closeModal();
        cardForm.reset();

        // Resetear estado
        amountOptions.forEach(option => {
            option.classList.remove('bg-button', 'text-white');
            option.classList.add('bg-white', 'text-button', 'border-button');
        });
        customAmountInput.value = '';
        selectedAmount = 0;
        selectedFrequency = 'once';
    });

    // Cerrar modal al hacer click fuera
    confirmationModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Cerrar modal con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && confirmationModal.style.display === 'flex') {
            closeModal();
        }
    });
});