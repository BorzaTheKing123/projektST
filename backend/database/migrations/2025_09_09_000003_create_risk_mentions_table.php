<?php
// database/migrations/2025_09_09_000003_create_risk_mentions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('risk_mentions', function (Blueprint $table) {
            $table->id();
            $table->string('link');
            $table->foreignId('risk_id')->constrained()->cascadeOnDelete(); //katera kategorija je to
            $table->float('confidence')->default(0); // 0..1 iz LLM
            $table->string('summary'); 
            $table->timestamps();

           // $table->index(['risk_id', 'created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('risk_mentions');
    }
};