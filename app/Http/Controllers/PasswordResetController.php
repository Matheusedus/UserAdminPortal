<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Events\PasswordResetRequested;
use App\Models\User;

class PasswordResetController extends Controller
{
    /**
     * Inicia o processo de recuperação de senha.
     * Gera um token e dispara um evento para envio de e-mail assíncrono.
     */
    public function forgot(ForgotPasswordRequest $request)
    {
        // Localiza o usuário pelo e-mail informado
        $user = User::where('email', $request->email)->first();

        // Cria um token de redefinição de senha
        $token = Password::createToken($user);

        // Dispara evento para envio do e-mail (tratado pelo Listener)
        event(new PasswordResetRequested($user, $token));

        return response()->json([
            'message' => 'E-mail de recuperação enviado com sucesso.'
        ]);
    }

    /**
     * Realiza a redefinição de senha com base no token enviado por e-mail.
     */
    public function reset(ResetPasswordRequest $request)
    {
        // Executa o processo de reset via método nativo do Laravel
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60), // reseta token de sessão
                ])->save();
            }
        );

        // Retorna sucesso ou erro, conforme resultado do processo
        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Senha redefinida com sucesso.']);
        }

        return response()->json(['message' => 'Erro ao redefinir a senha.'], 400);
    }
}
