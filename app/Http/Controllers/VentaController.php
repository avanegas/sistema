<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Venta;
use App\DetalleVenta;
use App\Persona;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $ventas = Venta::join('personas', 'ventas.idcliente', '=', 'personas.id')
                ->join('users', 'ventas.idusuario', '=', 'users.id')
                ->select(
                    'ventas.id',
                    'ventas.tipo_comprobante',
                    'ventas.serie_comprobante',
                    'ventas.num_comprobante',
                    'ventas.fecha_hora',
                    'ventas.impuesto',
                    'ventas.total_venta',
                    'ventas.estado',
                    'personas.nombre',
                    'users.usuario'
                )
                ->orderBy('ventas.id', 'desc')->paginate(3);
        } else {
            $ventas = Venta::join('personas', 'ventas.idcliente', '=', 'personas.id')
                ->join('users', 'ventas.idusuario', '=', 'users.id')
                ->select(
                    'ventas.id',
                    'ventas.tipo_comprobante',
                    'ventas.serie_comprobante',
                    'ventas.num_comprobante',
                    'ventas.fecha_hora',
                    'ventas.impuesto',
                    'ventas.total_venta',
                    'ventas.estado',
                    'personas.nombre',
                    'users.usuario'
                )
                ->where('ventas.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('ventas.id', 'desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page' => $ventas->perPage(),
                'last_page' => $ventas->lastPage(),
                'from' => $ventas->firstItem(),
                'to' => $ventas->lastItem(),
            ],
            'ventas' => $ventas
        ];
    }

    public function show(Request $request)
    {
        $id=$request->id;
        $venta = Venta::join('personas', 'ventas.idcliente', '=', 'personas.id')
            ->join('users', 'ventas.idusuario', '=', 'users.id')
            ->select(
                'ventas.id',
                'ventas.tipo_comprobante',
                'ventas.serie_comprobante',
                'ventas.num_comprobante',
                'ventas.fecha_hora',
                'ventas.impuesto',
                'ventas.total_venta',
                'ventas.estado',
                'personas.nombre',
                'users.usuario'
            )->findOrFail($id);

            $detalles = DetalleVenta::join('ventas','detalle_ventas.idventa','=','ventas.id')
            ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
            ->select(
                'articulos.nombre',
                'detalle_ventas.cantidad',
                'detalle_ventas.precio_venta',
                'detalle_ventas.descuento'
            )->where('ventas.id', '=', $id)->get();

        return['venta' => $venta, 'detalles' => $detalles ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            $mytime = Carbon::now('America/Guayaquil');

            $venta = new Venta();
            $venta->idcliente = $request->idcliente;
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_comprobante = $request->tipo_comprobante;
            $venta->serie_comprobante = $request->serie_comprobante;
            $venta->num_comprobante = $request->num_comprobante;
            $venta->fecha_hora = $mytime->ToDateString();
            $venta->impuesto = $request->impuesto;
            $venta->total_venta = $request->total_venta;
            $venta->estado = 'Registrado';
            $venta->save();

            $detalles = $request->data; // Array de detalles

            // Recorro todos los elementos
            foreach ($detalles as $ep => $det) {
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio_venta = $det['precio_venta'];
                $detalle->descuento = $det['descuento'];
                $detalle->save();
            }

            DB::commit();

        } catch (Excepcion $e) {
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();
    }

}
