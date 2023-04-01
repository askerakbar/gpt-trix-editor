<?php

namespace AskerAkbar\GptTrixEditor\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Buildix\Timex\Timex
 */
class GptTrixEditor extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "gpt-trix-editor";
    }
}
