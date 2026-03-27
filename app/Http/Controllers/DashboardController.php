<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $porEnviar = Pedido::porEnviar()
            ->with(['cliente', 'productos'])
            ->paginate(10, ['*'], 'porEnviar');

        $retrasados = Pedido::retrasados()
            ->with(['cliente', 'productos'])
            ->paginate(10, ['*'], 'retrasados');

        $entregados = Pedido::entregados()
            ->with(['cliente', 'productos'])
            ->paginate(10, ['*'], 'entregados');

        $cancelados = Pedido::cancelados()
            ->with(['cliente', 'productos'])
            ->paginate(10, ['*'], 'cancelados');

        return view('dashboard', compact(
            'porEnviar',
            'retrasados',
            'entregados',
            'cancelados'
        ));
    }
}