<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\ViaCepService;
use Illuminate\Support\Facades\Http;

class ViaCepServiceTest extends TestCase
{
    /**
     * Testa se o serviço ViaCepService retorna corretamente o endereço
     * ao fornecer um CEP válido.
     */
    public function test_busca_endereco_valido()
    {
        // Simula uma resposta da API do ViaCEP para o CEP 01001000
        Http::fake([
            'viacep.com.br/ws/01001000/json/' => Http::response([
                'logradouro' => 'Praça da Sé',
                'bairro' => 'Sé',
                'localidade' => 'São Paulo',
                'uf' => 'SP',
            ], 200)
        ]);

        // Instancia o serviço
        $service = new ViaCepService();

        // Executa o método com um CEP válido
        $endereco = $service->buscarEndereco('01001000');

        // Valida que o retorno é um array
        $this->assertIsArray($endereco);

        // Valida os dados retornados
        $this->assertEquals('Praça da Sé', $endereco['rua']);
        $this->assertEquals('Sé', $endereco['bairro']);
        $this->assertEquals('São Paulo', $endereco['cidade']);
        $this->assertEquals('SP', $endereco['estado']);
    }

    /**
     * Testa se o serviço retorna null quando a API responde com erro,
     * ou quando o CEP informado não existe.
     */
    public function test_retorna_null_para_cep_invalido()
    {
        // Simula resposta da API ViaCEP para um CEP inválido
        Http::fake([
            'viacep.com.br/ws/00000000/json/' => Http::response(['erro' => true], 200)
        ]);

        // Instancia o serviço
        $service = new ViaCepService();

        // Executa o método com um CEP inválido
        $endereco = $service->buscarEndereco('00000000');

        // Espera que retorne null
        $this->assertNull($endereco);
    }
}
