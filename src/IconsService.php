<?php

namespace IOPro\LaravelIcons;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class IconsService
{
    const ICONS_PATH = '/heroicons';
    const ICONS_PATH_OUTLINE = '24/outline';
    const ICONS_PATH_SOLID = '24/solid';
    const ICONS_PATH_MINI = '20/solid';
    const ICONS_PATH_MICRO = '16/solid';
    private Filesystem $storage;

    public function __construct()
    {
        $this->storage = Storage::build([
            'driver' => 'local',
            'root' => __DIR__ . self::ICONS_PATH,
        ]);
    }

    public function getIconsNames(): Collection
    {
        $files = $this->storage->files(self::ICONS_PATH_OUTLINE);
        return collect($files)->map(function (string $item) {
            return str(str($item)->explode('/')->last())->before('.');
        });
    }

    public function getContent($file): ?string
    {
        return $this->storage->exists($file) ?
            $this->storage->get($file) :
            null;
    }
}
