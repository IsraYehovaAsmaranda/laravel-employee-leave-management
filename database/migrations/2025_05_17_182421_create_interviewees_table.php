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
        Schema::create('interviewees', function (Blueprint $table) {
            $table->uuid("id")->primary()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('address');
            $table->string('cv');
            $table->json('metadata');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviewees');
    }
};
