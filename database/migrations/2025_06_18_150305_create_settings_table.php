<?php

// File: database/migrations/xxxx_xx_xx_xxxxxx_create_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // 'key' untuk nama pengaturan, cth: 'mosque_name', 'latitude', 'iqamah_subuh'
            $table->string('key')->unique(); 
            // 'value' untuk menyimpan nilai dari pengaturan tersebut
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};