<?php
 
 namespace AskerAkbar\GptTrixEditor;

 
use Spatie\LaravelPackageTools\Package;
use Filament\PluginServiceProvider;

class GptTrixEditorServiceProvider extends PluginServiceProvider
{
   
    protected array $scripts = [
        'gpt-trix-editor' => __DIR__ . '/../dist/gpt-trix-editor.js',
    ];
 
    public function configurePackage(Package $package): void
    {   
        $package
        ->name('gpt-trix-editor')
        ->hasConfigFile()
        ->hasViews()
        ->hasTranslations();
    }

    
    public function boot()
    {
        parent::boot();
    }


}