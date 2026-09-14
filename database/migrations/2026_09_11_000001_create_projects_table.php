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
            $table->string('client');
            $table->enum('project_type', [
                'tazaka_order',
                'subcontract',
                'external',
            ]);
            $table->unsignedTinyInteger('progress')->default(0);
            $table->date('target_completion_date')->nullable();
            $table->date('project_month')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('location')->nullable();
            $table->json('latlong')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
