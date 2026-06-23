<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->unique()->after('email');
            $table->string('phone')->nullable()->after('nik');
            $table->text('address')->nullable()->after('phone');
            $table->foreignId('village_id')->nullable()->after('address')->constrained('villages')->nullOnDelete();
            $table->string('photo')->nullable()->after('village_id');
            $table->string('status')->default('Aktif')->after('photo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
            $table->dropColumn(['nik', 'phone', 'address', 'village_id', 'photo', 'status']);
        });
    }
};
