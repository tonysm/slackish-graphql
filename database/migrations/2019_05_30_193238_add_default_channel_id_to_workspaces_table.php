<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultChannelIdToWorkspacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->unsignedBigInteger('default_channel_id')->nullable();

            $table->foreign('default_channel_id')
                ->references('id')->on('channels')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropForeign('workspaces_default_channel_id_foreign');
            $table->dropIndex('workspaces_default_channel_id_foreign');
            $table->dropColumn(['default_channel_id']);
        });
    }
}
