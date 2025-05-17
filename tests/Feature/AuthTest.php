<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    // Reseta o banco a cada teste para garantir um ambiente limpo
    use RefreshDatabase;

    /**
     * Testa se um usuário pode se registrar com sucesso
     */
    public function test_usuario_pode_se_registrar()
    {
        // Faz uma requisição POST com os dados do novo usuário
        $response = $this->postJson('/api/register', [
            'name' => 'Teste',
            'email' => 'teste@email.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'cep' => '01001000',
            'numero' => '123',
        ]);

        // Verifica se o status da resposta foi 200 (OK)
        $response->assertStatus(200);

        // Verifica se a resposta contém um token
        $this->assertArrayHasKey('token', $response->json());
    }

    /**
     * Testa se um usuário existente pode fazer login com sucesso
     */
    public function test_usuario_pode_logar()
    {
        // Cria um usuário no banco com senha criptografada
        $user = User::factory()->create([
            'password' => bcrypt('123456'),
        ]);

        // Envia requisição de login com e-mail e senha
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => '123456',
        ]);

        // Verifica se a resposta foi 200 (sucesso no login)
        $response->assertStatus(200);

        // Confirma que a resposta contém um token de autenticação
        $this->assertArrayHasKey('token', $response->json());
    }

    /**
     * Garante que usuários não autenticados não conseguem acessar rotas protegidas
     */
    public function test_usuario_nao_autenticado_nao_acessa_rotas_protegidas()
    {
        // Tenta acessar uma rota protegida sem autenticação
        $response = $this->getJson('/api/user');

        // Espera que a resposta seja 401 (unauthorized)
        $response->assertStatus(401);
    }
}
