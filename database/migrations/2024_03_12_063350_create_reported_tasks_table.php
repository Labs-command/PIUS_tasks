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
        Schema::create(
            'reported_tasks', function (Blueprint $table) {
                $table->uuid("task_id")->primary();
                $table->string("subject", 32);
                $table->text("text")->unique();
                $table->text("answer");
                $table->text("reason_comment")
                    ->nullable();
                $table->uuid("author_id");
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reported_tasks');
    }
};
