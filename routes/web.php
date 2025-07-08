<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout.app');
});

Route::resource('/contatos', ContactController::class);
