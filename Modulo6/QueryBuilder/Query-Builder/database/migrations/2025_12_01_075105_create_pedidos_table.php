<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // database/migrations/..._create_pedidos_table.php
Schema::create('pedidos', function (Blueprint $table) {
    $table->id(); // PK Id [cite: 20]
    $table->string('producto', 150); // producto [cite: 22]
    $table->integer('cantidad'); // cantidad [cite: 24]
    $table->decimal('total', 8, 2); // total [cite: 26]

    // Clave Foránea: id_usuario
    $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
