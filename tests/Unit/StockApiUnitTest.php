<?php

namespace Tests\Unit;

use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// use function PHPUnit\Framework\assertGreaterThanOrEqual;

// php artisan test --filter=StockApiUnitTest
class StockApiUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_stock_table_allows_negative_input_for_unit_price()
    {
        $stock = Stock::factory()->create(['unit_price' => -1]);
        // Stock::factory()->create($stock);
        // $this->assertGreaterThan(1, $stock->unit_price);
        dd(Stock::get());
        $this->assertDatabaseHas('stocks', ['unit_price' => $stock['unit_price']]);
    }
}
