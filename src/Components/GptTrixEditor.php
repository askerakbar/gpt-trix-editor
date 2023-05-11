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
        'strike',
        'undo',
        'redo',
        'gptTools'
    ];
    
    public $options = [];
    
    protected function setUp(): void
    {
        parent::setUp();


        $this->options = $this->getOptions();
        $this->registerListeners([
            
            'gptTrixEditor::execute' => [
                function ($component, string $statePath, string $uuid, string $action = null ,$selectedText = ''): void {
                    
                    if ($component->isDisabled() || $statePath !== $component->getStatePath()) {
                        return;
                    }

                    $livewire = $component->getLivewire();
                    $textToSend = $component->getState();
                   
                    $configPrompts  = optional(config('gpt-trix-editor'))['prompt-prefixes'];
                    $prompt        = collect($configPrompts)->where('prefix_key', $action)->first();
                    
                    if(is_null($component->getState())){
                        $livewire->dispatchBrowserEvent('update-selected-content',['id'=> $statePath]);
                        $this->sendNotification(__('gpt-trix-editor::gpt-trix-editor.notification.warning'), __('gpt-trix-editor::gpt-trix-editor.notification.input_empty'), 'warning');
                        return;
                    }

                    if(isset($prompt['on_selected']) && $prompt['on_selected'] == true){
                        if($selectedText == ''){
                            $livewire->dispatchBrowserEvent('update-selected-content',['id'=> $statePath]);
                            $this->sendNotification(__('gpt-trix-editor::gpt-trix-editor.notification.warning'), __('gpt-trix-editor::gpt-trix-editor.notification.input_empty'), 'warning');
                            return;
                        }
                        $textToSend = $selectedText;
                    }
                    
                    $req = $this->sendGptRrequest($textToSend,$action);
                    $gptResponse = $req['message'];

                    if(!$req['status']){
                        $this->sendNotification(__('gpt-trix-editor::gpt-trix-editor.notification.error'), $gptResponse, 'danger');
                        return;
                    }

                    if(!isset($prompt['on_selected'])){
                        $livewire->dispatchBrowserEvent('update-content',['id'=> $statePath,'content' => $gptResponse]);
                    }else{
                        $livewire->dispatchBrowserEvent('update-selected-content',['id'=> $statePath,'content' => $gptResponse]);
                    }

                    $this->sendNotification(__('gpt-trix-editor::gpt-trix-editor.notification.success'), null, 'success');


                },
            ],
        ]);

    }

    /**
     * Sent the Request to OPEN AI GPT
     *
     * @param $prompt
     * @param string $action
     * @return array
     */
    function sendGptRrequest($prompt = null,string $promptKey = 'run'): array
    {
        try{
            
            #sleep(2); return ['status' => true,'message' => "<b>New</b> Test-".time()];

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
        return $prefixes->map(function($value, $key) {
            return [
                'key'           => $value['prefix_key'],
                'label'         => Str::title(Str::replace('_', ' ', $value['prefix_key'])),
                'on_selected'   => isset($value['on_selected']) ? $value['on_selected'] : false
            ];
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
    
    protected array|Arrayable|string|Closure|null $option = [];

    public function option(array|Arrayable|string|Closure|null $option): static
    {
        $this->option = $option;

        return $this;
    }

    public function getOption(): array
    {
        $option = $this->evaluate($this->option) ?? [];
        $options = [];

        // Check if $option is a string and if a function called "enum_exists" exists,
        // and if the enum specified by $option exists

        if (is_string($option) && function_exists('enum_exists') && enum_exists($option)) {
            // Convert the enum cases into key-value pairs
            $option = collect($option::cases())->mapWithKeys(static fn($case) => [($case?->value ?? $case->name) => $case->name]);
        }

        // Convert $option to an array if it implements the Arrayable interface
        if ($option instanceof Arrayable) {
            $options = $option->toArray();
        }
        // If $option is already an array, assign it to $options directly
        elseif (is_array($option)) {
            $options = $option;
        }

        return $options;
    }


    public function hasDynamicOptions(): bool
    {
        return $this->option instanceof Closure;
    }

}
