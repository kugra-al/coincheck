<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BaseTest extends TestCase
{
    /**
     * Test index page loads correctly.
     *
     * @return void
     */
    public function testPageLoad()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

}
