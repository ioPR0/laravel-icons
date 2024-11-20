<?php

namespace IOPro\LaravelIcons;

use Closure;
use Illuminate\View\Component;

class Icon extends Component
{
    const SQUARE = '<rect x="1" y="1" width="100%" height="100%"/>';
    const ICONS_PATH = '/heroicons';
    const ICONS_PATH_OUTLINE = '24/outline';
    const ICONS_PATH_SOLID = '24/solid';
    const ICONS_PATH_MINI = '20/solid';
    const ICONS_PATH_MICRO = '16/solid';
    public ?bool $solid;
    public ?bool $mini;
    public ?bool $micro;

    private IconsService $service;
    private array $attr = [
        'width' => '24',
        'height' => '24',
        'class' => 'h-6 w-6',
    ];

    public function __construct(
        $solid = null,
        $mini = null,
        $micro = null,
    )
    {
        $this->service = app('laravel-icons');

        $this->solid = $solid;
        $this->mini = $mini;
        $this->micro = $micro;

        if ($mini) {
            $this->attr['width'] = '20';
            $this->attr['height'] = '20';
            $this->attr['class'] = 'h-5 w-5';
        } elseif ($micro) {
            $this->attr['width'] = '16';
            $this->attr['height'] = '16';
            $this->attr['class'] = 'h-4 w-4';
        }
    }

    protected function setViewBox(?string $content): void
    {
        $viewBox = str($content)->match('/viewBox="([\s\d]*)"/iu')->toString();
        if ($viewBox) {
            $this->attr['viewBox'] = $viewBox;
        }
    }

    protected function setFill(?string $content): void
    {
        $fill = str($content)->match('/fill="(\w*)"/iu')->toString();
        if ($fill) {
            $this->attr['fill'] = $fill;
        }
    }

    protected function setStroke(?string $content): void
    {
        $stroke = str($content)->match('/stroke="(\w*)"/iu')->toString();
        if ($stroke) {
            $this->attr['stroke'] = $stroke;
        }
        $stroke_width = str($content)->match('/stroke-width="([.\d]*)"/iu')->toString();
        if ($stroke_width) {
            $this->attr['stroke-width'] = $stroke_width;
        }
    }

    protected function createSvgPath(): string
    {
        $file = $this->getSvgFullFileName();

        $content = $this->getContentFromFile($file);
        if (!$content) {
            $content = self::SQUARE;
            $this->attr['fill'] = 'currentColor';
        }

        $this->setViewBox($content);
        $this->setFill($content);
        $this->setStroke($content);

        return $this->extractContent($content);
    }

    public function extractContent(string $content): string
    {
        return str($content)->replaceMatches(
            [
                '/(\s*<\/?svg.*>\s*)/iu',
                '/(fill="[^\s]*")/iu',
                '/(stroke="[^\s]*")/iu',
            ],
            [
                '',
                'fill="currentColor"',
                'stroke="currentColor"',
            ])
            ->toString();
    }

    public function getContentFromFile($file): ?string
    {
        $result = $this->service->hero()->exists($file) ? $this->service->hero()->get($file) : null;
        if (!$result) {
            $file = str($file)->afterLast('/')->toString();
            $result = $this->service->custom()->exists($file) ? $this->service->custom()->get($file) : null;
        }
        if (!$result) {
            $file = str($file)->replace('svg', 'blade.php')->toString();
            $result = $this->service->custom()->exists($file) ? $this->service->custom()->get($file) : null;
        }

        return $result;
    }

    protected function getSvgFullFileName(): string
    {
        $path = self::ICONS_PATH_OUTLINE;
        if ($this->solid) {
            $path = self::ICONS_PATH_SOLID;
        } elseif ($this->mini) {
            $path = self::ICONS_PATH_MINI;
        } elseif ($this->micro) {
            $path = self::ICONS_PATH_MICRO;
        }
        return $path . '/' . str($this->componentName)->after('.') . '.svg';
    }

    public function render(): Closure
    {
        $content = $this->createSvgPath();

        return function (array $data) use ($content) {
            return str('<svg xmlns="http://www.w3.org/2000/svg" ')
                ->append($data['attributes']->merge([
                    ...$this->attr,
                    'class' => $data['attributes']->has('class')
                        ? $data['attributes']->get('class')
                        : $this->attr['class'],
                ]))
                ->append('>')
                ->append($content)
                ->append('</svg>')
                ->toString();
        };
    }
}
