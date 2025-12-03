<?php

use App\Models\News;
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
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(News::class)
                ->constrained()
                ->onDelete('cascade');
            $table->string('type'); // BlockTypeEnum
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['news_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
