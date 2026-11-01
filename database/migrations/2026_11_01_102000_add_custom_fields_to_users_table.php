<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->unique()->after('name')->comment('NIP atau Username Admin');
            $table->string('email')->nullable()->change();
            $table->boolean('must_change_password')->default(false)->after('password');
            $table->boolean('is_active')->default(true)->after('must_change_password');
            $table->string('device_id')->nullable()->after('is_active')->comment('ID Perangkat Terikat');
            $table->text('device_info')->nullable()->after('device_id');
            $table->string('phone_number', 20)->nullable()->after('email');
            $table->timestamp('last_login_at')->nullable()->after('updated_at');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'must_change_password',
                'is_active',
                'device_id',
                'device_info',
                'phone_number',
                'last_login_at',
                'last_login_ip'
            ]);
        });
    }
};
