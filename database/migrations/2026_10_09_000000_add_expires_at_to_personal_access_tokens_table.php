<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Laravel Sanctum 3+ stores the token expiration date in the "expires_at" column.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('personal_access_tokens', 'expires_at')) {
            return;
        }

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('last_used_at');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('personal_access_tokens', 'expires_at')) {
            return;
        }

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
};
