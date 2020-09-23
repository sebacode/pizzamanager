<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function __construct() {
		parent::__construct();		
        $this->middleware('auth');
    }

    public function loadList() {
		$ingredients = Ingredient::all();
        return view('ingredient.list', ['ingredients' => $ingredients]);
    }
	
    public function loadDetail(Request $request) {
		$ingredient = Ingredient::find($request->id); 
		if ( !$ingredient ) return redirect()->route('ingredient.new');
        return view('ingredient.detail', [ "isView" => "Edit", "ingredient" => $ingredient, "status_code" => $this->_status_code ]);
    }

	public function loadNew() {
		$ingredient = new Ingredient;
		$ingredient->id = 0;
        return view('ingredient.detail', [ "isView" => "New", "ingredient" => $ingredient, "status_code" => $this->_status_code ]);
	}
	
	public function save(Request $request) {
		try {
			$validator = $this->validateIngredient($request);
			if ($validator->fails()) {
				return response()->json($validator->messages()->all(), $this->_status_code->VALIDATED);
			}
			$ingredient = Ingredient::find($request->id); 
			if ( !$ingredient ) {
				$ingredient = new Ingredient;
			}
			$ingredient->name = $request->name;
			$ingredient->price = $request->price;
			Auth::user()->ingredient()->save($ingredient); 
			return response()->json(["message" => __('text.ingredient.action.save'), "id" => $ingredient->id], $this->_status_code->SAVED);
		} catch( \Exception $e ) {
			return response()->json($this->errorMessage($e->getMessage()), $this->_status_code->ERROR);
		}
	}
	
	public function delete(Request $request) {
		try {
			$ingredient = Ingredient::find($request->id);
			if ( !$ingredient ) {
				return redirect()->route('ingredient.new');
			}
			$ingredient->delete();
			return response()->json(["message" => __('text.ingredient.action.delete')], $this->_status_code->DELETED);
		} catch( \Exception $e ) {
			return response()->json($this->errorMessage($e->getMessage()), $this->_status_code->ERROR);
		}
	}

	private function validateIngredient(Request $request) {
        $rules = array( 
			'name' => 'required|string',
            'price' => 'required|numeric|min:00.00|max:99999.99|regex:/^[0-9]{1,2}(,[0-9]{2})*(\.[0-9]+)*$/'
        );
        $validator = Validator::make($request->all(), $rules);
		return $validator;
	}

}
