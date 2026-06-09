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
        Schema::create('ad_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ad_id')
                ->constrained('tl_ads')
                ->cascadeOnDelete();

            $table->string('lang', 10)->index();

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['ad_id', 'lang']);
            $table->id();

            $table->foreignId('ad_id')
                ->constrained('tl_ads')
                ->cascadeOnDelete();

            $table->string('lang', 10)->index();

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['ad_id', 'lang']);
            $table->id();

            $table->foreignId('ad_id')
                ->constrained('tl_ads')
                ->cascadeOnDelete();

            $table->string('lang', 10)->index();

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['ad_id', 'lang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_translations');
    }
};
