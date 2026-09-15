<?php
// Autor: David Osorio
// Tabla pivote: relacion "Pedido 1..* -- 1..* Producto" del diagrama de clases.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_producto');
    }
};
