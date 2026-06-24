<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->references('id')->on('countries');

            $table->string('state')->nullable();
            $table->longText('ovpn');
            $table->boolean('ispro')->default(false);
            $table->string('ip_address');
            $table->string('username');
            $table->string('password');
            $table->double('priority')->nullable();
            $table->double('active_connection')->nullable();
            $table->double('max_connection')->nullable();
            $table->string('source')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('switch')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
