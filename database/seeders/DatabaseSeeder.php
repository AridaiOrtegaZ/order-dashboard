<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Producto::query()->delete();
        Cliente::query()->delete();
        Pedido::query()->delete();

        $productos = [
            ['nombre' => 'Laptop HP 15', 'sku' => 'LAP-HP15-001', 'precio' => 12499.00],
            ['nombre' => 'Mouse Logitech M185', 'sku' => 'MOU-LOG-185', 'precio' => 299.00],
            ['nombre' => 'Teclado Mecánico Redragon', 'sku' => 'TEC-RED-K552', 'precio' => 899.00],
            ['nombre' => 'Monitor Samsung 24"', 'sku' => 'MON-SAM-24', 'precio' => 3299.00],
            ['nombre' => 'Envío Express', 'sku' => 'SRV-EXP-001', 'precio' => 199.00],
            ['nombre' => 'Audífonos Sony WH-CH520', 'sku' => 'AUD-SON-520', 'precio' => 1099.00],
            ['nombre' => 'Cargador USB-C 65W', 'sku' => 'CAR-USC-65W', 'precio' => 649.00],
            ['nombre' => 'Mochila para Laptop', 'sku' => 'MOC-LAP-001', 'precio' => 799.00],
            ['nombre' => 'Disco SSD Kingston 1TB', 'sku' => 'SSD-KNG-1TB', 'precio' => 1499.00],
            ['nombre' => 'Webcam Logitech C920', 'sku' => 'WEB-LOG-C920', 'precio' => 1799.00],
            ['nombre' => 'Tablet Samsung Galaxy Tab A9', 'sku' => 'TAB-SAM-A9', 'precio' => 4599.00],
            ['nombre' => 'Smartwatch Xiaomi Redmi Watch', 'sku' => 'SWT-XIA-001', 'precio' => 1399.00],
            ['nombre' => 'Cable HDMI 2m', 'sku' => 'CBL-HDM-2M', 'precio' => 199.00],
            ['nombre' => 'Base Enfriadora para Laptop', 'sku' => 'ACC-COOL-01', 'precio' => 549.00],
            ['nombre' => 'Impresora HP DeskJet', 'sku' => 'IMP-HP-2775', 'precio' => 2199.00],
            ['nombre' => 'Memoria USB 128GB', 'sku' => 'USB-128-001', 'precio' => 249.00],
            ['nombre' => 'Silla de Oficina Ergonómica', 'sku' => 'OFI-ERG-001', 'precio' => 3899.00],
            ['nombre' => 'Router TP-Link AX1800', 'sku' => 'NET-TPL-1800', 'precio' => 1599.00],
            ['nombre' => 'Bocina JBL Flip 6', 'sku' => 'BOC-JBL-F6', 'precio' => 2499.00],
            ['nombre' => 'Soporte para Monitor', 'sku' => 'ACC-MON-001', 'precio' => 699.00],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }

        Cliente::factory(100)->create();

        $clienteIds = Cliente::pluck('id')->toArray();
        $productosDb = Producto::all();

        Pedido::factory(1000)
            ->make()
            ->each(function ($pedido) use ($clienteIds, $productosDb) {
                $pedido->cliente_id = fake()->randomElement($clienteIds);

                $estado = fake()->randomElement([
                    'entregado',
                    'entregado',
                    'entregado',
                    'entregado',
                    'entregado',
                    'cancelado',
                    'cancelado',
                    'pendiente',
                    'pendiente',
                    'pendiente',
                ]);

                $pedido->estado = $estado;

                $pedido->fecha_entrega = match ($estado) {
                'entregado' => fake()->dateTimeBetween('-30 days', '-1 day'),
                'pendiente' => fake()->boolean(35)
                    ? fake()->dateTimeBetween('-15 days', '-1 day')   // estos serán los retrasados visuales
                    : fake()->dateTimeBetween('now', '+5 days'),
                'cancelado' => fake()->dateTimeBetween('-10 days', '+3 days'),
                default => fake()->dateTimeBetween('-5 days', '+5 days'),
            };

                $pedido->save();

                $cantidadProductos = fake()->numberBetween(1, 4);

                $productosSeleccionados = $productosDb
                    ->where('nombre', '!=', 'Envío Express')
                    ->random($cantidadProductos);

                $idsProductos = collect($productosSeleccionados)->pluck('id')->toArray();

                $agregarExpress = $estado === 'pendiente'
                    ? fake()->boolean(35)
                    : fake()->boolean(15);

                if ($agregarExpress) {
                    $productoExpress = $productosDb->firstWhere('nombre', 'Envío Express');

                    if ($productoExpress && !in_array($productoExpress->id, $idsProductos)) {
                        $idsProductos[] = $productoExpress->id;
                    }
                }

                $pedido->productos()->attach($idsProductos);

                $total = $productosDb
                    ->whereIn('id', $idsProductos)
                    ->sum('precio');

                $pedido->update([
                    'total' => $total,
                ]);
            });
    }
}