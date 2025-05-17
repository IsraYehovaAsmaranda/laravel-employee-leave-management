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
        Schema::create('interviewee_tasks', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('interviewee_id')->references('id')->on('interviewees')->constrained()->cascadeOnDelete();
            $table->uuid('task_id')->references('id')->on('interviewees')->constrained()->cascadeOnDelete();
            $table->decimal("score", 5, 2)->nullable();
            $table->string("attachment");
            $table->text("comment")->nullable();
            $table->boolean("is_graded")->default(false);
            $table->json("detail")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviewee_tasks');
    }
};
