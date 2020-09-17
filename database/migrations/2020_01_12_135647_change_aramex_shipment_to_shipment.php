<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAramexShipmentToShipment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('aramex_shipment')) {
            Schema::table('aramex_shipment', function (Blueprint $table) {
                $table->string('shipment_company')->nullable();
                $table->string('trackingNumber')->nullable();
            });
        }
        else {
            Schema::table('shipment', function (Blueprint $table) {
                if (!Schema::hasColumn('shipment' , 'shipment_company')) {
                    $table->string('shipment_company')->nullable();
                }
                if (!Schema::hasColumn('shipment' , 'trackingNumber')) {
                    $table->string('trackingNumber')->nullable();
                }

            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('shipment')) {
            Schema::table('shipment', function (Blueprint $table) {
                $table->dropColumn('shipment_company');
                $table->dropColumn('trackingNumber');
                Schema::rename('shipment', 'aramex_shipment');
            });
        }
    }
}
