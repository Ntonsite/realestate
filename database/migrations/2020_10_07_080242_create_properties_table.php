<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->json('rental_frequency')->default(json_encode(['frequency' => ['Yearly' => null,'Monthly' => null,'Weekly' => null,'Daily' => null]]));
            $table->json('near_by_name')->nullable();
            $table->string('category');
            $table->string('type');
            $table->json('offer')->default(json_encode(['long_stay_night' => 0, 'offer_percentage' => 0]));
            $table->longText('media')->default(json_encode(['images' => [], 'videos' => []]));
            $table->longText('location');
            $table->integer('bedroom')->default(0);
            $table->integer('bathroom')->default(0);
            $table->integer('status')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
