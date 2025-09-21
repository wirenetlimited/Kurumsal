<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Provider;
use App\Models\Service;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('Admin123!'),
                'is_admin' => true,
            ]);
        }

        // Test verileri ekle
        if (Customer::count() === 0) {
            Customer::create([
                'name' => 'Test Müşteri 1',
                'tax_number' => '12345678901',
                'email' => 'test1@example.com',
                'phone' => '0555 123 45 67',
                'address' => 'Test Adres 1',
                'is_active' => true,
            ]);

            Customer::create([
                'name' => 'Test Müşteri 2',
                'tax_number' => '98765432109',
                'email' => 'test2@example.com',
                'phone' => '0555 987 65 43',
                'address' => 'Test Adres 2',
                'is_active' => true,
            ]);
        }

        if (Provider::count() === 0) {
            Provider::create([
                'name' => 'Test Domain',
                'type' => 'domain_registrar',
                'contact_info' => [
                    'website' => 'testdomain.com',
                    'support_email' => 'support@testdomain.com',
                    'phone' => '0212 123 45 67'
                ],
            ]);

            Provider::create([
                'name' => 'Test Hosting',
                'type' => 'hosting_company',
                'contact_info' => [
                    'website' => 'testhosting.com',
                    'support_email' => 'support@testhosting.com',
                    'phone' => '0212 987 65 43'
                ],
            ]);
        }

        if (Service::count() === 0) {
            Service::create([
                'customer_id' => 1,
                'provider_id' => 1,
                'service_type' => 'domain',
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'cycle' => 'yearly',
                'cost_price' => 100.00,
                'sell_price' => 150.00,
                'notes' => 'Test domain hizmeti',
            ]);

            Service::create([
                'customer_id' => 1,
                'provider_id' => 2,
                'service_type' => 'hosting',
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'cycle' => 'yearly',
                'cost_price' => 200.00,
                'sell_price' => 300.00,
                'notes' => 'Test hosting hizmeti',
            ]);
        }
    }
}
