<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('semester');
            $table->string('description')->nullable();
            $table->integer('registration_fee');
            $table->dateTime('registration_deadline');
            $table->string('countdown_text')->nullable();
            $table->dateTime('countdown_time')->nullable();
            $table->json('departments');
            $table->json('sections');
            $table->json('lab_teacher_names');
            $table->json('manual_payment_methods')->nullable();
            $table->string('student_id_rules')->default('regex:/^232[0-9-]*$/')->nullable();
            $table->string('student_id_rules_guide')->default('Student Id Must start with 232 and can only contain numbers and dashes')->nullable();
            $table->json('pickup_points')->nullable();
            $table->json('dates');
            $table->json('room_data')->nullable();
            $table->json('extra')->nullable();
            $table->integer('registration_limit')->nullable();
            $table->boolean('public')->default('false');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contests');
    }
};
