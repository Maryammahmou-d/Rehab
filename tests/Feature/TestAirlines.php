<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestAirlines extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_airlines(): void
    {
        $response = $this->get('/airlines-booking');

        $response->assertStatus(200);
    }
}
