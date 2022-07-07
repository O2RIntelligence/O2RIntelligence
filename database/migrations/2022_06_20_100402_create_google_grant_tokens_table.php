<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleGrantTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_grant_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('refresh_token')->nullable();
            $table->string('access_token')->nullable();
            $table->string('expires_in')->nullable();

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
        Schema::dropIfExists('google_grant_tokens');
    }
}
