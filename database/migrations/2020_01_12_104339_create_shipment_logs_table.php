<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('delivery_id' , '191');
            $table->float('state')->default(null)->nullable(true);
            $table->string('starName')->default(null)->nullable(true);
            $table->float('cod')->default(null)->nullable(true);
            $table->string('exceptionReason')->default(null)->nullable(true);
            $table->float('price')->default(null)->nullable(true);
            $table->float('weight')->default(null)->nullable(true);
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
        Schema::dropIfExists('shipment_logs');
    }
}
