<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout.app');
});

Route::resource('/contatos', ContactController::class);

//Rota de redirecionamento para a rota de api solicitada no item 5 do desafio.
Route::get('/contacts', function (){
    return redirect()->route('apiContacts');
});
