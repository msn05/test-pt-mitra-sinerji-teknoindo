<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDbTestSale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_sales_det', function (Blueprint $table) {
            $table->decimal('price_before', 20, 2)->default(0.00)->after('qty');
        });

        Schema::table('t_sales', function (Blueprint $table) {
            $table->enum('status', [0, 1, 2])->default(0)->after('grand_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_sales', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('t_sales_det', function (Blueprint $table) {
            $table->dropColumn('price_before');
        });
    }
}
