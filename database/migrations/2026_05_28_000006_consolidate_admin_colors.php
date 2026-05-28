<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Delete `--color-admin-header-bg` from settings
        DB::table('settings')->where('key', '--color-admin-header-bg')->delete();
    }

    public function down(): void
    {
        // Re-insert `--color-admin-header-bg`
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
};
