<?php

namespace Tests\Unit;

use App\Enums\ServiceStatus;
use App\Enums\InvoiceStatus;
use PHPUnit\Framework\TestCase;

class EnumsTest extends TestCase
{
    public function test_service_status_enum_values()
    {
        $this->assertEquals('active', ServiceStatus::ACTIVE->value);
        $this->assertEquals('expired', ServiceStatus::EXPIRED->value);
        $this->assertEquals('suspended', ServiceStatus::SUSPENDED->value);
        $this->assertEquals('cancelled', ServiceStatus::CANCELLED->value);
    }

    public function test_service_status_enum_labels()
    {
        $this->assertEquals('Aktif', ServiceStatus::ACTIVE->label());
        $this->assertEquals('Süresi Dolmuş', ServiceStatus::EXPIRED->label());
        $this->assertEquals('Askıya Alınmış', ServiceStatus::SUSPENDED->label());
        $this->assertEquals('İptal Edilmiş', ServiceStatus::CANCELLED->label());
    }

    public function test_service_status_enum_colors()
    {
        $this->assertEquals('green', ServiceStatus::ACTIVE->color());
        $this->assertEquals('red', ServiceStatus::EXPIRED->color());
        $this->assertEquals('yellow', ServiceStatus::SUSPENDED->color());
        $this->assertEquals('gray', ServiceStatus::CANCELLED->color());
    }

    public function test_service_status_enum_icons()
    {
        $this->assertEquals('✅', ServiceStatus::ACTIVE->icon());
        $this->assertEquals('⏰', ServiceStatus::EXPIRED->icon());
        $this->assertEquals('⏸️', ServiceStatus::SUSPENDED->icon());
        $this->assertEquals('❌', ServiceStatus::CANCELLED->icon());
    }

    public function test_service_status_enum_descriptions()
    {
        $this->assertEquals('Aktif hizmetler', ServiceStatus::ACTIVE->description());
        $this->assertEquals('Süresi dolmuş hizmetler', ServiceStatus::EXPIRED->description());
        $this->assertEquals('Askıya alınmış hizmetler', ServiceStatus::SUSPENDED->description());
        $this->assertEquals('İptal edilmiş hizmetler', ServiceStatus::CANCELLED->description());
    }

    public function test_service_status_enum_methods()
    {
        $this->assertTrue(ServiceStatus::ACTIVE->isActive());
        $this->assertFalse(ServiceStatus::ACTIVE->isExpired());
        $this->assertTrue(ServiceStatus::EXPIRED->isExpired());
        $this->assertTrue(ServiceStatus::SUSPENDED->isSuspended());
        $this->assertTrue(ServiceStatus::CANCELLED->isCancelled());
    }

    public function test_service_status_enum_values_array()
    {
        $values = ServiceStatus::values();
        $this->assertContains('active', $values);
        $this->assertContains('expired', $values);
        $this->assertContains('suspended', $values);
        $this->assertContains('cancelled', $values);
        $this->assertCount(4, $values);
    }

    public function test_service_status_enum_get_all_with_info()
    {
        $statuses = ServiceStatus::getAllWithInfo();
        $this->assertCount(4, $statuses);
        
        $activeStatus = collect($statuses)->firstWhere('value', 'active');
        $this->assertNotNull($activeStatus);
        $this->assertEquals('Aktif', $activeStatus['label']);
        $this->assertEquals('green', $activeStatus['color']);
        $this->assertEquals('✅', $activeStatus['icon']);
        $this->assertEquals('Aktif hizmetler', $activeStatus['description']);
    }

    public function test_invoice_status_enum_values()
    {
        $this->assertEquals('draft', InvoiceStatus::DRAFT->value);
        $this->assertEquals('sent', InvoiceStatus::SENT->value);
        $this->assertEquals('paid', InvoiceStatus::PAID->value);
        $this->assertEquals('overdue', InvoiceStatus::OVERDUE->value);
        $this->assertEquals('cancelled', InvoiceStatus::CANCELLED->value);
    }

    public function test_invoice_status_enum_labels()
    {
        $this->assertEquals('Taslak', InvoiceStatus::DRAFT->label());
        $this->assertEquals('Gönderildi', InvoiceStatus::SENT->label());
        $this->assertEquals('Ödendi', InvoiceStatus::PAID->label());
        $this->assertEquals('Gecikmiş', InvoiceStatus::OVERDUE->label());
        $this->assertEquals('İptal Edildi', InvoiceStatus::CANCELLED->label());
    }

    public function test_invoice_status_enum_colors()
    {
        $this->assertEquals('gray', InvoiceStatus::DRAFT->color());
        $this->assertEquals('blue', InvoiceStatus::SENT->color());
        $this->assertEquals('green', InvoiceStatus::PAID->color());
        $this->assertEquals('red', InvoiceStatus::OVERDUE->color());
        $this->assertEquals('gray', InvoiceStatus::CANCELLED->color());
    }

    public function test_invoice_status_enum_icons()
    {
        $this->assertEquals('📝', InvoiceStatus::DRAFT->icon());
        $this->assertEquals('📤', InvoiceStatus::SENT->icon());
        $this->assertEquals('✅', InvoiceStatus::PAID->icon());
        $this->assertEquals('⚠️', InvoiceStatus::OVERDUE->icon());
        $this->assertEquals('❌', InvoiceStatus::CANCELLED->icon());
    }

    public function test_invoice_status_enum_descriptions()
    {
        $this->assertEquals('Taslak faturalar', InvoiceStatus::DRAFT->description());
        $this->assertEquals('Gönderilmiş faturalar', InvoiceStatus::SENT->description());
        $this->assertEquals('Ödenmiş faturalar', InvoiceStatus::PAID->description());
        $this->assertEquals('Gecikmiş faturalar', InvoiceStatus::OVERDUE->description());
        $this->assertEquals('İptal edilmiş faturalar', InvoiceStatus::CANCELLED->description());
    }

    public function test_invoice_status_enum_methods()
    {
        $this->assertTrue(InvoiceStatus::DRAFT->isDraft());
        $this->assertTrue(InvoiceStatus::SENT->isSent());
        $this->assertTrue(InvoiceStatus::PAID->isPaid());
        $this->assertTrue(InvoiceStatus::OVERDUE->isOverdue());
        $this->assertTrue(InvoiceStatus::CANCELLED->isCancelled());
    }

    public function test_invoice_status_enum_unpaid_check()
    {
        $this->assertTrue(InvoiceStatus::SENT->isUnpaid());
        $this->assertTrue(InvoiceStatus::OVERDUE->isUnpaid());
        $this->assertFalse(InvoiceStatus::DRAFT->isUnpaid());
        $this->assertFalse(InvoiceStatus::PAID->isUnpaid());
        $this->assertFalse(InvoiceStatus::CANCELLED->isUnpaid());
    }

    public function test_invoice_status_enum_can_be_paid()
    {
        $this->assertTrue(InvoiceStatus::SENT->canBePaid());
        $this->assertTrue(InvoiceStatus::OVERDUE->canBePaid());
        $this->assertFalse(InvoiceStatus::DRAFT->canBePaid());
        $this->assertFalse(InvoiceStatus::PAID->canBePaid());
        $this->assertFalse(InvoiceStatus::CANCELLED->canBePaid());
    }

    public function test_invoice_status_enum_values_array()
    {
        $values = InvoiceStatus::values();
        $this->assertContains('draft', $values);
        $this->assertContains('sent', $values);
        $this->assertContains('paid', $values);
        $this->assertContains('overdue', $values);
        $this->assertContains('cancelled', $values);
        $this->assertCount(5, $values);
    }

    public function test_invoice_status_enum_get_all_with_info()
    {
        $statuses = InvoiceStatus::getAllWithInfo();
        $this->assertCount(5, $statuses);
        
        $sentStatus = collect($statuses)->firstWhere('value', 'sent');
        $this->assertNotNull($sentStatus);
        $this->assertEquals('Gönderildi', $sentStatus['label']);
        $this->assertEquals('blue', $sentStatus['color']);
        $this->assertEquals('📤', $sentStatus['icon']);
        $this->assertEquals('Gönderilmiş faturalar', $sentStatus['description']);
    }
}
