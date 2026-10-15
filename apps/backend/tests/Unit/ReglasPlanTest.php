<?php

namespace Tests\Unit;

use App\ReglasPlan;
use PHPUnit\Framework\TestCase;

class ReglasPlanTest extends TestCase
{
    public function test_el_organizador_puede_cancelar_un_borrador(): void
    {
        $this->assertTrue(ReglasPlan::puedeCancelar(1, 'borrador', 1));
    }

    public function test_el_organizador_puede_cancelar_uno_confirmado(): void
    {
        $this->assertTrue(ReglasPlan::puedeCancelar(1, 'confirmado', 1));
    }

    public function test_otra_persona_no_puede_cancelar(): void
    {
        $this->assertFalse(ReglasPlan::puedeCancelar(1, 'borrador', 2));
    }

    public function test_un_cancelado_no_se_vuelve_a_cancelar(): void
    {
        $this->assertFalse(ReglasPlan::puedeCancelar(1, 'cancelado', 1));
    }

    public function test_un_cancelado_no_se_puede_confirmar(): void
    {
        $this->assertFalse(ReglasPlan::puedeConfirmar(1, 'cancelado', 1));
    }

    public function test_el_organizador_puede_editar_un_borrador(): void
    {
        $this->assertTrue(ReglasPlan::puedeEditar(1, 'borrador', 1));
    }

    public function test_el_organizador_puede_editar_uno_confirmado(): void
    {
        $this->assertTrue(ReglasPlan::puedeEditar(1, 'confirmado', 1));
    }

    public function test_otra_persona_no_puede_editar(): void
    {
        $this->assertFalse(ReglasPlan::puedeEditar(1, 'confirmado', 2));
    }

    public function test_un_cancelado_no_se_edita(): void
    {
        $this->assertFalse(ReglasPlan::puedeEditar(1, 'cancelado', 1));
    }
}
