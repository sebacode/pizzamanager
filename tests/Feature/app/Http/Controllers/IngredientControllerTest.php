<?php

namespace Tests\Feature\app\Http\Controllers;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class IngredientControllerTest extends TestCase
{
	use WithoutMiddleware; 
	
    public function testSaveOK() {	
		$user = User::find(2);

		$response = $this->actingAs($user)
			->json('POST', route('ingredient.save'), [
				'name' => 'Tomato',
				'price' => 1.50
			]);
		$this->assertEquals(201,$response->getStatusCode());
    }
	
    public function testSaveValidated() {	
		$user = User::find(2);

		$response = $this->actingAs($user)
			->json('POST', route('ingredient.save'), [
				'name' => '',			
				'price' => '1.20'
			]);
		$this->assertEquals(202,$response->getStatusCode());
    }
	
    public function testDeleted() {	
		$user = User::find(2);

		$response = $this->actingAs($user)
			->json('DELETE', route('ingredient.delete'), [
				'id' => 41
			]);
		$this->assertEquals(204,$response->getStatusCode());
    }
}
