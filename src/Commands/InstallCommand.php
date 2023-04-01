<?php

namespace AskerAkbar\GptTrixEditor\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class InstallCommand extends Command
{
    protected $signature = 'gpt-trix-editor:install';

    public function __invoke(): int
    {
        
        return static::SUCCESS;
    }

    protected static function updateNpmPackages(bool $dev = true): void
    {
        return  ;
    }

    protected static function updateNpmPackageArray(array $packages): array
    {
       return [];
    }
}
