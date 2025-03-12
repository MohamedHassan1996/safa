<?php

use App\Enums\Charity\CharityCaseGender;
use App\Enums\Charity\CharityCaseSocialStatus;
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
        Schema::create('charity_cases', function (Blueprint $table) {
            $table->id();
            $table->string('national_id')->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->boolean('gender')->default(CharityCaseGender::MALE->value);
            $table->tinyInteger('social_status')->default(CharityCaseSocialStatus::SINGLE->value);
            $table->date('date_of_birth')->nullable();
            $table->string('note')->nullable();
            $this->createdUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charity_cases');
    }
};
