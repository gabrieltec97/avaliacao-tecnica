<?php

use App\Http\Controllers\Api\ApiContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/contacts', [ApiContactController::class, 'index'])->name('apiContacts');
