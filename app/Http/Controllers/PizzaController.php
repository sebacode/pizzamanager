<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Pizza;
use App\Models\Ingredient;
use App\Models\PizzaIngredient;

class PizzaController extends Controller
{
	private $_select_ingredients = array();
	
    public function __construct() {
		parent::__construct();		
        $this->middleware('auth');
		$this->_select_ingredients = Ingredient::orderBy('name', 'asc')->get();		
    }

    public function loadList() {
		//$pizzas = Pizza::all();
		$pizzas = DB::select('
			select p.id, p.name, sum(i.price) price 
			from pizzas p 
			inner join pizza_ingredients pi on pi.pizza_id = p.id 
			inner join ingredients i on i.id = pi.ingredient_id 
			group by p.id, p.name'
		);
        return view('pizza.list', ['pizzas' => $pizzas]);
    }
	
    public function loadDetail(Request $request) {			
		$pizza = Pizza::find($request->id); 
		if ( !$pizza ) {
			return redirect()->route('pizza.new');
		} 
		$ingredients = Pizza::find($request->id)->ingredients;	
		$ingredients_json = $ingredients->toJson();
        return view('pizza.detail', [ 
			"isView" => "Edit", 
			"pizza" => $pizza, 
			"ingredients_json" => $ingredients_json,			
			"select_ingredients" => $this->_select_ingredients , 
			"status_code" => $this->_status_code 
		]);
    }

	public function loadNew() {
		if ( count($this->_select_ingredients) == 0 ) {
			Session::put("message.text", __('text.ingredient.load'));
			Session::put("message.a.href", route('ingredient.new'));
			Session::put("message.a.text", __('text.ingredient.add'));
			return redirect()->route('message');
		}
		$pizza = new Pizza;
		$pizza->id = 0;
        return view('pizza.detail', [ 
			"isView" => "New", 
			"pizza" => $pizza, 
			"ingredients_json" => "[]", 
			"select_ingredients" => $this->_select_ingredients,
			"status_code" => $this->_status_code  
		]);		
	}
	
	public function save(Request $request) {
		try {
			$validator = $this->validatePizza($request);
			if ($validator->fails()) {
				return response()->json($validator->messages()->all(), $this->_status_code->VALIDATED);
			}
			$pizza = Pizza::find($request->id); 
			if ( !$pizza ) $pizza = new Pizza;
			else PizzaIngredient::where('pizza_id', $request->id)->delete();
			$pizza->name = $request->name;		
			Auth::user()->pizza()->save($pizza);
			$ingredients_id = explode(",",$request->ingredients_id);
			for( $i = 0; $i < count($ingredients_id); $i++){
				$pizzaIngredient = new PizzaIngredient;
				$pizzaIngredient->pizza_id = $pizza->id;
				$pizzaIngredient->ingredient_id = $ingredients_id[$i];
				$pizzaIngredient->save();
			}
			return response()->json(["message" => __('text.pizza.action.save'), "id" => $pizza->id], $this->_status_code->SAVED);
		} catch( \Exception $e ) {
			return response()->json($this->errorMessage($e->getMessage()), $this->_status_code->ERROR);
		}
	}

	public function delete(Request $request) {
		try {
			$pizza = Pizza::find($request->id);
			if ( !$pizza ) {
				return redirect()->route('pizza.new');
			}
			$pizza->delete();
			PizzaIngredient::where('pizza_id', $request->id)->delete();
			return response()->json(["message" => __('text.pizza.action.delete')], $this->_status_code->DELETED);
		} catch( \Exception $e ) {
			return response()->json($this->errorMessage($e->getMessage()), $this->_status_code->ERROR);
		}
	}
	
	private function validatePizza(Request $request) {
        $rules = array('name' => 'required|string');
        $validator = Validator::make($request->all(), $rules);
		return $validator;
	}
}
