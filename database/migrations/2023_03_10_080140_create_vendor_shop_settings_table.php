<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorShopSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_shop_settings', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id')->nullable();
            $table->string('shop_name');
            $table->string('shop_logo')->nullable();
            $table->string('shop_banner')->nullable();
            $table->string('shop_email');
            $table->string('shop_mobile');
            $table->integer('shop_url_slug');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postcode');
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
        Schema::dropIfExists('vendor_shop_settings');
    }
}
