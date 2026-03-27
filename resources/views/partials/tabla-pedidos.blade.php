@php
    $badgeStyles = match($color) {
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'rose' => 'bg-rose-50 text-rose-700 ring-rose-200',
        default => 'bg-slate-50 text-slate-700 ring-slate-200',
    };

    $headerStyles = match($color) {
        'blue' => 'bg-blue-50/70 text-blue-800',
        'amber' => 'bg-amber-50/70 text-amber-800',
        'emerald' => 'bg-emerald-50/70 text-emerald-800',
        'rose' => 'bg-rose-50/70 text-rose-800',
        default => 'bg-slate-50 text-slate-800',
    };
@endphp

<section id="{{ $id }}" class="scroll-mt-32 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">
                {{ $titulo }}
                <span class="ml-2 text-sm font-normal text-slate-400">
                    ({{ $pedidos->total() }} pedidos)
                </span>
            </h2>
            <p class="text-sm text-slate-500">
                Página {{ $pedidos->currentPage() }} de {{ $pedidos->lastPage() }}
            </p>
        </div>
    </div>

    @if($pedidos->isEmpty())
        <p class="px-5 py-6 text-sm text-slate-400">No hay pedidos en esta categoría.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed text-left text-sm">
                <thead class="text-xs font-semibold uppercase tracking-wide {{ $headerStyles }}">
                    <tr>
                        <th class="w-16 px-4 py-3">#</th>
                        <th class="w-52 px-4 py-3">Cliente</th>
                        <th class="w-32 px-4 py-3">Total</th>
                        <th class="w-36 px-4 py-3">Entrega</th>
                        <th class="px-4 py-3">Productos</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach($pedidos as $pedido)
                        <tr class="align-top transition hover:bg-slate-50/80">
                            <td class="px-4 py-4 text-sm text-slate-400">
                                {{ $pedido->id }}
                            </td>

                            <td class="px-4 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $pedido->cliente->nombre }}
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <span class="font-semibold text-slate-700">
                                    ${{ number_format($pedido->total, 2) }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-slate-600">
                                {{ $pedido->fecha_entrega->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex max-w-3xl flex-wrap gap-2">
                                    @foreach($pedido->productos->take(4) as $producto)
                                        <span class="rounded-full px-2.5 py-1 text-xs font-medium ring-1 {{ $badgeStyles }}">
                                            {{ $producto->nombre }}
                                        </span>
                                    @endforeach

                                    @if($pedido->productos->count() > 4)
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-slate-200">
                                            +{{ $pedido->productos->count() - 4 }} más
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 px-5 py-4">
            {{ $pedidos->appends(request()->except($paginaParam))->fragment($id)->links() }}
        </div>
    @endif
</section>