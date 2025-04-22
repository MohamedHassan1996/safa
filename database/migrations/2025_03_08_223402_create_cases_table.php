<?php

use App\Enums\Charity\CharityCaseGender;
use App\Enums\Charity\HousingType;
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
            $table->string('pair_name')->nullable();
            $table->string('pair_national_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->boolean('gender')->default(CharityCaseGender::MALE->value);
            $table->boolean('housing_type')->default(HousingType::OWN->value);
            $table->tinyInteger('number_of_children')->defaul(0);
            $table->unsignedBigInteger('social_status_id')->nullable()->index();
            $table->foreign('social_status_id')->references('id')->on('parameter_values')->onUpdate('cascade');
            $table->unsignedBigInteger('area_id')->nullable()->index();
            $table->foreign('area_id')->references('id')->on('parameter_values')->onUpdate('cascade');
            $table->unsignedBigInteger('donation_priority_id')->nullable()->index();
            $table->foreign('donation_priority_id')->references('id')->on('parameter_values')->onUpdate('cascade');
            $table->unsignedBigInteger('charity_id')->nullable()->index();
            $table->foreign('charity_id')->references('id')->on('charities')->onUpdate('cascade');
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
