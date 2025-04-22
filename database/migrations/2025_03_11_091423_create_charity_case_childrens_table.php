<?php

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
        Schema::create('charity_case_children', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('age')->nullable();
            $table->string('note')->nullable();
            $table->foreignId('charity_case_id')->constrained('charity_cases')->onDelete('cascade');
            $table->foreignId('education_level_id')->constrained('parameter_values')->onDelete('cascade');
            $table->foreignId('donation_type_id')->constrained('parameter_values')->onDelete('cascade');
            $this->createdUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charity_case_children');
    }
};
