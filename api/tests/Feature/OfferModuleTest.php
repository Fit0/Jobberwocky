<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OfferModuleTest extends TestCase
{
    /**
     * @test
     */
    public function it_loads_the_offers_list()
    {
        $response = $this->get('/api/offers');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_loads_the_offer_details()
    {
        $offer = \App\Models\Offer::factory()->create();

        $response = $this->get('/api/showOffer/' . $offer->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_creates_a_new_offer()
    {
        $this->post('/api/storeOffer', [
            'name' => 'Developer Offer Insert',
            'description' => 'Description new offer',
            'remote'=>0,
            'salary'=>25000,
            'skill'  => "1,2",
            'country_id'=>2,
        ]);

        $this->assertDatabaseHas('offers', [
            'name' => 'Developer Offer Insert'
        ]);
    }

    /**
     * @test
     */
    public function it_updates_a_offer()
    {
        $offer = \App\Models\Offer::factory()->create();

        $this->put('/api/updateOffer/' . $offer->id, [
            'name' => 'Developer Offer Update',
            'description' => $offer->description,
            'remote'=>$offer->remote,
            'salary'=>$offer->salary,
            'country_id'=>$offer->country_id,
        ]);

        $this->assertDatabaseHas('offers', [
            'name' => 'Developer Offer Update'
        ]);
    }

    /**
     * @test
     */
    public function it_delete_a_offer()
    {
        $offer = \App\Models\Offer::factory()->create();

        $this->delete('/api/delOffer/' . $offer->id);

        $this->assertDatabaseMissing('offers', [
            'id' => $offer->id
        ]);
    }
}
