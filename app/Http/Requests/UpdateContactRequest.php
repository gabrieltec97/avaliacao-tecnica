<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'email' => 'required|email',
            'cep' => ['required', 'string', 'max:9']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo "Nome" é obrigatório',
            'name.max' => 'O campo de nome pode ter no máximo 100 caracteres',

            'phone.required' => 'O campo Telefone é obrigatório',
            'phone.max' => 'Número de telefone inválido',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.',

            'cep.required' => 'O campo CEP é obrigatório.',
            'cep.string' => 'O CEP deve ser uma sequência de caracteres.',
            'cep.max' => 'O CEP deve ter no máximo 8 caracteres.',
        ];
    }
}
