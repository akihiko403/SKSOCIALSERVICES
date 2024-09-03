<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranspoassistanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transpoassistance', function (Blueprint $table) {
            $table->id();
            $table->string('status', 50);
            $table->date('referral_date');
            $table->string('referral', 255);
            $table->string('lastname', 100);
            $table->string('firstname', 100);
            $table->string('middlename', 100)->nullable();
            $table->string('ext', 10)->nullable();
            $table->string('municipality', 100);
            $table->integer('age');
            $table->text('diagnosis_cause_of_death')->nullable();
            $table->string('pick_up_point', 255);
            $table->string('drop_point', 255);
            $table->string('unit', 100);
            $table->string('name_of_driver', 100);
            $table->text('remarks')->nullable();
            $table->string('encodedby'); 
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transpoassistance');
    }
}
