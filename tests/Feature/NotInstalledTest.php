<?php

/*
 * This file is part of Atrium (https://github.com/AlveBy/atrium).
 * Copyright (c) 2026 AlveBy. Licensed under the MIT License, see LICENSE.
 */

declare(strict_types=1);

namespace Atrium\Stores\Tests\Feature;

use Atrium\Admin\Resources\ResourceRegistry;
use Atrium\Stores\Models\Store;
use Atrium\Stores\Tests\TestCase;
use Illuminate\Support\Facades\Schema;

/**
 * Required with Composer is not installed: until `atrium:module:install`
 * the module is not in the panel, the site or the database.
 */
final class NotInstalledTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('atrium.modules', []);
    }

    public function test_a_module_required_but_not_installed_is_nowhere(): void
    {
        $this->assertArrayNotHasKey('stores', $this->app->make(ResourceRegistry::class)->all());
        $this->assertNull(config('atrium.public_urls.'.Store::class));
        $this->assertFalse(Schema::hasTable('stores'));

        $this->get('/stores/city-centre')->assertNotFound();
    }
}
