<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogisticRegressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistic_regressions', function (Blueprint $table) {
            $table->id();
            $table->float('bud_injection_x1', 20, 2);
            $table->unsignedBigInteger('area_id');
            $table->date('bu_injection_date')->nullable();
            $table->float('bagging_report_x2', 20, 2)->nullable();
            $table->date('bagging_report_date')->nullable();
            $table->float('stem_cut_y', 20, 2)->nullable();
            $table->date('stem_cut_y_date')->nullable();
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
        Schema::dropIfExists('logistic_regressions');
    }
}
