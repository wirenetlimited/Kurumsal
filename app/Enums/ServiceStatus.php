<?php

namespace App\Enums;

enum ServiceStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case SUSPENDED = 'suspended';
    case CANCELLED = 'cancelled';

    /**
     * Get all status values as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get status label for display
     */
    public function label(): string
    {
        // Try to get from site settings first
        try {
            if (class_exists('\App\Models\Setting')) {
                $settings = \App\Models\Setting::get('service_statuses');
                if ($settings && is_array($settings)) {
                    foreach ($settings as $status) {
                        if ($status['value'] === $this->value) {
                            return $status['label'] ?? $this->getDefaultLabel();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback to default if Laravel is not available
        }
        
        return $this->getDefaultLabel();
    }

    /**
     * Get status color for UI
     */
    public function color(): string
    {
        // Try to get from site settings first
        try {
            if (class_exists('\App\Models\Setting')) {
                $settings = \App\Models\Setting::get('service_statuses');
                if ($settings && is_array($settings)) {
                    foreach ($settings as $status) {
                        if ($status['value'] === $this->value) {
                            return $status['color'] ?? $this->getDefaultColor();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback to default if Laravel is not available
        }
        
        return $this->getDefaultColor();
    }

    /**
     * Get status icon
     */
    public function icon(): string
    {
        // Try to get from site settings first
        try {
            if (class_exists('\App\Models\Setting')) {
                $settings = \App\Models\Setting::get('service_statuses');
                if ($settings && is_array($settings)) {
                    foreach ($settings as $status) {
                        if ($status['value'] === $this->value) {
                            return $status['icon'] ?? $this->getDefaultIcon();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback to default if Laravel is not available
        }
        
        return $this->getDefaultIcon();
    }

    /**
     * Get status description
     */
    public function description(): string
    {
        // Try to get from site settings first
        try {
            if (class_exists('\App\Models\Setting')) {
                $settings = \App\Models\Setting::get('service_statuses');
                if ($settings && is_array($settings)) {
                    foreach ($settings as $status) {
                        if ($status['value'] === $this->value) {
                            return $status['description'] ?? $this->getDefaultDescription();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback to default if Laravel is not available
        }
        
        return $this->getDefaultDescription();
    }

    /**
     * Get default label (fallback)
     */
    private function getDefaultLabel(): string
    {
        return match($this) {
            self::ACTIVE => 'Aktif',
            self::EXPIRED => 'Süresi Dolmuş',
            self::SUSPENDED => 'Askıya Alınmış',
            self::CANCELLED => 'İptal Edilmiş',
        };
    }

    /**
     * Get default color (fallback)
     */
    private function getDefaultColor(): string
    {
        return match($this) {
            self::ACTIVE => 'green',
            self::EXPIRED => 'red',
            self::SUSPENDED => 'yellow',
            self::CANCELLED => 'gray',
        };
    }

    /**
     * Get default icon (fallback)
     */
    private function getDefaultIcon(): string
    {
        return match($this) {
            self::ACTIVE => '✅',
            self::EXPIRED => '⏰',
            self::SUSPENDED => '⏸️',
            self::CANCELLED => '❌',
        };
    }

    /**
     * Get default description (fallback)
     */
    private function getDefaultDescription(): string
    {
        return match($this) {
            self::ACTIVE => 'Aktif hizmetler',
            self::EXPIRED => 'Süresi dolmuş hizmetler',
            self::SUSPENDED => 'Askıya alınmış hizmetler',
            self::CANCELLED => 'İptal edilmiş hizmetler',
        };
    }

    /**
     * Check if status is active
     */
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * Check if status is expired
     */
    public function isExpired(): bool
    {
        return $this === self::EXPIRED;
    }

    /**
     * Check if status is suspended
     */
    public function isSuspended(): bool
    {
        return $this === self::SUSPENDED;
    }

    /**
     * Check if status is cancelled
     */
    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    /**
     * Get all statuses with full information
     */
    public static function getAllWithInfo(): array
    {
        $statuses = [];
        foreach (self::cases() as $status) {
            $statuses[] = [
                'value' => $status->value,
                'label' => $status->label(),
                'color' => $status->color(),
                'icon' => $status->icon(),
                'description' => $status->description(),
            ];
        }
        return $statuses;
    }
}
