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
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->json('account_type')->default(json_encode(['Customer' => true, 'Client' => false, 'Pro' => false]));
            $table->string('name');
            $table->string('first_name');
            $table->string('last_name');
            $table->json('phone')->default(json_encode(['Phone1' =>null, 'Phone2' => null]));
            $table->enum('country', ['Tz', 'Bw']);
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

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
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
