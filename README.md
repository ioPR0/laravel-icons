# Heroicons for Laravel

Easily use <a href="https://heroicons.com/" target="_blank">Heroicons</a> in Laravel projects.

## Requirements

- Laravel
- Tailwind CSS

## Installation

You can install the package via Composer:

```bash
composer require iopro/laravel-icons
```

## Usage

```blade
<x-icons-icon_name />
```

By default, the `outline` icons are used. If you want to use another type (`solid`, `mini`, `micro`), add needed attribute to the component:

```blade
<x-icons-icon_name solid />
<x-icons-icon_name mini />
<x-icons-icon_name micro />
```

You can also customize other icon attributes. For example:

```blade
<x-icons-icon_name 
    stroke-width="1.5" 
    fill="none" 
    stroke="currentColor" 
    class="w-12 h-12" 
/>
```

## Credits
- [ioPRO](https://iopro.ru/)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

Thank you [Vojislav](https://vojislavd.com/)
The [vojislavd/heroicons-laravel](https://github.com/VojislavD/heroicons-laravel) package was the basis of my work.
