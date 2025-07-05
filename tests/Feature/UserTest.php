<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_User_index_returns_success()
    {
        $response = $this->getJson('/api/user');
        $response->assertStatus(200);
    }
}
