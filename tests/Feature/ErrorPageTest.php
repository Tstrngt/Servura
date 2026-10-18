<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ErrorPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config()->set('app.debug', false);
    }

    public function test_missing_page_uses_custom_404_page(): void
    {
        $this->get('/deze-pagina-bestaat-niet')
            ->assertNotFound()
            ->assertSee('Deze pagina bestaat niet')
            ->assertSee('Naar de homepage');
    }

    public function test_unauthenticated_error_uses_custom_401_page(): void
    {
        Route::get('/test-401', fn () => abort(401));

        $this->get('/test-401')
            ->assertStatus(401)
            ->assertSee('U bent nog niet ingelogd')
            ->assertSee('Naar de homepage');
    }

    public function test_server_error_uses_custom_500_page(): void
    {
        Route::get('/test-500', fn () => abort(500));

        $this->get('/test-500')
            ->assertStatus(500)
            ->assertSee('Er ging iets mis')
            ->assertSee('Naar de homepage');
    }
}
