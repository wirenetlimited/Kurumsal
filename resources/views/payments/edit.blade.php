<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <!-- Başlık -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ödeme Düzenle</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Ödeme bilgilerini güncelle</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
                @csrf
                @method('PUT')
                
                @include('payments._form')
                
                <!-- Butonlar -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        İptal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>



