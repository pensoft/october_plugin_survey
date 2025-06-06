<?php namespace Pensoft\Survey;

use Backend;
use Pensoft\Accordions\Controllers\Accordions;
use Pensoft\Accordions\Models\Category;
use System\Classes\PluginBase;

/**
 * Survey Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Get Involved',
            'description' => 'No description provided yet...',
            'author'      => 'Pensoft',
            'icon'        => 'icon-external-link-square'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot()
    {
        \Event::listen('backend.menu.extendItems', function($navigationManager) {
            $user = \BackendAuth::getUser(); // get the logged in user
            if(!$user->is_superuser){
                $navigationManager->removeMainMenuItem('October.System', 'system');
                $navigationManager->removeMainMenuItem('Pensoft.Cardprofiles', 'profile-cards');
                $navigationManager->removeMainMenuItem('Pensoft.Calendar', 'main-menu-item');
                $navigationManager->removeMainMenuItem('Pensoft.Accordions', 'main-menu-item');
                $navigationManager->removeMainMenuItem('Pensoft.Partners', 'main-menu-item');
                $navigationManager->removeMainMenuItem('Pensoft.Media', 'media-center');
                $navigationManager->removeMainMenuItem('Pensoft.Articles', 'main-menu-item');
                $navigationManager->removeMainMenuItem('Pensoft.Library', 'main-menu-item');
                $navigationManager->removeMainMenuItem('Pensoft.Jumbotron', 'main-menu-item');
            }
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Pensoft\Survey\Components\Form' => 'survey_form',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {

        return [
            'pensoft.survey.all' => [
                'tab' => 'Survey',
                'label' => 'Permission Survey'
            ],
        ];
    }
}
