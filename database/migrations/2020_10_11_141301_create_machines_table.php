<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('parent');
            $table->string('name');
            $table->ipAddress('ip')->unique()->nullable();
            $table->string('token', 32)->unique();
            $table->enum('status', ['online', 'offline', 'rebooting', 'pending-install', 'installing', 'updating', 'unknown', 'error'])->default('pending-install')->index();
            $table->integer('max_servers')->nullable();
            $table->json('stats')->nullable();
            $table->json('specification')->nullable();
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
        Schema::dropIfExists('machines');
    }
}
