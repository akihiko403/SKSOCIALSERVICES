<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpmcmedicalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spmcmedical', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->date('date');
            $table->string('referral')->nullable();
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('ext')->nullable();
            $table->string('diagnosis')->nullable();
            $table->integer('age');
            $table->date('birthdate');
            $table->string('municipality');
            $table->string('province');
            $table->string('patient_status')->nullable();
            $table->string('transaction_status')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('encodedby'); // Add the encodedby field
            $table->softDeletes(); // This enables soft deletes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spmcmedical');
    }
}
