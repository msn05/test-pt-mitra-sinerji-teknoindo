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
        Schema::connection('dbtest')->create('barangs', function (Blueprint $table) {
            $table->id();
            $table->char('code', 10)->unique();
            $table->string('name', 100);
            $table->double('price', 20)->default(0);
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
