<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = ['nombre', 'correo', 'telefono'];
    protected $table = 'usuarios';

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_usuario');
    }
}
