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
    Schema::create('order_items', function (Blueprint $table) {
        $table->engine = 'InnoDB';
        $table->id(); // también unsignedBigInteger

        $table->unsignedBigInteger('order_id');
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

        $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
        $table->integer('cantidad');
        $table->decimal('precio_unitario', 10, 2);
        $table->timestamps();
    });

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    protected $fillable = ['order_id', 'producto_id', 'cantidad', 'precio_unitario'];

};
