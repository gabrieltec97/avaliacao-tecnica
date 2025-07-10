<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

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
            'email' => 'required|email',
            'cep' => 'required|string|max:255',
        ]);

        Contact::create($validated);

        return redirect()->route('contatos.index');
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
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'cep'   => 'required|string|max:9',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->update($validated);

        return redirect()->route('contatos.index')
            ->with('msg-success', 'Contato atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
