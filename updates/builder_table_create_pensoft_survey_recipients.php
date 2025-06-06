<?php namespace Pensoft\Survey\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftSurveyRecipients extends Migration
{
    public function up()
    {
        Schema::create('pensoft_survey_recipients', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('name');
            $table->text('emails')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_survey_recipients');
    }
}
