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
            $table->unsignedBigInteger('id');
            $table->boolean('is_active')->default(true);
            $table->string('username')->nullable();
            $table->string('name')->nullable();
            $table->tinyInteger('role')->default(0);
            $table->decimal('money', 7)->default(0.00);
            $table->decimal('money_from_referrals', 7)->default(0.00);
            $table->timestamp('registered_at')->useCurrent();

            $table->primary('id');
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
