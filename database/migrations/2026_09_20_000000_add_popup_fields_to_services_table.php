<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('popup_label')->nullable()->after('features');
            $table->json('popup_badges')->nullable()->after('popup_label');
            $table->json('popup_details')->nullable()->after('popup_badges');
            $table->text('popup_price_note')->nullable()->after('popup_details');
        });

        DB::table('services')->where('service_type', 'website_pakket')->update([
            'popup_label' => 'Meest gekozen',
            'popup_badges' => json_encode(['Maatwerk design', 'CMS inbegrepen', '1 jaar onderhoud']),
            'popup_details' => json_encode([
                ['title' => 'Uniek ontwerp', 'description' => 'Een design dat aansluit bij uw merk en doelgroep, zonder standaard templates.'],
                ['title' => 'Responsive', 'description' => 'Perfect zichtbaar en bruikbaar op mobiel, tablet en desktop.'],
                ['title' => 'CMS & blog', 'description' => 'Zelf eenvoudig pagina’s en nieuwsberichten beheren zonder technische kennis.'],
                ['title' => 'SEO-optimaal', 'description' => 'Technisch en inhoudelijk ingericht voor betere vindbaarheid in Google.'],
                ['title' => 'Snelle hosting', 'description' => 'Inclusief 1 jaar snelle, veilige hosting met SSL-certificaat en backups.'],
                ['title' => 'Support', 'description' => 'Persoonlijke support, kleine aanpassingen en beveiligingsupdates.'],
            ]),
            'popup_price_note' => 'Eenmalig, exclusief maandelijks onderhoud na het eerste jaar.',
        ]);
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['popup_label', 'popup_badges', 'popup_details', 'popup_price_note']);
        });
    }
};
