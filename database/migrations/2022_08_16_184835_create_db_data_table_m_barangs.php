<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbDataTableMBarangs extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('barangs', function (Blueprint $table) {
      $table->id();
      $table->char('code', 10)->unique()->comment('Kode Barang');
      $table->string('name', 100)->comment('Nama Barang');
      $table->double('price', 20)->default(0)->comment('Harga Barang');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::connection('dbtest')->dropIfExists('barangs');
  }
}
