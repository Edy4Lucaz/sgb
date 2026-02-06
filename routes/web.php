<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\GestorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// --- 1. ÁREA PÚBLICA ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- 2. ÁREAS PROTEGIDAS (Apenas Logados) ---
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD E GESTÃO DO ADMIN ---
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Gestão de Utilizadores (Barbeiros/Staff)
    Route::post('/admin/utilizador/salvar', [UserController::class, 'store'])->name('utilizadores.store');
    Route::put('/admin/utilizador/editar/{id}', [UserController::class, 'update'])->name('utilizadores.update');
    Route::delete('/admin/utilizador/remover/{id}', [UserController::class, 'destroy'])->name('utilizadores.destroy');

    // Gestão de Salários
    Route::post('/admin/salario/salvar', [AdminController::class, 'salvarSalario'])->name('admin.salvarSalario');

    // --- DASHBOARD DO BARBEIRO ---
    Route::get('/barbeiro/dashboard', [ServicoController::class, 'dashboard'])->name('barbeiro.dashboard');
    Route::get('/barbeiro/historico', [ServicoController::class, 'historico'])->name('barbeiro.historico');

    // --- PAINEL DO GESTOR / RECEPÇÃO ---
    Route::get('/gestor/dashboard', [GestorController::class, 'dashboard'])->name('gestor.dashboard');
    
    // Fechar Caixa (Definido como GET para funcionar via link simples no botão)
Route::post('/gestor/caixa/fechar', [GestorController::class, 'fecharCaixa'])->name('caixa.fechar');

    // --- GESTÃO DE RELATÓRIOS (PDF e CSV) ---
    Route::get('/gestor/relatorio/pdf', [GestorController::class, 'gerarRelatorioPDF'])->name('relatorio.pdf');
    Route::get('/gestor/relatorio/csv', [GestorController::class, 'gerarRelatorioCSV'])->name('relatorio.csv');

    // --- GESTÃO DE MENSALIDADES ---
    Route::get('/gestor/mensalidades', [GestorController::class, 'mensalidades'])->name('mensalidades.index');
    Route::post('/gestor/mensalidades/salvar', [GestorController::class, 'storeMensalidade'])->name('mensalidades.store');
    Route::post('/gestor/mensalidades/renovar/{id}', [GestorController::class, 'renovarMensalidade'])->name('mensalidades.renovar');
    Route::put('/gestor/mensalidades/editar/{id}', [GestorController::class, 'updateMensalidade'])->name('mensalidades.update');
    Route::delete('/gestor/mensalidades/eliminar/{id}', [GestorController::class, 'eliminarMensalidade'])->name('mensalidades.eliminar');

    // --- REGISTO DE ATENDIMENTO ---
    Route::post('/atendimento/salvar', [ServicoController::class, 'store'])->name('servicos.store');

});