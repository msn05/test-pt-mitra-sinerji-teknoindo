<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSoftDeleteInDbtestTableSale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('dbtest')->table('t_sales', function (Blueprint $table) {
            $table->softDeletes();
            $table->mediumText('reason')->nullable()->after('status');
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
            $table->dropColumn('deleted_at');
            $table->dropColumn('reason');
        });
    }
}
