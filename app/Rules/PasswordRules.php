<?php

namespace App\Rules;

use App\SettingsHelper;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PasswordRules implements Rule
{
    use SettingsHelper;

    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        $securitySettings = $this->getSecuritySettings();
        
        $rules = [
            'min:' . $securitySettings['password_min_length']
        ];

        if ($securitySettings['password_require_uppercase']) {
            $rules[] = 'regex:/[A-Z]/';
        }

        if ($securitySettings['password_require_numbers']) {
            $rules[] = 'regex:/[0-9]/';
        }

        if ($securitySettings['password_require_symbols']) {
            $rules[] = 'regex:/[^A-Za-z0-9]/';
        }

        // Laravel'in varsayılan parola kurallarını al
        $defaultRules = Password::defaults();
        
        // Özel kuralları ekle
        foreach ($rules as $rule) {
            if (str_starts_with($rule, 'min:')) {
                $minLength = (int) substr($rule, 4);
                $defaultRules = $defaultRules->min($minLength);
            } elseif (str_starts_with($rule, 'regex:')) {
                $pattern = substr($rule, 6);
                $defaultRules = $defaultRules->regex($pattern);
            }
        }

        return $defaultRules->passes($attribute, $value);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        $securitySettings = $this->getSecuritySettings();
        $requirements = [];
        
        $requirements[] = "en az {$securitySettings['password_min_length']} karakter";
        
        if ($securitySettings['password_require_uppercase']) {
            $requirements[] = "en az bir büyük harf";
        }
        
        if ($securitySettings['password_require_numbers']) {
            $requirements[] = "en az bir rakam";
        }
        
        if ($securitySettings['password_require_symbols']) {
            $requirements[] = "en az bir özel karakter";
        }
        
        $requirementsText = implode(', ', $requirements);
        
        return "Şifre şu gereksinimleri karşılamalıdır: {$requirementsText}.";
    }

    /**
     * Get the validation rules as an array for use in validation arrays
     */
    public static function getRules(): array
    {
        $instance = new static();
        $securitySettings = $instance->getSecuritySettings();
        
        $rules = [
            'required',
            'confirmed',
            'min:' . $securitySettings['password_min_length']
        ];

        if ($securitySettings['password_require_uppercase']) {
            $rules[] = 'regex:/[A-Z]/';
        }

        if ($securitySettings['password_require_numbers']) {
            $rules[] = 'regex:/[0-9]/';
        }

        if ($securitySettings['password_require_symbols']) {
            $rules[] = 'regex:/[^A-Za-z0-9]/';
        }

        return $rules;
    }

    /**
     * Get the validation error messages as an array
     */
    public static function getMessages(): array
    {
        $instance = new static();
        $securitySettings = $instance->getSecuritySettings();
        
        $messages = [
            'password.min' => "Şifre en az {$securitySettings['password_min_length']} karakter olmalıdır.",
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
        ];

        if ($securitySettings['password_require_uppercase']) {
            $messages['password.regex'] = 'Şifre en az bir büyük harf içermelidir.';
        }
        
        if ($securitySettings['password_require_numbers']) {
            $messages['password.regex'] = 'Şifre en az bir rakam içermelidir.';
        }
        
        if ($securitySettings['password_require_symbols']) {
            $messages['password.regex'] = 'Şifre en az bir özel karakter içermelidir.';
        }

        return $messages;
    }

    /**
     * Static method to provide compatibility with Laravel's Password::defaults()
     * This method returns a PasswordRules instance that can be used in validation arrays
     */
    public static function defaults()
    {
        return new static();
    }
}
