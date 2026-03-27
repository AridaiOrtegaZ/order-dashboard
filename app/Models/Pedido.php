<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'fecha_entrega', 'total', 'estado'];

    protected $casts = [
        'fecha_entrega' => 'date',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'pedido_producto');
    }

    // Local Scopes
    public function scopePorEnviar(Builder $query): Builder
    {
        return $query->where('estado', 'pendiente')
            ->whereDate('fecha_entrega', '>=', now()->toDateString())
            ->whereDate('fecha_entrega', '<=', now()->addDays(3)->toDateString());
    }

    public function scopeRetrasados($query)
    {
        return $query->where('estado', 'pendiente')
            ->whereDate('fecha_entrega', '<', now()->toDateString());
    }

    public function scopeEntregados(Builder $query): Builder
    {
        return $query->where('estado', 'entregado');
    }

    public function scopeCancelados(Builder $query): Builder
    {
        return $query->where('estado', 'cancelado');
    }
}