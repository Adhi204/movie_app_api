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
        Schema::create('favourites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->references('id')->on('users')
                ->constrained()
                ->onDelete('cascade');

            $table->string('title', 50);
            $table->string('year', 10);
            $table->text('description')->nullable();
            $table->string('poster', 1024)->nullable();
            $table->integer('like_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourites');
    }
};
