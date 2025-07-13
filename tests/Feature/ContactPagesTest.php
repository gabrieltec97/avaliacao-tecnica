<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPagesTest extends TestCase
{
    use RefreshDatabase;

    //Teste de fluxo normal de renderização da view da página principal.
    public function test_index_view_is_accessible(): void
    {
        $response = $this->get(route('contatos.index'));
        $response->assertStatus(200);
        $response->assertViewIs('contacts.index');
    }

    //Teste de fluxo normal de renderização da view da página de novo contato.
    public function test_create_view_is_accessible(): void
    {
        $response = $this->get(route('contatos.create'));
        $response->assertStatus(200);
        $response->assertViewIs('contacts.new-contact');
    }

    //Teste de fluxo normal de renderização da view da página de edição de contato.
    public function test_edit_view_is_accessible(): void
    {
        $contact = Contact::create([
            'name' => 'Teste Delete',
            'phone' => '21000000000',
            'email' => 'teste@test.com',
            'cep' => '01001-000',
            'address' => 'Rua Teste',
            'neighborhood' => 'Teste',
            'city' => 'Cidade Teste',
            'state' => 'RJ',
        ]);

        $response = $this->get(route('contatos.edit', $contact));
        $response->assertStatus(200);
        $response->assertViewIs('contacts.edit-contact');
        $response->assertViewHas('contact', $contact);
    }
}

