<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('contatos.index');
});

//Rotas criadas mesmo existindo a rota de resource em virtude dos services e requests.
Route::get('contatos/{contact}/edit', [ContactController::class, 'edit'])->name('contatos.edit');
Route::delete('contatos/{contact}', [ContactController::class, 'destroy'])->name('contatos.destroy');
Route::put('contatos/{contact}', [ContactController::class, 'update'])->name('contatos.update');
Route::resource('/contatos', ContactController::class);

//Rota de redirecionamento para a rota de api solicitada no item 5 do desafio.
Route::get('/contacts', function (){
    return redirect()->route('apiContacts');
});
