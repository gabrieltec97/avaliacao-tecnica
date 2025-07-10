<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactDestroyTest extends TestCase
{
    use RefreshDatabase;

    //Teste de fluxo normal de deleção de contato
    public function test_a_contact_can_be_deleted_successfully()
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

        $this->assertDatabaseHas('contacts', ['id' => $contact->id]);
        $this->assertDatabaseCount('contacts', 1); // Confirma que há 1 contato

       //Deletando o contato
        $response = $this->delete(route('contatos.destroy', $contact->id));

        $response->assertRedirect(route('contatos.index'));
        $response->assertSessionHas('msg-success', 'Contato deletado com sucesso!');

        // Verifica se o contato foi removido do banco de dados
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
        $this->assertDatabaseCount('contacts', 0);
    }

    //Teste de deleção de contato com id inexistente.
    public function test_no_existing_id()
    {
        $nonExistentId = 000333;

        $response = $this->delete(route('contatos.destroy', $nonExistentId));

        $response->assertStatus(404);

        $this->assertDatabaseCount('contacts', 0);
    }
}
