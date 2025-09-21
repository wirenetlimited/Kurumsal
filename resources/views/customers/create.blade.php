<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Header -->
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-8 shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center justify-between">
                    <div class="space-y-2">
                        <h1 class="text-4xl font-bold text-white drop-shadow-lg">Yeni Müşteri Ekle</h1>
                        <p class="text-blue-100 text-lg">Müşteri bilgilerini girerek sisteme ekleyin</p>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">👤</div>
                        <div class="text-blue-100 text-lg">Müşteri Kaydı</div>
                    </div>
                </div>
                
                <!-- Dekoratif Elementler -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
            </div>

            <!-- Form -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="relative p-8 border-b border-gray-200 dark:border-gray-700">
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/30 dark:to-pink-900/30 border border-red-200 dark:border-red-800 rounded-2xl p-6 shadow-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-200">
                                        Form gönderilirken hatalar oluştu:
                                    </h3>
                                    <div class="mt-3 text-sm text-red-700 dark:text-red-300">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Session Messages -->
                @if (session('status'))
                    <div class="relative p-8 border-b border-gray-200 dark:border-gray-700">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border border-green-200 dark:border-green-800 rounded-2xl p-6 shadow-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-lg font-semibold text-green-800 dark:text-green-200">
                                        {{ session('status') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('customers.store') }}" class="relative p-8 space-y-8">
                    @csrf
                    
                    <!-- Müşteri Türü -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Müşteri Türü</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="customer_type" value="individual" {{ old('customer_type','individual')==='individual' ? 'checked' : '' }} class="sr-only">
                                <div class="border-2 border-gray-200 dark:border-gray-600 rounded-2xl p-6 transition-all duration-300 hover:border-blue-400 hover:shadow-xl peer-checked:border-blue-500 peer-checked:bg-gradient-to-br peer-checked:from-blue-50 peer-checked:to-indigo-50 dark:peer-checked:from-blue-900/30 dark:peer-checked:to-indigo-900/30 group-hover:scale-105">
                                    <div class="flex items-center gap-4">
                                        <div class="w-6 h-6 border-2 border-gray-300 dark:border-gray-500 rounded-full flex items-center justify-center">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full hidden peer-checked:block"></div>
                                        </div>
                                        <div>
                                            <div class="text-lg font-semibold text-gray-900 dark:text-white">Bireysel Müşteri</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Gerçek kişi müşteriler için</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="customer_type" value="corporate" {{ old('customer_type')==='corporate' ? 'checked' : '' }} class="sr-only">
                                <div class="border-2 border-gray-200 dark:border-gray-600 rounded-2xl p-6 transition-all duration-300 hover:border-blue-400 hover:shadow-xl peer-checked:border-blue-500 peer-checked:bg-gradient-to-br peer-checked:from-blue-50 peer-checked:to-indigo-50 dark:peer-checked:from-blue-900/30 dark:peer-checked:to-indigo-900/30 group-hover:scale-105">
                                    <div class="flex items-center gap-4">
                                        <div class="w-6 h-6 border-2 border-gray-300 dark:border-gray-500 rounded-full flex items-center justify-center">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full hidden peer-checked:block"></div>
                                        </div>
                                        <div>
                                            <div class="text-lg font-semibold text-gray-900 dark:text-white">Kurumsal Müşteri</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Şirket ve kurumlar için</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Temel Bilgiler -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Temel Bilgiler</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Ad *</label>
                                <input name="name" value="{{ old('name') }}" 
                                       class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                       placeholder="Müşteri adı" required>
                            </div>
                            
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Soyad</label>
                                <input name="surname" value="{{ old('surname') }}" 
                                       class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                       placeholder="Müşteri soyadı">
                            </div>
                            
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">E-posta</label>
                                <input name="email" type="email" value="{{ old('email') }}" 
                                       class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                       placeholder="ornek@email.com">
                            </div>
                            
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Telefon</label>
                                <input name="phone" value="{{ old('phone') }}" 
                                       class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                       placeholder="0212 123 45 67">
                            </div>
                            
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Cep Telefonu</label>
                                <input name="phone_mobile" value="{{ old('phone_mobile') }}" 
                                       class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                       placeholder="0532 123 45 67">
                            </div>
                            
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Website</label>
                                <input name="website" value="{{ old('website') }}" 
                                       class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                       placeholder="https://www.website.com">
                            </div>
                        </div>
                    </div>

                    <!-- Adres Bilgileri -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Adres Bilgileri</h2>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Adres</label>
                                <textarea name="address" rows="3" 
                                          class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                          placeholder="Detaylı adres bilgisi...">{{ old('address') }}</textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Şehir</label>
                                    <input name="city" value="{{ old('city') }}" 
                                           class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                           placeholder="İstanbul">
                                </div>
                                
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">İlçe</label>
                                    <input name="district" value="{{ old('district') }}" 
                                           class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                           placeholder="Kadıköy">
                                </div>
                                
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Posta Kodu</label>
                                    <input name="zip" value="{{ old('zip') }}" 
                                           class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                           placeholder="34000">
                                </div>
                                
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Ülke</label>
                                    <input name="country" value="{{ old('country', 'Türkiye') }}" 
                                           class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                           placeholder="Türkiye">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vergi/Kimlik Bilgileri -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Vergi/Kimlik Bilgileri</h2>
                        </div>
                        
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">TC Kimlik No / Vergi No</label>
                            <input name="tax_number" value="{{ old('tax_number') }}" 
                                   class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                   placeholder="12345678901">
                        </div>
                    </div>

                    <!-- Fatura Adresi -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fatura Adresi</h2>
                            </div>
                            
                            <label class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400 cursor-pointer">
                                <input type="checkbox" name="copy_address" value="1" {{ old('copy_address') ? 'checked' : '' }}
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded-lg focus:ring-blue-500 focus:ring-2">
                                Adres bilgilerini kopyala
                            </label>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Fatura Adresi</label>
                                <textarea name="invoice_address" rows="3" 
                                          class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                          placeholder="Fatura adresi...">{{ old('invoice_address') }}</textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Fatura Şehir</label>
                                    <input name="invoice_city" value="{{ old('invoice_city') }}" 
                                           class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                           placeholder="İstanbul">
                                </div>
                                
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Fatura İlçe</label>
                                    <input name="invoice_district" value="{{ old('invoice_district') }}" 
                                           class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                           placeholder="Kadıköy">
                                </div>
                                
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Fatura Posta Kodu</label>
                                    <input name="invoice_zip" value="{{ old('invoice_zip') }}" 
                                           class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                           placeholder="34000">
                                </div>
                                
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Fatura Ülke</label>
                                    <input name="invoice_country" value="{{ old('invoice_country', 'Türkiye') }}" 
                                           class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                           placeholder="Türkiye">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Durum ve Notlar -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Durum ve Notlar</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Durum</label>
                                <select name="is_active" 
                                        class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm hover:shadow-md">
                                    <option value="1" {{ old('is_active','1')==='1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active')==='0' ? 'selected' : '' }}>Pasif</option>
                                </select>
                            </div>
                            
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Notlar</label>
                                <textarea name="notes" rows="3" 
                                          class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm hover:shadow-md" 
                                          placeholder="Müşteri hakkında notlar...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-6 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('customers.index') }}" 
                           class="inline-flex items-center px-8 py-4 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-semibold shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            İptal
                        </a>
                        @php($isDemo = auth()->check() && strcasecmp(auth()->user()->email, config('app.demo_email')) === 0)

                        <button type="submit" @if($isDemo) disabled @endif
                                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl {{ $isDemo ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isDemo ? 'Demo modunda kapalı' : '' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Müşteriyi Kaydet
                        </button>
                    </div>
                </form>
            </div>
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
                    r.closest('label').querySelector('div').classList.remove('border-blue-500', 'bg-gradient-to-br', 'from-blue-50', 'to-indigo-50', 'dark:from-blue-900/30', 'dark:to-indigo-900/30');
                    r.closest('label').querySelector('div').classList.add('border-gray-200');
                });
                
                if (this.checked) {
                    this.closest('label').querySelector('div').classList.remove('border-gray-200');
                    this.closest('label').querySelector('div').classList.add('border-blue-500', 'bg-gradient-to-br', 'from-blue-50', 'to-indigo-50', 'dark:from-blue-900/30', 'dark:to-indigo-900/30');
                }
            });
        });
    </script>
</x-app-layout>


