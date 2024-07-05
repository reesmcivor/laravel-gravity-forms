<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gravity_form_entries', function (Blueprint $table) {
            $table->string('external_id')->nullable()->after('entry');
        });
    }

    public function down(): void
    {
        Schema::table('gravity_form_entries', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });
    }
};

