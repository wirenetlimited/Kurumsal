<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-orange-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Fatura Düzenle</h1>
                    <p class="text-red-100 mt-1">Fatura bilgilerini güncelleyin</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">📄</div>
                    <div class="text-red-100">Fatura Düzenleme</div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <form method="POST" action="{{ route('invoices.update', $invoice) }}" class="p-8 space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Temel Bilgiler</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Müşteri *</label>
                            <select name="customer_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                    required>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ $c->id == $invoice->customer_id ? 'selected' : '' }}>
                                        {{ $c->customer_type === 'corporate' ? $c->name : $c->name . ' ' . ($c->surname ?? '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Para Birimi *</label>
                            <select name="currency" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                    required>
                                <option value="TRY" {{ $invoice->currency == 'TRY' ? 'selected' : '' }}>🇹🇷 TRY (Türk Lirası)</option>
                                <option value="USD" {{ $invoice->currency == 'USD' ? 'selected' : '' }}>🇺🇸 USD (Amerikan Doları)</option>
                                <option value="EUR" {{ $invoice->currency == 'EUR' ? 'selected' : '' }}>🇪🇺 EUR (Euro)</option>
                                <option value="GBP" {{ $invoice->currency == 'GBP' ? 'selected' : '' }}>🇬🇧 GBP (İngiliz Sterlini)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Durum *</label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                <option value="draft" {{ $invoice->status->value === 'draft' ? 'selected' : '' }}>Taslak</option>
                                <option value="sent" {{ $invoice->status->value === 'sent' ? 'selected' : '' }}>Gönderildi</option>
                                <option value="paid" {{ $invoice->status->value === 'paid' ? 'selected' : '' }}>Ödendi</option>
                                <option value="overdue" {{ $invoice->status->value === 'overdue' ? 'selected' : '' }}>Gecikmiş</option>
                                <option value="cancelled" {{ $invoice->status->value === 'cancelled' ? 'selected' : '' }}>İptal</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Date Information -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Tarih Bilgileri</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Fatura Tarihi *</label>
                            <input type="date" name="issue_date" value="{{ old('issue_date', $invoice->issue_date?->format('Y-m-d')) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Vade Tarihi</label>
                            <input type="date" name="due_date" value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Invoice Items -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Fatura Kalemleri</h2>
                        </div>
                        <button type="button" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                                onclick="addRow()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Kalem Ekle
                        </button>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Hizmet</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Açıklama</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Miktar</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Birim Fiyat</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">KDV %</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Toplam</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody" class="divide-y divide-gray-200">
                                    @php $rowIndex = 0; @endphp
                                    @foreach($invoice->items as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4">
                                            <select name="items[{{ $rowIndex }}][service_id]" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                <option value="">Hizmet seçin</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}" {{ $item->service_id == $s->id ? 'selected' : '' }}>
                                                        [{{ $s->service_code }}] {{ $s->display_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-3 px-4">
                                            <input name="items[{{ $rowIndex }}][description]" value="{{ $item->description }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                                   placeholder="Açıklama" required>
                                        </td>
                                        <td class="py-3 px-4">
                                            <input name="items[{{ $rowIndex }}][qty]" type="number" min="0.01" step="0.01" value="{{ $item->qty }}" 
                                                   class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                                   required onchange="calculateRowTotal(this)">
                                        </td>
                                        <td class="py-3 px-4">
                                            <input name="items[{{ $rowIndex }}][unit_price]" type="number" step="0.01" min="0" value="{{ $item->unit_price }}" 
                                                   class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                                   required onchange="calculateRowTotal(this)">
                                        </td>
                                        <td class="py-3 px-4">
                                            <input name="items[{{ $rowIndex }}][tax_rate]" type="number" min="0" max="100" step="0.01" value="{{ $item->tax_rate }}" 
                                                   class="w-16 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                                   onchange="calculateRowTotal(this)">
                                        </td>
                                        <td class="py-3 px-4">
                                            @php $rowSubtotal = (float)$item->qty * (float)$item->unit_price; $rowTotal = $rowSubtotal + ($rowSubtotal * (float)$item->tax_rate / 100); @endphp
                                            <span class="row-total font-medium text-gray-900">₺{{ number_format($rowTotal, 2, '.', '') }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <button type="button" 
                                                    class="text-red-600 hover:text-red-800 transition-colors" 
                                                    onclick="removeRow(this)">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                            <input type="hidden" name="items[{{ $rowIndex }}][id]" value="{{ $item->id }}">
                                        </td>
                                    </tr>
                                    @php $rowIndex++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Financial Summary (preview) -->
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Fatura Özeti (Önizleme)</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Ara Toplam</label>
                            <div class="text-2xl font-bold text-gray-900" id="subtotal">₺0.00</div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">KDV Toplam</label>
                            <div class="text-2xl font-bold text-blue-600" id="taxTotal">₺0.00</div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Genel Toplam</label>
                            <div class="text-3xl font-bold text-green-600" id="grandTotal">₺0.00</div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('invoices.show', $invoice) }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        İptal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg hover:from-red-700 hover:to-orange-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Faturayı Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    let rowIndex = {{ $invoice->items->count() }};

    function addRow() {
        const body = document.getElementById('itemsBody');
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50';
        tr.innerHTML = `
            <td class="py-3 px-4">
                <select name="items[${rowIndex}][service_id]" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Hizmet seçin</option>
                    @foreach($services as $s)
                        <option value="{{ $s->id }}">[{{ $s->service_code }}] {{ $s->display_name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="py-3 px-4">
                <input name="items[${rowIndex}][description]" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                       placeholder="Açıklama" required>
            </td>
            <td class="py-3 px-4">
                <input name="items[${rowIndex}][qty]" type="number" min="0.01" step="0.01" value="1" 
                       class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                       required onchange="calculateRowTotal(this)">
            </td>
            <td class="py-3 px-4">
                <input name="items[${rowIndex}][unit_price]" type="number" step="0.01" min="0" value="0" 
                       class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                       required onchange="calculateRowTotal(this)">
            </td>
            <td class="py-3 px-4">
                <input name="items[${rowIndex}][tax_rate]" type="number" min="0" max="100" step="0.01" value="20" 
                       class="w-16 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                       onchange="calculateRowTotal(this)">
            </td>
            <td class="py-3 px-4">
                <span class="row-total font-medium text-gray-900">₺0.00</span>
            </td>
            <td class="py-3 px-4">
                <button type="button" 
                        class="text-red-600 hover:text-red-800 transition-colors" 
                        onclick="removeRow(this)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </td>
        `;
        body.appendChild(tr);
        rowIndex++;
        calculateTotals();
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

    document.addEventListener('DOMContentLoaded', function() {
        calculateTotals();
    });
</script>
