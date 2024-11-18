<?php
namespace IOPro\LaravelIcons;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class IconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-icons.php' => config_path('laravel-icons.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-icons.php', 'laravel-icons'
        );

        $this->app->singleton('laravel-icons', function ($app) {
            return new IconsService();
        });
    }

    public function boot(): void
    {
        foreach (app('laravel-icons')->getAllIconsNames() as $icon) {
            Blade::component('icons.' . $icon, Icon::class);
        }
    }
}
