<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblEmploye extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employe', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();  
            $table->foreign('company_id')
                        ->references('id')->on('tbl_company')
                        ->onDelete('cascade')->nullable();  
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email_address')->unique()->nullable();
            $table->string('position')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->enum('status',['0','1'])->comment='0=Active,1=Inactive';
            $table->softDeletes();
            
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
        Schema::dropIfExists('tbl_employe');
    }
}
