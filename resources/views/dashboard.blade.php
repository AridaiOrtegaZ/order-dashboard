<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Logística</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">

    {{-- Navbar --}}
    <nav class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6">
            <div>
                <h1 class="text-xl font-bold tracking-tight">📦 Panel de Logística</h1>
                <p class="text-sm text-slate-500">Vista general de pedidos por estado</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden text-right sm:block">
                    <p class="text-sm font-medium text-slate-700">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400">Sesión activa</p>
                </div>

                <img
                    src="{{ Auth::user()->avatar }}"
                    alt="Avatar de usuario"
                    class="h-10 w-10 rounded-full ring-2 ring-slate-200"
                >

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-500 transition hover:bg-red-50">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6">
        {{-- Navegación rápida --}}
        <div class="sticky top-[73px] z-30 mb-6 rounded-2xl border border-slate-200 bg-white/90 p-3 shadow-sm backdrop-blur">
            <div class="flex flex-wrap gap-2">
                <a href="#porEnviar" class="rounded-full bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100">
                    🚚 Por Enviar
                </a>
                <a href="#retrasados" class="rounded-full bg-amber-50 px-4 py-2 text-sm font-medium text-amber-700 hover:bg-amber-100">
                    ⚠️ Retrasados
                </a>
                <a href="#entregados" class="rounded-full bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-100">
                    ✅ Entregados
                </a>
                <a href="#cancelados" class="rounded-full bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 hover:bg-rose-100">
                    ❌ Cancelados
                </a>
            </div>
        </div>

        <div class="space-y-6">
            @include('partials.tabla-pedidos', [
                'id'         => 'porEnviar',
                'titulo'     => '🚚 Por Enviar',
                'color'      => 'blue',
                'pedidos'    => $porEnviar,
                'paginaParam'=> 'porEnviar',
            ])

            @include('partials.tabla-pedidos', [
                'id'         => 'retrasados',
                'titulo'     => '⚠️ Retrasados',
                'color'      => 'amber',
                'pedidos'    => $retrasados,
                'paginaParam'=> 'retrasados',
            ])

            @include('partials.tabla-pedidos', [
                'id'         => 'entregados',
                'titulo'     => '✅ Entregados',
                'color'      => 'emerald',
                'pedidos'    => $entregados,
                'paginaParam'=> 'entregados',
            ])

            @include('partials.tabla-pedidos', [
                'id'         => 'cancelados',
                'titulo'     => '❌ Cancelados',
                'color'      => 'rose',
                'pedidos'    => $cancelados,
                'paginaParam'=> 'cancelados',
            ])
        </div>
    </main>
</body>
</html>