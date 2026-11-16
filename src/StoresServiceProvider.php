<?php

declare(strict_types=1);

namespace Atrium\Stores;

use Illuminate\Support\ServiceProvider;

/**
 * The model, its migrations and its demo are in the manifest (composer.json,
 * extra.atrium): they are on once `atrium:module:install` has run.
 */
final class StoresServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'atrium-stores');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../lang' => $this->app->langPath('vendor/atrium-stores'),
            ], 'atrium-stores-lang');
        }
    }
}
