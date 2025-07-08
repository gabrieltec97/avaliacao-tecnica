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
        echo 'here';
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
//        $validated = $request->validate([
//            'contactName' => 'required|string|max:255',
//            'contactPhone' => 'required|string|max:255',
//            'contactEmail' => 'required|email|unique:contacts,email',
//            'contactCep' => 'required|string|max:255',
//        ]);
//
//        Contact::create($validated);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
