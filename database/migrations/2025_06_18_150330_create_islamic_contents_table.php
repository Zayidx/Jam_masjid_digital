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
        Schema::create('islamic_contents', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['quran', 'hadith']); // Tipe konten
            $table->text('text_ar')->nullable(); // Teks Arab
            $table->text('text_id'); // Teks terjemahan/isi
            $table->string('source'); // Sumber (e.g., "Q.S. Al-Baqarah: 183", "H.R. Bukhari")
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('islamic_contents');
    }
};
