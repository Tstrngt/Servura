<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PortfolioItem;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portfolioItems = [
            [
                'title' => 'Bakkerij de Gouden Koren',
                'slug' => 'bakkerij-de-gouden-koren',
                'description' => 'Een moderne, responsive website voor een traditionele bakkerij. De website bestaat uit een homepage, over ons pagina, producten overzicht, contactpagina en een blog voor recepten. Het design straalt warmte en ambacht uit met gebruik van aardse kleuren en hoogwaardige fotografie van brood en gebak.',
                'client_name' => 'Bakkerij de Gouden Koren',
                'website_url' => 'https://www.degoudenkoren.nl',
                'image_url' => null,
                'technologies' => ['Laravel', 'Tailwind CSS', 'MySQL', 'Alpine.js'],
                'is_featured' => true,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Fysiotherapie Praktijk Welzijn',
                'slug' => 'fysiotherapie-praktijk-welzijn',
                'description' => 'Een professionele website voor een fysiotherapiepraktijk. De site bevat informatie over behandelingen, het team, online afspraak maken en een patiëntenportaal. De focus ligt op gebruiksvriendelijkheid en vertrouwen, met een kalme en professionele uitstraling.',
                'client_name' => 'Fysiotherapie Praktijk Welzijn',
                'website_url' => 'https://www.fysiowelzijn.nl',
                'image_url' => null,
                'technologies' => ['Laravel', 'Tailwind CSS', 'MySQL', 'Google Calendar API'],
                'is_featured' => true,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Restaurant Italiaanse Smaak',
                'slug' => 'restaurant-italiaanse-smaak',
                'description' => 'Een stijlvolle website voor een Italiaans restaurant. De website toont het menu, de sfeer van het restaurant en biedt online tafelreservering. Het design is elegant en uitnodigend, met professionele foto\'s van gerechten en het interieur.',
                'client_name' => 'Restaurant Italiaanse Smaak',
                'website_url' => 'https://www.italiaansesmaak.nl',
                'image_url' => null,
                'technologies' => ['Laravel', 'Tailwind CSS', 'MySQL', 'Reserveringssysteem'],
                'is_featured' => false,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Technisch Installatiebedrijf Jansen',
                'slug' => 'technisch-installatiebedrijf-jansen',
                'description' => 'Een zakelijke website voor een installatiebedrijf. De site toont diensten zoals CV-installaties, zonnepanelen en elektrische installaties. Het design is robuust en betrouwbaar, met focus op professionaliteit en expertise.',
                'client_name' => 'Technisch Installatiebedrijf Jansen',
                'website_url' => 'https://www.installatiejansen.nl',
                'image_url' => null,
                'technologies' => ['Laravel', 'Tailwind CSS', 'MySQL', 'Project Portfolio'],
                'is_featured' => true,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Kledingwinkel Mode & Stijl',
                'slug' => 'kledingwinkel-mode-stijl',
                'description' => 'Een trendy webshop voor een modewinkel. De site bevat een productcatalogus, maatgids, lookbook en blog over fashion trends. Het design is modern en visueel aantrekkelijk, met focus op productpresentatie en gebruiksgemak.',
                'client_name' => 'Kledingwinkel Mode & Stijl',
                'website_url' => 'https://www.modenstijl.nl',
                'image_url' => null,
                'technologies' => ['Laravel', 'Tailwind CSS', 'MySQL', 'Payment Gateway'],
                'is_featured' => false,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Advocatenkantoor Lex Legal',
                'slug' => 'advocatenkantoor-lex-legal',
                'description' => 'Een serieuze en professionele website voor een advocatenkantoor. De site presenteert de praktijkgebieden, het team en biedt een contactformulier voor intakegesprekken. Het design straalt autoriteit en betrouwbaarheid uit.',
                'client_name' => 'Advocatenkantoor Lex Legal',
                'website_url' => 'https://www.lexlegal.nl',
                'image_url' => null,
                'technologies' => ['Laravel', 'Tailwind CSS', 'MySQL', 'Beveiligd Contactformulier'],
                'is_featured' => false,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Sportschool Fit & Vitaal',
                'slug' => 'sportschool-fit-vitaal',
                'description' => 'Een energieke website voor een sportschool. De site toont de faciliteiten, groepslessen schema, personal training opties en tarieven. Het design is dynamisch en motiverend, met foto\'s van de sportschool en leden.',
                'client_name' => 'Sportschool Fit & Vitaal',
                'website_url' => 'https://www.fitvitaal.nl',
                'image_url' => null,
                'technologies' => ['Laravel', 'Tailwind CSS', 'MySQL', 'Les Rooster Systeem'],
                'is_featured' => false,
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'Schildersbedrijf Kleur & Klassiek',
                'slug' => 'schildersbedrijf-kleur-klassiek',
                'description' => 'Een overzichtelijke website voor een schildersbedrijf. De site toont diensten zoals binnen- en buitenschilderwerk, wandafwerking en kleuradvies. Het design is schoon en professioneel, met een portfolio van afgeronde projecten.',
                'client_name' => 'Schildersbedrijf Kleur & Klassiek',
                'website_url' => 'https://www.kleurklassiek.nl',
                'image_url' => null,
                'technologies' => ['Laravel', 'Tailwind CSS', 'MySQL', 'Project Galerij'],
                'is_featured' => false,
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($portfolioItems as $item) {
            PortfolioItem::create($item);
        }
    }
}
