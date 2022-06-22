<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHourlyDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hourly_data', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->date('date');
            $table->integer('hour');

            $table->uuid('master_account_id');
            $table->foreign('master_account_id')->references('id')->on('master_accounts')->onDelete('cascade');

            $table->uuid('sub_account_id');
            $table->foreign('sub_account_id')->references('id')->on('sub_accounts')->onDelete('cascade');

            $table->string('cost');
            $table->string('cost_usd');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_online')->default(false);

            $table->softDeletes();
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
        Schema::dropIfExists('hourly_data');
    }
}
