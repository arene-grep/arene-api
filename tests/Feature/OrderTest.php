<?php

namespace Tests\Feature;

use App\Exceptions\Message;
use App\Helpers\ApiHeaders;
use App\Models\Order;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderTest extends TestCase
{
    const URL = '/api/orders/';

    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function indexEmptyHappyPath()
    {
        $response = $this->get(OrderTest::URL, ApiHeaders::getGuest());
        $response->assertOk();
        $response->assertJson(Order::all()->toArray());
    }

    //TODO add this test to others classes

    /** @test */
    public function indexFullHappyPath()
    {
        $this->withoutExceptionHandling();
        Order::factory()->count(5)->create();
        $response = $this->get(OrderTest::URL, ApiHeaders::getGuest());
        $response->assertOk();
        $response->assertJson(Order::all()->toArray());
    }

    /** @test */
    public function showHappyPath()
    {
        $order = Order::factory()->create();
        $response = $this->get(OrderTest::URL . $order->id, ApiHeaders::getGuest());
        $response->assertSimilarJson($order->toArray());
        $response->assertOk();
    }

    /** @test */
    public function showIdDoesntExist()
    {
        $order = Order::factory()->create();
        $wrongId = $order->id + 1;
        $response = $this->get(OrderTest::URL . $wrongId, ApiHeaders::getGuest());
        $this->assertEquals(null, $response->getContent());
        $response->assertNoContent();
    }

    /** @test */
    public function storeOrderHappyPath()
    {
        $user = User::factory()->create();
        $response = $this->post(OrderTest::URL,
            [
                'date' => $this->faker->dateTime,
                'user_id' => $user->id
            ],
            ApiHeaders::getAuth()
        );

        $response->assertCreated();
        $this->assertCount(1, Order::all());
    }

    /** @test */
    public function storeOrderWithoutDate()
    {
        $user = User::factory()->create();
        $response = $this->post(OrderTest::URL,
            [
                'user_id' => $user->id
            ],
            ApiHeaders::getAuth()
        );

        $response->assertCreated();
        $this->assertCount(1, Order::all());
    }

    /** @test */
    public function updateOrderHappyPath()
    {
        $user = User::factory()->create();
        $oldDate = new DateTime('NOW');

        $order = Order::create([
            'date' => $oldDate,
            'user_id' => $user->id
        ]);
        $expected_date = $oldDate->add(new DateInterval('P1D'));
        $response = $this->put(OrderTest::URL . $order->id,
            [
                'date' => $expected_date
            ],
            ApiHeaders::getAuth()
        );
        $actual_order = Order::find($order->id);

        $response->assertOk();
        $this->assertEquals($expected_date->format('Y-m-d H:i:s'), $actual_order->date);
    }

    /** @test */
    public function updateOrderDoesntExist()
    {
        $now = new DateTime('NOW');
        $order = Order::create([
            'date' => new DateTime('NOW')
        ]);
        $order_id = $order->id + 1;
        $expected_date = $now->add(new DateInterval('P1D'));
        $response = $this->put(OrderTest::URL . $order_id,
            ['date' => $expected_date],
            ApiHeaders::getAuth()
        );
        $actual_order = Order::find($order->id);
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_UPDATE);
        $now = new DateTime('NOW');
        //check the date didn't change
        $this->assertEquals($now->format('Y-m-d H:i:s'), $actual_order->date);
    }

    /** @test */
    public function updateOrderWithNoDate()
    {
        $user1 = User::factory()->create();
        $order = Order::create([
            'date' => $this->faker->dateTime,
            'user_id' => $user1->id
        ]);

        $user2 = User::factory()->create();
        $response = $this->put(OrderTest::URL . $order->id,
            ['user_id' => $user2->id],
            ApiHeaders::getAuth()
        );
        $actual_order = Order::find($order->id);
        $this->assertEquals($order->user_id, $actual_order->user_id);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('date');
    }

    /** @test */
    public function deleteProductHappyPath()
    {
        $order = Order::factory()->create();
        $response = $this->delete(OrderTest::URL . $order->id,
            [],
            ApiHeaders::getAuth()
        );
        $this->assertNull(Order::find($order->id));
        $response->assertOk();
    }

    /** @test */
    public function deleteOrderUnhappyPath()
    {
        $order = Order::factory()->create();
        $order_id = $order->id + 1;
        $response = $this->delete(OrderTest::URL . $order_id,
            [],
            ApiHeaders::getAuth()
        );
        $this->assertNotNull(Order::find($order->id));
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_DELETED);
    }
}
