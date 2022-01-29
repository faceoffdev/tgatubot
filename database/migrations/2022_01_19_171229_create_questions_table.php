<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->unsignedBigInteger('id');

            $table->string('name');
            $table->decimal('price', 7)->default(0.00);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->unsignedTinyInteger('semester')->default(1)->index();
            $table->string('group', 5)->index();
            $table->string('discipline')->index();
            $table->unsignedTinyInteger('module')->default(1)->index();
            $table->boolean('is_laboratory')->default(false)->index();
            $table->unsignedInteger('delay')->default(15);

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
        Schema::dropIfExists('questions');
    }
}
