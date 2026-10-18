<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_displays_configured_company_phone_and_email(): void
    {
        config()->set('company.phone', '+31 6 1234 5678');
        config()->set('company.email', 'support@example.test');

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('+31 6 1234 5678')
            ->assertSee('tel:+31612345678', false)
            ->assertSee('support@example.test')
            ->assertSee('Binnen 1 werkdag')
            ->assertDontSee('binnen 48 uur', false)
            ->assertDontSee('06 123 456 78')
            ->assertDontSee('info@servura.nl');
    }

    public function test_contact_page_hides_phone_card_without_configured_phone(): void
    {
        config()->set('company.phone', '');

        $this->get(route('contact'))
            ->assertOk()
            ->assertDontSee('>Telefoon<', false);
    }
}
