<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => [
                'required',
                'string',
                'size:11',
                Rule::unique('contacts')->ignore($this->contact?->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('contacts')->ignore($this->contact?->id),
            ],
            'cep' => ['required', 'string', 'size:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo "Nome" é obrigatório',
            'name.max' => 'O campo de nome pode ter no máximo 100 caracteres',

            'phone.required' => 'O campo Telefone é obrigatório',
            'phone.unique' => 'Este número de telefone já está cadastrado.',
            'phone.size' => 'Número de telefone inválido',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'email.email' => 'O e-mail informado não é válido.',

            'cep.required' => 'O campo CEP é obrigatório.',
            'cep.string' => 'O CEP deve ser uma sequência de caracteres.',
            'cep.size' => 'Preencha o campo de cep apenas com números (8 caracteres).',
        ];
    }
}
