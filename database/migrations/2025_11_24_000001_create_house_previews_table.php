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
        Schema::create('house_previews', function (Blueprint $table) {
            $table->id();
            
           
            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->onDelete('cascade');
            
            $table->text('colors')->nullable();
            $table->string('png_image');
            $table->string('svg_image')->nullable();
            $table->text('customer_message')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])
                  ->default('pending');
            $table->foreignId('processed_by')
                  ->nullable()
                  ->constrained('users')->onDelete('set null');
            
            $table->timestamp('processed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            $table->index('customer_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_previews');
    }
};
