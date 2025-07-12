<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AddressService
{
    //Requisição para ViaCep fazer a validação do cep recebido pelo formulário.
    public function getAddressFromCep(string $cep): ?array
    {
        $cepClean = preg_replace('/[^0-9]/', '', $cep);
        $cacheKey = 'cep_address_' . $cepClean;

        $addressData = Cache::get($cacheKey);

        if (!$addressData) {
            try {
                $response = Http::timeout(5)->get("https://viacep.com.br/ws/{$cepClean}/json/");
                $addressData = $response->json();

                if (!empty($addressData['erro'])) {
                    return null;
                }

                //Colocando cep em cache para evitar diversas requisições para o mesmo cep.
                Cache::put($cacheKey, $addressData, now()->addHours(24));
            } catch (\Exception $e) {
                return null;
            }
        }

        if (!empty($addressData['erro'])) {
            return null;
        }

        return [
            'address' => $addressData['logradouro'] ?? null,
            'neighborhood' => $addressData['bairro'] ?? null,
            'city' => $addressData['localidade'] ?? null,
            'state' => $addressData['uf'] ?? null,
        ];
    }

    public function mergeAddressIntoData(array $data): ?array
    {
        $address = $this->getAddressFromCep($data['cep']);

        if (!$address) {
            return null;
        }

        return array_merge($data, $address);
    }
}
