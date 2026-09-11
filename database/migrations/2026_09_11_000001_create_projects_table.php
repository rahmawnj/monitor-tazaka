<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('client_name');
            $table->date('started_at')->nullable();
            $table->date('target_date')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->enum('status', ['running', 'completed', 'on_hold'])->default('running');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
