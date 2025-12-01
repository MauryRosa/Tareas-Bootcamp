<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class ConsultaController extends Controller
{
    public function ejecutarConsulta(string $metodo)
    {
        // Probar si el método existe y llamarlo dinámicamente
        if (method_exists($this, $metodo)) {
            return $this->$metodo();
        }
        return response()->json(['error' => 'Método de consulta no encontrado'], 404);
    }
    
    // ----------------------------------------------------------------------
    // EJERCICIOS DE CONSULTA
    // ----------------------------------------------------------------------

    /**
     * 2. Recupera todos los pedidos asociados al usuario con ID 2. [cite: 34]
     */
    public function getPedidosUsuario2()
    {
        // Uso de Eloquent (ORM) para aprovechar la relación definida en el modelo Usuario
        $pedidos = Usuario::find(2)->pedidos;

        return response()->json($pedidos);
    }

    /**
     * 3. Obtencion de la información detallada de los pedidos, incluyendo el nombre y correo
     * electrónico de los usuarios.
     */
    public function getPedidosConDatosUsuario()
    {
        // Uso de Eloquent con Eager Loading (load('usuario'))
        $pedidos = Pedido::with('usuario:id,nombre,correo')->get();
        
        /*
        $pedidos = DB::table('pedidos')
            ->join('usuarios', 'pedidos.id_usuario', '=', 'usuarios.id')
            ->select('pedidos.*', 'usuarios.nombre', 'usuarios.correo')
            ->get();
        */

        return response()->json($pedidos);
    }

    /**
     * 4. Recupera todos los pedidos cuyo total esté en el rango de $100 a $250. [cite: 37]
     */
    public function getPedidosPorRangoTotal()
    {
        // Uso de Eloquent con whereBetween
        $pedidos = Pedido::whereBetween('total', [100.00, 250.00])->get();

        return response()->json($pedidos);
    }

    /**
     * 5. Encuentra todos los usuarios cuyos nombres comiencen con la letra "R". [cite: 38]
     */
    public function getUsuariosPorNombreR()
    {
        // Uso de Eloquent y operador LIKE con comodín '%'
        $usuarios = Usuario::where('nombre', 'like', 'R%')->get();

        return response()->json($usuarios);
    }

    /**
     * 6. Calcula el total de registros en la tabla de pedidos para el usuario con ID 5. [cite: 39]
     */
    public function getTotalPedidosUsuario5()
    {
        // Uso de Eloquent (método count)
        $totalPedidos = Pedido::where('id_usuario', 5)->count();

        return response()->json(['usuario_id' => 5, 'total_pedidos' => $totalPedidos]);
    }

    /**
     * 7. Recupera todos los pedidos junto con la información de los usuarios, ordenándolos
     * de forma descendente según el total del pedido. [cite: 40, 41]
     */
    public function getPedidosOrdenadosConUsuario()
    {
        // Uso de Eloquent con Eager Loading y orderBy
        $pedidos = Pedido::with('usuario')
                        ->orderBy('total', 'desc')
                        ->get();

        return response()->json($pedidos);
    }

    /**
     * 8. Obtener la suma total del campo "total" en la tabla de pedidos. [cite: 42]
     */
    public function getSumaTotalPedidos()
    {
        // Uso de Eloquent (método sum)
        $sumaTotal = Pedido::sum('total');

        return response()->json(['suma_total_pedidos' => $sumaTotal]);
    }

    /**
     * 9. Encontrar el pedido más económico, junto con el nombre del usuario asociado. [cite: 43]
     */
    public function getPedidoMasEconomico()
    {
        // 1. Encontrar el pedido con el mínimo total
        $pedido = Pedido::with('usuario')
                        ->orderBy('total', 'asc')
                        ->first();
                        
        // Si no hay pedidos, devolver nulo
        if (!$pedido) {
            return response()->json(['message' => 'No hay pedidos.'], 404);
        }

        return response()->json([
            'producto' => $pedido->producto,
            'total' => $pedido->total,
            'usuario' => $pedido->usuario->nombre
        ]);
    }

    /**
     * 10. Obtener el producto, la cantidad y el total de cada pedido, agrupándolos por usuario. [cite: 44]
     */
    public function getPedidosAgrupadosPorUsuario()
    {
        // Uso de Eloquent y la relación: agrupar los usuarios y cargar sus pedidos.
        $usuariosConPedidos = Usuario::with(['pedidos' => function ($query) {
            $query->select('id_usuario', 'producto', 'cantidad', 'total');
        }])->get();

        return response()->json($usuariosConPedidos);
    }
}