<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CepValidationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cep = preg_replace('/[^0-9]/', '', $value);

        // Verifica se o CEP tem 8 dígitos
        if (strlen($cep) !== 8) {
            $fail('O campo :attribute deve conter 8 dígitos.');
            return;
        }

        // Definindo uma chave única para o cache do CEP
        $cacheKey = 'cep_validation_' . $cep;

        // Tenta obter o resultado do cache
        $data = Cache::get($cacheKey);

        try {
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
            $data = $response->json();

            // Verifica se a API retornou erro ou não encontrou o CEP
            if (isset($data['erro']) && $data['erro']) {
                $fail('O CEP informado não é válido ou não foi encontrado.');
            }
        } catch (\Exception $e) {
            // Trata erros de conexão ou outros problemas
            $fail('Não foi possível validar o CEP no momento. Tente novamente mais tarde.');
        }
    }
}
