<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->text('value')->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        DB::table('settings')->insert([
            [
                'key' => '--color-client-page',
                'value' => '#f8fafc',
                'description' => 'Fondo general del frontend cliente.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => '--color-client-primary',
                'value' => '#4f46e5',
                'description' => 'Color principal de botones, enlaces y textos importantes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => '--color-client-login-bg',
                'value' => '#eef2ff',
                'description' => 'Fondo exclusivo de la pantalla de login del cliente.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
