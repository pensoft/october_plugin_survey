<?php namespace Pensoft\Survey\Models;

use Model;
use RainLab\Location\Models\Country;

/**
 * Data Model
 */
class Data extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table associated with the model
     */
    public $table = 'pensoft_survey_data';

    /**
     * @var array guarded attributes aren't mass assignable
     */
    protected $guarded = ['*'];

    /**
     * @var array fillable attributes are mass assignable
     */
    protected $fillable = [];

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array jsonable attribute names that are json encoded and decoded from the database
     */
//    protected $jsonable = ['interest'];

    /**
     * @var array appends attributes to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array hidden attributes removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array dates attributes that should be mutated to dates
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public $belongsTo = [
        'country' => ['RainLab\Location\Models\Country'],
    ];

    public $belongsToMany = [
        'interest' => [
            'Pensoft\Survey\Models\Interest',
            'table' => 'pensoft_survey_data_interest',
            'order' => 'name'
        ],
        'category' => [
            'Pensoft\Survey\Models\Category',
            'table' => 'pensoft_survey_data_categories',
            'order' => 'name',
        ],
        'group' => [
            'Pensoft\Survey\Models\Group',
            'table' => 'pensoft_survey_data_groups',
            'order' => 'name',
        ],
    ];

    /**
     * @var array hasOne and other relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function scopeSearchTerms($query, $searchTerms)
    {
        if (!empty($searchTerms) && is_array($searchTerms)) {
            foreach ($searchTerms as $term) {
                $query->orWhere('name', 'ILIKE', "%{$term}%");
                $query->orWhere('affiliation', 'ILIKE', "%{$term}%");
                $query->orWhere('email', 'ILIKE', "%{$term}%");
            }
        }
        return $query;
    }

//    public function countries() {
//        return Country::orderBy('name')->get();
//    }
//
//
//    public function interests() {
//        return Interest::get();
//    }
//
//    public function categories() {
//        return Category::get();
//    }
//
//    public function groups() {
//        return Group::get();
//    }

//    function groups() {
//        return $this->belongsToMany('Pensoft\Survey\Models\Group');
//    }
//
//    function categories() {
//        return $this->belongsToMany('Pensoft\Survey\Models\Category');
//    }
//
//    function interests() {
//        return $this->belongsToMany('Pensoft\Survey\Models\Interest');
//    }
//
//    function countries(){
//        return $this->belongsTo('RainLab\Location\Models\Country');
//    }


    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('category', function ($query) use ($categoryId) {
            $query->where('category_id', (int)$categoryId);
        });
    }

    public function scopeByGroup($query, $groupId)
    {
        return $query->whereHas('group', function ($query) use ($groupId) {
            $query->where('group_id', (int)$groupId);
        });
    }

    public function scopeByInterest($query, $interestId)
    {
        return $query->whereHas('interest', function ($query) use ($interestId) {
            $query->where('interest_id', (int)$interestId);
        });
    }

    public function scopeByCountry($query, $countryId)
    {
        return $query->whereHas('country', function ($query) use ($countryId) {
            $query->where('country_id', (int)$countryId);
        });
    }
}
