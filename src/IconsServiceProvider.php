<?php

namespace IOPro\LaravelIcons;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class IconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        foreach ((new IconsService())->getIconsNames() as $icon) {
            Blade::component('icons.' . $icon, Icon::class);
        }
    }
}
