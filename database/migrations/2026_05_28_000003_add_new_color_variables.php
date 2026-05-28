<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const NEW_SETTINGS = [
        [
            'key' => '--color-admin-login-bg',
            'value' => '#eef2ff',
            'description' => 'Color de fondo exclusivo de la pantalla de login del admin.',
        ],
        [
            'key' => '--color-admin-page-bg',
            'value' => '#f3f4f6',
            'description' => 'Color de fondo principal para el panel administrativo.',
        ],
    ];

    public function up(): void
    {
        foreach (self::NEW_SETTINGS as $setting) {
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

    public function down(): void
    {
        $keys = array_column(self::NEW_SETTINGS, 'key');
        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
