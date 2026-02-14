<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab' => 'nullable|string|max:255',
        ]);

        $request->user()->update([
            'current_lab' => $validated['lab']
        ]);

        return back();
    }
}
