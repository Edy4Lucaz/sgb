<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class LucroServiceTest extends TestCase
{
    /**
     * Teste unitário basico.
     */
    public function test_calculo_lucro_esta_correto()
{
    $preco = 1000;
    $comissao = 200;
    $lucroEsperado = 800;

    $lucroReal = $preco - $comissao;

    $this->assertEquals($lucroEsperado, $lucroReal);
}
}
