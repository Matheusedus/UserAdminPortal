<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ViaCepService
{
    /**
     * Consulta a API ViaCEP para obter os dados de endereço.
     *
     * @param string $cep CEP informado pelo usuário
     * @return array|null Retorna array com dados ou null se o CEP for inválido
     */
    public function buscarEndereco(string $cep): ?array
    {
        // Remove qualquer caractere que não seja número
        $cep = preg_replace('/[^0-9]/', '', $cep);

        // Chama a API pública do ViaCEP
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        // Verifica se a requisição falhou ou se o CEP não existe
        if ($response->failed() || $response->json('erro')) {
            return null;
        }

        // Retorna os dados formatados em um array associativo
        return [
            'rua' => $response->json('logradouro'),
            'bairro' => $response->json('bairro'),
            'cidade' => $response->json('localidade'),
            'estado' => $response->json('uf'),
        ];
    }
}
