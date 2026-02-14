<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MonitorController;
use App\Models\User; // <--- ⚠️ ESSA LINHA É OBRIGATÓRIA PARA A LISTA FUNCIONAR
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Redireciona a página inicial para o Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rota do Dashboard (Onde a mágica acontece)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        // Aqui buscamos todos os usuários para mandar para a tela
        'monitores' => User::select('id', 'name', 'current_lab', 'updated_at')
                            ->orderBy('name')
                            ->get()
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Grupo de rotas autenticadas
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Nossa rota de atualizar o laboratório
    Route::post('/monitor/update', [MonitorController::class, 'store'])->name('monitor.update');
});

require __DIR__.'/auth.php';