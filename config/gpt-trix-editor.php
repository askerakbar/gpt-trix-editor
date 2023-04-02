<?php

return [

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
            'prefix_key'    => 'check_grammar',
            'prefix_label'  => 'prompt_prefixes.check_grammar',
            'prefix'        => 'Check only the grammar and return the same HTML format:',
        ]
    ]


];