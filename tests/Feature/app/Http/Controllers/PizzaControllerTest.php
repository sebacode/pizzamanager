<?php

namespace Tests\Feature\app\Http\Controllers;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class PizzaControllerTest extends TestCase
{
	use WithoutMiddleware; 
	
    public function testSaveOK() {	
		$user = User::find(2);

		$response = $this->actingAs($user)
			->json('POST', route('pizza.save'), [
				'id' => 0,
				'name' => 'Pizza X',
				'ingredients_id' => '28,29'
			]);
		$this->assertEquals(201,$response->getStatusCode());
    }

    public function testDeleted() {	
		$user = User::find(2);

		$response = $this->actingAs($user)
			->json('DELETE', route('pizza.delete'), [
				'id' => 9
			]);
		$this->assertEquals(204,$response->getStatusCode());
    }

}
