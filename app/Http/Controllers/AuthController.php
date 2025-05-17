<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Services\ViaCepService;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    /**
     * Realiza o cadastro de um novo usuário com preenchimento automático do endereço via ViaCEP.
     * Retorna token de autenticação e dados do usuário.
     */
    public function register(RegisterUserRequest $request, ViaCepService $viaCepService)
    {
        // Consulta o endereço baseado no CEP informado
        $endereco = $viaCepService->buscarEndereco($request->cep);

        // Retorna erro caso o CEP seja inválido
        if (!$endereco) {
            return response()->json(['error' => 'CEP inválido.'], 422);
        }

        // Cria o novo usuário com os dados preenchidos
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'cep'      => $request->cep,
            'numero'   => $request->numero,
            'rua'      => $endereco['rua'],
            'bairro'   => $endereco['bairro'],
            'cidade'   => $endereco['cidade'],
            'estado'   => $endereco['estado'],
        ]);

        // Retorna o token gerado e os dados do usuário
        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * Realiza o login do usuário.
     * Valida e-mail e senha e retorna um token de autenticação.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // Verifica se o usuário existe e se a senha está correta
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais estão incorretas.'],
            ]);
        }

        // Retorna o token e os dados do usuário
        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * Realiza logout do usuário autenticado.
     * Revoga todos os tokens ativos.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout efetuado com sucesso.']);
    }

    /**
     * Retorna os dados do usuário autenticado.
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
