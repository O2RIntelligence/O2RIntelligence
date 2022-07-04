<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('master_account_id');
            $table->foreign('master_account_id')->references('id')->on('master_accounts')->onDelete('cascade');

            $table->string('name');
            $table->string('account_id')->unique();
            $table->string('timezone')->nullable();
            $table->string('currency_code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_online')->default(true);

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
        Schema::dropIfExists('sub_accounts');
    }
}
