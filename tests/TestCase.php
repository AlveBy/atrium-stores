<?php

/*
 * This file is part of Atrium (https://github.com/AlveBy/atrium).
 * Copyright (c) 2026 AlveBy. Licensed under the MIT License, see LICENSE.
 */

declare(strict_types=1);

namespace Atrium\Stores\Tests;

use Atrium\Admin\AtriumServiceProvider;
use Atrium\Admin\Models\AdminUser;
use Atrium\Admin\Support\PublicUrl;
use Atrium\Stores\StoresServiceProvider;
use Atrium\Theme\ThemeServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Storage;
use Inertia\ServiceProvider as InertiaServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Tighten\Ziggy\ZiggyServiceProvider;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    /**
     * On MySQL and PostgreSQL the whole suite shares one database: each
     * class migrates it again with the modules it installs.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        RefreshDatabaseState::$migrated = false;
    }

    public static function tearDownAfterClass(): void
    {
        RefreshDatabaseState::$migrated = false;
        parent::tearDownAfterClass();
    }

    protected function getPackageProviders($app): array
    {
        return [
            InertiaServiceProvider::class,
            ZiggyServiceProvider::class,
            AtriumServiceProvider::class,
            ThemeServiceProvider::class,
            StoresServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $config = $app['config'];
        $config->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
        $config->set('app.name', 'Demo shop');
        $target = env('DB_CONNECTION');
        $config->set('database.default', in_array($target, ['mysql', 'pgsql'], true) ? $target : 'testing');
        $config->set('atrium.modules', ['alveby/atrium-stores']);
        $config->set('atrium.auth.two_factor.enabled', false);
        $config->set('atrium.dashboard.timings.enabled', false);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Storage::fake('public');
        PublicUrl::forgetPaths();
    }

    protected function admin(): AdminUser
    {
        $admin = AdminUser::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secret-password',
            'is_active' => true,
        ]);

        $admin->forceFill(['is_superadmin' => true])->save();

        return $admin;
    }

    protected function root(): string
    {
        return rtrim(url('/'), '/');
    }
}
