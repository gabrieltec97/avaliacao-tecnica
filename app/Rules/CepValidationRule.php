<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CepValidationRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cep = preg_replace('/[^0-9]/', '', $value);

        if (strlen($cep) !== 8) {
            $fail('O campo :attribute deve conter 8 dígitos.');
            return;
        }


        $cacheKey = 'cep_validation_' . $cep;
        $data = Cache::get($cacheKey);

        try {
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
            $data = $response->json();

            if (isset($data['erro']) && $data['erro']) {
                $fail('O CEP informado não é válido ou não foi encontrado.');
            }
        } catch (\Exception $e) {
            $fail('Não foi possível validar o CEP no momento. Tente novamente mais tarde.');
        }
    }
}
