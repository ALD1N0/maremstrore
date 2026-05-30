<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('new_email_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('new_email')->unique();
            $table->string('otp', 6);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('new_email_verifications');
    }
};
