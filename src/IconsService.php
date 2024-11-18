<?php
namespace IOPro\LaravelIcons;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

final class IconsService
{

    private Filesystem $hero_icons_storage;
    private Filesystem $custom_icons_storage;

    public function __construct()
    {
        $this->hero_icons_storage = Storage::build([
            'driver' => 'local',
            'root' => __DIR__ . Icon::ICONS_PATH,
        ]);
        $this->custom_icons_storage = Storage::build([
            'driver' => 'local',
            'root' => base_path('resources/views/' . config('laravel-icons.custom_path')),
        ]);
    }

    public function hero(): Filesystem
    {
        return $this->hero_icons_storage;
    }

    public function custom(): Filesystem
    {
        return $this->custom_icons_storage;
    }

    public function getAllIconsNames(): Collection
    {
        $heroIcons = collect($this->hero_icons_storage->files(Icon::ICONS_PATH_OUTLINE))
            ->map(function (string $item) {
                return str($item)->afterLast('/')->before('.')->toString();
            });

        $customIcons = collect($this->custom_icons_storage->files())
            ->map(function (string $item) {
                return str($item)->before('.')->toString();
            });

        return $heroIcons->merge($customIcons);
    }

}
