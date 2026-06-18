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
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kerja_sama_id')->nullable();
            $table->string('type');
            $table->string('recipient');
            $table->text('message');
            $table->string('status'); // success, failed
            $table->text('response')->nullable();
            $table->timestamps();

            $table->foreign('kerja_sama_id')
                  ->references('id')
                  ->on('kerja_sama')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
