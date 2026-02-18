@extends('layouts.app')

@section('content')
<div class="donations-container">
    <section class="donation-section full-width bg-white py-8 md:py-12 border-b-2 border-background-light">
        <div class="container max-w-7xl mx-auto px-4">
            <h1 class="donation-title text-3xl md:text-4xl font-semibold text-text-dark text-center mb-8 md:mb-12 border-b-2 border-black pb-2">Donar con tarjeta</h1>

            <div class="donation-content grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 max-w-6xl mx-auto">
                <div class="donation-left">
                    <div class="form-group mb-8">
                        <h3 class="subtitle text-xl font-semibold text-black mb-4 md:mb-6">Monto</h3>
                        <div class="amount-options grid grid-cols-2 gap-3 md:gap-4 mb-6 md:mb-8">
                            <button type="button" class="amount-option px-4 py-3 md:px-6 md:py-4 border-2 border-button bg-white text-button rounded-lg cursor-pointer font-semibold text-center hover:bg-button hover:text-white transition-all text-sm md:text-base" data-amount="1000">$1.000</button>
                            <button type="button" class="amount-option px-4 py-3 md:px-6 md:py-4 border-2 border-button bg-white text-button rounded-lg cursor-pointer font-semibold text-center hover:bg-button hover:text-white transition-all text-sm md:text-base" data-amount="2000">$2.000</button>
                            <button type="button" class="amount-option px-4 py-3 md:px-6 md:py-4 border-2 border-button bg-white text-button rounded-lg cursor-pointer font-semibold text-center hover:bg-button hover:text-white transition-all text-sm md:text-base" data-amount="10000">$10.000</button>
                            <button type="button" class="amount-option px-4 py-3 md:px-6 md:py-4 border-2 border-button bg-white text-button rounded-lg cursor-pointer font-semibold text-center hover:bg-button hover:text-white transition-all text-sm md:text-base" data-amount="20000">$20.000</button>
                            <div class="custom-amount col-span-2 relative">
                                <input type="number" id="customAmount" placeholder="Monto personalizado" min="100" class="w-full px-4 py-3 md:py-4 pl-12 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-light font-semibold">$</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <h3 class="subtitle text-xl font-semibold text-black mb-4 md:mb-6">Frecuencia</h3>
                        <div class="frequency-options space-y-3 mb-6">
                            <label class="frequency-option flex items-center p-3 md:p-4 border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-button">
                                <input type="radio" name="frequency" value="once" checked class="mr-3 md:mr-4 h-5 w-5 text-button focus:ring-button">
                                <span class="checkmark"></span>
                                Únicamente
                            </label>
                            <label class="frequency-option flex items-center p-3 md:p-4 border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-button">
                                <input type="radio" name="frequency" value="weekly" class="mr-3 md:mr-4 h-5 w-5 text-button focus:ring-button">
                                <span class="checkmark"></span>
                                Semanalmente
                            </label>
                            <label class="frequency-option flex items-center p-3 md:p-4 border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-button">
                                <input type="radio" name="frequency" value="biweekly" class="mr-3 md:mr-4 h-5 w-5 text-button focus:ring-button">
                                <span class="checkmark"></span>
                                Quincenalmente
                            </label>
                            <label class="frequency-option flex items-center p-3 md:p-4 border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-button">
                                <input type="radio" name="frequency" value="monthly" class="mr-3 md:mr-4 h-5 w-5 text-button focus:ring-button">
                                <span class="checkmark"></span>
                                Mensualmente
                            </label>
                        </div>
                        <p class="info-text italic text-text-light leading-relaxed text-sm md:text-base">
                            Puede donar por única vez o con una frecuencia recurrente. En caso de elegir una frecuencia, se realizarán automáticamente según lo seleccionado. Por defecto, se realiza una donación única.
                        </p>
                    </div>
                </div>

                <div class="donation-right">
                    <h3 class="subtitle text-xl font-semibold text-black mb-4 md:mb-6">Datos de la Tarjeta</h3>
                    <form id="cardForm" class="card-form bg-background-light p-6 md:p-8 rounded-xl shadow-sm">
                        <div class="form-row mb-4 md:mb-6">
                            <div class="form-group full-width">
                                <label for="cardHolder" class="block font-semibold text-text-dark mb-2 text-sm md:text-base">Titular de la tarjeta</label>
                                <input type="text" id="cardHolder" name="cardHolder" required placeholder="Como figura en la tarjeta" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                        </div>

                        <div class="form-row mb-4 md:mb-6">
                            <div class="form-group full-width">
                                <label for="cardNumber" class="block font-semibold text-text-dark mb-2 text-sm md:text-base">Número de tarjeta</label>
                                <input type="text" id="cardNumber" name="cardNumber" required placeholder="1234 5678 9012 3456" maxlength="19" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                        </div>

                        <div class="form-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 md:mb-6">
                            <div class="form-group">
                                <label for="expiryDate" class="block font-semibold text-text-dark mb-2 text-sm md:text-base">Vencimiento (MM/YY)</label>
                                <input type="text" id="expiryDate" name="expiryDate" required placeholder="MM/YY" maxlength="5" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                            <div class="form-group">
                                <label for="cvv" class="block font-semibold text-text-dark mb-2 text-sm md:text-base">Código de seguridad</label>
                                <input type="text" id="cvv" name="cvv" required placeholder="CVV" maxlength="4" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                        </div>

                        <div class="form-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="form-group">
                                <label for="dni" class="block font-semibold text-text-dark mb-2 text-sm md:text-base">Documento</label>
                                <input type="text" id="dni" name="dni" required placeholder="DNI o CUIT" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                            <div class="form-group">
                                <label for="email" class="block font-semibold text-text-dark mb-2 text-sm md:text-base">Email</label>
                                <input type="email" id="email" name="email" required placeholder="Email" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                            </div>
                        </div>

                        <button type="submit" class="donate-button w-full bg-button text-white py-3 md:py-4 px-8 border-none rounded-lg cursor-pointer font-poppins font-semibold text-lg hover:bg-blue-900 hover:translate-y-[-2px] transition-all mb-4 shadow-md">Donar Ahora</button>

                        <p class="info-text italic text-text-light text-xs md:text-sm leading-relaxed text-center">
                            En el resumen de su tarjeta, el cargo aparecerá como MERPAGO*PQAINMACONCEP.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="donation-methods bg-white py-8 md:py-12">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="methods-grid grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 max-w-6xl mx-auto">
                <div class="method-left">
                    <h2 class="method-title text-2xl font-semibold text-text-dark mb-6 md:mb-8 text-center md:text-left">Transferencia Bancaria</h2>
                    <div class="bank-details bg-background-light p-6 md:p-8 rounded-xl shadow-sm border border-gray-100">
                        <div class="bank-info space-y-4"> 
                            <div class="bank-detail flex flex-col md:flex-row md:justify-between md:items-center py-3 border-b border-gray-300">
                                <strong class="text-text-dark min-w-24 mb-1 md:mb-0">Titular:</strong>
                                <span class="text-left md:text-right text-sm md:text-base break-words">Parroquia Inmaculada Concepción</span>
                            </div>
                            <div class="bank-detail flex flex-col md:flex-row md:justify-between md:items-center py-3 border-b border-gray-300">
                                <strong class="text-text-dark min-w-24 mb-1 md:mb-0">CBU:</strong>
                                <div class="flex items-center justify-between md:justify-end gap-2 w-full md:w-auto">
                                    <span class="font-mono text-sm md:text-base bg-gray-100 px-2 py-1 rounded select-all">0070365720000000151573</span>
                                    <button type="button" onclick="copyToClipboard('0070365720000000151573')" class="text-button hover:text-blue-900 transition-colors p-1" title="Copiar CBU">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="bank-detail flex flex-col md:flex-row md:justify-between md:items-center py-3 border-b border-gray-300">
                                <strong class="text-text-dark min-w-24 mb-1 md:mb-0">Alias:</strong>
                                <div class="flex items-center justify-between md:justify-end gap-2 w-full md:w-auto">
                                    <span class="font-bold text-button text-sm md:text-base select-all">laredondadebelgrano</span>
                                    <button type="button" onclick="copyToClipboard('laredondadebelgrano')" class="text-button hover:text-blue-900 transition-colors p-1" title="Copiar Alias">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="method-right pb-12">
                    <h2 class="method-title text-2xl font-semibold text-text-dark mb-6 md:mb-8 text-center md:text-left">Donar QR</h2>
                    <div class="qr-section text-center bg-background-light p-6 md:p-8 rounded-xl shadow-sm border border-gray-100 h-full flex flex-col justify-center">
                        <div class="qr-placeholder w-48 h-48 flex items-center justify-center text-text-light mx-auto mb-6 shadow-inner bg-white rounded-lg p-2">
                            <img src="{{ asset('img/qr-mercadopago-inmaculada.jpeg') }}" alt="Codigo QR MercadoPago" class="w-full h-full object-contain">
                        </div>
                        <p class="info-text text-text-light leading-relaxed text-sm md:text-base">
                            Escanee el código QR o copie el alias para realizar una donación desde su billetera virtual.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- MODAL DE CONFIRMACIÓN --}}
<div id="confirmationModal" class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-black bg-opacity-70">
    <div class="relative transform bg-white rounded-2xl shadow-2xl transition-all w-full max-w-md p-6 md:p-8 overflow-hidden">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-text-dark">Confirmar Donación</h2>
            <span class="modal-close cursor-pointer text-2xl text-gray-400 hover:text-red-500">&times;</span>
        </div>
        <div class="confirmation-details bg-gray-50 p-6 rounded-2xl mb-6 border border-gray-100 space-y-4">
            <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                <strong class="text-gray-500 text-xs uppercase tracking-widest">Monto</strong>
                <span id="confirmAmount" class="font-black text-button text-xl"></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                <strong class="text-gray-500 text-xs uppercase tracking-widest">Frecuencia</strong>
                <span id="confirmFrequency" class="font-bold text-text-dark"></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                <strong class="text-gray-500 text-xs uppercase tracking-widest">Tarjeta</strong>
                <span id="confirmCard" class="font-mono text-sm text-text-dark"></span>
            </div>
            <div class="flex flex-col">
                <strong class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Email de contacto</strong>
                <span id="confirmEmail" class="text-sm font-medium text-gray-700 break-all"></span>
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-3">
            <button type="button" id="cancelDonation" class="flex-1 py-4 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition-all uppercase text-xs tracking-widest">Cancelar</button>
            <button type="button" id="confirmDonation" class="flex-1 py-4 bg-button text-white font-bold rounded-xl hover:bg-blue-900 transition-all shadow-lg shadow-blue-100 uppercase text-xs tracking-widest">Confirmar</button>
        </div>
    </div>
</div>

{{-- MODAL DE ESTADO (Resultado final) --}}
<div id="statusModal" class="hidden fixed inset-0 z-[60] items-center justify-center p-4 bg-black bg-opacity-75 backdrop-blur-sm">
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center animate-fade-in">
        <div id="statusModalIcon" class="mb-6">
            {{-- Icono dinámico vía JS --}}
        </div>
        <h3 id="statusModalTitle" class="text-2xl font-black text-text-dark mb-2 uppercase tracking-tighter"></h3>
        <p id="statusModalMessage" class="text-text-light mb-8 text-sm leading-relaxed"></p>
        <button id="closeStatusModal" class="w-full bg-button text-white py-4 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-blue-900 transition-all shadow-lg shadow-blue-100">
            Entendido
        </button>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="js/donaciones.js"></script>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Podrías añadir un pequeño feedback visual aquí si quisieras
        }, function(err) {
            console.error('Error al copiar: ', err);
        });
    }
</script>

<style>
    @keyframes fade-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
</style>
@endsection