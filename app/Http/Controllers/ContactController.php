<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use App\Services\AddressService;

class ContactController extends Controller
{
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function index()
    {
        return view('contacts.index');
    }

    public function create()
    {
        return view('contacts.new-contact');
    }

    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();

        $address = $this->addressService->getAddressFromCep($validated['cep']);

        if (!$address) {
            return back()->withErrors(['cep' => 'O CEP informado não é válido ou não foi encontrado.'])->withInput();
        }

        $contactData = array_merge($validated, $address);

        Contact::create($contactData);

        return redirect()->route('contatos.index')->with('msg-success', 'Contato criado com sucesso!');
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit-contact', compact('contact'));
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $validated = $request->validated();

        $address = $this->addressService->getAddressFromCep($validated['cep']);

        if (!$address) {
            return back()->withErrors(['cep' => 'O CEP informado não é válido ou não foi encontrado.'])->withInput();
        }

        $contact->update(array_merge($validated, $address));

        return redirect()->route('contatos.index')->with('msg-success', 'Contato atualizado com sucesso!');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('contatos.index')
            ->with('msg-success', 'Contato deletado com sucesso!');
    }
}
