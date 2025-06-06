<?php namespace Pensoft\Survey\Models;

use Model;
use October\Rain\Database\Traits\Sortable;

/**
 * Model
 */
class Interest extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    use Sortable;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_survey_interest';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
