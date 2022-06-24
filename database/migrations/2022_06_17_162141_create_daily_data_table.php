<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_data', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->date('date');

            $table->uuid('master_account_id');
            $table->foreign('master_account_id')->references('id')->on('master_accounts')->onDelete('cascade');

            $table->uuid('sub_account_id');
            $table->foreign('sub_account_id')->references('id')->on('sub_accounts')->onDelete('cascade');

            $table->string('cost');
            $table->string('cost_usd')->nullable();
            $table->string('discount')->default(0);
            $table->string('revenue')->default(0);
            $table->string('google_media_cost')->default(0);
            $table->string('plus_m_share')->nullable();
            $table->string('total_cost')->nullable();
            $table->string('net_income')->nullable();
            $table->string('net_income_percent')->nullable();
            $table->string('account_budget')->nullable();
            $table->string('budget_usage_percent')->nullable();
            $table->string('monthly_run_rate')->nullable();

            $table->boolean('is_active')->default(true);

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
        Schema::dropIfExists('daily_data');
    }
}
