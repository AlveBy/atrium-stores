<?php

declare(strict_types=1);

namespace Atrium\Stores\Database;

use Atrium\Admin\Modules\DemoSeeder;
use Atrium\Stores\Models\Store;

/**
 * The demo shop's two stores.
 */
final class StoresDemoSeeder extends DemoSeeder
{
    public function run(): bool
    {
        if (Store::query()->exists()) {
            return false;
        }

        $this->copyFiles(dirname(__DIR__, 2).'/resources/demo');

        Store::query()->create([
            'name' => 'City centre',
            'code' => 'city-centre',
            'sort' => 100,
            'preview_picture' => $this->path('store.svg'),
            'address' => "12 Market Street\nOld Town",
            'phones' => ['+1 555 0100', '+1 555 0101'],
            'email' => 'centre@example.com',
            'opens_at' => now()->subDays(3)->setTime(10, 0),
            'location' => ['lat' => 52.5200, 'lng' => 13.4050, 'address' => '12 Market Street'],
            'detail_text' => '<p>Two floors, every model from the catalog on the tables, and a service desk for repairs.</p>',
        ]);

        Store::query()->create([
            'name' => 'Riverside outlet',
            'code' => 'riverside-outlet',
            'sort' => 200,
            'preview_picture' => $this->path('outlet.svg'),
            'address' => "3 River Road\nHarbour district",
            'phones' => ['+1 555 0150'],
            'location' => ['lat' => 52.5070, 'lng' => 13.4440, 'address' => '3 River Road'],
            'detail_text' => '<p>Display units and last year’s models.</p>',
        ]);

        return true;
    }
}
