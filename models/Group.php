<?php namespace Pensoft\Survey\Models;

use Model;

/**
 * Model
 */
class Group extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_survey_groups';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
