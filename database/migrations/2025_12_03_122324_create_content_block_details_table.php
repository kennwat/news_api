<?php

use App\Models\ContentBlock;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_block_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ContentBlock::class)
                ->constrained()
                ->onDelete('cascade');
            $table->json('text_content')->nullable();
            $table->string('image_path')->nullable();
            $table->json('image_alt_text')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['content_block_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_block_details');
    }
};
