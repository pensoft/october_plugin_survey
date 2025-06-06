<?php namespace Pensoft\Survey\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Mail;
use October\Rain\Support\Facades\Flash;
use Pensoft\Survey\Models\Data;
use Pensoft\Survey\Models\Group;
use Pensoft\Survey\Models\Interest;
use Pensoft\Survey\Models\Category;
use Pensoft\Survey\Models\Data as MailsData;
use Pensoft\Survey\Models\Recipient;
use Multiwebinc\Recaptcha\Validators\RecaptchaValidator;
use RainLab\Location\Models\Country;
use Cms\Classes\Theme;

/**
 * Form Component
 */

class Form extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Form Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'recaptcha_key' => [
                'title' => 'Recaptcha site key',
                'type' => 'string',
                'default' => ''
            ],
            'message_label' => [
                'title' => 'Thank you message',
                'type' => 'string',
                'default' => 'Thank you for contacting us!'
            ],
        ];
    }

    public function onRun() {
        $this->page['countries'] = $this->countries();
        $this->page['interests'] = $this->interests();
        $this->page['categories'] = $this->categories();
        $this->page['groups'] = $this->groups();
        $this->page['themeName'] = Theme::getActiveTheme()->getConfig()['name'];
    }

    public function countries() {
        return Country::orderBy('name')->get();
    }


    public function interests() {
        return Interest::get();
    }

    public function categories() {
        return Category::get();
    }

    public function groups() {
        return Group::get();
    }

    public function onSubmit(){

        $name = \Input::get('name');
        $email = \Input::get('email');
        $affiliation = \Input::get('affiliation');
        $country = \Input::get('country');
        $interest = \Input::get('interest');
        $category = \Input::get('category');
        $group = \Input::get('group');
        $lMessage = \Input::get('message');
        $is_involved = \Input::get('is_involved');
        $project_events = \Input::get('project_events');
        $external_events = \Input::get('external_events');


        $validator = \Validator::make(
            [
                'name' => $name,
                'email' => $email,
                'affiliation' => $affiliation,
                'country' => $country,
                'group' => $group,
                'category' => $category,
                'interest' => $interest,
                'g-recaptcha-response' => \Input::get('g-recaptcha-response'),
            ],
            [
                'name' => 'required|string|min:2',
                'email' => 'required|min:6|email',
                'affiliation' => 'required|string|min:2',
                'country' => 'required',
                'group' => 'required',
                'category' => 'required',
                'interest' => 'required',
                'g-recaptcha-response' => [
                    'required',
                    new RecaptchaValidator,
                ],
            ]
        );



        if($validator->fails()){
            foreach ($validator->messages()->toArray() as $k => $e){
                if(isset($errArray[$k])){
                    Flash::error($errArray[$k]);
                }else{
                    Flash::error($validator->messages()->first());
                }
                return ['scroll_to_field' => $validator->messages()->toArray()];
            }
        }else{
            $recipientsData = Recipient::first()->toArray();
            $recipientsEmails = explode(',', $recipientsData['emails']);
            $recipients = array_unique($recipientsEmails);

            // These variables are available inside the message as Twig
            $vars = [
                'name' => $name,
                'email' => $email,
                'affiliation' => $affiliation,
                'country' => $this->getCountryName($country),
                'interest' => $this->getInterestName($interest),
                'category' => $this->getCategoryName($category),
                'groups' => $this->getGroupName($group),
                'is_involved' => (int)$is_involved ? 'YES' : 'NO',
                'msg' => $lMessage,
                'project_events' => $project_events ? 'I consent to receive invitations and information about ' . Theme::getActiveTheme()->getConfig()['name'] . ' events.' : '',
                'external_events' => $external_events ? 'I consent to receive invitations and information about relevant events from external projects.' : '',
            ];

            // send mail to user submitting the form
            Mail::send('pensoft.survey::mail.autoreply', $vars, function($message) {
                $message->to(\Input::get('email'), \Input::get('name'));
            });

            $replyToMail = \Input::get('email');

            foreach($recipients as $mail){
                Mail::send('pensoft.survey::mail.notification', $vars, function($message)  use ($mail, $replyToMail) {
                    $message->to(trim($mail));
                    $message->replyTo($replyToMail);
                });
            }

            $data = new MailsData();
            $data->name = $name;
            $data->email =  $email;
            $data->country = $this->getCountryName($country);
            $data->affiliation =  $affiliation;
            $data->country_id = (int)$country;
            $data->is_involved = (int)$is_involved;
            $data->message = $lMessage;
//            $data->main_language = $main_language;
//            $data->second_language = $second_language;
            $data->project_events = $project_events;
            $data->external_events = $external_events;
            $data->save();

            $recordID = $data->id;

            foreach ($interest as $item){
                $mailData = Data::find($recordID);
                $mailData->interest()->attach($item);
            }
            foreach ($category as $item){
                $mailData = Data::find($recordID);
                $mailData->category()->attach($item);
            }
            foreach ($group as $item){
                $mailData = Data::find($recordID);
                $mailData->group()->attach($item);
            }

            Flash::success($this->property('message_label'));

        }


    }

    private function getCountryName($id) {
        $country = Country::where('id', $id)->first()->toARray();
        if(count($country)){
            return $country['name'];
        }
        return '';
    }

    private function getInterestName($ids) {
        $interests = Interest::whereIn('id', $ids)->get()->toArray();
        $arr = [];
        if(count($interests)){
            foreach ($interests as $interest){
                $arr[] = $interest['name'];
            }
        }
        return implode(', ', $arr);
    }

    private function getCategoryName($ids) {
        $categories = Category::whereIn('id', $ids)->get()->toArray();
        $arr = [];
        if(count($categories)){
            foreach ($categories as $category){
                $arr[] = $category['name'];
            }
        }
        return implode(', ', $arr);
    }

    private function getGroupName($ids) {
        $groups = Group::whereIn('id', $ids)->get()->toArray();
        $arr = [];
        if(count($groups)){
            foreach ($groups as $group){
                $arr[] = $group['name'];
            }
        }
        return implode(', ', $arr);
    }
}
