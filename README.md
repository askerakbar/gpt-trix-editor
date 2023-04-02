# GPT Trix Editor 🪄 

GPT Trix Editor package is a field component that extends the default Trix editor with OpenAI GPT features in the Filament PHP framework.

![check-grammar-demo](demo/check-grammar.gif)

## Installation

You can install the package via composer:

```bash
composer require askerakbar/gpt-trix-editor
```

## Quick Start

1. Publish the configuration files
```bash
php artisan openai-php/laravel:publish --tag=config
php artisan vendor:publish --tag="gpt-trix-editor-config"
php artisan vendor:publish --tag="gpt-trix-editor-translations"
```

2. We're using https://github.com/openai-php/laravel laravel package to call the OpenAI APIs, once you publish the all configuration files above, please set the OpenAPI key on config/openapi.php
3. Optionally you can customize some features on ```config/gpt-trix-editor.php```, including adding more prompts in the menu dropdown.
4. Make sure to clear the config cache once you make the changes, using php ```php artisan config:clear ```
5. Done!

# Usage

Import the field component: 
```
use AskerAkbar\GptTrixEditor\Components\GptTrixEditor;
```

You can use the GPT Trix Editor field component like any other field component:

```
GptTrixEditor::make('content')->columnSpan('full');
```

By default, the GPT Toolbar Button is enabled in the GP Trix Editor. However, if you want to disable it, you can do so like this:

```
GptTrixEditor::make('content')->disableToolbarButtons([
    'gptTools',
])->columnSpan('full');
```

## To do 
- [ ] Run the GPT actions on selected/highlighted text 

## Contribute / Report a bug / Security Vulnerabilities 🐞
If you would like to contriubte, please feel free to submit pull requests or open issues.

## License 📝

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
