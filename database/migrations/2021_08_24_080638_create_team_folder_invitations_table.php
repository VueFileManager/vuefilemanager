<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamFolderInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_folder_invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_id');
            $table->uuid('inviter_id');
            $table->text('email');
            $table->string('color')->nullable();
            $table->enum('permission', ['can-edit', 'can-view', 'can-view-and-download']);
            $table->enum('status', ['pending', 'accepted', 'waiting-for-registration', 'rejected'])->default('pending');
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
