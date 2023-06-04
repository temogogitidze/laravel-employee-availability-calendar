<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_holidays', function (Blueprint $table) {
            $table->id();
            $table->string('holiday_id');
            $table->string('name');
            $table->string('country');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('public_holidays');
    }
};
