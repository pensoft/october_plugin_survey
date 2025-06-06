<?php namespace Pensoft\Survey\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftSurveyDataInterest extends Migration
{
    public function up()
    {
        Schema::create('pensoft_survey_data_interest', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('interest_id');
            $table->integer('data_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_survey_data_interest');
    }
}
