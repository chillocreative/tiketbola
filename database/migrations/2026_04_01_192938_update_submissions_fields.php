<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('ic_number')->after('name');
            $table->text('address')->after('phone');
            $table->dropColumn(['email', 'message']);
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('email')->after('phone');
            $table->text('message')->after('email');
            $table->dropColumn(['ic_number', 'address']);
        });
    }
};
