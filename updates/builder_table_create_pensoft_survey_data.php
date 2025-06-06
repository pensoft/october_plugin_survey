<?php namespace Pensoft\Survey\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftSurveyData extends Migration
{
    public function up()
    {
        Schema::create('pensoft_survey_data', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('affiliation')->nullable();
            $table->integer('country_id');
            $table->string('country')->nullable();
            $table->string('main_language')->nullable();
            $table->string('second_language')->nullable();
            $table->integer('interest_id')->nullable();
            $table->text('interest')->nullable();
            $table->smallInteger('project_events')->nullable();
            $table->smallInteger('external_events')->nullable();
            $table->integer('group_id')->nullable();
            $table->smallInteger('is_involved')->default(1);
            $table->text('message')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_survey_data');
    }
}
