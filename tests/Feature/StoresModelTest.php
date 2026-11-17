<?php

/*
 * This file is part of Atrium (https://github.com/AlveBy/atrium).
 * Copyright (c) 2026 AlveBy. Licensed under the MIT License, see LICENSE.
 */

declare(strict_types=1);

namespace Atrium\Stores\Tests\Feature;

use Atrium\Stores\Database\StoresDemoSeeder;
use Atrium\Stores\Models\Store;
use Atrium\Stores\StoresServiceProvider;
use Atrium\Stores\Tests\TestCase;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

/**
 * What the model asks of the form, what the demo puts into the shop and what
 * an application may publish.
 */
final class StoresModelTest extends TestCase
{
    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();

        parent::tearDown();
    }

    public function test_a_store_is_active_and_sorted_by_default_and_read_back_as_such(): void
    {
        $store = Store::query()->create(['name' => 'Kiosk', 'code' => 'kiosk']);

        $this->assertTrue($store->fresh()?->active);
        $this->assertSame(500, $store->fresh()?->sort);
        $this->assertFalse(Store::query()->create(['name' => 'Off', 'code' => 'off', 'active' => 0])->fresh()?->active);
    }

    public function test_the_form_starts_with_an_active_store_of_sort_500_and_five_phones_at_most(): void
    {
        $fields = [];

        foreach (Store::getAdministrationFields() as $field) {
            $fields[$field->getName()] = $field;
        }

        $this->assertSame(['active', 'name', 'code', 'sort', 'preview_picture', 'address', 'phones', 'email', 'opens_at', 'location', 'detail_text'], array_keys($fields));
        $this->assertTrue($fields['active']->getDefault());
        $this->assertSame(500, $fields['sort']->getDefault());
        $this->assertSame(5, $fields['phones']->toArray()['maxItems']);
        $this->assertContains('email', $fields['email']->getValidationRules());
        $this->assertContains('nullable', $fields['email']->getValidationRules());
    }

    public function test_the_list_shows_the_number_the_name_and_the_flag(): void
    {
        $this->assertSame(
            ['id', 'name', 'active'],
            array_map(fn ($column): string => $column->getName(), Store::getAdministrationListColumns()),
        );
    }

    public function test_the_demo_stores_have_their_order_picture_phones_hours_and_place(): void
    {
        CarbonImmutable::setTestNow('2026-03-20 15:30:00');
        $this->assertTrue((new StoresDemoSeeder)->run());

        $stores = Store::query()->orderBy('id')->get();
        $this->assertSame([100, 200], $stores->pluck('sort')->all());
        $this->assertSame(['demo/store.svg', 'demo/outlet.svg'], $stores->pluck('preview_picture')->all());
        $this->assertSame('2026-03-17 10:00:00', $stores[0]->opens_at?->format('Y-m-d H:i:s'));
        $this->assertNull($stores[1]->opens_at);
        $this->assertSame(['+1 555 0150'], $stores[1]->phones);
        $this->assertSame(['lat' => 52.507, 'lng' => 13.444, 'address' => '3 River Road'], $stores[1]->location);
        $this->assertSame(['lat' => 52.52, 'lng' => 13.405, 'address' => '12 Market Street'], $stores[0]->location);

        Storage::disk('public')->assertExists('demo/store.svg');
        Storage::disk('public')->assertExists('demo/outlet.svg');
    }

    public function test_the_module_and_its_fields_are_called_by_their_own_translations(): void
    {
        $this->assertSame('Stores', Store::getAdminLabel());
        $this->assertSame('Phones', Store::getAdministrationFields()[6]->toArray()['label']);
    }

    public function test_the_texts_can_be_published(): void
    {
        $paths = ServiceProvider::pathsToPublish(StoresServiceProvider::class, 'atrium-stores-lang');

        $this->assertSame([realpath(dirname(__DIR__, 2).'/src/../lang') => app()->langPath('vendor/atrium-stores')], array_combine(array_map('realpath', array_keys($paths)), $paths));
    }
}
