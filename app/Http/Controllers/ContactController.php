<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use App\Services\AddressService;
use Illuminate\View\View;

class ContactController extends Controller
{
    //Serviço de requisição de Cep para evitar duplicidade de código.
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function index(): View
    {
        return view('contacts.index');
    }

    public function create(): View
    {
        return view('contacts.new-contact');
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $contactData = $this->addressService->mergeAddressIntoData($validated);

        if (!$contactData) {
            return back()->withErrors(['cep' => 'O CEP informado não é válido ou não foi encontrado.'])->withInput();
        }

        Contact::create($contactData);
        return redirect()->route('contatos.index')->with('msg-success', 'Contato criado com sucesso!');
    }

    public function edit(Contact $contact): View
    {
        return view('contacts.edit-contact', compact('contact'));
    }

    public function update(UpdateContactRequest $request, Contact $contact): RedirectResponse
    {
        $validated = $request->validated();

        $contactData = $this->addressService->mergeAddressIntoData($validated);

        if (!$contactData) {
            return back()->withErrors(['cep' => 'O CEP informado não é válido ou não foi encontrado.'])->withInput();
        }

        $contact->update($contactData);
        return redirect()->route('contatos.index')->with('msg-success', 'Contato atualizado com sucesso!');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();
        return redirect()
            ->route('contatos.index')
            ->with('msg-success', 'Contato deletado com sucesso!');
    }
}
