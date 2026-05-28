<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Delete outdated login & header/footer color settings
        DB::table('settings')->whereIn('key', [
            '--color-client-login-bg',
            '--color-client-header-bg',
            '--color-client-footer-bg',
            '--color-admin-login-bg',
        ])->delete();

        // 2. Insert consolidated client header/footer background setting
        DB::table('settings')->updateOrInsert(
            ['key' => '--color-client-header-footer-bg'],
            [
                'value' => '#ffffff',
                'description' => 'Color de fondo compartido para la barra superior (header) y el pie de página (footer) de la tienda cliente.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('settings')->where('key', '--color-client-header-footer-bg')->delete();

        // Reinsert defaults if rolled back
        $defaults = [
            ['key' => '--color-client-login-bg', 'value' => '#eef2ff', 'description' => 'Fondo exclusivo de la pantalla de login del cliente.'],
            ['key' => '--color-client-header-bg', 'value' => '#ffffff', 'description' => 'Fondo del encabezado superior del frontend.'],
            ['key' => '--color-client-footer-bg', 'value' => '#ffffff', 'description' => 'Fondo del pie de página del frontend.'],
            ['key' => '--color-admin-login-bg', 'value' => '#eef2ff', 'description' => 'Fondo exclusivo de la pantalla de login del admin.'],
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
