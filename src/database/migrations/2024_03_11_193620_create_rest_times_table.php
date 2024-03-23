<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rest_times', function (Blueprint $table) {
        $table->id();
        $table->foreignID('work_time_id')->constrained()->cascadeOnDelate();
        $table->string('start')->nullable();
        $table->datetime('finish')->nullable()->useCurrent();
        $table->float('total_time')->nullable()->useCurrent();
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
        Schema::dropIfExists('rest_times');
    }
}
