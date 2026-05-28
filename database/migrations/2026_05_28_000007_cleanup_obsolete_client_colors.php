<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Delete obsolete client colors from settings table
        DB::table('settings')->whereIn('key', [
            '--color-client-border',
            '--color-client-header-footer-bg',
            '--color-client-card',
            '--color-client-card-border'
        ])->delete();
    }

    public function down(): void
    {
        // Re-insert defaults if rolled back
        $defaults = [
            ['key' => '--color-client-border', 'value' => '#e2e8f0', 'description' => 'Bordes de inputs, cards, tablas y divisores.'],
            ['key' => '--color-client-header-footer-bg', 'value' => '#ffffff', 'description' => 'Color de fondo compartido para la barra superior y el pie de página.'],
            ['key' => '--color-client-card', 'value' => '#ffffff', 'description' => 'Tarjetas de productos, paneles y módulos del frontend.'],
            ['key' => '--color-client-card-border', 'value' => '#e2e8f0', 'description' => 'Borde visual de tarjetas, paneles y contenedores.'],
        ];

        foreach ($defaults as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'description' => $setting['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
};
