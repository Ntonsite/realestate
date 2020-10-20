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
            $table->json('account_type')->default(json_encode(['Customer' => true, 'Dalali' => [], 'Client' => false, 'Pro' => false]));
            $table->string('name');
            $table->string('first_name')->default('user');
            $table->string('last_name')->default('user-lastname');
            $table->json('phone')->nullable();
            $table->enum('country', ['Tz', 'BW']);
            $table->string('business_email')->nullable();
            $table->string('image')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->json('favorites')->nullable();//array of property list
            $table->integer('status')->default(1); // 1 = active user 0 = suspended user
            $table->rememberToken();
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
