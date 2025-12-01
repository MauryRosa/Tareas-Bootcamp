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
        Schema::create('usuarios', function (Blueprint $table) {
    $table->id(); // PK Id [cite: 19]
    $table->string('nombre', 100); // nombre [cite: 21]
    $table->string('correo', 100)->unique(); // correo [cite: 23]
    $table->string('telefono', 20)->nullable(); // telefono [cite: 25]
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
