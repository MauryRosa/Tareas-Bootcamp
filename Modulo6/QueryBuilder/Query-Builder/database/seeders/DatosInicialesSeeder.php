<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Pedido;

class DatosInicialesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    // Usuario 1
    $u1 = Usuario::create(['nombre' => 'Mauricio Bustillo', 'correo' => 'maury@gmail.com', 'telefono' => '7272888']);
    // Usuario 2 (ID 2 para la consulta 2)
    $u2 = Usuario::create(['nombre' => 'Jairo Vega', 'correo' => 'jairo@gmail.com', 'telefono' => '21212828']);
    // Usuario 3 (Nombre con 'R' para la consulta 5)
    $u3 = Usuario::create(['nombre' => 'Roberto Gómez', 'correo' => 'gomez@gmail.com', 'telefono' => '60609090']);
    // ... crea al menos 5 usuarios

    // Pedidos para el usuario 2 (ID 2)
    Pedido::create(['producto' => 'Laptop', 'cantidad' => 1, 'total' => 1200.00, 'id_usuario' => $u2->id]);
    Pedido::create(['producto' => 'Mouse Pad', 'cantidad' => 2, 'total' => 20.00, 'id_usuario' => $u2->id]);

    // Pedido en el rango $100-$250 (para la consulta 4)
    Pedido::create(['producto' => 'Teclado Mecánico', 'cantidad' => 1, 'total' => 150.50, 'id_usuario' => $u1->id]);

    // Pedido más económico (para la consulta 9)
    Pedido::create(['producto' => 'Sticker', 'cantidad' => 5, 'total' => 5.00, 'id_usuario' => $u3->id]);
    // ... crea al menos 5 pedidos asegurando que algunos cumplan las condiciones.
}
}
