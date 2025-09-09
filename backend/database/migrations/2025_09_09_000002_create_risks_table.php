<?php
// database/migrations/2025_09_09_000002_create_risks_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();               // stabilen ključ (npr. "cyberattack")
            $table->string('name');                         // prikazno ime
            $table->string('category')->nullable();         // npr. "kibernetika", "naravne nesreče"
            $table->unsignedBigInteger('mention_count')->default(0); // agregat
            $table->unsignedBigInteger('article_count')->default(0); // kolikim člankom je pripeto
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('risks');
    }
};
