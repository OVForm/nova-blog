<?php

namespace Den1n\NovaBlog;

use App\Nova\User;
use Laravel\Nova\Nova;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\Menu;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Menu\MenuItem;

class Tool extends \Laravel\Nova\Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     */
    public function boot(): void
    {
        Nova::script('nova-blog', __DIR__ . '/../dist/nova.js');

        $models = config('nova-blog.models');
        $resources = config('nova-blog.resources');

        foreach ($resources as $name => $class) {
            if ($name !== 'user') {
                $class::$model = $models[$name];
                Nova::resources([$class]);
            }
        }
    }


    public function menu(Request $request)
    {

        $resources = config('nova-blog.resources');

        return MenuSection::make('Blog', [
            MenuItem::resource($resources['post']),
            MenuItem::resource($resources['category']),
            MenuItem::resource($resources['tag']),
        ])->icon('user')->collapsable();

    }

    /**
     * Build the view that renders the navigation links for the tool.
     */
    public function renderNavigation()
    {
        $resources = config('nova-blog.resources');

        return view('nova-blog::navigation', [
            'postUriKey' => $resources['post']::uriKey(),
            'commentUriKey' => $resources['comment']::uriKey(),
            'categoryUriKey' => $resources['category']::uriKey(),
            'tagUriKey' => $resources['tag']::uriKey(),
        ]);
    }
}
