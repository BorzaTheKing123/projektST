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
        Schema::create('tveganja', function (Blueprint $table) {
            $table->id();
            $table->string('ime'); // ime tveganja
            $table->unsignedBigInteger('stranka_id'); // FK do stranke
            $table->text('ukrepi'); // opis ukrepov
            $table->timestamps();

            // Tu dodamo tuji ključ
            $table->foreign('stranka_id')->references('id')->on('stranke')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tveganja');
    }
};
