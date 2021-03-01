<?php

namespace Tests\Feature;

use App\Exceptions\Message;
use App\Helpers\ApiHeaders;
use App\Models\Language;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class LanguageTest extends TestCase
{
    const URL = '/api/languages/';

    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function indexHappyPath()
    {
        $response = $this->get(LanguageTest::URL, ApiHeaders::getGuest());
        $response->assertOk();
        $response->assertJson(Language::all()->toArray());
    }

    /** @test */
    public function showHappyPath()
    {
        $language = Language::factory()->create();
        $response = $this->get(LanguageTest::URL . $language->id, ApiHeaders::getGuest());
        $response->assertSimilarJson($language->toArray());
        $response->assertOk();
    }

    /** @test */
    public function showIdDoesntExist()
    {
        $language = Language::factory()->create();
        $wrongId = $language->id + 1;
        $response = $this->get(LanguageTest::URL . $wrongId, ApiHeaders::getGuest());
        $this->assertEquals(null, $response->getContent());
        $response->assertNoContent();
    }

    /** @test */
    public function storeLanguageHappyPath()
    {
        $response = $this->post(LanguageTest::URL,
            ['name' => $this->faker->name],
            ApiHeaders::getAuth()
        );

        $response->assertCreated();
        $this->assertCount(1, Language::all());
    }

    /** @test */
    public function storeLanguageWithNoName()
    {
        $response = $this->post(LanguageTest::URL,
            ['name' => ''],
            ApiHeaders::getAuth()
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('name');
        $this->assertEmpty(Language::all());
    }

    /** @test */
    public function updateLanguageHappyPath()
    {
        $language = Language::create([
            'name' => 'old language'
        ]);
        $expected_name = 'new language';
        $response = $this->put(LanguageTest::URL . $language->id,
            ['name' => $expected_name],
            ApiHeaders::getAuth()
        );
        $actual_language = Language::find($language->id);

        $response->assertOk();
        $this->assertEquals($expected_name, $actual_language->name);
    }

    /** @test */
    public function updateLanguageDoesntExist()
    {
        $expected_name = 'old language';
        $language = Language::create([
            'name' => $expected_name
        ]);
        $language_id = $language->id + 1;
        $response = $this->put(LanguageTest::URL . $language_id,
            ['name' => $expected_name],
            ApiHeaders::getAuth()
        );
        $actual_language = Language::find($language->id);
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_UPDATE);
        $this->assertEquals($expected_name, $actual_language->name);
    }

    /** @test */
    public function updateLanguageWithNoName()
    {
        $language = Language::create([
            'name' => 'old language'
        ]);
        $response = $this->put(LanguageTest::URL . $language->id,
            ['name' => ''],
            ApiHeaders::getAuth()
        );
        $actual_language = Language::find($language->id);
        $this->assertEquals($actual_language->toArray(), $language->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSeeText('name');
    }

    /** @test */
    public function deleteLanguageHappyPath()
    {
        $language = Language::factory()->create();
        $product = Product::factory()->create();
        $product->language()->associate($language)->save();
        $response = $this->delete(LanguageTest::URL . $language->id,
            [],
            ApiHeaders::getAuth()
        );
        $products = Product::where(['language_id' => $language->id])->get();

        $this->assertEmpty($products->toArray());
        $this->assertNull(Language::find($language->id));
        $response->assertOk();
    }

    /** @test */
    public function deleteLanguageUnhappyPath()
    {
        $language = Language::factory()->create();
        $language_id = $language->id + 1;
        $response = $this->delete(LanguageTest::URL . $language_id,
            [],
            ApiHeaders::getAuth()
        );
        $this->assertNotNull(Language::find($language->id));
        $response->assertNotFound();
        $response->assertJson(Message::FAILED_DELETED);
    }
}
