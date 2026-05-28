<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const NEW_SETTINGS = [
        [
            'key' => 'color_theme_admin',
            'value' => 'slate_corporate',
            'description' => 'Tema de color elegante predefinido para el panel administrativo y login admin.',
        ],
        [
            'key' => 'color_theme_web',
            'value' => 'indigo_imperial',
            'description' => 'Tema de color elegante predefinido para el sitio web y login cliente.',
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
