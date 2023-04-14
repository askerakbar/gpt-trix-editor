<?php

return [

    /*
        max_tokens
        The maximum number of tokens to generate in the completion.
        https://platform.openai.com/docs/api-reference/completions/create#completions/create-max_tokens
    */

    'max_tokens' => 1000,

    /*
        temperature
        What sampling temperature to use, between 0 and 2. 
        Higher values like 0.8 will make the output more random, while lower values like 0.2 
        will make it more focused and deterministic.
        https://platform.openai.com/docs/api-reference/completions/create#completions/create-temperature
    */

    'temperature' => 0,


    /*
        If set to true, notifications will be enabled for successfull gpt api calls.
    */
    'enable_notifications'  => true,

    /*
    |
    | Prompt labels and propmpts that are currently listed on the dropdown menu
    |
    | for example: if the Text area content is "Write a poem about Space" and
    | when you click run, as default that content will be appended with the following and send to GPT
    | Complete the following text and return back with the same HTML : Write a poem about Space
    |
    |
    */

    'prompt-prefixes'   =>  [
        [
            'prefix_key'    => 'run',
            'prefix_label'  => 'prompt_prefixes.run',
            'prefix'        =>  'Complete the following and return the same HTML format:',
        ],
        [
            'prefix_key'    => 'run_on_selected_text',  
            'prefix_label'  => 'prompt_prefixes.run_on_selected_text',
            'prefix'        => 'Complete the following and return the same HTML format:',
            'on_selected'  => true
        ],
        [
            'prefix_key'    => 'check_grammar',
            'prefix_label'  => 'prompt_prefixes.check_grammar',
            'prefix'        => 'Check only the grammar and return the same HTML format:',
        ],
        [
            'prefix_key'    => 'fix_grammar_on_selected_text',
            'prefix_label'  => 'prompt_prefixes.check_grammar_on_selected_text',
            'prefix'        => 'Fix the grammar and spelling issues and return the same HTML format without changes:',
            'on_selected'  => true
        ]
    ]


];