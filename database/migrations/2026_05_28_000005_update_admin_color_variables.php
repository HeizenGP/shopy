<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Delete outdated admin accent color setting
        DB::table('settings')->where('key', '--color-admin-accent')->delete();

        // 2. Insert admin container background setting
        DB::table('settings')->updateOrInsert(
            ['key' => '--color-admin-container-bg'],
            [
                'value' => '#ffffff',
                'description' => 'Color de fondo para tarjetas, listados y contenedores en el panel administrativo.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 3. Insert admin header background setting
        DB::table('settings')->updateOrInsert(
            ['key' => '--color-admin-header-bg'],
            [
                'value' => '#ffffff',
                'description' => 'Color de fondo para la barra superior (cabecera) del panel administrativo.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        // Delete new variables
        DB::table('settings')->whereIn('key', [
            '--color-admin-container-bg',
            '--color-admin-header-bg'
        ])->delete();

        // Reinsert admin accent
        DB::table('settings')->updateOrInsert(
            ['key' => '--color-admin-accent'],
            [
                'value' => '#ec4899',
                'description' => 'Color de acento en el admin.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
};
