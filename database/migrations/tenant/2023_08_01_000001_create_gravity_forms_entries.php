<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gravity_form_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gravity_form_id')->constrained('gravity_forms');
            $table->json('fields')->nullable();
            $table->json('entry')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gravity_form_entries');
    }
};

