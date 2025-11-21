@extends('layouts.app')

@section('content')
<div class="donations-container">
    <!-- Sección 1: Donar con Tarjeta -->
    <section class="donation-section full-width bg-white py-12 border-b-2 border-background-light">
        <div class="container max-w-7xl mx-auto px-4">
            <h1 class="donation-title text-4xl font-semibold text-text-dark text-center mb-12 border-b-2 border-black pb-2z">Donar con tarjeta de crédito/débito</h1>

            <div class="donation-content grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <!-- Columna Izquierda -->
                <div class="donation-left">
                    <!-- Monto -->
                    <div class="form-group mb-8">
                        <h3 class="subtitle text-xl font-semibold text-black mb-6">Monto</h3>
                        <div class="amount-options grid grid-cols-2 gap-4 mb-8">
                            <button type="button" class="amount-option px-6 py-4 border-2 border-button bg-white text-button rounded-lg cursor-pointer font-semibold text-center hover:bg-button hover:text-white transition-all" data-amount="1000">$1.000</button>
                            <button type="button" class="amount-option px-6 py-4 border-2 border-button bg-white text-button rounded-lg cursor-pointer font-semibold text-center hover:bg-button hover:text-white transition-all" data-amount="2000">$2.000</button>
                            <button type="button" class="amount-option px-6 py-4 border-2 border-button bg-white text-button rounded-lg cursor-pointer font-semibold text-center hover:bg-button hover:text-white transition-all" data-amount="10000">$10.000</button>
                            <button type="button" class="amount-option px-6 py-4 border-2 border-button bg-white text-button rounded-lg cursor-pointer font-semibold text-center hover:bg-button hover:text-white transition-all" data-amount="20000">$20.000</button>
                            <div class="custom-amount col-span-2 relative">
                                <input type="number" id="customAmount" placeholder="Monto personalizado" min="100" class="w-full px-4 py-4 pl-12 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-light font-semibold">$</span>
                            </div>
                        </div>
                    </div>

                    <!-- Frecuencia -->
                    <div class="form-group">
                        <h3 class="subtitle text-xl font-semibold text-black mb-6">Frecuencia</h3>
                        <div class="frequency-options space-y-3 mb-6">
                            <label class="frequency-option flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-button">
                                <input type="radio" name="frequency" value="once" checked class="mr-4">
                                <span class="checkmark"></span>
                                Únicamente
                            </label>
                            <label class="frequency-option flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-button">
                                <input type="radio" name="frequency" value="weekly" class="mr-4">
                                <span class="checkmark"></span>
                                Semanalmente
                            </label>
                            <label class="frequency-option flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-button">
                                <input type="radio" name="frequency" value="biweekly" class="mr-4">
                                <span class="checkmark"></span>
                                Quincenalmente
                            </label>
                            <label class="frequency-option flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-button">
                                <input type="radio" name="frequency" value="monthly" class="mr-4">
                                <span class="checkmark"></span>
                                Mensualmente
                            </label>
                        </div>
                        <p class="info-text italic text-text-light leading-relaxed">
                            Puede donar por única vez o con una frecuencia recurrente. En caso de elegir una frecuencia, se realizarán automáticamente según lo seleccionado. Por defecto, se realiza una donación única.
                        </p>
                    </div>
                </div>

                <!-- Columna Derecha - Datos de Tarjeta -->
                <div class="donation-right">
                    <h3 class="subtitle text-xl font-semibold text-black mb-6">Datos de la Tarjeta</h3>
                    <form id="cardForm" class="card-form bg-background-light p-8 rounded-xl">
                        <div class="form-row mb-6">
                            <div class="form-group full-width">
                                <label for="cardHolder" class="block font-semibold text-text-dark mb-2">Titular de la tarjeta</label>
                                <input type="text" id="cardHolder" name="cardHolder" required placeholder="Titular de la tarjeta" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                        </div>

                        <div class="form-row mb-6">
                            <div class="form-group full-width">
                                <label for="cardNumber" class="block font-semibold text-text-dark mb-2">Número de tarjeta</label>
                                <input type="text" id="cardNumber" name="cardNumber" required placeholder="1234 5678 9012 3456" maxlength="19" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                        </div>

                        <div class="form-row grid grid-cols-2 gap-4 mb-6">
                            <div class="form-group">
                                <label for="expiryDate" class="block font-semibold text-text-dark mb-2">Fecha de vencimiento</label>
                                <input type="text" id="expiryDate" name="expiryDate" required placeholder="MM/YY" maxlength="5" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                            <div class="form-group">
                                <label for="cvv" class="block font-semibold text-text-dark mb-2">Código de seguridad</label>
                                <input type="text" id="cvv" name="cvv" required placeholder="Código de seguridad" maxlength="4" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                        </div>

                        <div class="form-row grid grid-cols-2 gap-4 mb-6">
                            <div class="form-group">
                                <label for="dni" class="block font-semibold text-text-dark mb-2">Número de documento</label>
                                <input type="text" id="dni" name="dni" required placeholder="DNI o CUIT" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                            <div class="form-group">
                                <label for="email" class="block font-semibold text-text-dark mb-2">Correo Electrónico</label>
                                <input type="email" id="email" name="email" required placeholder="Email" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                        </div>

                        <button type="submit" class="donate-button w-full bg-button text-white py-4 px-8 border-none rounded-lg cursor-pointer font-poppins font-semibold text-lg hover:bg-blue-500 hover:translate-y-[-2px] transition-all mb-4">Donar Ahora</button>

                        <p class="info-text italic text-text-light text-sm leading-relaxed">
                            En el resumen de su tarjeta, el cargo aparecerá como MERPAGO*PQAINMACONCEP.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección 2 y 3: Transferencia Bancaria y QR -->
    <section class="donation-methods bg-white py-12">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="methods-grid grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <!-- Sección 2: Transferencia Bancaria -->
                <div class="method-left">
                    <h2 class="method-title text-2xl font-semibold text-text-dark mb-8">Transferencia Bancaria</h2>
                    <div class="bank-details bg-background-light p-8 rounded-xl">
                        <div class="bank-info">
                            <div class="bank-detail flex justify-between items-center py-4 border-b border-gray-300">
                                <strong class="text-text-dark min-w-24">Titular:</strong>
                                <span>Parroquia Inmaculada Concepción</span>
                            </div>
                            <div class="bank-detail flex justify-between items-center py-4 border-b border-gray-300">
                                <strong class="text-text-dark min-w-24">CBU:</strong>
                                <span>0070365720000000151573</span>
                            </div>
                            <div class="bank-detail flex justify-between items-center py-4 border-b border-gray-300">
                                <strong class="text-text-dark min-w-24">Alias:</strong>
                                <span>laredondadebelgrano</span>
                            </div>
                            <div class="bank-detail flex justify-between items-center py-4 border-b border-gray-300">
                                <strong class="text-text-dark min-w-24">Cuenta Corriente n:°</strong>
                                <span>15153657</span>
                            </div>
                            <div class="bank-detail flex justify-between items-center py-4">
                                <strong class="text-text-dark min-w-24">CUIT:</strong>
                                <span>30-51907091-6</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Donar QR -->
                <div class="method-right">
                    <h2 class="method-title text-2xl font-semibold text-text-dark mb-8">Donar QR</h2>
                    <div class="qr-section text-center">
                        <div class="qr-placeholder w-48 h-48 bg-gray-100 border-2 border-dashed border-gray-400 rounded-xl flex items-center justify-center text-text-light mx-auto mb-6">
                            [CÓDIGO QR]
                        </div>
                        <p class="info-text text-text-light leading-relaxed">
                            Escanee este código QR con su aplicación bancaria y/o Mercado Pago para realizar una donación.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal de Confirmación -->
<div id="confirmationModal" class="modal hidden fixed inset-0 bg-black bg-opacity-70 z-50 items-center justify-center p-4">
    <div class="modal-content confirmation-modal bg-white p-8 rounded-xl max-w-md w-full max-h-[85vh] overflow-y-auto shadow-2xl">
        <span class="modal-close absolute top-4 right-6 cursor-pointer text-2xl text-text-light hover:text-button transition-colors">&times;</span>
        <h2 class="text-2xl font-semibold text-text-dark mb-6">Confirmar Donación</h2>

        <div class="confirmation-details bg-background-light p-6 rounded-lg mb-6">
            <div class="detail-row flex justify-between items-center py-3 border-b border-gray-300">
                <strong class="text-text-dark">Monto:</strong>
                <span id="confirmAmount">$0</span>
            </div>
            <div class="detail-row flex justify-between items-center py-3 border-b border-gray-300">
                <strong class="text-text-dark">Frecuencia:</strong>
                <span id="confirmFrequency">Única</span>
            </div>
            <div class="detail-row flex justify-between items-center py-3 border-b border-gray-300">
                <strong class="text-text-dark">Tarjeta:</strong>
                <span id="confirmCard">**** **** **** 1234</span>
            </div>
            <div class="detail-row flex justify-between items-center py-3">
                <strong class="text-text-dark">Email:</strong>
                <span id="confirmEmail">usuario@email.com</span>
            </div>
        </div>

        <div class="modal-actions flex gap-4 justify-end">
            <button type="button" class="cancel-btn bg-gray-100 text-text-dark py-3 px-6 border-2 border-gray-300 rounded-lg cursor-pointer font-semibold hover:bg-gray-200 transition-all" id="cancelDonation">Cancelar</button>
            <button type="button" class="confirm-btn bg-button text-white py-3 px-6 border-none rounded-lg cursor-pointer font-semibold hover:bg-blue-500 transition-all" id="confirmDonation">Confirmar Donación</button>
        </div>
    </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="js/donaciones.js"></script>

@endsection
