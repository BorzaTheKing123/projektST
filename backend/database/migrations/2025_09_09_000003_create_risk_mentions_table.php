<?php
// database/migrations/2025_09_09_000003_create_risk_mentions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('risk_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('risk_id')->constrained()->cascadeOnDelete();
            $table->float('confidence')->default(0); // 0..1 iz LLM
            $table->json('spans')->nullable();       // opcijsko: indeks odstavkov, lokacije v besedilu
            $table->timestamps();

            $table->unique(['article_id', 'risk_id']); // ena povezava na članek na tveganje
            $table->index(['risk_id', 'created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('risk_mentions');
    }
};