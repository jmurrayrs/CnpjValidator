<?php
use PHPUnit\Framework\TestCase;

class CnpjCalculatorTest extends TestCase
{
    /**
     * Teste para CNPJ numérico válido
     */
    public function testCnpjNumericoValido()
    {
        $this->assertTrue(CnpjCalculator::validarCNPJ("11.444.777/0001-61"));
        $this->assertTrue(CnpjCalculator::validarCNPJ("11444777000161"));
    }

    /**
     * Teste para CNPJ alfanumérico válido
     */
    public function testCnpjAlfanumericoValido()
    {
        $this->assertTrue(CnpjCalculator::validarCNPJ("E9.640.DP2/MQ95-33"));
        $this->assertTrue(CnpjCalculator::validarCNPJ("E9640DP2MQ9533"));
    }

    /**
     * Teste para CNPJ numérico inválido
     */
    public function testCnpjNumericoInvalido()
    {
        $this->assertFalse(CnpjCalculator::validarCNPJ("11.444.777/0001-62"));
        $this->assertFalse(CnpjCalculator::validarCNPJ("11444777000162"));
    }

    /**
     * Teste para CNPJ alfanumérico inválido
     */
    public function testCnpjAlfanumericoInvalido()
    {
        $this->assertFalse(CnpjCalculator::validarCNPJ("E9.640.DP2/MQ95-34"));
        $this->assertFalse(CnpjCalculator::validarCNPJ("E9640DP2MQ9534"));
    }

    /**
     * Teste para CNPJ com tamanho incorreto
     */
    public function testCnpjTamanhoIncorreto()
    {
        $this->assertFalse(CnpjCalculator::validarCNPJ("123")); // Muito curto
        $this->assertFalse(CnpjCalculator::validarCNPJ("11.444.777/0001-61-999")); // Muito longo
        $this->assertFalse(CnpjCalculator::validarCNPJ("")); // Vazio
    }

    /**
     * Teste para CNPJ com caracteres inválidos
     */
    public function testCnpjCaracteresInvalidos()
    {
        $this->assertFalse(CnpjCalculator::validarCNPJ("11.444.777/0001-6a")); // Letra no DV
        $this->assertFalse(CnpjCalculator::validarCNPJ("11@444#777/0001-61")); // Caracteres especiais
    }

    /**
     * Teste para CNPJ com formato válido mas dígitos errados
     */
    public function testCnpjFormatoValidoDigitosInvalidos()
    {
        $this->assertFalse(CnpjCalculator::validarCNPJ("00.000.000/0000-00")); // CNPJ conhecidamente inválido
        $this->assertFalse(CnpjCalculator::validarCNPJ("11.111.111/1111-11")); // CNPJ conhecidamente inválido
    }

    /**
     * Teste para CNPJ válido sem formatação
     */
    public function testCnpjSemFormatacao()
    {
        $this->assertTrue(CnpjCalculator::validarCNPJ("11444777000161"));
        $this->assertTrue(CnpjCalculator::validarCNPJ("E9640DP2MQ9533"));
    }

    /**
     * Teste para o método valorChar com caracteres válidos
     */
    public function testValorCharCaracteresValidos()
    {
        $this->assertEquals(0, CnpjCalculator::valorChar('0'));
        $this->assertEquals(9, CnpjCalculator::valorChar('9'));
        $this->assertEquals(1, CnpjCalculator::valorChar('A'));
        $this->assertEquals(25, CnpjCalculator::valorChar('Z'));
    }

    /**
     * Teste para o método valorChar com caractere inválido
     */
    public function testValorCharCaractereInvalido()
    {
        $this->expectException(InvalidArgumentException::class);
        CnpjCalculator::valorChar('@');
    }

    /**
     * Teste para o método gerarPesos
     */
    public function testGerarPesos()
    {
        $pesos12 = CnpjCalculator::gerarPesos(12);
        $pesos13 = CnpjCalculator::gerarPesos(13);
        
        $this->assertCount(12, $pesos12);
        $this->assertCount(13, $pesos13);
        
        // Verifica se os pesos estão na faixa correta (2-9)
        $this->assertGreaterThanOrEqual(2, min($pesos12));
        $this->assertLessThanOrEqual(9, max($pesos12));
    }

    /**
     * Teste para calcularUmDv com valores conhecidos
     */
    public function testCalcularUmDv()
    {
        // Teste com base conhecida
        $this->assertEquals(6, CnpjCalculator::calcularUmDv("114447770001", false));
        $this->assertEquals(1, CnpjCalculator::calcularUmDv("1144477700016", true));
    }

    /**
     * Teste de performance com múltiplas validações
     */
    public function testMultiplasValidacoes()
    {
        $cnpjs = [
            "11.444.777/0001-61" => true,
            "E9.640.DP2/MQ95-33" => true,
            "11.444.777/0001-62" => false,
            "E9.640.DP2/MQ95-34" => false,
            "123" => false
        ];

        foreach ($cnpjs as $cnpj => $esperado) {
            $this->assertEquals($esperado, CnpjCalculator::validarCNPJ($cnpj));
        }
    }
}
