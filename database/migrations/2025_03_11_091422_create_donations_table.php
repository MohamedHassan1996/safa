<?php

use App\Enums\Donation\DonationType;
use App\Traits\CreatedUpdatedByMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    use CreatedUpdatedByMigration;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->timestamp('date');
            $table->string('type')->default(DonationType::CASH->value);
            $table->string('amount')->nullable();
            $table->string('note')->nullable();
            $table->foreignId('charity_case_id')->nullable()->constrained()->nullOnDelete();
            //$this->createdUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
