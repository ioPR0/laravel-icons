<?php

namespace IOPro\LaravelIcons;

use Closure;
use Illuminate\View\Component;

class Icon extends Component
{
    const string SQUARE = '<rect x="1" y="1" width="100%" height="100%"/>';
    public ?bool $solid;
    public ?bool $mini;
    public ?bool $micro;
    private array $attr = [
        'fill' => 'none',
        'viewBox' => '0 0 24 24',
        'stroke-width' => '1.5',
        'stroke' => 'currentColor',
        'width' => '24',
        'height' => '24',
        'class' => 'h-6 w-6',
    ];

    public function __construct(
        $solid = null,
        $mini = null,
        $micro = null
    )
    {
        $this->solid = $solid;
        $this->mini = $mini;
        $this->micro = $micro;

        if ($mini) {
            $this->attr['width'] = '20';
            $this->attr['height'] = '20';
            $this->attr['viewBox'] = '0 0 20 20';
            $this->attr['class'] = 'h-5 w-5';
        } elseif ($micro) {
            $this->attr['width'] = '16';
            $this->attr['height'] = '16';
            $this->attr['viewBox'] = '0 0 16 16';
            $this->attr['class'] = 'h-4 w-4';
        }
    }

    protected function createPathSvg(): string
    {
        $file = $this->getSvgFile();
        $content = (new IconsService())->getContent($file);
        if ($content) {
            return $content;
        }

        $this->attr['fill'] = 'currentColor';
        return self::SQUARE;
    }

    protected function getSvgFile(): string
    {
        $path = IconsService::ICONS_PATH_OUTLINE;
        if ($this->solid) {
            $path = IconsService::ICONS_PATH_SOLID;
        } elseif ($this->mini) {
            $path = IconsService::ICONS_PATH_MINI;
        } elseif ($this->micro) {
            $path = IconsService::ICONS_PATH_MICRO;
        }
        return $path . '/' . str($this->componentName)->after('.') . '.svg';
    }

    public function render(): Closure
    {
        $content = preg_replace(['/(\s*<[\/]?svg.*>\s*)/iu', '/([fill|stroke]+=+"[^\s]*")/iu'], '', $this->createPathSvg());
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
