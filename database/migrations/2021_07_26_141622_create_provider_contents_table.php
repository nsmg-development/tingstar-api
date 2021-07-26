<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->string('title');
            $table->text('description');
            $table->string('service_id')->unique();
            $table->string('service_bi_url')->nullable();
            $table->string('service_bi_bg')->nullable();
            $table->string('service_default_url')->nullable();
            $table->boolean('auto_approve_clients')->nullable()->default(1)->comment('클라이언트 자동승인');
            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('providers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_contents');
    }
}
