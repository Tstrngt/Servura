<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Starter Website',
                'slug' => 'starter-website',
                'short_description' => 'Perfecte start voor kleine bedrijven die online willen groeien.',
                'description' => '
                    <p>Onze Starter Website is de ideale oplossing voor kleine bedrijven en zzp\'ers die een professionele online aanwezigheid willen zonder hoge kosten.</p>
                    
                    <h3>Wat krijgt u?</h3>
                    <ul>
                        <li>Responsieve website die perfect werkt op mobiel, tablet en desktop</li>
                        <li>Professioneel design op maat gemaakt voor uw bedrijf</li>
                        <li>5 pagina\'s (home, over ons, diensten, portfolio, contact)</li>
                        <li>Contactformulier met spam-bescherming</li>
                        <li>Basis SEO optimalisatie</li>
                        <li>Google Analytics integratie</li>
                        <li>1 jaar gratis hosting inbegrepen</li>
                    </ul>
                    
                    <h3>Extra voordelen</h3>
                    <p>Naast de website krijgt u ook:</p>
                    <ul>
                        <li>Persoonlijke begeleiding van begin tot eind</li>
                        <li>Training zodat u zelf content kunt wijzigen</li>
                        <li>3 maanden gratis technische support</li>
                        <li>Hulp bij domeinnaam registratie</li>
                    </ul>
                    
                    <p>De Starter Website is de perfecte basis om uw bedrijf online te presenteren en klanten te werven.</p>
                ',
                'price' => 1500.00,
                'price_type' => 'eenmalig',
                'features' => [
                    '5 pagina\'s website',
                    'Responsive design',
                    'Contactformulier',
                    'Basis SEO',
                    '1 jaar hosting',
                    'Training & support'
                ],
                'is_popular' => false,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Professional Website',
                'slug' => 'professional-website',
                'short_description' => 'Uitgebreide website voor grotere bedrijven met meer functionaliteiten.',
                'description' => '
                    <p>De Professional Website is geschikt voor bedrijven die meer nodig hebben dan een basis website. Met uitgebreide functionaliteiten en een professionele uitstraling.</p>
                    
                    <h3>Wat krijgt u?</h3>
                    <ul>
                        <li>Volledig op maat gemaakte website</li>
                        <li>Onbeperkt aantal pagina\'s</li>
                        <li>Geavanceerd content management systeem</li>
                        <li>Blog functionaliteit</li>
                        <li>Portfolio met projecten</li>
                        <li>Testimonial pagina</li>
                        <li>Geavanceerde SEO optimalisatie</li>
                        <li>Google Analytics & Search Console</li>
                        <li>Social media integratie</li>
                        <li>1 jaar premium hosting</li>
                    </ul>
                    
                    <h3>Technische specificaties</h3>
                    <ul>
                        <li>Laravel framework backend</li>
                        <li>Modern frontend met Tailwind CSS</li>
                        <li>Snelle laadtijd (Lighthouse score 90+)</li>
                        <li>Volledig veilig en GDPR compliant</li>
                        <li>Automatische backups</li>
                    </ul>
                    
                    <p>De Professional Website groeit mee met uw bedrijf en biedt alle functionaliteiten die u nodig heeft.</p>
                ',
                'price' => 3000.00,
                'price_type' => 'eenmalig',
                'features' => [
                    'Onbeperkt pagina\'s',
                    'Content management',
                    'Blog functionaliteit',
                    'Geavanceerde SEO',
                    'Premium hosting',
                    'Social media integratie'
                ],
                'is_popular' => true,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Webshop',
                'slug' => 'webshop',
                'short_description' => 'Complete webshop voor online verkoop van uw producten of diensten.',
                'description' => '
                    <p>Onze Webshop oplossing is perfect voor bedrijven die online willen verkopen. Een complete e-commerce oplossing met alle functionaliteiten die u nodig heeft.</p>
                    
                    <h3>Webshop functionaliteiten</h3>
                    <ul>
                        <li>Productcatalogus met categorieën</li>
                        <li>Productvarianten (kleur, maat, etc.)</li>
                        <li>Winkelwagen en checkout proces</li>
                        <li>Veilige betalingen (iDEAL, Creditcard)</li>
                        <li>Klantenaccounts met orderhistorie</li>
                        <li>Stock management</li>
                        <li>Verzendkosten instellingen</li>
                        <li>Kortingscode systeem</li>
                        <li>Product reviews en ratings</li>
                    </ul>
                    
                    <h3>Marketing tools</h3>
                    <ul>
                        <li>Email marketing integratie</li>
                        <li>Abandoned cart recovery</li>
                        <li>Upsell en cross-sell functionaliteit</li>
                        <li>Product recommendations</li>
                        <li>SEO voor productpagina\'s</li>
                    </ul>
                    
                    <h3>Support en onderhoud</h3>
                    <ul>
                        <li>6 maanden gratis support</li>
                        <li>Training voor het beheersysteem</li>
                        <li>Hulp bij product import</li>
                        <li>Performance monitoring</li>
                    </ul>
                ',
                'price' => 5000.00,
                'price_type' => 'eenmalig',
                'features' => [
                    'Complete webshop',
                    'Veilige betalingen',
                    'Product management',
                    'Klantenaccounts',
                    'Marketing tools',
                    '6 maanden support'
                ],
                'is_popular' => false,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Website Onderhoud',
                'slug' => 'website-onderhoud',
                'short_description' => 'Maandelijks onderhoud en support voor uw bestaande website.',
                'description' => '
                    <p>Het onderhouden van een website is essentieel voor veiligheid, performance en vindbaarheid. Ons onderhoudspakket neemt al deze zorgen uit handen.</p>
                    
                    <h3>Wat doen wij maandelijks?</h3>
                    <ul>
                        <li>Software updates (PHP, framework, libraries)</li>
                        <li>Security scans en patches</li>
                        <li>Performance monitoring</li>
                        <li>Database optimalisatie</li>
                        <li>Backups en restore tests</li>
                        <li>SSL certificaat vernieuwing</li>
                        <li>SEO monitoring en rapportage</li>
                        <li>Uptime monitoring</li>
                    </ul>
                    
                    <h3>Support inbegrepen</h3>
                    <ul>
                        <li>Telefonische support tijdens kantooruren</li>
                        <li>Email support binnen 24 uur</li>
                        <li>Kleine aanpassingen (max 2 uur per maand)</li>
                        <li>Content hulp (indien nodig)</li>
                        <li>Technisch advies</li>
                    </ul>
                    
                    <h3>Extra voordelen</h3>
                    <ul>
                        <li>Prioriteit bij nieuwe projecten</li>
                        <li>Korting op extra werk</li>
                        <li>Maandelijkse rapportage</li>
                        <li>Vrijblijvend advies</li>
                    </ul>
                ',
                'price' => 75.00,
                'price_type' => 'maandelijks',
                'features' => [
                    'Software updates',
                    'Security monitoring',
                    'Performance checks',
                    'Backups',
                    'Email & telefoon support',
                    'Maandelijkse rapportage'
                ],
                'is_popular' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'SEO Optimalisatie',
                'slug' => 'seo-optimalisatie',
                'short_description' => 'Verbeter de vindbaarheid van uw website in Google en andere zoekmachines.',
                'description' => '
                    <p>SEO (Search Engine Optimization) is cruciaal voor online succes. Onze SEO dienst helpt uw website hoger te ranken in zoekresultaten en meer organische traffic te genereren.</p>
                    
                    <h3>Technische SEO</h3>
                    <ul>
                        <li>Website speed optimalisatie</li>
                        <li>Mobile-friendliness checks</li>
                        <li>XML sitemaps</li>
                        <li>Robots.txt optimalisatie</li>
                        <li>Structured data (Schema.org)</li>
                        <li>Canonical tags</li>
                        <li>Meta tags optimalisatie</li>
                    </ul>
                    
                    <h3>Content SEO</h3>
                    <ul>
                        <li>Keyword research en analyse</li>
                        <li>Content strategie</li>
                        <li>On-page optimalisatie</li>
                        <li>Blog content advies</li>
                        <li>Internal linking structuur</li>
                        <li>Content gap analyse</li>
                    </ul>
                    
                    <h3>Local SEO</h3>
                    <ul>
                        <li>Google My Business optimalisatie</li>
                        <li>Lokale keyword targeting</li>
                        <li>Citatie building</li>
                        <li>Review management</li>
                        <li>Lokale content strategie</li>
                    </ul>
                    
                    <h3>Rapportage en analyse</h3>
                    <ul>
                        <li>Maandelijkse ranking reports</li>
                        <li>Traffic analyse</li>
                        <li>Conversie tracking</li>
                        <li>Competitor analyse</li>
                        <li>Advies voor verbetering</li>
                    </ul>
                ',
                'price' => 500.00,
                'price_type' => 'eenmalig',
                'features' => [
                    'Technische SEO audit',
                    'Keyword research',
                    'Content optimalisatie',
                    'Local SEO',
                    'Google Analytics setup',
                    'Maandelijkse rapportage'
                ],
                'is_popular' => false,
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
