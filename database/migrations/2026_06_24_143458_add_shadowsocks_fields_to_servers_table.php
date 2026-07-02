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
        Schema::table('servers', function (Blueprint $table) {
            $table->string('ss_password')->nullable()->after('password');
            $table->unsignedSmallInteger('ss_port')->default(8388)->after('ss_password');
            $table->string('ss_method')->default('chacha20-ietf-poly1305')->after('ss_port');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn(['ss_password', 'ss_port', 'ss_method']);
        });
    }
};
