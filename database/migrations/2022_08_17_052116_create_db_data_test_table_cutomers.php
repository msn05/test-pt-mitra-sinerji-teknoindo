<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbDataTestTableCutomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dbtest')->create('m_customers', function (Blueprint $table) {
            $table->id();
            $table->char('code', 10)->unique()->comment('Kode Pelanggan');
            $table->string('name')->comment('Nama Pelanggan');
            $table->bigInteger('phone')->unique()->comment('Nomor Telephone Pelanggan');
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
        Schema::dropIfExists('m_customers');
    }
}
