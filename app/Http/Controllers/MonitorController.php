<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function store(Request $request)
    {
        // Validação
        $validated = $request->validate([
            'lab' => 'nullable|string|max:255',
        ]);

        // Atualização (ATENÇÃO AQUI)
        // Usamos $validated['lab'] para pegar o texto, não o array todo.
        $request->user()->update([
            'current_lab' => $validated['lab'] 
        ]);

        return back();
    }
}