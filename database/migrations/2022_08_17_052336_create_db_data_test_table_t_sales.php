<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbDataTestTableTSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_sales', function (Blueprint $table) {
            $table->id();
            $table->char('code')->unique()->comment('Kode Penjualan');
            $table->date('date_of_sale')->comment('Tanggal Penjualan');
            $table->foreignId('customer_id')->references('id')->on('m_customers')->onDelete('restrict');
            $table->decimal('sub_total')->default(0)->comment('Sub Total');
            $table->decimal('discount')->default(0)->comment('Diskon');
            $table->decimal('shipping')->default(0)->comment('Ongkir');
            $table->decimal('grand_total')->default(0)->comment('Total Bayar');
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
        Schema::dropIfExists('t_sales');
    }
}
