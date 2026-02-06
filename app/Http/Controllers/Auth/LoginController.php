<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Exibe a tela de login (Preto e Dourado)
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processa a tentativa de login
     */
    public function login(Request $request)
    {
        // Validação básica dos campos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tenta autenticar o utilizador
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Padronizamos para comparar sem erro de maiúsculas/minúsculas
            $perfil = ucfirst(strtolower($user->perfil)); 

            /**
             * REDIRECIONAMENTO POR PERFIL
             */
            if ($perfil === 'Admin') {
                return redirect()->route('admin.dashboard');
            } 
            
            if ($perfil === 'Barbeiro') {
                return redirect()->route('barbeiro.dashboard');
            }

            if ($perfil === 'Gestor' || $perfil === 'Gestordeatendimento') {
                return redirect()->route('gestor.dashboard');
            }

            // Fallback: Se não for nenhum perfil específico, manda para a welcome
            // NUNCA mais manda para /atendimento fixo
            return redirect()->intended(route('welcome'));
        }

        // Se falhar, volta com erro
        return back()->withErrors([
            'email' => 'As credenciais informadas não coincidem com os nossos registos.',
        ])->onlyInput('email');
    }

    /**
     * Finaliza a sessão do utilizador
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}