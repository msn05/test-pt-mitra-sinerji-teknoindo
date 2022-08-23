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
        Schema::connection('dbtest')->table('t_sales_det', function (Blueprint $table) {
            $table->decimal('price_before', 20, 2)->default(0.00)->after('qty');
        });

        Schema::connection('dbtest')->table('t_sales', function (Blueprint $table) {
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
        Schema::connection('dbtest')->table('t_sales', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::connection('dbtest')->table('t_sales_det', function (Blueprint $table) {
            $table->dropColumn('price_before');
        });
    }
}
