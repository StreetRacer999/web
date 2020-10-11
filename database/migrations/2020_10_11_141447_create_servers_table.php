<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->integer('msid')->index();
            $table->string('name');
            $table->integer('port');
            $table->integer('players')->default(0);
            $table->integer('slots');
            $table->enum('status', ['running', 'starting', 'stopping', 'restarting', 'stopped', 'updating', 'initializing', 'error', 'unknown'])->default('initializing')->index();
            $table->decimal('version', 8, 3)->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
