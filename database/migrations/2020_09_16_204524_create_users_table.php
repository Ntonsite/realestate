<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->json('account_type')->default(json_encode(['Customer' => true, 'Client' => false, 'Pro' => false]));
            $table->string('name');
<<<<<<< HEAD
            $table->string('first_name');
            $table->string('last_name');
            $table->json('phone')->default(json_encode(['Phone1' =>null, 'Phone2' => null]));
            $table->enum('country', ['Tz', 'Bw']);
=======
            $table->string('first_name')->default('user');
            $table->string('last_name')->default('user-lastname');
            $table->json('phone')->nullable();
            $table->enum('country', ['Tz', 'BW']);
>>>>>>> 11f3cde66fd4b8d8399d262cda9968a0e60ae8f2
            $table->string('business_email')->nullable();
            $table->string('image')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('verified_token')->nullable();
            $table->json('favorites')->default(json_encode(['favorite_list' => []]));//array of property list
            $table->json('client')->default(json_encode(['client_list' => []]));//array of client list
            $table->json('dalali')->default(json_encode(['dalali' => []]));//array of dalali list
            $table->integer('status')->default(1); // 1 = active user 0 = suspended user
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
