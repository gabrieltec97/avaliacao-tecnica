<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacts.index');
    }

    public function create()
    {
        return view('contacts.new-contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:14|unique:contacts,phone',
            'email' => 'required|email|unique:contacts,email',
            'cep' => ['required', 'string', 'max:8']
        ],[
            'name.required' => 'O campo "Nome" é obrigatório',
            'name.max' => 'O campo de nome pode ter no máximo 100 caracteres',

            'phone.required' => 'O campo Telefone é obrigatório',
            'phone.unique' => 'Este número de telefone já está cadastrado.',
            'phone.max' => 'Número de telefone inválido',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',

            'cep.required' => 'O campo CEP é obrigatório.',
            'cep.string' => 'O CEP deve ser uma sequência de caracteres.',
            'cep.max' => 'O CEP deve ter no máximo 8 caracteres.',
        ]);

        $cepClean = preg_replace('/[^0-9]/', '', $validated['cep']);

        // Inserindo os valores em cache para evitar múltiplas requisições e otimização de tempo.
        $addressData = null;
        $cacheKey = 'cep_address_' . $cepClean;

        $addressData = Cache::get($cacheKey);

        if ($addressData === null) {
            try {
                $response = Http::timeout(5)->get("https://viacep.com.br/ws/{$cepClean}/json/");
                $addressData = $response->json();

                if (isset($addressData['erro']) && ($addressData['erro'] === true || $addressData['erro'] === 'true')) {
                    return back()->withErrors(['cep' => 'O CEP informado não é válido ou não foi encontrado.'])
                        ->withInput();
                }

                Cache::put($cacheKey, $addressData, now()->addHours(24));

            } catch (\Exception $e) {
                return back()->withErrors(['cep' => 'Não foi possível validar o CEP no momento. Tente novamente mais tarde.'])
                    ->withInput();
            }
        } else {
            if (isset($addressData['erro']) && ($addressData['erro'] === true || $addressData['erro'] === 'true')) {
                return back()->withErrors(['cep' => 'O CEP informado não é válido ou não foi encontrado.'])
                    ->withInput();
            }
        }

        $validated['address'] = $addressData['logradouro'] ?? null;
        $validated['neighborhood'] = $addressData['bairro'] ?? null;
        $validated['city'] = $addressData['localidade'] ?? null;
        $validated['state'] = $addressData['uf'] ?? null;

        Contact::create($validated);

        return redirect()->route('contatos.index')->with('msg-success', 'Contato criado com sucesso!');
    }

    public function edit(string $id)
    {
        $contact = Contact::find($id);
        return view('contacts.edit-contact', [
            'contact' => $contact
        ]);
    }

    public function update(Request $request, string $id)
    {
        $contact = Contact::findOrFail($id);
        $mail = trim($request->email);
        $phone = trim($request->phone);
        $checkMail = DB::table('contacts')->select('id', 'email')->where('email', $mail)->get();
        $checkPhone = DB::table('contacts')->select('id', 'phone')->where('phone', $phone)->get();

        if ($checkMail->isNotEmpty() && $checkMail->first()->id != $id) {
            return redirect()->back()->with('msg-error', 'Este endereço de e-mail já está sendo utilizado.');
        }

        if ($checkPhone->isNotEmpty() && $checkPhone->first()->id != $id) {
            return redirect()->back()->with('msg-error', 'Este número de telefone já está sendo utilizado.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:14',
            'email' => 'required|email',
            'cep' => ['required', 'string', 'max:8']
        ],[
            'name.required' => 'O campo "Nome" é obrigatório',
            'name.max' => 'O campo de nome pode ter no máximo 100 caracteres',

            'phone.required' => 'O campo Telefone é obrigatório',
            'phone.max' => 'Número de telefone inválido',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.',

            'cep.required' => 'O campo CEP é obrigatório.',
            'cep.string' => 'O CEP deve ser uma sequência de caracteres.',
            'cep.max' => 'O CEP deve ter no máximo 8 caracteres.',
        ]);

        $cepClean = preg_replace('/[^0-9]/', '', $validated['cep']);

        // Inserindo os valores em cache para evitar múltiplas requisições e otimização de tempo.
        $addressData = null;
        $cacheKey = 'cep_address_' . $cepClean;

        $addressData = Cache::get($cacheKey);

        if ($addressData === null) {
            try {
                $response = Http::timeout(5)->get("https://viacep.com.br/ws/{$cepClean}/json/");
                $addressData = $response->json();

                if (isset($addressData['erro']) && ($addressData['erro'] === true || $addressData['erro'] === 'true')) {
                    return back()->withErrors(['cep' => 'O CEP informado não é válido ou não foi encontrado.'])
                        ->withInput();
                }

                Cache::put($cacheKey, $addressData, now()->addHours(24));

            } catch (\Exception $e) {
                return back()->withErrors(['cep' => 'Não foi possível validar o CEP no momento. Tente novamente mais tarde.'])
                    ->withInput();
            }
        } else {
            if (isset($addressData['erro']) && ($addressData['erro'] === true || $addressData['erro'] === 'true')) {
                return back()->withErrors(['cep' => 'O CEP informado não é válido ou não foi encontrado.'])
                    ->withInput();
            }
        }

        $validated['address'] = $addressData['logradouro'] ?? null;
        $contact->update($validated);

        return redirect()->route('contatos.index')->with('msg-success', 'Contato atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        Contact::findOrFail($id)->delete();

        return redirect()
            ->route('contatos.index')
            ->with('msg-success', 'Contato deletado com sucesso!');
    }
}
