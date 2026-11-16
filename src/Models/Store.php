<?php

/*
 * This file is part of Atrium (https://github.com/AlveBy/atrium).
 * Copyright (c) 2026 AlveBy. Licensed under the MIT License, see LICENSE.
 */

declare(strict_types=1);

namespace Atrium\Stores\Models;

use Atrium\Admin\Columns\BooleanColumn;
use Atrium\Admin\Columns\TextColumn;
use Atrium\Admin\Contracts\Administrable;
use Atrium\Admin\Fields\CheckboxField;
use Atrium\Admin\Fields\ContentField;
use Atrium\Admin\Fields\DateTimeField;
use Atrium\Admin\Fields\ImageField;
use Atrium\Admin\Fields\MapField;
use Atrium\Admin\Fields\NumberField;
use Atrium\Admin\Fields\RepeatableField;
use Atrium\Admin\Fields\SlugField;
use Atrium\Admin\Fields\TextareaField;
use Atrium\Admin\Fields\TextField;
use Atrium\Admin\Traits\InteractsWithAdmin;
use Illuminate\Database\Eloquent\Model;

/**
 * Where to buy: an address, phones, a point on the map.
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property array<int,string>|null $phones
 * @property array<string,mixed>|null $location
 */
class Store extends Model implements Administrable
{
    use InteractsWithAdmin;

    protected $table = 'stores';

    protected $fillable = ['name', 'code', 'active', 'sort', 'preview_picture', 'address', 'phones', 'email', 'opens_at', 'location', 'detail_text'];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'sort' => 'integer', 'phones' => 'array', 'opens_at' => 'datetime', 'location' => 'array'];
    }

    public static function getAdminLabel(): string
    {
        return __('atrium-stores::stores.module');
    }

    public static function getAdminRoutePrefix(): string
    {
        return 'stores';
    }

    public static function getAdministrationFields(): array
    {
        return [
            CheckboxField::make('active')->label(__('atrium::fields.standard.active'))->default(true),
            TextField::make('name')->label(__('atrium::fields.standard.name'))->required(),
            SlugField::make('code')->label(__('atrium::fields.standard.code'))->from('name')->unique('stores', 'code'),
            NumberField::make('sort')->label(__('atrium::fields.standard.sort'))->default(500),
            ImageField::make('preview_picture')->label(__('atrium::fields.standard.picture'))->directory('stores'),
            TextareaField::make('address')->label(__('atrium-stores::stores.fields.address')),
            RepeatableField::make('phones')->label(__('atrium-stores::stores.fields.phones'))->itemType('tel')->maxItems(5),
            TextField::make('email')->label(__('atrium-stores::stores.fields.email'))->rules(['nullable', 'email']),
            DateTimeField::make('opens_at')->label(__('atrium-stores::stores.fields.opens_at')),
            MapField::make('location')->label(__('atrium-stores::stores.fields.location')),
            ContentField::make('detail_text')->label(__('atrium::fields.standard.description')),
        ];
    }

    public static function getAdministrationListColumns(): array
    {
        return [
            TextColumn::make('id')->label(__('atrium::fields.standard.id'))->sortable(),
            TextColumn::make('name')->label(__('atrium::fields.standard.name'))->sortable()->searchable(),
            BooleanColumn::make('active')->label(__('atrium::fields.standard.active')),
        ];
    }
}
