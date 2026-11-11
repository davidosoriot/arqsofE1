<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carrito_producto', function (Blueprint $table) {
            $table->decimal('cantidad', 8, 3)->default(1)->change();
        });

        Schema::table('pedido_producto', function (Blueprint $table) {
            $table->decimal('cantidad', 8, 3)->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('carrito_producto', function (Blueprint $table) {
            $table->unsignedInteger('cantidad')->default(1)->change();
        });

        Schema::table('pedido_producto', function (Blueprint $table) {
            $table->unsignedInteger('cantidad')->default(1)->change();
        });
    }
};