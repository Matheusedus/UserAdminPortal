<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Lista os usuários com filtros opcionais por nome e e-mail.
     * Retorna os dados paginados, ordenados por ID descendente.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Aplica filtro por nome, se informado
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Aplica filtro por e-mail, se informado
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Retorna os resultados com paginação (10 por página)
        return response()->json(
            $query->select('id', 'name', 'email', 'role')
                ->orderBy('id', 'desc')
                ->paginate(10)
        );
    }

    /**
     * Atualiza os dados de um usuário existente.
     * Requer autenticação e validação via UserUpdateRequest.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        // Atualiza somente os campos permitidos
        $user->update($request->only('name', 'email', 'role'));

        return response()->json([
            'message' => 'Usuário atualizado com sucesso.',
            'user' => $user
        ]);
    }

    /**
     * Remove um usuário do sistema.
     * Requer autenticação e perfil de administrador.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Usuário excluído com sucesso.'
        ]);
    }
}
