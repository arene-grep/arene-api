<?php

namespace Tests\Feature;

use App\Exceptions\Message;
use App\Models\Event;
use DateInterval;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class EventTest extends TestCase
{
    const URL = '/api/events/';
    const HEADERS = ['Accept' => 'application/json'];

    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function indexHappyPath()
    {
        $response = $this->get(EventTest::URL, EventTest::HEADERS);
        $response->assertOk();
        $response->assertJson(Event::all()->toArray());
    }

    /** @test */
    public function showHappyPath()
    {
        $event_created = Event::factory()->create();
        $event = Event::find($event_created->id);
        $response = $this->get(EventTest::URL . $event->id, EventTest::HEADERS);
        $response->assertSimilarJson($event->toArray());
        $response->assertOk();
    }

    /** @test */
    public function showIdDoesntExist()
    {
        $Event = Event::factory()->create();
        $wrongId = $Event->id + 1;
        $response = $this->get(EventTest::URL . $wrongId, EventTest::HEADERS);
        $this->assertEquals(null, $response->getContent());
        $response->assertNoContent();
    }

    /** @test */
    public function storeEventHappyPath()
    {

        $response = $this->post(EventTest::URL,
            [
                'name' => $this->faker->name,
                'date' => $this->faker->dateTime(),
            ],
            EventTest::HEADERS
        );

        $response->assertCreated();
        $this->assertCount(1, Event::all());
    }

    /** @test */
    public function storeEventWithNoName()
    {
        $response = $this->post(EventTest::URL,
            ['name' => ''],
            EventTest::HEADERS
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('name');
        $this->assertEmpty(Event::all());
    }

    /** @test */
    public function storeEventWithNoDate()
    {
        $response = $this->post(EventTest::URL,
            ['name' => $this->faker->name],
            EventTest::HEADERS
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('date');
        $this->assertEmpty(Event::all());
    }

    /** @test */
    public function updateEventNameHappyPath()
    {
        $Event = Event::create([
            'name' => 'old Event',
            'date' => $this->faker->dateTime
        ]);
        $expected_name = 'new Event';
        $response = $this->put(EventTest::URL . $Event->id,
            ['name' => $expected_name],
            EventTest::HEADERS
        );
        $actual_Event = Event::find($Event->id);

        $response->assertOk();
        $this->assertEquals($expected_name, $actual_Event->name);
    }

    /** @test */
    public function updateEventUserHappyPath()
    {
        //uncomment this to check the error if it returns 500
        //$this->handleValidationExceptions();

        $oldDate = new DateTime('NOW');
        $oldDate->add(new DateInterval('P1D'));

        $event = Event::create([
            'name' => $this->faker->name,
            'date' => $oldDate
        ]);
        $expected_date = $oldDate->add(new DateInterval('P1D'));
        $response = $this->put(EventTest::URL . $event->id,
            ['date' => $expected_date],
            EventTest::HEADERS
        );
        $actual_event = Event::find($event->id);

        $response->assertOk();
        $this->assertEquals($expected_date->format('Y-m-d H:i:s'), $actual_event->date);
    }

    /** @test */
    public function updateEventDoesntExist()
    {
        $expected_name = 'old Event';
        $Event = Event::create([
            'name' => $expected_name,
            'date' => $this->faker->dateTime
        ]);
        $Event_id = $Event->id + 1;
        $response = $this->put(EventTest::URL . $Event_id,
            ['name' => $expected_name],
            EventTest::HEADERS
        );
        $actual_Event = Event::find($Event->id);
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_UPDATE);
        $this->assertEquals($expected_name, $actual_Event->name);
    }

    /** @test */
    public function deleteEventHappyPath()
    {
        $event = Event::factory()->create();
        $response = $this->delete(EventTest::URL . $event->id,
            [],
            EventTest::HEADERS
        );
        $this->assertNull(Event::find($event->id));
        $response->assertOk();
    }

    /** @test */
    public function deleteEventDoesntExist()
    {
        $event = Event::factory()->create();
        $event_id = $event->id + 1;
        $response = $this->delete(EventTest::URL . $event_id,
            [],
            EventTest::HEADERS
        );
        $this->assertNotNull(Event::find($event->id));
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_DELETED);
    }
}
