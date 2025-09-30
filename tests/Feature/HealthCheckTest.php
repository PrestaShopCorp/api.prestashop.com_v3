<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/api/version/check_version.php');
        $response->assertStatus(200);

        $response = $this->get('/api/iframe/install.php');
        $response->assertStatus(200);
    }
}
