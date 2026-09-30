<?php

namespace Database\Seeders;

use App\Models\LegalDocument;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Blade;

class LegalDocumentSeeder extends Seeder
{
    use WithoutModelEvents;

    private const DOCUMENTS = [
        'terms' => [
            'title' => 'Algemene Voorwaarden',
            'version' => '1.0',
            'view' => 'legal.terms',
        ],
        'hosting_terms' => [
            'title' => 'Hostingvoorwaarden',
            'version' => '1.0',
            'view' => 'legal.hosting',
        ],
        'privacy' => [
            'title' => 'Privacyverklaring',
            'version' => '1.0',
            'view' => 'legal.privacy',
        ],
        'cookies' => [
            'title' => 'Cookieverklaring',
            'version' => '1.0',
            'view' => 'legal.cookies',
        ],
        'acceptable_use' => [
            'title' => 'Acceptable Use Policy',
            'version' => '1.0',
            'view' => 'legal.acceptable-use',
        ],
        'dpa' => [
            'title' => 'Verwerkersovereenkomst',
            'version' => '1.0',
            'view' => 'legal.dpa',
        ],
    ];

    public function run(): void
    {
        $owner = User::where('role', 'owner')->first();

        foreach (self::DOCUMENTS as $slug => $data) {
            $viewPath = resource_path('views/'.str_replace('.', '/', $data['view']).'.blade.php');
            $content = '';

            if (file_exists($viewPath) && preg_match("/@section\('legal-content'\)(.*?)@endsection/s", file_get_contents($viewPath), $matches)) {
                $raw = trim($matches[1]);
                try {
                    $content = Blade::render($raw, []);
                } catch (\Throwable $e) {
                    report($e);
                    $content = $raw;
                }
            }

            LegalDocument::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $data['title'],
                    'content' => $content,
                    'status' => LegalDocument::STATUS_PUBLISHED,
                    'version' => $data['version'],
                    'effective_date' => now(),
                    'published_at' => now(),
                    'published_by' => $owner?->id,
                ]
            );
        }
    }
}
