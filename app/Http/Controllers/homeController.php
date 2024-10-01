<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Marca;
use App\Models\Venta;
use App\Models\Compra;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Presentacione;
use Illuminate\Support\Facades\Auth;

class homeController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return view('welcome');
        }

        $compras = count(Compra::all());

        $ventas = count(Venta::all());

        $ventasMes = Venta::whereMonth('fecha_hora', Carbon::now()->month)
            ->whereYear('fecha_hora', Carbon::now()->year)
            ->where('estado', 1)
            ->sum('total');
        $comprasMes = Compra::whereMonth('fecha_hora', Carbon::now()->month)
            ->whereYear('fecha_hora', Carbon::now()->year)
            ->where('estado', 1)
            ->sum('total');

        $ventasAnio = Venta::whereYear('fecha_hora', Carbon::now()->year)
            ->where('estado', 1)
            ->sum('total');
        $comprasAnio = Compra::whereYear('fecha_hora', Carbon::now()->year)
            ->where('estado', 1)
            ->sum('total');



        // Obtener ventas mensuales
        $ventasMensuales = Venta::selectRaw('MONTH(fecha_hora) as month, SUM(total) as total')
            ->where('estado', 1)
            ->groupByRaw('MONTH(fecha_hora)')
            ->get()
            ->keyBy('month');

        // Obtener compras mensuales
        $comprasMensuales = Compra::selectRaw('MONTH(fecha_hora) as month, SUM(total) as total')
            ->where('estado', 1)
            ->groupByRaw('MONTH(fecha_hora)')
            ->get()
            ->keyBy('month');

        // Inicializar los datos
        $ventasData = [];
        $comprasData = [];
        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        // Rellenar los datos para cada mes
        foreach ($months as $num => $mes) {
            $ventasData[] = $ventasMensuales[$num]->total ?? 0;  // Si no hay ventas en ese mes, se pone 0
            $comprasData[] = $comprasMensuales[$num]->total ?? 0; // Si no hay compras en ese mes, se pone 0
        }
        return view(
            'panel.index',
            compact('compras', 'ventas', 'ventasMes', 'comprasMes', 'ventasAnio', 'comprasAnio', 'ventasData', 'comprasData', 'months')
        );
    }
}
