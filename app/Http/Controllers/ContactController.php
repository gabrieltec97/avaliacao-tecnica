<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('contacts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.new-contact');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'cep' => 'required', 'string'
        ]);

        $cepClean = preg_replace('/[^0-9]/', '', $validated['cep']);

        // Inserindo os valores em cache para evitar multiplas requisições
        // e otimização de tempo.
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

        Contact::create($validated);

        return redirect()->route('contatos.index')->with('msg-success', 'Contato criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contact = Contact::find($id);
        return view('contacts.edit-contact', [
            'contact' => $contact
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'cep' => ['required', 'string']
        ]);

        $cepClean = preg_replace('/[^0-9]/', '', $validated['cep']);

        // Inserindo os valores em cache para evitar multiplas requisições
        // e otimização de tempo.
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

        $contact = Contact::findOrFail($id);
        $contact->update($validated);

        return redirect()->route('contatos.index')->with('msg-success', 'Contato atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('contatos.index')
            ->with('msg-success', 'Contato deletado com sucesso!');
    }
}
