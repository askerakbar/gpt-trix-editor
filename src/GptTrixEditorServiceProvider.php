<?php
 
 namespace AskerAkbar\GptTrixEditor;

 
use Spatie\LaravelPackageTools\Package;
use Filament\PluginServiceProvider;

class GptTrixEditorServiceProvider extends PluginServiceProvider
{
   
    protected array $beforeCoreScripts = [
        'gpt-trix-editor' => __DIR__ . '/../dist/gpt-trix-editor.js',
        //'gpt-trix-editor' => __DIR__ . '/../resources/js/gpt-trix-editor.js',
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