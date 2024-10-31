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
        Schema::create('transactions', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $t->date('date');
            $t->integer('amount');
            $t->string('note')->nullable();
            $t->string('image')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
