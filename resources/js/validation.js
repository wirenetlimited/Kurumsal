/**
 * Frontend Validation Helper
 * Form validation için yardımcı fonksiyonlar
 */

class ValidationHelper {
    constructor() {
        this.init();
    }

    init() {
        this.setupFormValidation();
        this.setupRealTimeValidation();
        this.setupCustomValidators();
    }

    /**
     * Form validation kurulumu
     */
    setupFormValidation() {
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });
    }

    /**
     * Gerçek zamanlı validation
     */
    setupRealTimeValidation() {
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });
    }

    /**
     * Özel validation kuralları
     */
    setupCustomValidators() {
        // Domain name validation
        this.addCustomValidator('domain_name', (value) => {
            const domainRegex = /^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*$/;
            return domainRegex.test(value) || 'Geçersiz domain adı formatı';
        });

        // IP address validation
        this.addCustomValidator('ip_address', (value) => {
            const ipRegex = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
            return ipRegex.test(value) || 'Geçersiz IP adresi formatı';
        });

        // Phone number validation
        this.addCustomValidator('phone', (value) => {
            const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
            return phoneRegex.test(value) || 'Geçerli bir telefon numarası giriniz';
        });

        // Tax number validation
        this.addCustomValidator('tax_number', (value) => {
            const taxRegex = /^[0-9]{10,11}$/;
            return taxRegex.test(value) || 'Vergi numarası 10-11 haneli olmalıdır';
        });
    }

    /**
     * Özel validator ekleme
     */
    addCustomValidator(fieldName, validator) {
        this.customValidators = this.customValidators || {};
        this.customValidators[fieldName] = validator;
    }

    /**
     * Form validation
     */
    validateForm(form) {
        let isValid = true;
        const fields = form.querySelectorAll('input, select, textarea');
        
        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    /**
     * Alan validation
     */
    validateField(field) {
        const value = field.value.trim();
        const rules = this.getFieldRules(field);
        let isValid = true;
        let errorMessage = '';

        // Required validation
        if (rules.required && !value) {
            errorMessage = rules.requiredMessage || 'Bu alan zorunludur';
            isValid = false;
        }

        // Min length validation
        if (rules.minLength && value.length < rules.minLength) {
            errorMessage = rules.minLengthMessage || `En az ${rules.minLength} karakter olmalıdır`;
            isValid = false;
        }

        // Max length validation
        if (rules.maxLength && value.length > rules.maxLength) {
            errorMessage = rules.maxLengthMessage || `En fazla ${rules.maxLength} karakter olabilir`;
            isValid = false;
        }

        // Email validation
        if (rules.email && value && !this.isValidEmail(value)) {
            errorMessage = 'Geçerli bir e-posta adresi giriniz';
            isValid = false;
        }

        // URL validation
        if (rules.url && value && !this.isValidUrl(value)) {
            errorMessage = 'Geçerli bir URL giriniz';
            isValid = false;
        }

        // Numeric validation
        if (rules.numeric && value && !this.isValidNumber(value)) {
            errorMessage = 'Sayısal bir değer giriniz';
            isValid = false;
        }

        // Min value validation
        if (rules.minValue && value && parseFloat(value) < rules.minValue) {
            errorMessage = `Değer en az ${rules.minValue} olmalıdır`;
            isValid = false;
        }

        // Max value validation
        if (rules.maxValue && value && parseFloat(value) > rules.maxValue) {
            errorMessage = `Değer en fazla ${rules.maxValue} olabilir`;
            isValid = false;
        }

        // Custom validation
        if (rules.custom && value) {
            const customResult = this.customValidators[rules.custom](value);
            if (customResult !== true) {
                errorMessage = customResult;
                isValid = false;
            }
        }

        // Show/hide error
        if (!isValid) {
            this.showFieldError(field, errorMessage);
        } else {
            this.clearFieldError(field);
        }

        return isValid;
    }

    /**
     * Alan kurallarını alma
     */
    getFieldRules(field) {
        const rules = {};
        
        // Required
        if (field.hasAttribute('required')) {
            rules.required = true;
            rules.requiredMessage = field.getAttribute('data-required-message');
        }

        // Min length
        if (field.hasAttribute('minlength')) {
            rules.minLength = parseInt(field.getAttribute('minlength'));
            rules.minLengthMessage = field.getAttribute('data-min-message');
        }

        // Max length
        if (field.hasAttribute('maxlength')) {
            rules.maxLength = parseInt(field.getAttribute('maxlength'));
            rules.maxLengthMessage = field.getAttribute('data-max-message');
        }

        // Email
        if (field.type === 'email') {
            rules.email = true;
        }

        // URL
        if (field.hasAttribute('data-url')) {
            rules.url = true;
        }

        // Numeric
        if (field.hasAttribute('data-numeric')) {
            rules.numeric = true;
        }

        // Min value
        if (field.hasAttribute('min')) {
            rules.minValue = parseFloat(field.getAttribute('min'));
        }

        // Max value
        if (field.hasAttribute('max')) {
            rules.maxValue = parseFloat(field.getAttribute('max'));
        }

        // Custom validator
        if (field.hasAttribute('data-validate')) {
            rules.custom = field.getAttribute('data-validate');
        }

        return rules;
    }

    /**
     * E-posta validation
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * URL validation
     */
    isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }

    /**
     * Sayı validation
     */
    isValidNumber(value) {
        return !isNaN(value) && !isNaN(parseFloat(value));
    }

    /**
     * Hata gösterme
     */
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'validation-error text-red-600 text-sm mt-1';
        errorDiv.textContent = message;
        
        field.classList.add('border-red-500');
        field.parentNode.appendChild(errorDiv);
    }

    /**
     * Hata temizleme
     */
    clearFieldError(field) {
        field.classList.remove('border-red-500');
        
        const existingError = field.parentNode.querySelector('.validation-error');
        if (existingError) {
            existingError.remove();
        }
    }

    /**
     * Tüm hataları temizleme
     */
    clearAllErrors() {
        const errors = document.querySelectorAll('.validation-error');
        errors.forEach(error => error.remove());
        
        const fields = document.querySelectorAll('input, select, textarea');
        fields.forEach(field => field.classList.remove('border-red-500'));
    }

    /**
     * Form reset
     */
    resetForm(form) {
        form.reset();
        this.clearAllErrors();
    }
}

// Global instance
window.ValidationHelper = new ValidationHelper();

// Export for modules
export default ValidationHelper;
