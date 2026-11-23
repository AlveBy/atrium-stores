<?php

/*
 * This file is part of Atrium (https://github.com/AlveBy/atrium).
 * Copyright (c) 2026 AlveBy. Licensed under the MIT License, see LICENSE.
 */

declare(strict_types=1);

namespace Atrium\Stores\Tests\Feature;

use Atrium\Admin\Fields\Field;
use Atrium\Stores\Models\Store;
use Atrium\Stores\Tests\TestCase;

/**
 * Which texts of the module the AI helps with: the ones the editors write for
 * the site, and not the ones visitors write or the ones that are code.
 */
final class AiFieldsTest extends TestCase
{
    public function test_the_description_of_a_store_can_be_written_by_the_ai(): void
    {
        $this->assertSame(['task' => 'describe', 'from' => []], $this->field(Store::class, 'detail_text')->toArray()['ai'] ?? null);
    }

    public function test_the_address_of_a_store_is_left_alone(): void
    {
        foreach (['name', 'address', 'email'] as $name) {
            $this->assertNull($this->field(Store::class, $name)->toArray()['ai'] ?? null, $name);
        }
    }

    /**
     * @param  class-string  $model
     */
    private function field(string $model, string $name): Field
    {
        $fields = method_exists($model, 'getAdminFieldsWithTabs') ? $model::getAdminFieldsWithTabs() : $model::getAdministrationFields();

        return collect($fields)->first(fn (Field $field): bool => $field->getName() === $name) ?? $this->fail("No field {$name} in {$model}.");
    }
}
