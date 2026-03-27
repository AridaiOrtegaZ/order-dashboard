<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcesarCargosExpress extends Command
{
    protected $signature   = 'pedidos:procesar-cargos-express';
    protected $description = 'Aplica un recargo del 10% a pedidos pendientes con entrega mañana que incluyan el producto de Manejo Especial (id=5)';

    public function handle(): void
    {
        $manana = Carbon::tomorrow()->toDateString();

        $pedidos = Pedido::where('estado', 'pendiente')
            ->where('recargo_aplicado', false)
            ->whereDate('fecha_entrega', $manana)
            ->whereHas('productos', fn($q) => $q->where('productos.id', 5))
            ->get();

        if ($pedidos->isEmpty()) {
            $this->info('No hay pedidos que procesar hoy.');
            return;
        }

        $ids = $pedidos->pluck('id');

        Pedido::whereIn('id', $ids)
            ->update([
                'total'             => DB::raw('total * 1.10'),
                'recargo_aplicado'  => true,
            ]);

        $this->info("✅ {$pedidos->count()} pedidos actualizados con recargo del 10%.");
    }
}