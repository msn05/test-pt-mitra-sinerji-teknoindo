<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbDataTestTableTSalesDet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dbtest')->create('t_sales_det', function (Blueprint $table) {
            $table->foreignId('sale_id')->references('id')->on('t_sales')->onDelete('cascade')->comment('Relasi tabel Penjulan');
            $table->foreignId('product_id')->references('id')->on('barangs')->onDelete('restrict')->comment('Relasi banyak barang di tabel barang');;
            $table->integer('qty')->default(9)->comment('Jumlah Barang');
            $table->decimal('discount_pcs')->default(0)->comment('Diskon per barang');
            $table->decimal('grand_total')->default(0)->comment('Total Harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_sales_det');
    }
}
