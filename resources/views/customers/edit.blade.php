<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Müşteri Düzenle</h1>
                    <p class="text-blue-100 mt-1">Müşteri bilgilerini güncelleyin</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">✏️</div>
                    <div class="text-blue-100">Müşteri Düzenleme</div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <form method="POST" action="{{ route('customers.update', $customer) }}" class="p-8 space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Müşteri Türü -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Müşteri Türü</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="customer_type" value="individual" {{ old('customer_type', $customer->customer_type)==='individual' ? 'checked' : '' }} class="sr-only">
                            <div class="border-2 border-gray-200 rounded-xl p-4 transition-all duration-200 hover:border-blue-300 hover:shadow-md peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full hidden peer-checked:block"></div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Bireysel Müşteri</div>
                                        <div class="text-sm text-gray-500">Gerçek kişi müşteriler için</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                        
                        <label class="relative cursor-pointer">
                            <input type="radio" name="customer_type" value="corporate" {{ old('customer_type', $customer->customer_type)==='corporate' ? 'checked' : '' }} class="sr-only">
                            <div class="border-2 border-gray-200 rounded-xl p-4 transition-all duration-200 hover:border-blue-300 hover:shadow-md peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full hidden peer-checked:block"></div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Kurumsal Müşteri</div>
                                        <div class="text-sm text-gray-500">Şirket ve kurumlar için</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Temel Bilgiler -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Temel Bilgiler</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Ad *</label>
                            <input name="name" value="{{ old('name', $customer->name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Müşteri adı" required>
                            @error('name')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Soyad</label>
                            <input name="surname" value="{{ old('surname', $customer->surname) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Müşteri soyadı">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">E-posta</label>
                            <input name="email" type="email" value="{{ old('email', $customer->email) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="ornek@email.com">
                            @error('email')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Telefon</label>
                            <input name="phone" value="{{ old('phone', $customer->phone) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="0212 123 45 67">
                            @error('phone')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Cep Telefonu</label>
                            <input name="phone_mobile" value="{{ old('phone_mobile', $customer->phone_mobile) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="0532 123 45 67">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Website</label>
                            <input name="website" value="{{ old('website', $customer->website) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="https://www.website.com">
                        </div>
                    </div>
                </div>

                <!-- Adres Bilgileri -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Adres Bilgileri</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Adres</label>
                            <textarea name="address" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                      placeholder="Detaylı adres bilgisi...">{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Şehir</label>
                                <input name="city" value="{{ old('city', $customer->city) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="İstanbul">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">İlçe</label>
                                <input name="district" value="{{ old('district', $customer->district) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="Kadıköy">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Posta Kodu</label>
                                <input name="zip" value="{{ old('zip', $customer->zip) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="34000">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Ülke</label>
                                <input name="country" value="{{ old('country', $customer->country ?? 'Türkiye') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="Türkiye">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vergi/Kimlik Bilgileri -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Vergi/Kimlik Bilgileri</h2>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">TC Kimlik No / Vergi No</label>
                        <input name="tax_number" value="{{ old('tax_number', $customer->tax_number) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               placeholder="12345678901">
                    </div>
                </div>

                <!-- Fatura Adresi -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Fatura Adresi</h2>
                        </div>
                        
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="copy_address" value="1" {{ old('copy_address') ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            Adres bilgilerini kopyala
                        </label>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Fatura Adresi</label>
                            <textarea name="invoice_address" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                      placeholder="Fatura adresi...">{{ old('invoice_address', $customer->invoice_address) }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Fatura Şehir</label>
                                <input name="invoice_city" value="{{ old('invoice_city', $customer->invoice_city) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="İstanbul">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Fatura İlçe</label>
                                <input name="invoice_district" value="{{ old('invoice_district', $customer->invoice_district) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="Kadıköy">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Fatura Posta Kodu</label>
                                <input name="invoice_zip" value="{{ old('invoice_zip', $customer->invoice_zip) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="34000">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Fatura Ülke</label>
                                <input name="invoice_country" value="{{ old('invoice_country', $customer->invoice_country ?? 'Türkiye') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="Türkiye">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Durum ve Notlar -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Durum ve Notlar</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Durum</label>
                            <select name="is_active" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                @php $activeVal = old('is_active', $customer->is_active ? '1' : '0'); @endphp
                                <option value="1" {{ $activeVal==='1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $activeVal==='0' ? 'selected' : '' }}>Pasif</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Notlar</label>
                            <textarea name="notes" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                      placeholder="Müşteri hakkında notlar...">{{ old('notes', $customer->notes ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('customers.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        İptal
                    </a>
                    @php($isDemo = auth()->check() && strcasecmp(auth()->user()->email, config('app.demo_email')) === 0)
                    <button type="submit" @if($isDemo) disabled @endif
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl {{ $isDemo ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isDemo ? 'Demo modunda kapalı' : '' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Müşteriyi Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Adres kopyalama fonksiyonu
        document.querySelector('input[name="copy_address"]').addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('textarea[name="invoice_address"]').value = document.querySelector('textarea[name="address"]').value;
                document.querySelector('input[name="invoice_city"]').value = document.querySelector('input[name="city"]').value;
                document.querySelector('input[name="invoice_district"]').value = document.querySelector('input[name="district"]').value;
                document.querySelector('input[name="invoice_zip"]').value = document.querySelector('input[name="zip"]').value;
                document.querySelector('input[name="invoice_country"]').value = document.querySelector('input[name="country"]').value;
            }
        });

        // Radio button styling
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[type="radio"]').forEach(r => {
                    r.closest('label').querySelector('div').classList.remove('border-blue-500', 'bg-blue-50');
                    r.closest('label').querySelector('div').classList.add('border-gray-200');
                });
                
                if (this.checked) {
                    this.closest('label').querySelector('div').classList.remove('border-gray-200');
                    this.closest('label').querySelector('div').classList.add('border-blue-500', 'bg-blue-50');
                }
            });
        });
    </script>
</x-app-layout>


