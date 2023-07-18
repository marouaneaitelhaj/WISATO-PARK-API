<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('userclients', function (Blueprint $table) {
            $table->string('username')->nullable();
            $table->string('gender')->nullable();
            $table->string('city')->nullable();
            $table->string('cin')->nullable();
            $table->string('Phone')->nullable();
            $table->string('image')->nullable();
        });
    }

    public function down()
    {
        Schema::table('userclients', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('gender');
            $table->dropColumn('city');
            $table->dropColumn('cin');
            $table->dropColumn('Phone');
            $table->dropColumn('image');
        });
    }
};
