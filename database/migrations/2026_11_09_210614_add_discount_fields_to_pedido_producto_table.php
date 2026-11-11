
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_producto', function (Blueprint $table) {
            $table->decimal('precio_original', 10, 2)->nullable();
            $table->decimal('descuento_porcentaje', 5, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('pedido_producto', function (Blueprint $table) {
            $table->dropColumn([
                'precio_original',
                'descuento_porcentaje',
            ]);
        });
    }
};
