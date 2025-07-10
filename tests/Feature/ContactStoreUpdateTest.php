<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use App\Models\Contact;

class ContactStoreUpdateTest extends TestCase
{
    use RefreshDatabase;

    //Teste de fluxo normal de armazenamento com os dados válidos.
    public function test_normal_store_data()
    {
        //Simulação de requisição
        Http::fake([
            'viacep.com.br/*' => Http::response([
                'cep' => '20000-000',
                'logradouro' => 'Rua do Teste',
                'bairro' => 'Centro',
                'localidade' => 'Rio de Janeiro',
            ], 200),
        ]);

        $contactData = [
            'name' => 'Teste',
            'phone' => '21000000000',
            'email' => 'teste@test.com',
            'cep' => '20000-000',
        ];

        $response = $this->post(route('contatos.store'), $contactData);

        $response->assertRedirect(route('contatos.index'));
        $response->assertSessionHas('msg-success', 'Contato criado com sucesso!');

        // Testando se o contato foi criado no banco de dados.
        $this->assertDatabaseHas('contacts', [
            'name' => 'Teste',
            'phone' => '21000000000',
            'email' => 'teste@test.com',
            'cep' => '20000-000',
            'address' => 'Rua do Teste',
        ]);

        $this->assertNotNull(Cache::get('cep_address_20000000'));
    }

    //Teste de campos obrigatórios (required, string, max).
    public function test_fields_requirements()
    {
        $response = $this->post(route('contatos.store'), [
            'name' => '',
            'phone' => '',
            'email' => '',
            'cep' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'phone', 'email', 'cep']);

        $this->assertDatabaseCount('contacts', 0);
    }

    //Teste de e-mail duplicado.
    public function test_duplicated_email()
    {
        Contact::create([
            'name' => 'Nome',
            'phone' => '11111111111',
            'email' => 'teste@test.com',
            'cep' => '00000-000',
            'address' => 'Rua Existente'
        ]);

        $response = $this->post(route('contatos.store'), [
            'name' => 'Novo Nome',
            'phone' => '99999999999',
            'email' => 'teste@test.com',
            'cep' => '20000-000',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);

        $this->assertDatabaseCount('contacts', 1);
    }

    //Teste de falha de criação de contato caso tenha um cep inválido após consulta via API.
    public function test_invalid_cep()
    {
        Http::fake([
            'viacep.com.br/*' => Http::response([
                'erro' => true
            ], 200),
        ]);

        $response = $this->post(route('contatos.store'), [
            'name' => 'Nome Teste',
            'phone' => '21000000000',
            'email' => 'teste@test.com',
            'cep' => '00000000',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['cep']);
        $response->assertSessionHas('errors');

        $this->assertDatabaseCount('contacts', 0);
    }

    //Teste de falha na comunicação com a API do ViaCep.
    public function test_communication_error()
    {
        Http::fake([
            'viacep.com.br/*' => Http::response([
                'erro' => true
            ], 500),
        ]);

        $response = $this->post(route('contatos.store'), [
            'name' => 'João Teste',
            'phone' => '21987654321',
            'email' => 'joao.teste@example.com',
            'cep' => '20000-000',
        ]);


        $response->assertRedirect();
        $response->assertSessionHasErrors(['cep']);
        $response->assertSessionHas('errors');

        $this->assertDatabaseCount('contacts', 0);
    }

    //Teste de uso de endereço já em cache.
    public function test_cache_address()
    {
        $cepClean = '20000000';
        $cacheKey = 'cep_address_' . $cepClean;
        $cachedAddressData = [
            'cep' => '20000-000',
            'logradouro' => 'Rua Cache',
            'localidade' => 'Rio de Janeiro',
            'uf' => 'RJ'
        ];
        Cache::put($cacheKey, $cachedAddressData, now()->addHours(24));

        // Assegura que nenhuma requisição HTTP real seja feita
        Http::fake();

        $contactData = [
            'name' => 'Teste Cache',
            'phone' => '21000000000',
            'email' => 'teste@test.com',
            'cep' => '20000-000',
        ];

        $response = $this->post(route('contatos.store'), $contactData);

        $response->assertRedirect(route('contatos.index'));
        $response->assertSessionHas('msg-success', 'Contato criado com sucesso!');

        $this->assertDatabaseHas('contacts', [
            'name' => 'Teste Cache',
            'email' => 'teste@test.com',
            'address' => 'Rua Cache',
        ]);

        // Verificando se o cache foi usado para evitar requisição externa
        Http::assertNothingSent();
    }
}
