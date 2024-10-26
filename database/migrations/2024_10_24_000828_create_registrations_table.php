<?php

use App\Enums\RegistrationStatuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('contest_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('student_id');
            $table->string('phone');
            $table->string('section')->nullable();
            $table->string('department')->nullable();
            $table->string('lab_teacher_name')->nullable();
            $table->string('tshirt_size')->nullable();
            $table->string('transportation_service')->default('No');
            $table->string('pickup_point')->nullable();
            $table->string('gender');
            $table->enum('status', RegistrationStatuses::toArray())->default(RegistrationStatuses::UNPAID);
            $table->json('extra')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
