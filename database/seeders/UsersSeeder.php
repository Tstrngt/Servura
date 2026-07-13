<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CustomerService;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin Servura',
            'email' => 'admin@servura.nl',
            'password' => Hash::make('password'),
            'phone' => '06 123 456 78',
            'company' => 'Servura',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create employee user
        $employee = User::create([
            'name' => 'Medewerker Servura',
            'email' => 'medewerker@servura.nl',
            'password' => Hash::make('password'),
            'phone' => '06 123 456 79',
            'company' => 'Servura',
            'role' => 'employee',
            'is_active' => true,
        ]);

        // Create test customers
        $customers = [
            [
                'name' => 'Jan Jansen',
                'email' => 'jan@bakkerijdegoudenkoren.nl',
                'password' => Hash::make('password'),
                'phone' => '06 234 567 89',
                'company' => 'Bakkerij de Gouden Koren',
                'role' => 'customer',
                'is_active' => true,
            ],
            [
                'name' => 'Lisa de Vries',
                'email' => 'lisa@fysiowelzijn.nl',
                'password' => Hash::make('password'),
                'phone' => '06 345 678 90',
                'company' => 'Fysiotherapie Praktijk Welzijn',
                'role' => 'customer',
                'is_active' => true,
            ],
            [
                'name' => 'Marco Italiano',
                'email' => 'marco@italiaansesmaak.nl',
                'password' => Hash::make('password'),
                'phone' => '06 456 789 01',
                'company' => 'Restaurant Italiaanse Smaak',
                'role' => 'customer',
                'is_active' => true,
            ],
            [
                'name' => 'Peter Jansen',
                'email' => 'peter@installatiejansen.nl',
                'password' => Hash::make('password'),
                'phone' => '06 567 890 12',
                'company' => 'Technisch Installatiebedrijf Jansen',
                'role' => 'customer',
                'is_active' => true,
            ],
            [
                'name' => 'Sophie Mode',
                'email' => 'sophie@modenstijl.nl',
                'password' => Hash::make('password'),
                'phone' => '06 678 901 23',
                'company' => 'Kledingwinkel Mode & Stijl',
                'role' => 'customer',
                'is_active' => true,
            ],
        ];

        $createdCustomers = [];
        foreach ($customers as $customerData) {
            $customer = User::create($customerData);
            $createdCustomers[] = $customer;
        }

        // Assign services to customers
        $services = Service::all();
        
        // Jan Jansen - Bakkerij de Gouden Koren
        $this->assignServiceToCustomer($createdCustomers[0], $services->where('slug', 'starter-website')->first(), '2024-01-01', null);
        $this->assignServiceToCustomer($createdCustomers[0], $services->where('slug', 'website-onderhoud')->first(), '2024-01-01', null);

        // Lisa de Vries - Fysiotherapie Praktijk Welzijn
        $this->assignServiceToCustomer($createdCustomers[1], $services->where('slug', 'professional-website')->first(), '2024-02-01', null);
        $this->assignServiceToCustomer($createdCustomers[1], $services->where('slug', 'website-onderhoud')->first(), '2024-02-01', null);

        // Marco Italiano - Restaurant Italiaanse Smaak
        $this->assignServiceToCustomer($createdCustomers[2], $services->where('slug', 'professional-website')->first(), '2024-03-01', null);
        $this->assignServiceToCustomer($createdCustomers[2], $services->where('slug', 'seo-optimalisatie')->first(), '2024-03-01', null);

        // Peter Jansen - Technisch Installatiebedrijf Jansen
        $this->assignServiceToCustomer($createdCustomers[3], $services->where('slug', 'professional-website')->first(), '2023-06-01', '2025-06-01');
        $this->assignServiceToCustomer($createdCustomers[3], $services->where('slug', 'website-onderhoud')->first(), '2023-06-01', null);

        // Sophie Mode - Kledingwinkel Mode & Stijl
        $this->assignServiceToCustomer($createdCustomers[4], $services->where('slug', 'webshop')->first(), '2024-04-01', null);
        $this->assignServiceToCustomer($createdCustomers[4], $services->where('slug', 'website-onderhoud')->first(), '2024-04-01', null);

        echo "Users and customer services created successfully!\n";
        echo "Admin login: admin@servura.nl / password\n";
        echo "Customer login: jan@bakkerijdegoudenkoren.nl / password\n";
    }

    private function assignServiceToCustomer($customer, $service, $startDate, $endDate)
    {
        if (!$customer || !$service) {
            return;
        }

        CustomerService::create([
            'user_id' => $customer->id,
            'service_id' => $service->id,
            'status' => 'active',
            'price' => $service->price,
            'price_type' => $service->price_type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'notes' => 'Automatisch gegenereerd via seeder',
        ]);
    }
}
