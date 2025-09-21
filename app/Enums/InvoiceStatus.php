<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case PAID = 'paid';
    case OVERDUE = 'overdue';
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
                $settings = \App\Models\Setting::get('invoice_statuses');
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
                $settings = \App\Models\Setting::get('invoice_statuses');
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
                $settings = \App\Models\Setting::get('invoice_statuses');
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
                $settings = \App\Models\Setting::get('invoice_statuses');
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
            self::DRAFT => 'Taslak',
            self::SENT => 'Gönderildi',
            self::PAID => 'Ödendi',
            self::OVERDUE => 'Gecikmiş',
            self::CANCELLED => 'İptal Edildi',
        };
    }

    /**
     * Get default color (fallback)
     */
    private function getDefaultColor(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::SENT => 'blue',
            self::PAID => 'green',
            self::OVERDUE => 'red',
            self::CANCELLED => 'gray',
        };
    }

    /**
     * Get default icon (fallback)
     */
    private function getDefaultIcon(): string
    {
        return match($this) {
            self::DRAFT => '📝',
            self::SENT => '📤',
            self::PAID => '✅',
            self::OVERDUE => '⚠️',
            self::CANCELLED => '❌',
        };
    }

    /**
     * Get default description (fallback)
     */
    private function getDefaultDescription(): string
    {
        return match($this) {
            self::DRAFT => 'Taslak faturalar',
            self::SENT => 'Gönderilmiş faturalar',
            self::PAID => 'Ödenmiş faturalar',
            self::OVERDUE => 'Gecikmiş faturalar',
            self::CANCELLED => 'İptal edilmiş faturalar',
        };
    }

    /**
     * Check if status is draft
     */
    public function isDraft(): bool
    {
        return $this === self::DRAFT;
    }

    /**
     * Check if status is sent
     */
    public function isSent(): bool
    {
        return $this === self::SENT;
    }

    /**
     * Check if status is paid
     */
    public function isPaid(): bool
    {
        return $this === self::PAID;
    }

    /**
     * Check if status is overdue
     */
    public function isOverdue(): bool
    {
        return $this === self::OVERDUE;
    }

    /**
     * Check if status is cancelled
     */
    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    /**
     * Check if invoice is unpaid (sent or overdue)
     */
    public function isUnpaid(): bool
    {
        return in_array($this, [self::SENT, self::OVERDUE]);
    }

    /**
     * Check if invoice can be paid
     */
    public function canBePaid(): bool
    {
        return in_array($this, [self::SENT, self::OVERDUE]);
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
