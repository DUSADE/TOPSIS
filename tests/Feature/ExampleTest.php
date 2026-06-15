<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        // Root redirects to dashboard
        $response->assertStatus(302);
        
        // Or check dashboard directly if we want a 200 check
        // $response = $this->get('/dashboard');
        // $response->assertStatus(200);
    }
}
