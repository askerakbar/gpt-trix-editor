<?php

namespace AskerAkbar\GptTrixEditor\Components;

use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Str;
use Closure;

class GptTrixEditor extends RichEditor
{

    protected string $view = 'gpt-trix-editor::trix-editor';

    protected array | Closure $toolbarButtons = [
        'attachFiles',
        'blockquote',
        'bold',
        'bulletList',
        'codeBlock',
        'h2',
        'h3',
        'italic',
        'link',
        'orderedList',
        'redo',
        'strike',
        'undo',
        'gptTools'
    ];
    
    public $options = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->options = $this->getOptions();
        $this->registerListeners([
            'gptTrixEditor::execute' => [
                function ($component, string $statePath, string $uuid, string $action = null): void {
                    
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    if(is_null($component->getState())){
                        $this->sendNotification(__('gpt-trix-editor::gpt-trix-editor.notification.warning'), __('gpt-trix-editor::gpt-trix-editor.notification.input_empty'), 'warning');
                        return;
                    }

                    $req = $this->sendGptRrequest($component->getState(),$action);
                    $gptResponse = $req['message'];
               
                    if(!$req['status']){
                        $this->sendNotification(__('gpt-trix-editor::gpt-trix-editor.notification.error'), $gptResponse, 'danger');
                        return;
                    }
                    
                    $component->state($gptResponse);
                    $this->sendNotification(__('gpt-trix-editor::gpt-trix-editor.notification.success'), null, 'success');


                },
            ],
        ]);

    }

    /**
     * Sent the Request to OPEN AI GPT
     *
     * @param string $prompt
     * @param string $action
     * @return array
     */
    function sendGptRrequest(string $prompt,string $promptKey = 'run'): array
    {
        try{

            //return ['status' => true,'message' => "Test"];

            $promptPrefix = $this->getPrompt($promptKey);
            if(is_null($promptPrefix)){
                return ['status' => false,'message' => __('gpt-trix-editor::gpt-trix-editor.notification.invalid_action')];
            }

            //https://github.com/openai-php/client
            $result = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $promptPrefix.$prompt,
                'max_tokens' => config('gpt-trix-editor.max_tokens'),
                'temperature' => config('gpt-trix-editor.temperature')
            ]);

            return ['status' => true,'message' => $result['choices'][0]['text']];

        }catch(\Throwable $e){

            return ['status' => false,'message' => $e->getMessage()];

        }
    }   

    /**
     * Get options for the GPT Button Dropdown
     *
     * @return array
     */
    function getOptions():array
    {
        $prefixes = collect(config('gpt-trix-editor')['prompt-prefixes']); 
        return $prefixes->pluck('prefix_label', 'prefix_key')->map(function($value, $key) {
            return Str::title(Str::replace('_', ' ', $key));
        })->all();    
    }


    /**
     * Returns the prompt associated with the given key.
     *
     * @param string $promptKey The key of the prompt to retrieve.
     *
     * @return string|null The prompt associated with the given key, or null if not found.
     */    
    function getPrompt(string $promptKey): ?string
    {
        $configPrompts = optional(config('gpt-trix-editor'))['prompt-prefixes'];
        if(is_null($configPrompts)){
            return null;
        }
        $prompt = collect($configPrompts)->where('prefix_key', $promptKey)->first();
        return $prompt ? $prompt['prefix'] : null;
    }



    /**
     * Sends a notification with the specified icon, color, body and type.
     *
     * @param string|null $icon
     * @param string $color
     * @param string $body
     * @param string $type
     */
    protected function sendNotification(?string $title, ?string $body, string $type = 'success'): void
    {
        if(config('gpt-trix-editor.enable_notifications') == false && $type == 'success'){
            return;
        }
        Notification::make()
            ->title($title)
            ->{$type}()
            ->body($body)
            ->send();
    }


}
