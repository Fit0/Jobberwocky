<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountryModuleTest extends TestCase
{
    /**
     * @test
     */
    public function it_loads_the_country_list()
    {
        $response = $this->get('/api/country');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_loads_the_country_details()
    {
        $country = \App\Models\Country::factory()->create();

        $response = $this->get('/api/showCountry/'. $country->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_creates_a_new_country()
    {
        $this->post('/api/storeCountry', [
            'name' => 'Argentina'
        ]);

        $this->assertDatabaseHas('countries', [
            'name' => 'Argentina'
        ]);
    }

    /**
     * @test
     */
    public function it_updates_a_country()
    {
        $country = \App\Models\Country::factory()->create();

        $this->put('/api/updateCountry/'. $country->id, [
            'name' => 'Argentina'
        ]);

        $this->assertDatabaseHas('countries', [
            'name' => 'Argentina'
        ]);
    }

    /**
     * @test
     */
    public function it_delete_a_country()
    {
        $country = \App\Models\Country::factory()->create();

        $this->delete('/api/delCountry/' . $country->id);

        $this->assertDatabaseMissing('countries', [
            'id' => $country->id
        ]);
    }
}
