<?php

namespace Tests\Feature;

use App\Support\Docs;
use Tests\TestCase;

class HelpCenterTest extends TestCase
{
    public function test_helpcentrum_index_shows_all_categories(): void
    {
        $response = $this->get('/docs');
        $response->assertOk();
        $response->assertSee('Waar kunnen we je mee helpen?');

        foreach (Docs::categories() as $category) {
            $response->assertSee($category['title']);
        }
    }

    public function test_all_pages_are_reachable(): void
    {
        foreach (Docs::categories() as $category) {
            $this->get('/docs/'.$category['slug'])->assertOk();

            foreach ($category['pages'] as $page) {
                $response = $this->get('/docs/'.$category['slug'].'/'.$page['slug']);
                $response->assertOk();
                $response->assertSee($page['title']);
            }
        }
    }

    public function test_search_finds_articles_case_insensitive(): void
    {
        $response = $this->get('/docs/zoeken?q=FACTUUR');
        $response->assertOk();
        $response->assertSee('resultaten');
        $response->assertSee('facturen', false);
    }

    public function test_search_with_empty_query_redirects_to_index(): void
    {
        $response = $this->get('/docs/zoeken');
        $response->assertRedirect('/docs');
    }

    public function test_search_without_results_shows_message_and_support_link(): void
    {
        $response = $this->get('/docs/zoeken?q=bestaatniet123');
        $response->assertOk();
        $response->assertSee('Geen artikelen gevonden');
        $response->assertSee('Ticket aanmaken');
    }

    public function test_draft_articles_are_marked(): void
    {
        $drafts = collect(Docs::categories())
            ->flatMap(fn ($c) => collect($c['pages'])->where('draft')->map(fn ($p) => [$c['slug'], $p['slug']]));

        $this->assertNotEmpty($drafts, 'Verwacht minstens één conceptartikel voor de review');

        foreach ($drafts as [$category, $slug]) {
            $this->get('/docs/'.$category.'/'.$slug)
                ->assertOk()
                ->assertSee('Concept');
        }
    }

    public function test_unknown_article_returns_404(): void
    {
        $this->get('/docs/bestaat-niet/pagina')->assertNotFound();
    }
}
