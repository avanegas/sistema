<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'idpersona', 'idusuario', 'tipo_comprobante', 'serie_comprobante', 'num_comprobante', 'fecha_hora', 'impuesto', 'total_venta', 'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo('App\User');
    }

    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
}
