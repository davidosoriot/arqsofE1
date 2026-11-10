<?php
// Autor: Mateo Ariztizabal

namespace Tests\Unit;

use App\Models\Promocion;
use Carbon\Carbon;
use Tests\TestCase;

class PromocionTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_promocion_del_dia_actual_esta_vigente(): void
    {
        // Fijamos "hoy" en un martes (2026-11-10 es martes).
        Carbon::setTestNow(Carbon::create(2026, 11, 10, 12));

        $promocion = new Promocion(['dia_semana' => 'martes', 'tipo_carne' => 'res', 'descuento' => 15]);

        $this->assertTrue($promocion->estaVigente());
    }

    public function test_promocion_de_otro_dia_no_esta_vigente(): void
    {
        // Mismo martes, pero la promocion es de jueves.
        Carbon::setTestNow(Carbon::create(2026, 11, 10, 12));

        $promocion = new Promocion(['dia_semana' => 'jueves', 'tipo_carne' => 'cerdo', 'descuento' => 10]);

        $this->assertFalse($promocion->estaVigente());
    }
}
