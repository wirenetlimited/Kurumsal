<!-- Quick Payment Modal -->
<div id="quickPaymentModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Hızlı Tahsilat
                </h3>
                <button type="button" onclick="closeQuickPaymentModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="quickPaymentForm" class="p-6 space-y-6">
                @csrf
                
                <!-- Hidden Fields -->
                <input type="hidden" id="modal_invoice_id" name="invoice_id">
                <input type="hidden" id="modal_customer_id" name="customer_id">
                
                <!-- Customer Info (Readonly) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Müşteri
                    </label>
                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                        <span id="modal_customer_name" class="text-sm font-medium text-gray-900 dark:text-white"></span>
                    </div>
                </div>

                <!-- Invoice Info (Readonly) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fatura No
                    </label>
                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                        <span id="modal_invoice_number" class="text-sm font-medium text-gray-900 dark:text-white"></span>
                    </div>
                </div>

                <!-- Amount -->
                <div>
                    <label for="modal_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tahsilat Tutarı <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="modal_amount" 
                               name="amount" 
                               step="0.01" 
                               min="0.01" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <div class="absolute right-3 top-2 text-xs text-gray-500 dark:text-gray-400">
                            Kalan: ₺<span id="modal_remaining_amount">0.00</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="button" onclick="setFullAmount()" class="text-xs text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300">
                            Tümünü Tahsil Et
                        </button>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="modal_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ödeme Yöntemi
                    </label>
                                            <select id="modal_payment_method" name="payment_method" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="cash">Nakit</option>
                        <option value="bank">Banka</option>
                        <option value="card">Kart</option>
                        <option value="transfer">Havale</option>
                        <option value="other">Diğer</option>
                    </select>
                </div>

                <!-- Payment Date -->
                <div>
                    <label for="modal_paid_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ödeme Tarihi <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="modal_paid_at" 
                           name="paid_at" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Payment Record Date -->
                <div>
                    <label for="modal_payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kayıt Tarihi <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="modal_payment_date" 
                           name="payment_date" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Notes -->
                <div>
                    <label for="modal_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notlar
                    </label>
                    <textarea id="modal_notes" 
                              name="notes" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                              placeholder="Tahsilat ile ilgili notlar..."></textarea>
                </div>

                <!-- Error Messages -->
                <div id="modal_errors" class="hidden">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p id="modal_error_text" class="text-sm text-red-800 dark:text-red-200"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" 
                            onclick="closeQuickPaymentModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        İptal
                    </button>
                    <button type="submit" 
                            id="modal_submit_btn"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                        <span id="modal_submit_text">Tahsilatı Kaydet</span>
                        <span id="modal_submit_loading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Kaydediliyor...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentInvoiceId = null;
let currentRemainingAmount = 0;

function openQuickPaymentModal(invoiceId) {
    currentInvoiceId = invoiceId;
    
    // Fetch invoice context
    fetch(`/admin/invoices/${invoiceId}/payments/context`)
        .then(response => response.json())
        .then(data => {
            // Fill modal fields
            document.getElementById('modal_invoice_id').value = data.invoice_id;
            document.getElementById('modal_customer_id').value = data.customer_id;
            document.getElementById('modal_customer_name').textContent = data.customer_name;
            document.getElementById('modal_invoice_number').textContent = data.invoice_number;
            document.getElementById('modal_amount').value = data.remaining_amount;
            document.getElementById('modal_remaining_amount').textContent = data.remaining_amount.toFixed(2);
            
            // Set max amount
            document.getElementById('modal_amount').max = data.remaining_amount;
            currentRemainingAmount = data.remaining_amount;
            
            // Set default date to today
            document.getElementById('modal_paid_at').value = new Date().toISOString().split('T')[0];
            
            // Reset form
            document.getElementById('quickPaymentForm').reset();
            document.getElementById('modal_amount').value = data.remaining_amount;
            document.getElementById('modal_errors').classList.add('hidden');
            
            // Show modal
            document.getElementById('quickPaymentModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching invoice context:', error);
            alert('Fatura bilgileri alınırken hata oluştu');
        });
}

function closeQuickPaymentModal() {
    document.getElementById('quickPaymentModal').classList.add('hidden');
    currentInvoiceId = null;
    currentRemainingAmount = 0;
}

function setFullAmount() {
    document.getElementById('modal_amount').value = currentRemainingAmount;
}

// Form submission
document.getElementById('quickPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('modal_submit_btn');
    const submitText = document.getElementById('modal_submit_text');
    const submitLoading = document.getElementById('modal_submit_loading');
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.classList.add('hidden');
    submitLoading.classList.remove('hidden');
    
    // Hide errors
    document.getElementById('modal_errors').classList.add('hidden');
    
    // Get form data
    const formData = new FormData(this);
    
    // Submit payment
    fetch('/admin/payments', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            // Success - update UI
            updateInvoiceRow(currentInvoiceId, data);
            
            // Show success message
            showToast(data.flash, 'success');
            
            // Close modal
            closeQuickPaymentModal();
        } else {
            // Show error
            document.getElementById('modal_error_text').textContent = data.message || 'Bir hata oluştu';
            document.getElementById('modal_errors').classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error submitting payment:', error);
        document.getElementById('modal_error_text').textContent = 'Bağlantı hatası oluştu';
        document.getElementById('modal_errors').classList.remove('hidden');
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        submitLoading.classList.add('hidden');
    });
});

function updateInvoiceRow(invoiceId, data) {
    // Find the invoice row
    const row = document.querySelector(`[data-invoice-id="${invoiceId}"]`);
    if (!row) return;
    
    // Update status badge
    const statusCell = row.querySelector('.status-badge');
    if (statusCell) {
        if (data.remaining_amount === 0) {
            statusCell.innerHTML = `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">
                    <svg class="w-1.5 h-1.5 mr-1.5 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    Ödendi
                </span>
            `;
        } else {
            statusCell.innerHTML = `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200">
                    <svg class="w-1.5 h-1.5 mr-1.5 text-orange-400" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    Kısmen Ödendi
                </span>
            `;
        }
    }
    
    // Update remaining amount if displayed
    const remainingCell = row.querySelector('.remaining-amount');
    if (remainingCell) {
        remainingCell.textContent = `₺${data.remaining_amount.toFixed(2)}`;
    }
    
    // Hide payment button if fully paid
    if (data.remaining_amount === 0) {
        const paymentBtn = row.querySelector('.payment-btn');
        if (paymentBtn) {
            paymentBtn.style.display = 'none';
        }
    }
}

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium shadow-lg transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    toast.textContent = message;
    
    // Add to page
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Close modal when clicking outside
document.getElementById('quickPaymentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQuickPaymentModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('quickPaymentModal').classList.contains('hidden')) {
        closeQuickPaymentModal();
    }
});
</script>
