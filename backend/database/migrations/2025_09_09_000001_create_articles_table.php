<?php
// database/migrations/2025_09_09_000001_create_articles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->nullable()->index(); // hash/URL idempotenca
            $table->string('source')->nullable()->index();
            $table->string('title');
            $table->text('content');
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['external_id', 'source']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('articles');
    }
};
