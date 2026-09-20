<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        $defaults = [
            'default_vat_rate' => '21.00',
            'country_vat_enabled' => '0',
            'invoice_due_days' => '14',
            'suspension_grace_days' => '7',
            'business_country' => 'NL',
        ];
        foreach ($defaults as $key => $value) {
            DB::table('billing_settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('vat_percentage', 5, 2)->default(21.00)->after('subtotal');
            $table->string('billing_country', 2)->default('NL')->after('vat_percentage');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['vat_percentage', 'billing_country']);
        });
        Schema::dropIfExists('billing_settings');
    }
};
