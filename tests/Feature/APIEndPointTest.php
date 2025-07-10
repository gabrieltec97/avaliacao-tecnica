<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class APIEndPointTest extends TestCase
{
    use RefreshDatabase;

    //Teste de fluxo normal de recuperação de todos os contatos.
    public function test_normal_get_test()
    {
        $contact1 = Contact::create([
            'name' => 'Contato Um',
            'phone' => '11911111111',
            'email' => 'teste@test.com',
            'cep' => '01001-000',
            'address' => 'Rua A',
        ]);

        $response = $this->get(route('apiContacts'));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'phone',
                    'email',
                    'cep',
                    'address',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);

        $response->assertJsonCount(1);

        // Verifica se os dados específicos dos contatos criados estão presentes no JSON
        $response->assertJsonFragment([
            'id' => $contact1->id,
            'name' => 'Contato Um',
            'email' => 'teste@test.com',
        ]);
    }

    //Teste de retorno com o banco de dados vazio.
    public function test_empty_data()
    {
        $this->assertDatabaseCount('contacts', 0);

        $response = $this->get(route('apiContacts'));

        $response->assertStatus(200);

        $response->assertJson([]);
    }
}
