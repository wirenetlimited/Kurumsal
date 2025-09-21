<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <!-- Başlık ve Butonlar -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ödeme Detayı</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Ödeme #{{ $payment->id }}</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.payments.edit', $payment) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
                <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri
                </a>
            </div>
        </div>

        <!-- Ödeme Bilgileri -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ödeme Bilgileri</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tutar</label>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">₺{{ number_format($payment->amount, 2, ',', '.') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Ödeme Yöntemi</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $payment->payment_method === 'cash' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 
                           ($payment->payment_method === 'bank' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 
                           ($payment->payment_method === 'card' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : 
                           ($payment->payment_method === 'transfer' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 
                           'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300')))) }}">
                        {{ $payment->methodIcon }} {{ $payment->methodLabel }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Ödeme Tarihi</label>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($payment->paid_at)->translatedFormat('d M Y H:i') }}
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Oluşturulma Tarihi</label>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse($payment->created_at)->translatedFormat('d M Y H:i') }}
                    </p>
                </div>
            </div>
            
            @if($payment->notes)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Notlar</label>
                <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                    {{ $payment->notes }}
                </p>
            </div>
            @endif
        </div>

        <!-- Müşteri Bilgileri -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Müşteri Bilgileri</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Ad Soyad</label>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $payment->customer->name }}@if($payment->customer->surname) {{ ' ' . $payment->customer->surname }}@endif
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">E-posta</label>
                    <p class="text-gray-900 dark:text-white">{{ $payment->customer->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Telefon</label>
                    <p class="text-gray-900 dark:text-white">{{ $payment->customer->phone ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Müşteri Tipi</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $payment->customer->customer_type === 'individual' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' }}">
                        {{ $payment->customer->customer_type === 'individual' ? 'Bireysel' : 'Kurumsal' }}
                    </span>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('customers.show', $payment->customer) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                    Müşteri detaylarını görüntüle →
                </a>
            </div>
        </div>

        <!-- Fatura Bilgileri -->
        @if($payment->invoice)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Fatura Bilgileri</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Fatura No</label>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ $payment->invoice->invoice_number ?? '#' . $payment->invoice->id }}
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Fatura Tutarı</label>
                    <p class="text-lg font-medium text-gray-900 dark:text-white">₺{{ number_format($payment->invoice->total, 2, ',', '.') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Düzenleme Tarihi</label>
                    <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($payment->invoice->issue_date)->translatedFormat('d M Y') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Durum</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $payment->invoice->status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 
                           ($payment->invoice->status === 'sent' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 
                           ($payment->invoice->status === 'overdue' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 
                           'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300'))) }}">
                        {{ ucfirst($payment->invoice->status) }}
                    </span>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                    Fatura detaylarını görüntüle →
                </a>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>



