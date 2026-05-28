@extends('layouts.admin')

@section('title', 'Super Admin')
@section('admin_heading', 'Welcome back, '.Auth::user()->name)
@section('admin_subheading', "Here are today's stats from your online store")

@section('content')
<div class="space-y-7">
    <section class="grid gap-6 xl:grid-cols-3">
        <article class="rounded-[30px] bg-[#202123] p-7 text-white">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="grid h-11 w-11 place-items-center rounded-2xl bg-white/20">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M7 8V6a5 5 0 0 1 10 0v2h2v13H5V8h2Zm2 0h6V6a3 3 0 0 0-6 0v2Zm2 5v3h2v-3h-2Z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black">Total Sales</h2>
                        <p class="text-sm font-semibold text-white/45">{{ $stats['orders'] }} Orders</p>
                    </div>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-2xl leading-none text-white/80">›</a>
            </div>
            <p class="mt-8 text-3xl font-black tracking-tight">${{ number_format($stats['revenue'], 2) }}</p>
            <div class="mt-5 flex items-center gap-8 text-sm font-black">
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="m4 16 6-6 4 4 5-7 1.6 1.2-6.4 9-4.1-4.1L5.4 17.4 4 16Z"/></svg>
                    +15.6%
                </span>
                <span>+{{ $stats['orders'] }} this week</span>
            </div>
        </article>

        <article class="rounded-[30px] bg-white p-7">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-100">
                        <svg class="h-6 w-6 text-slate-950" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-8 9a8 8 0 1 1 16 0H4Z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black">Usuarios</h2>
                        <p class="text-sm font-semibold text-slate-400">Clientes y equipo</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-2xl leading-none text-slate-950">›</a>
            </div>
            <p class="mt-8 text-3xl font-black tracking-tight">{{ number_format($stats['users']) }}</p>
            <div class="mt-5 flex items-center gap-8 text-sm font-black">
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="m4 16 6-6 4 4 5-7 1.6 1.2-6.4 9-4.1-4.1L5.4 17.4 4 16Z"/></svg>
                    +12.7%
                </span>
                <span class="text-slate-500">roles activos</span>
            </div>
        </article>

        <article class="rounded-[30px] bg-white p-7">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-100">
                        <svg class="h-6 w-6 text-slate-950" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 10 10h-2a8 8 0 1 1-2.35-5.65L15 9h7V2l-2.93 2.93A9.97 9.97 0 0 0 12 2Zm-1 5v6l5 3 .9-1.8-3.9-2.3V7h-2Z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black">Stock Alerts</h2>
                        <p class="text-sm font-semibold text-slate-400">{{ $stats['lowStock'] }} low stock</p>
                    </div>
                </div>
                <a href="{{ route('admin.inventory.index') }}" class="text-2xl leading-none text-slate-950">›</a>
            </div>
            <p class="mt-8 text-3xl font-black tracking-tight">{{ number_format($stats['alerts']) }}</p>
            <div class="mt-5 flex items-center gap-8 text-sm font-black">
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4 rotate-180" viewBox="0 0 24 24" fill="currentColor"><path d="m4 16 6-6 4 4 5-7 1.6 1.2-6.4 9-4.1-4.1L5.4 17.4 4 16Z"/></svg>
                    -12.7%
                </span>
                <span class="text-slate-500">revisar hoy</span>
            </div>
        </article>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1fr_246px]">
        <article class="rounded-[30px] bg-white p-7">
            <div class="mb-7 flex flex-wrap items-center justify-between gap-4">
                <h2 class="text-lg font-black text-slate-950">Sales Performance</h2>
                <div class="flex gap-3">
                    <button class="rounded-full border border-slate-200 px-5 py-2 text-xs font-bold text-slate-600">Export data</button>
                    <button class="rounded-full border border-slate-200 px-5 py-2 text-xs font-bold text-slate-600">Last 14 Days</button>
                </div>
            </div>

            <div class="relative h-72 overflow-hidden rounded-2xl">
                <svg viewBox="0 0 760 290" class="h-full w-full">
                    <g stroke="#ececec" stroke-width="1">
                        <line x1="48" y1="42" x2="735" y2="42"/>
                        <line x1="48" y1="82" x2="735" y2="82"/>
                        <line x1="48" y1="122" x2="735" y2="122"/>
                        <line x1="48" y1="162" x2="735" y2="162"/>
                        <line x1="48" y1="202" x2="735" y2="202"/>
                        <line x1="48" y1="242" x2="735" y2="242"/>
                    </g>
                    <g fill="#a8a8a8" font-size="12" font-weight="700">
                        <text x="22" y="246">0</text><text x="16" y="206">10</text><text x="16" y="166">20</text>
                        <text x="16" y="126">30</text><text x="16" y="86">40</text><text x="16" y="46">50</text>
                    </g>
                    <polyline fill="none" stroke="#d5d5d5" stroke-width="3" points="48,82 100,175 152,132 204,182 256,132 308,104 360,170 412,175 464,124 516,150 568,160 620,176 672,172 735,146"/>
                    <polyline fill="none" stroke="#555" stroke-width="3" points="48,160 100,126 152,110 204,140 256,120 308,45 360,70 412,202 464,150 516,132 568,176 620,207 672,152 735,90"/>
                    <g fill="#fff" stroke="#555" stroke-width="3">
                        <circle cx="48" cy="160" r="4"/><circle cx="100" cy="126" r="4"/><circle cx="152" cy="110" r="4"/><circle cx="204" cy="140" r="4"/><circle cx="256" cy="120" r="4"/><circle cx="308" cy="45" r="4"/><circle cx="360" cy="70" r="4"/><circle cx="412" cy="202" r="4"/><circle cx="464" cy="150" r="4"/><circle cx="516" cy="132" r="4"/><circle cx="568" cy="176" r="4"/><circle cx="620" cy="207" r="4"/><circle cx="672" cy="152" r="4"/><circle cx="735" cy="90" r="4"/>
                    </g>
                    <g fill="#aaa" font-size="12" font-weight="700">
                        <text x="40" y="275">03 Wed</text><text x="99" y="275">04 Thu</text><text x="158" y="275">05 Fri</text><text x="217" y="275">06 Sat</text><text x="276" y="275">07 Sun</text><text x="335" y="275">08 Mon</text><text x="394" y="275">09 Tue</text><text x="453" y="275">10 Wed</text><text x="512" y="275">11 Thu</text><text x="571" y="275">12 Fri</text><text x="630" y="275">13 Sat</text><text x="690" y="275">14 Sun</text>
                    </g>
                </svg>
            </div>
        </article>

        <article class="rounded-[30px] bg-white p-7">
            <h2 class="text-lg font-black text-slate-950">Top Categories</h2>
            <div class="mx-auto mt-8 grid h-36 w-36 place-items-center rounded-full border-[10px] border-[#202123] border-l-slate-200 border-b-slate-200">
                <span class="text-xl font-black">${{ number_format($stats['revenue'] / 1000, 1) }}k</span>
            </div>
            <div class="mt-8 space-y-4">
                @forelse($topCategories as $category)
                    <div class="flex items-center justify-between text-sm font-black">
                        <span class="flex items-center gap-3">
                            <span class="h-4 w-4 rounded bg-slate-800"></span>
                            {{ $category->category }}
                        </span>
                        <span class="text-slate-400">{{ $category->total }}</span>
                    </div>
                @empty
                    <p class="text-sm font-semibold text-slate-400">No hay categorías todavía.</p>
                @endforelse
            </div>
        </article>
    </section>
</div>
@endsection
