<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Criar Novo (POST)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'perfil' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'perfil' => $request->perfil,
        ]);

        return redirect()->back()->with('success', 'Utilizador criado com sucesso!');
    }

    // Editar Existente (PUT)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'perfil' => 'required'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->perfil = $request->perfil;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
    }

    // Remover (DELETE)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Impede que o admin se apague a si próprio
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Não pode apagar a sua própria conta!');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Utilizador removido do sistema.');
    }
}