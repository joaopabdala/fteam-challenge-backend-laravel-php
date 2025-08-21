<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique();

            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('price');

            $table->foreignId('category_id')->constrained('categories');

            $table->string('image')->nullable();

            $table->decimal('rating_rate', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);


            $table->index('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
