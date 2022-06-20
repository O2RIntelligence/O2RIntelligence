<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('account_id')->unique();
            $table->string('developer_token');
            $table->string('discount')->default(0);
            $table->string('revenue_conversion_rate')->nullable();
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
        Schema::dropIfExists('master_accounts');
    }
}
