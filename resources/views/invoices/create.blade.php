<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-red-50 to-orange-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Header -->
            <div class="relative overflow-hidden bg-gradient-to-r from-red-600 via-orange-600 to-amber-600 rounded-3xl p-8 shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center justify-between">
                    <div class="space-y-2">
                        <h1 class="text-4xl font-bold text-white drop-shadow-lg">Yeni Fatura Oluştur</h1>
                        <p class="text-red-100 text-lg">Müşteri için yeni fatura kaydı oluşturun</p>
                    </div>
                    <div class="text-right">
                        <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm">
                            <div class="text-4xl">🧾</div>
                        </div>
                        <div class="text-red-100 text-lg mt-2">Fatura Kaydı</div>
                    </div>
                </div>
                
                <!-- Dekoratif Elementler -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
            </div>

            <!-- Form -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                <form method="POST" action="{{ route('invoices.store') }}" class="relative p-8 space-y-8">
                    @csrf
                    
                    <!-- Fatura Bilgileri -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fatura Bilgileri</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Müşteri *</label>
                                <select name="customer_id" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400" 
                                        required>
                                    <option value="">Müşteri seçin</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id')==$c->id ? 'selected' : '' }}>
                                            {{ $c->customer_type === 'corporate' ? $c->name : $c->name . ' ' . ($c->surname ?? '') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Fatura Tarihi *</label>
                                <input type="date" name="issue_date" value="{{ old('issue_date', now()->format('Y-m-d')) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400" 
                                       required>
                            </div>
                            
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Vade Tarihi</label>
                                <input type="date" name="due_date" value="{{ old('due_date') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Para Birimi *</label>
                                <select name="currency" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400" 
                                        required>
                                    <option value="TRY" {{ old('currency','TRY')==='TRY' ? 'selected' : '' }}>🇹🇷 Türk Lirası (TRY)</option>
                                    <option value="USD" {{ old('currency')==='USD' ? 'selected' : '' }}>🇺🇸 Amerikan Doları (USD)</option>
                                    <option value="EUR" {{ old('currency')==='EUR' ? 'selected' : '' }}>🇪🇺 Euro (EUR)</option>
                                    <option value="GBP" {{ old('currency')==='GBP' ? 'selected' : '' }}>🇬🇧 İngiliz Sterlini (GBP)</option>
                                </select>
                            </div>
                            
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Fatura Numarası</label>
                                <input name="invoice_number" value="{{ old('invoice_number') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 hover:border-gray-400" 
                                       placeholder="Otomatik oluşturulacak">
                            </div>
                        </div>
                    </div>

                    <!-- Fatura Kalemleri -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fatura Kalemleri</h2>
                            </div>
                            
                            <button type="button" 
                                    class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105"
                                    onclick="addRow()">
                                <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-white/10 rounded-2xl"></div>
                                <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="relative z-10">Kalem Ekle</span>
                            </button>
                        </div>
                        
                        <div class="relative bg-gray-50/80 dark:bg-gray-700/80 rounded-2xl p-6 border border-gray-200/50 dark:border-gray-600/50">
                            <div class="w-full">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <th class="text-left py-4 px-4 font-bold text-gray-700 dark:text-gray-300">Hizmet</th>
                                            <th class="text-left py-4 px-4 font-bold text-gray-700 dark:text-gray-300">Açıklama</th>
                                            <th class="text-left py-4 px-4 font-bold text-gray-700 dark:text-gray-300">Miktar</th>
                                            <th class="text-left py-4 px-4 font-bold text-gray-700 dark:text-gray-300">Birim Fiyat</th>
                                            <th class="text-left py-4 px-4 font-bold text-gray-700 dark:text-gray-300">KDV %</th>
                                            <th class="text-left py-4 px-4 font-bold text-gray-700 dark:text-gray-300">Toplam</th>
                                            <th class="text-left py-4 px-4 font-bold text-gray-700 dark:text-gray-300">İşlem</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                                        <tr class="hover:bg-gray-100/50 dark:hover:bg-gray-600/50 transition-all duration-200">
                                            <td class="py-4 px-4">
                                                <select name="items[0][service_id]" 
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400">
                                                    <option value="">Hizmet seçin</option>
                                                    @foreach($services as $s)
                                                        <option value="{{ $s->id }}">[{{ $s->service_code }}] {{ $s->display_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="py-4 px-4">
                                                <input name="items[0][description]" 
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 hover:border-gray-400" 
                                                       placeholder="Açıklama" required>
                                            </td>
                                            <td class="py-4 px-4">
                                                <input name="items[0][qty]" type="number" min="1" value="1" 
                                                       class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400" 
                                                       required onchange="calculateRowTotal(this)">
                                            </td>
                                            <td class="py-4 px-4">
                                                <input name="items[0][unit_price]" type="number" step="0.01" min="0" value="0" 
                                                       class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400" 
                                                       required onchange="calculateRowTotal(this)">
                                            </td>
                                            <td class="py-4 px-4">
                                                <input name="items[0][tax_rate]" type="number" min="0" max="100" value="20" 
                                                       class="w-16 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400" 
                                                       onchange="calculateRowTotal(this)">
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="row-total font-semibold text-gray-900 dark:text-white">₺0.00</span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <button type="button" 
                                                        class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200" 
                                                        onclick="removeRow(this)">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Fatura Özeti -->
                    <div class="relative bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-800/30 rounded-2xl p-8 border border-blue-200/50 dark:border-blue-700/50">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/30 to-blue-50/30 dark:from-blue-900/20 dark:to-indigo-800/20 rounded-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fatura Özeti</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Ara Toplam</label>
                                    <div class="text-3xl font-bold text-gray-900 dark:text-white" id="subtotal">₺0.00</div>
                                </div>
                                
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">KDV Toplam</label>
                                    <div class="text-3xl font-bold text-blue-600" id="taxTotal">₺0.00</div>
                                </div>
                                
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Genel Toplam</label>
                                    <div class="text-4xl font-bold text-green-600" id="grandTotal">₺0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-4 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('invoices.index') }}" 
                           class="group relative inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl"></div>
                            <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="relative z-10">İptal</span>
                        </a>
                        <button type="submit" 
                                class="group relative inline-flex items-center px-8 py-3 bg-gradient-to-r from-red-600 via-orange-600 to-amber-600 text-white font-semibold rounded-2xl hover:from-red-700 hover:via-orange-700 hover:to-amber-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-white/10 rounded-2xl"></div>
                            <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="relative z-10">Faturayı Kaydet</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let rowIndex = 1;
        
        function addRow() {
            const body = document.getElementById('itemsBody');
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-100/50 dark:hover:bg-gray-600/50 transition-all duration-200';
            tr.innerHTML = `
                <td class="py-4 px-4">
                    <select name="items[${rowIndex}][service_id]" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400">
                        <option value="">Hizmet seçin</option>
                        @foreach($services as $s)
                            <option value="{{ $s->id }}">[{{ $s->service_code }}] {{ $s->display_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="py-4 px-4">
                    <input name="items[${rowIndex}][description]" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 hover:border-gray-400" 
                           placeholder="Açıklama" required>
                </td>
                <td class="py-4 px-4">
                    <input name="items[${rowIndex}][qty]" type="number" min="1" value="1" 
                           class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400" 
                           required onchange="calculateRowTotal(this)">
                </td>
                <td class="py-4 px-4">
                    <input name="items[${rowIndex}][unit_price]" type="number" step="0.01" min="0" value="0" 
                           class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400" 
                           required onchange="calculateRowTotal(this)">
                </td>
                <td class="py-4 px-4">
                    <input name="items[${rowIndex}][tax_rate]" type="number" min="0" max="100" value="20" 
                           class="w-16 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white hover:border-gray-400" 
                           onchange="calculateRowTotal(this)">
                </td>
                <td class="py-4 px-4">
                    <span class="row-total font-semibold text-gray-900 dark:text-white">₺0.00</span>
                </td>
                <td class="py-4 px-4">
                    <button type="button" 
                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200" 
                            onclick="removeRow(this)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </td>
            `;
            body.appendChild(tr);
            rowIndex++;
        }
        
        function removeRow(btn) {
            btn.closest('tr').remove();
            calculateTotals();
        }
        
        function calculateRowTotal(input) {
            const row = input.closest('tr');
            const qty = parseFloat(row.querySelector('input[name*="[qty]"]').value) || 0;
            const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
            const taxRate = parseFloat(row.querySelector('input[name*="[tax_rate]"]').value) || 0;
            
            const subtotal = qty * unitPrice;
            const taxAmount = subtotal * (taxRate / 100);
            const total = subtotal + taxAmount;
            
            row.querySelector('.row-total').textContent = `₺${total.toFixed(2)}`;
            calculateTotals();
        }
        
        function calculateTotals() {
            let subtotal = 0;
            let taxTotal = 0;
            
            document.querySelectorAll('#itemsBody tr').forEach(row => {
                const qty = parseFloat(row.querySelector('input[name*="[qty]"]').value) || 0;
                const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
                const taxRate = parseFloat(row.querySelector('input[name*="[tax_rate]"]').value) || 0;
                
                const rowSubtotal = qty * unitPrice;
                const rowTaxAmount = rowSubtotal * (taxRate / 100);
                
                subtotal += rowSubtotal;
                taxTotal += rowTaxAmount;
            });
            
            const grandTotal = subtotal + taxTotal;
            
            document.getElementById('subtotal').textContent = `₺${subtotal.toFixed(2)}`;
            document.getElementById('taxTotal').textContent = `₺${taxTotal.toFixed(2)}`;
            document.getElementById('grandTotal').textContent = `₺${grandTotal.toFixed(2)}`;
        }
        
        // Sayfa yüklendiğinde ilk hesaplamayı yap
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotals();
        });
    </script>
</x-app-layout>


