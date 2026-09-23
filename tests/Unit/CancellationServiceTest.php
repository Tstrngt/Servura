<?php

namespace Tests\Unit;

use App\Models\CustomerService;
use App\Models\Service;
use App\Models\User;
use App\Services\CancellationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CancellationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_cancellation_is_free_during_first_seven_days(): void
    {
        Carbon::setTestNow('2026-09-23 12:00:00');
        $service = $this->customerService(now()->subDays(6));

        $preview = app(CancellationService::class)->preview($service);

        $this->assertTrue($preview['is_free']);
        $this->assertSame(0.0, $preview['estimated_usage_cost']);
        $this->assertSame('2026-09-23', $preview['effective_at']->format('Y-m-d'));
    }

    public function test_cancellation_after_seven_days_has_one_month_notice_and_prorata_estimate(): void
    {
        Carbon::setTestNow('2026-09-23 12:00:00');
        $service = $this->customerService(now()->subDays(20), now()->startOfMonth(), now()->startOfMonth()->addMonth(), 100);

        $preview = app(CancellationService::class)->preview($service);

        $this->assertFalse($preview['is_free']);
        $this->assertSame('2026-10-23', $preview['effective_at']->format('Y-m-d'));
        $this->assertGreaterThan(0, $preview['estimated_usage_cost']);
        $this->assertLessThanOrEqual(100, $preview['estimated_usage_cost']);
    }

    private function customerService($startDate, $periodStart = null, $periodEnd = null, float $price = 100): CustomerService
    {
        $customer = User::create([
            'name' => 'Opzegklant',
            'email' => uniqid('cancel-', true).'@servura.test',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);
        $service = Service::create([
            'title' => 'Hosting',
            'slug' => uniqid('hosting-'),
            'short_description' => 'Hostingpakket',
            'description' => 'Hostingpakket voor tests',
            'price' => $price,
            'price_type' => 'maandelijks',
            'is_active' => true,
        ]);

        return CustomerService::create([
            'user_id' => $customer->id,
            'service_id' => $service->id,
            'status' => 'active',
            'price' => $price,
            'price_type' => 'maandelijks',
            'billing_cycle' => 'monthly',
            'start_date' => $startDate,
            'current_period_start' => $periodStart,
            'current_period_end' => $periodEnd,
            'auto_renew' => true,
        ]);
    }
}
