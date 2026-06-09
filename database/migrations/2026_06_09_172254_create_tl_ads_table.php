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





        Schema::create('tl_ads', function (Blueprint $table) {
            $table->id();

            $table->string('image')->nullable();

            $table->foreignId('seller_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('shop_id')
                ->nullable()
                ->constrained('seller_shops')
                ->nullOnDelete();

            $table->integer('sort_order')->default(0)->index();

            $table->tinyInteger('status')->default(1)->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tl_ads');
    }
};
