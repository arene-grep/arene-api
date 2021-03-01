<?php

namespace Tests\Feature;

use App\Exceptions\Message;
use App\Helpers\ApiHeaders;
use App\Models\TradingCardGame;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TradingCardGameTest extends TestCase
{
    const URL = '/api/tcgames/';

    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function indexHappyPath()
    {
        $response = $this->get(TradingCardGameTest::URL, ApiHeaders::getGuest());
        $response->assertOk();
        $response->assertJson(TradingCardGame::all()->toArray());
    }

    /** @test */
    public function showHappyPath()
    {
        $trading_card_game = TradingCardGame::factory()->create();
        $response = $this->get(TradingCardGameTest::URL . $trading_card_game->id, ApiHeaders::getGuest());
        $response->assertSimilarJson($trading_card_game->toArray());
        $response->assertOk();
    }

    /** @test */
    public function showIdDoesntExist()
    {
        $trading_card_game = TradingCardGame::factory()->create();
        $wrongId = $trading_card_game->id + 1;
        $response = $this->get(TradingCardGameTest::URL . $wrongId, ApiHeaders::getGuest());
        $this->assertEquals(null, $response->getContent());
        $response->assertNoContent();
    }

    /** @test */
    public function storeTradingCardGameHappyPath()
    {
        $response = $this->post(TradingCardGameTest::URL,
            ['name' => $this->faker->name],
            ApiHeaders::getAuth()
        );

        $response->assertCreated();
        $this->assertCount(1, TradingCardGame::all());
    }

    /** @test */
    public function storeTradingCardGameWithNoName()
    {
        $response = $this->post(TradingCardGameTest::URL,
            ['name' => ''],
            ApiHeaders::getAuth()
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('name');
        $this->assertEmpty(TradingCardGame::all());
    }

    /** @test */
    public function updateTradingCardGameHappyPath()
    {
        $trading_card_game = TradingCardGame::create([
            'name' => 'old trading_card_game'
        ]);
        $expected_name = 'new trading_card_game';
        $response = $this->put(TradingCardGameTest::URL . $trading_card_game->id,
            ['name' => $expected_name],
            ApiHeaders::getAuth()
        );
        $actual_trading_card_game = TradingCardGame::find($trading_card_game->id);

        $response->assertOk();
        $this->assertEquals($expected_name, $actual_trading_card_game->name);
    }

    /** @test */
    public function updateTradingCardGameDoesntExist()
    {
        $expected_name = 'old trading_card_game';
        $trading_card_game = TradingCardGame::create([
            'name' => $expected_name
        ]);
        $trading_card_game_id = $trading_card_game->id + 1;
        $response = $this->put(TradingCardGameTest::URL . $trading_card_game_id,
            ['name' => $expected_name],
            ApiHeaders::getAuth()
        );
        $actual_trading_card_game = TradingCardGame::find($trading_card_game->id);
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_UPDATE);
        $this->assertEquals($expected_name, $actual_trading_card_game->name);
    }

    /** @test */
    public function updateTradingCardGameWithNoName()
    {
        $trading_card_game = TradingCardGame::create([
            'name' => 'old trading_card_game'
        ]);
        $response = $this->put(TradingCardGameTest::URL . $trading_card_game->id,
            ['name' => ''],
            ApiHeaders::getAuth()
        );
        $actual_trading_card_game = TradingCardGame::find($trading_card_game->id);
        $this->assertEquals($actual_trading_card_game->toArray(), $trading_card_game->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('name');
    }

    /** @test */
    public function deleteTradingCardGameHappyPath()
    {
        $trading_card_game = TradingCardGame::factory()->create();
        $product = Product::factory()->create();
        $product->trading_card_game()->associate($trading_card_game)->save();
        $response = $this->delete(TradingCardGameTest::URL . $trading_card_game->id,
            [],
            ApiHeaders::getAuth()
        );
        $products = Product::whereTradingCardGameId($trading_card_game->id)->get();

        $this->assertEmpty($products->toArray());
        $this->assertNull(TradingCardGame::find($trading_card_game->id));
        $response->assertOk();
    }

    /** @test */
    public function deleteTradingCardGameUnhappyPath()
    {
        $trading_card_game = TradingCardGame::factory()->create();
        $trading_card_game_id = $trading_card_game->id + 1;
        $response = $this->delete(TradingCardGameTest::URL . $trading_card_game_id,
            [],
            ApiHeaders::getAuth()
        );
        $this->assertNotNull(TradingCardGame::find($trading_card_game->id));
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_DELETED);
    }
}
