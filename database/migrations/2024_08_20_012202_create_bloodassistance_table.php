<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloodassistanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bloodassistance', function (Blueprint $table) {
            $table->id('id'); // Assuming NO. is the primary key
            $table->string('status');
            $table->string('agency');
            $table->string('control_no');
            $table->date('date');
            $table->string('referral');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('ext')->nullable(); // Extension name if any
            $table->integer('age');
            $table->date('birthdate');
            $table->string('municipality');
            $table->string('diagnosis');
            $table->string('hospital');
            $table->string('blood_type');
            $table->integer('qty');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_amount', 10, 2);
            $table->unsignedBigInteger('encodedby'); // Foreign key to the users table
            $table->softDeletes(); // Add soft deletes
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bloodassistance');
    }
}
