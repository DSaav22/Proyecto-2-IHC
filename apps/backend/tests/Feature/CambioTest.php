<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CambioTest extends TestCase
{
    use RefreshDatabase;

    public function test_al_crear_un_plan_el_cambio_queda_guardado(): void
    {
        $user = User::create([
            'name' => 'Uno',
            'email' => 'uno@planazo.com',
            'password' => 'Planazo123!',
        ]);

        $this->actingAs($user);

        $this->postJson('/api/planes', [
            'nombre' => 'Cena',
            'fecha' => 'hoy',
        ])->assertStatus(201);

        $res = $this->getJson('/api/cambios');

        $res->assertStatus(200);
        $cambios = $res->json('cambios');
        $this->assertCount(1, $cambios);
        $this->assertSame('Cena', $cambios[0]['nombre']);
        $this->assertSame('creado', $cambios[0]['accion']);
        $this->assertSame('borrador', $cambios[0]['resultado']);
    }
}
