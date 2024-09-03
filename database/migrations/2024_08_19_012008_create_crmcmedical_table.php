<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('CRMCMEDICAL', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->date('date');
            $table->string('referral');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('ext')->nullable();
            $table->string('diagnosis');
            $table->integer('age');
            $table->date('birthdate');
            $table->string('municipality');
            $table->string('province');
            $table->string('patient_status');
            $table->string('transaction_status');
            $table->decimal('amount', 10, 2);
            $table->string('encodedby'); // Add the encodedby field
            $table->softDeletes(); // Ensure this is included
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('CRMCMEDICAL');
    }
};
