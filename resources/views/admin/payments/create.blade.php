<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-4xl mx-auto p-6 space-y-8">
            <!-- Başlık -->
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-3xl p-8 shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center justify-between">
                    <div class="space-y-2">
                        <h1 class="text-4xl font-bold text-white drop-shadow-lg">Yeni Ödeme Ekle</h1>
                        <p class="text-blue-100 text-lg">Genel ödeme kaydı oluşturun</p>
                    </div>
                    <a href="{{ route('admin.payments.index') }}" class="group relative inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-2xl hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-white/10 rounded-2xl"></div>
                        <svg class="w-5 h-5 mr-3 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="relative z-10 font-semibold">Geri Dön</span>
                    </a>
                </div>
                
                <!-- Dekoratif Elementler -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
            </div>

            <!-- Form -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                <div class="relative">
                    <form method="POST" action="{{ route('admin.payments.store') }}" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Müşteri Seçimi -->
                            <div class="space-y-3">
                                <label for="customer_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                    Müşteri <span class="text-red-500">*</span>
                                </label>
                                <select id="customer_id" name="customer_id" required 
                                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 shadow-sm">
                                    <option value="">Müşteri seçin</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->customer_type === 'corporate' ? $customer->name : $customer->name . ' ' . ($customer->surname ?? '') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fatura Seçimi -->
                            <div class="space-y-3">
                                <label for="invoice_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                    Fatura (Opsiyonel)
                                </label>
                                <select id="invoice_id" name="invoice_id" 
                                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 shadow-sm">
                                    <option value="">Fatura seçin</option>
                                    @foreach($invoices as $invoice)
                                        <option value="{{ $invoice->id }}" {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                            {{ $invoice->invoice_number ?? '#' . $invoice->id }} - ₺{{ number_format($invoice->total, 2, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('invoice_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tutar -->
                            <div class="space-y-3">
                                <label for="amount" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                    Tutar <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 font-semibold">₺</span>
                                    <input type="number" id="amount" name="amount" step="0.01" min="0.01" required
                                           value="{{ old('amount') }}"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 shadow-sm"
                                           placeholder="0.00">
                                </div>
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ödeme Yöntemi -->
                            <div class="space-y-3">
                                <label for="method" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                    Ödeme Yöntemi <span class="text-red-500">*</span>
                                </label>
                                <select id="payment_method" name="payment_method" required 
                                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 shadow-sm">
                                    <option value="">Yöntem seçin</option>
                                    <option value="cash" {{ old('method') === 'cash' ? 'selected' : '' }}>Nakit</option>
                                    <option value="bank" {{ old('method') === 'bank' ? 'selected' : '' }}>Banka</option>
                                    <option value="card" {{ old('method') === 'card' ? 'selected' : '' }}>Kart</option>
                                    <option value="transfer" {{ old('method') === 'transfer' ? 'selected' : '' }}>Havale</option>
                                    <option value="other" {{ old('method') === 'other' ? 'selected' : '' }}>Diğer</option>
                                </select>
                                @error('method')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ödeme Tarihi -->
                            <div class="space-y-3">
                                <label for="paid_at" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                    Ödeme Tarihi <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="paid_at" name="paid_at" required
                                       value="{{ old('paid_at', now()->format('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 shadow-sm">
                                @error('paid_at')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ödeme Tarihi (Kayıt) -->
                            <div class="space-y-3">
                                <label for="payment_date" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                    Ödeme Kayıt Tarihi <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="payment_date" name="payment_date" required
                                       value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 shadow-sm">
                                @error('payment_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notlar -->
                            <div class="md:col-span-2 space-y-3">
                                <label for="notes" class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                    Notlar
                                </label>
                                <textarea id="notes" name="notes" rows="4"
                                          class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 shadow-sm resize-none"
                                          placeholder="Ödeme ile ilgili notlar...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.payments.index') }}" 
                               class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                İptal
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-2xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Ödemeyi Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Müşteri seçildiğinde faturaları güncelle
        document.getElementById('customer_id').addEventListener('change', function() {
            const customerId = this.value;
            const invoiceSelect = document.getElementById('invoice_id');
            
            if (customerId) {
                // AJAX ile müşterinin faturalarını getir
                fetch(`/admin/payments/customer-invoices/${customerId}`)
                    .then(response => response.json())
                    .then(data => {
                        invoiceSelect.innerHTML = '<option value="">Fatura seçin</option>';
                        
                        data.invoices.forEach(invoice => {
                            const option = document.createElement('option');
                            option.value = invoice.id;
                            option.textContent = `${invoice.invoice_number || '#' + invoice.id} - ₺${parseFloat(invoice.total).toFixed(2)}`;
                            invoiceSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching invoices:', error);
                    });
            } else {
                invoiceSelect.innerHTML = '<option value="">Fatura seçin</option>';
            }
        });
    </script>
</x-app-layout>
