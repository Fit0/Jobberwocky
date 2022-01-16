<?php

namespace Tests\Feature;

use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SkillsModuleTest extends TestCase
{
    /**
     * @test
     */
    public function it_loads_the_skills_list()
    {
        $response = $this->get('/api/skills');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_loads_the_skill_details()
    {
        $skill = \App\Models\Skill::factory()->create();

        $response = $this->get('/api/showSkill/' . $skill->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_creates_a_new_skill()
    {
        $this->post('/api/storeSkill', [
            'name' => 'new skill'
        ]);

        $this->assertDatabaseHas('skills', [
            'name' => 'new skill'
        ]);
    }

    /**
     * @test
     */
    public function it_updates_a_skill()
    {
        $skill = \App\Models\Skill::factory()->create();

        $this->put('/api/updateSkill/'. $skill->id, [
            'name' => 'new skill update'
        ]);

        $this->assertDatabaseHas('skills', [
            'name' => 'new skill update'
        ]);
    }

    /**
     * @test
     */
    public function it_delete_a_skill()
    {
        $skill = \App\Models\Skill::factory()->create();

        $this->delete('/api/delSkill/' . $skill->id);

        $this->assertDatabaseMissing('skills', [
            'id' => $skill->id
        ]);
    }
}
