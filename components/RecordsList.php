<?php namespace Pensoft\Survey\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Cms\Classes\Theme;
use Pensoft\Survey\Models\Data;
use RainLab\Location\Models\Country;

/**
 * RecordsList Component
 */
class RecordsList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'RecordsList Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    /**
     * Run the component and fetch galleries for the current article.
     */
    public function onRun()
    {
        $this->page['available_countries'] = $this->countries();
    }

    public function countries() {
        $highlightedCountries = Data::lists('country_id');
        return Country::whereIn('id', $highlightedCountries)->get();
    }

    public function onSearchRecords() {
        $searchTerms = post('searchTerms');
        $sortGroup = post('sortGroup');
        $sortCategory = post('sortCategory');
        $sortInterest = post('sortInterest');
        $sortCountry = post('sortCountry');
        $this->page['records'] = $this->searchRecords($searchTerms, $sortGroup, $sortCategory, $sortInterest, $sortCountry);
        $highlightedCountries = Data::lists('country_id');
        $this->page['available_countries'] = Country::whereIn('id', $highlightedCountries)->get();
        return ['#recordsContainer' => $this->renderPartial('stakeholder_records')];
    }

    protected function searchRecords(
        $searchTerms = '',
        $sortGroup = 0,
        $sortCategory = 0,
        $sortInterest = 0,
        $sortCountry = 0
    ) {
        $searchTerms = is_string($searchTerms) ? json_decode($searchTerms, true) : (array)$searchTerms;
        $result = Data::searchTerms($searchTerms);
        if($sortGroup){
            $result->byGroup("{$sortGroup}");
        }
        if($sortCategory){
            $result->byCategory("{$sortCategory}");
        }
        if($sortInterest){
            $result->byInterest("{$sortInterest}");
        }
        if($sortCountry){
            $result->where('country_id', "{$sortCountry}");
            $result->country = Country::find($sortCountry);
        }

        return $result->get();
    }
}
