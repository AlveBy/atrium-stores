<?php

declare(strict_types=1);

namespace Atrium\Stores\Tests\Feature;

use Atrium\Stores\Database\StoresDemoSeeder;
use Atrium\Stores\Models\Store;
use Atrium\Stores\Tests\TestCase;

final class StoresTest extends TestCase
{
    public function test_the_stores_open_in_the_admin_and_on_the_site(): void
    {
        $this->assertTrue((new StoresDemoSeeder)->run());
        $store = Store::query()->where('code', 'city-centre')->sole();

        $this->assertSame(['+1 555 0100', '+1 555 0101'], $store->phones);
        $this->assertSame(52.52, $store->location['lat'] ?? null);

        $this->actingAs($this->admin(), 'admin');
        $this->get('/admin/stores')->assertOk();
        $this->get('/admin/stores/create')->assertOk();
        $this->get("/admin/stores/{$store->id}/edit")->assertOk();

        $this->get('/stores/city-centre')
            ->assertOk()
            ->assertSee('+1 555 0100, +1 555 0101')
            ->assertSee('centre@example.com');
    }

    public function test_the_demo_is_filled_once(): void
    {
        (new StoresDemoSeeder)->run();

        $this->assertFalse((new StoresDemoSeeder)->run());
        $this->assertSame(2, Store::query()->count());
    }
}
