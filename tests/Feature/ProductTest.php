<?php

namespace Tests\Feature;

use App\Exceptions\Message;
use App\Helpers\ApiHeaders;
use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\TradingCardGame;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductTest extends TestCase
{
    const URL = '/api/products/';

    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function indexHappyPath()
    {
        $response = $this->get(ProductTest::URL, ApiHeaders::getGuest());
        $response->assertOk();
        $response->assertJson(Product::all()->toArray());
    }

    /** @test */
    public function showHappyPath()
    {
        $product = Product::factory()->create();
        $response = $this->get(ProductTest::URL . $product->id, ApiHeaders::getGuest());
        $response->assertSimilarJson($product->toArray());
        $response->assertOk();
    }

    /** @test */
    public function showIdDoesntExist()
    {
        $product = Product::factory()->create();
        $wrongId = $product->id + 1;
        $response = $this->get(ProductTest::URL . $wrongId, ApiHeaders::getGuest());
        $this->assertEmpty($response->getContent());
        $response->assertNoContent();
    }

    /** @test */
    public function storeProductHappyPath()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3)
        ], ApiHeaders::getAuth());

        $response->assertCreated();
        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function storeProductWithEmptyName()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => '',
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3)
        ], ApiHeaders::getAuth());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('name');
        $this->assertEmpty(Product::all());
    }

    /** @test */
    public function storeProductWithNoName()
    {
        $response = $this->post(ProductTest::URL, [
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3)
        ], ApiHeaders::getAuth());


        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('name');
        $this->assertEmpty(Product::all());
    }

    /** @test */
    public function storeProductWithEmptyPrice()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => null,
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3)
        ], ApiHeaders::getAuth());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('price');
        $this->assertEmpty(Product::all());
    }

    /** @test */
    public function storeProductWithPriceAsString()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->word,
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3)
        ], ApiHeaders::getAuth());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('price');
        $this->assertEmpty(Product::all());
    }

    /** @test */
    public function storeProductWithNoPrice()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3)
        ], ApiHeaders::getAuth());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('price');
        $this->assertEmpty(Product::all());
    }

    /** @test */
    public function storeProductWithEmptyStock()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'minimum_stock' => $this->faker->randomNumber(3)
        ], ApiHeaders::getAuth());

        $response->assertCreated();
        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function storeProductWithStockAsString()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->word,
            'minimum_stock' => $this->faker->randomNumber(3)
        ], ApiHeaders::getAuth());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('stock');
        $this->assertEmpty(Product::all());
    }

    /** @test */
    public function storeProductWithEmptyMinimumStock()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3)
        ], ApiHeaders::getAuth());

        $response->assertCreated();
        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function storeProductWithMinimumStockAsString()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->word
        ], ApiHeaders::getAuth());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('minimum_stock');
        $this->assertEmpty(Product::all());
    }

    /** @test */
    public function storeProductCategoryDoesntExist()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3),
            'category_id' => 1,
        ], ApiHeaders::getAuth());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('category_id');
        $this->assertEmpty(Product::all());
    }

    /** @test */
    public function storeProductCategoryExist()
    {
        $category = Category::factory()->create();

        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3),
            'category_id' => $category->id,
        ], ApiHeaders::getAuth());

        $response->assertCreated();
        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function storeProductTradingCardGameDoesntExist()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3),
            'trading_card_game_id' => 1,
        ], ApiHeaders::getAuth());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('trading_card_game_id');
        $this->assertEmpty(Product::all());
    }

    /** @test */
    public function storeProductTradingCardGameExist()
    {
        $trading_card_game = TradingCardGame::factory()->create();

        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3),
            'trading_card_game_id' => $trading_card_game->id,
        ], ApiHeaders::getAuth());

        $response->assertCreated();
        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function storeProductLanguageDoesntExist()
    {
        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3),
            'language_id' => 1,
        ], ApiHeaders::getAuth());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('language_id');
        $this->assertEmpty(Product::all());
    }

    /** @test */
    public function storeProductLanguageExist()
    {
        $language = Language::factory()->create();

        $response = $this->post(ProductTest::URL, [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3),
            'language_id' => $language->id,
        ], ApiHeaders::getAuth());

        $response->assertCreated();
        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function updateOnlyNameHappyPath()
    {
        $product = Product::create([
            'name' => 'old product',
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3),
        ]);
        $expected_name = 'new product';
        $response = $this->put(ProductTest::URL . $product->id,
            ['name' => $expected_name],
            ApiHeaders::getAuth()
        );
        $actual_product = Product::find($product->id);

        $this->assertEquals($expected_name, $actual_product->name);
        $this->assertEquals($product->price, $actual_product->price);
        $this->assertEquals($product->stock, $actual_product->stock);
        $this->assertEquals($product->minimum_stock, $actual_product->minimum_stock);
        $this->assertEquals($product->category_id, $actual_product->category_id);
        $this->assertEquals($product->trading_card_game_id, $actual_product->trading_card_game_id);
        $this->assertEquals($product->language_id, $actual_product->language_id);
        $response->assertOk();
    }

    /** @test */
    public function updateOnlyPriceHappyPath()
    {
        $product = Product::create([
            'name' => $this->faker->name,
            'price' => 777.77,
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3),
        ]);
        $expected_price = 999.99;
        $response = $this->put(ProductTest::URL . $product->id,
            ['price' => $expected_price],
            ApiHeaders::getAuth()
        );
        $actual_product = Product::find($product->id);

        $this->assertEquals($product->name, $actual_product->name);
        $this->assertEquals($expected_price, $actual_product->price);
        $this->assertEquals($product->stock, $actual_product->stock);
        $this->assertEquals($product->minimum_stock, $actual_product->minimum_stock);
        $this->assertEquals($product->category_id, $actual_product->category_id);
        $this->assertEquals($product->trading_card_game_id, $actual_product->trading_card_game_id);
        $this->assertEquals($product->language_id, $actual_product->language_id);
        $response->assertOk();
    }

    /** @test */
    public function updateOnlyStockHappyPath()
    {
        $product = Product::create([
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => 777,
            'minimum_stock' => $this->faker->randomNumber(3),
        ]);
        $expected_stock = 999;
        $response = $this->put(ProductTest::URL . $product->id,
            ['stock' => $expected_stock],
            ApiHeaders::getAuth()
        );
        $actual_product = Product::find($product->id);

        $this->assertEquals($product->name, $actual_product->name);
        $this->assertEquals($product->price, $actual_product->price);
        $this->assertEquals($expected_stock, $actual_product->stock);
        $this->assertEquals($product->minimum_stock, $actual_product->minimum_stock);
        $this->assertEquals($product->category_id, $actual_product->category_id);
        $this->assertEquals($product->trading_card_game_id, $actual_product->trading_card_game_id);
        $this->assertEquals($product->language_id, $actual_product->language_id);
        $response->assertOk();
    }

    /** @test */
    public function updateOnlyMinimumStockHappyPath()
    {
        $product = Product::create([
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => 777,
        ]);
        $expected_minimum_stock = 999;
        $response = $this->put(ProductTest::URL . $product->id,
            ['minimum_stock' => $expected_minimum_stock],
            ApiHeaders::getAuth()
        );
        $actual_product = Product::find($product->id);

        $this->assertEquals($product->name, $actual_product->name);
        $this->assertEquals($product->price, $actual_product->price);
        $this->assertEquals($product->stock, $actual_product->stock);
        $this->assertEquals($expected_minimum_stock, $actual_product->minimum_stock);
        $this->assertEquals($product->category_id, $actual_product->category_id);
        $this->assertEquals($product->trading_card_game_id, $actual_product->trading_card_game_id);
        $this->assertEquals($product->language_id, $actual_product->language_id);
        $response->assertOk();
    }

    /** @test */
    public function updateOnlyCategoryHappyPath()
    {
        $categories = Category::factory()->count(2)->create();
        $product = Product::factory()->create();
        $product->category()->associate($categories[0])->save();
        $expected_category_id = $categories[0]->id+1;
        $response = $this->put(ProductTest::URL . $product->id,
            ['category_id' => $expected_category_id],
            ApiHeaders::getAuth()
        );
        $actual_product = Product::find($product->id);

        $this->assertEquals($product->name, $actual_product->name);
        $this->assertEquals($product->price, $actual_product->price);
        $this->assertEquals($product->stock, $actual_product->stock);
        $this->assertEquals($product->minimum_stock, $actual_product->minimum_stock);
        $this->assertEquals($expected_category_id, $actual_product->category_id);
        $this->assertEquals($product->trading_card_game_id, $actual_product->trading_card_game_id);
        $this->assertEquals($product->language_id, $actual_product->language_id);
        $response->assertOk();
    }

    /** @test */
    public function updateOnlyTradingCardGameHappyPath()
    {
        $trading_card_games = TradingCardGame::factory()->count(2)->create();
        $product = Product::factory()->create();
        $product->trading_card_game()->associate($trading_card_games[0])->save();
        $expected_trading_card_game_id = $trading_card_games[0]->id+1;
        $response = $this->put(ProductTest::URL . $product->id,
            ['trading_card_game_id' => $expected_trading_card_game_id],
            ApiHeaders::getAuth()
        );
        $actual_product = Product::find($product->id);

        $this->assertEquals($product->name, $actual_product->name);
        $this->assertEquals($product->price, $actual_product->price);
        $this->assertEquals($product->stock, $actual_product->stock);
        $this->assertEquals($product->minimum_stock, $actual_product->minimum_stock);
        $this->assertEquals($product->category_id, $actual_product->category_id);
        $this->assertEquals($expected_trading_card_game_id, $actual_product->trading_card_game_id);
        $this->assertEquals($product->language_id, $actual_product->language_id);
        $response->assertOk();
    }

    /** @test */
    public function updateOnlyLanguageHappyPath()
    {
        $languages = Language::factory()->count(2)->create();
        $product = Product::factory()->create();
        $product->language()->associate($languages[0])->save();
        $expected_language_id = $languages[0]->id+1;
        $response = $this->put(ProductTest::URL . $product->id,
            ['language_id' => $expected_language_id],
            ApiHeaders::getAuth()
        );
        $actual_product = Product::find($product->id);

        $this->assertEquals($product->name, $actual_product->name);
        $this->assertEquals($product->price, $actual_product->price);
        $this->assertEquals($product->stock, $actual_product->stock);
        $this->assertEquals($product->minimum_stock, $actual_product->minimum_stock);
        $this->assertEquals($product->category_id, $actual_product->category_id);
        $this->assertEquals($product->trading_card_game_id, $actual_product->trading_card_game_id);
        $this->assertEquals($expected_language_id, $actual_product->language_id);
        $response->assertOk();
    }

    /** @test */
    public function updateProductDoesntExist()
    {
        $expected_name = 'new product';
        $product = Product::create([
            'name' => $expected_name,
            'price' => $this->faker->randomFloat(2, 0, 99),
            'stock' => $this->faker->randomNumber(3),
            'minimum_stock' => $this->faker->randomNumber(3),
        ]);
        $product_id = $product->id + 1;
        $response = $this->put(ProductTest::URL . $product_id,
            ['name' => 'old product'],
            ApiHeaders::getAuth()
        );
        $actual_product = Product::find($product->id);
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_UPDATE);
        $this->assertEquals($expected_name, $actual_product->name);
    }

    /** @test */
    public function deleteProductHappyPath()
    {
        $product = Product::factory()->create();
        $response = $this->delete(ProductTest::URL . $product->id,
            [],
            ApiHeaders::getAuth()
        );
        $this->assertNull(Product::find($product->id));
        $response->assertOk();
    }

    /** @test */
    public function deleteProductDoesntExist()
    {
        $product = Product::factory()->create();
        $product_id = $product->id + 1;
        $response = $this->delete(ProductTest::URL . $product_id,
            [],
            ApiHeaders::getAuth()
        );
        $this->assertNotNull(Product::find($product->id));
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_DELETED);
    }
}
