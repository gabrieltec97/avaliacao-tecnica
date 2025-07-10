<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Support\Facades\Log;

class ApiContactController extends Controller
{
    public function index()
    {
        try {
            $contacts = Contact::all();

            return response()->json([
                'data' => $contacts
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Erro ao buscar contatos', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Falha ao se conectar com a API. Contate o administrador do sistema.'
            ], 500);
        }
    }
}
