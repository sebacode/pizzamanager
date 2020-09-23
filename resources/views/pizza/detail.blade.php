@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-transparent border-0">
                <div class="card-header bg-transparent h5">
					<a href="{{ route('pizza.list') }}" class="btn btn-sm btn-secondary rounded-0">{{__('text.pizza.button.list')}}</a> | {{__('text.pizza.title')}}: {{ $isView }}
				</div>
                <div class="card-body bg-white">
					<div class="alert alert-secondary border-0 rounded-0" id="pizza_messages">{{ $isView }} {{ __('text.pizza.title') }}..</div>
					<div class="mb-3">
						<label for="address">{{__('text.pizza.name.label')}}</label>
						<input type="text" class="form-control" id="pizza_name" placeholder="{{__('text.pizza.name.placeholder')}}" value="{{$pizza->name}}" required="">
					</div>
					<div class="mb-3">
						<label for="address">{{__('text.pizza.label.ingredient')}} <sup class="text-secondary">(*)</sup></label>
						<select class="form-control" id="select_ingredient">
							<option value="0">{{__('text.pizza.ingredient.select')}}..</option>
							@foreach ($select_ingredients as $ingredient)
								<option value="{{ $ingredient->id }}" data-name="{{ $ingredient->name }}" data-price="{{ $ingredient->price }}">{{ $ingredient->name }} ({{ $ingredient->price }})</option>
							@endforeach
						</select>
						<div class="row mt-2">
							<div class="col-6">
								<a href="#" class="btn btn-sm btn-secondary rounded-0" onclick="addIngredient();return false;" >{{__('text.pizza.ingredient.btn.add')}}</a>
								<small class="text-danger" id="add_message"></small>
							</div>
							<div class="col-6 text-right">
								<a href="" class="btn btn-sm bg-warning text-dark rounded-0">{{__('text.pizza.price.msg')}} : <strong><span id="price">0.00</span></strong></a>
							</div>
						</div>
						<table class="table table-hover mt-2">
							<thead class="bg-light text-secondary">
								<tr>							
									<th class="text-center">{{__('text.pizza.ingredient.data.column.order')}}</th>
									<th class="text-left">{{__('text.pizza.ingredient.data.column.name')}}</th>
									<th class="text-center">{{__('text.pizza.ingredient.data.column.price')}}</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="ingredients_list">
								<tr>
									<td class="text-center text-muted" colspan="4">
										@if ( $isView == "Edit" ) 
											{{__('text.ingredient.data.loading')}}
										@else 
											 {{__('text.ingredient.data.empty')}}
										@endif
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div>
						<div class="alert alert-light m-0 p-0 pt-2">
							<sup>(*) {{__('text.pizza.ingredient.save.msg')}}</sup>
						</div>
					</div>
                </div>
				<div class="card-footer bg-transparent text-right">
					<a href="#" id="btnSave" class="btn btn-secondary btn-block rounded-0 mb-2" onclick="savePizza({{$pizza->id}},'post'); return false;">{{ __('text.pizza.button.save') }}</a>
					<a href="{{ route('pizza.list') }}" class="btn btn-sm btn-secondary rounded-0">{{ __('text.pizza.button.cancel') }}</a>
					@if ( $isView == "Edit" ) <a href="#" class="btn btn-sm btn-secondary rounded-0" onclick="deletePizza({{$pizza->id}}); return false;">{{ __('text.pizza.button.delete') }}</a> @endif
					
				</div>
            </div>
        </div>
    </div>
</div>
@endsection


<script>
	let ingredients = [];
	
	setTimeout(function() {
		$(function() {
			ingredients = {!! $ingredients_json !!};
			renderIngredients();
		});
	},1000);
	
	function savePizza(id, action) {	
		$.ajax({url: getUrl(action),method: action, data: getData(id), success: saveSuccess, error: saveError });
	}
	
	function saveSuccess(data, textStatus, jqXHR ){
		if ( jqXHR.status === {{$status_code->VALIDATED}} ) $("#pizza_messages").removeClass("alert-secondary").addClass("alert-danger").html(data.join("<br/>"));
		else {
			$(".btn").addClass("disabled");
			if ( jqXHR.status === {{$status_code->DELETED}} ) window.location.href = "{{ route('pizza.list') }}";
			else {
				$("#pizza_messages").removeClass("alert-secondary").removeClass("alert-danger").addClass("alert-success").html(data.message);
				setTimeout(function() { window.location.href = ("{{ route('pizza.edit', ['id' => ':id' ]) }}").split(":id").join(data.id); }, 2000);
			}			
		}
	}
	
	function deletePizza(id) {
		let pizza_messages = $("#pizza_messages").removeClass("alert-secondary");
		let btnDelete = "<a href=\"#\" class=\"btn btn-sm btn-danger rounded-0\" onclick=\"savePizza({{$pizza->id}},\'delete\');return false;\">{{ __('text.pizza.button.delete') }}</a>";
		let btnCancel = "<a href=\"#\" class=\"btn btn-sm btn-secondary rounded-0\" onclick=\"window.location.href=window.location.href;return false;\">{{ __('text.pizza.button.cancel') }}</a>";		
		let html = "{{ __('text.pizza.confirm.delete') }} " + btnCancel + " " + btnDelete + "";
		pizza_messages.addClass("alert-danger").html(html);
	}
	
	function addIngredient() {
		$("#add_message").html("");
		let sel_id = $("#select_ingredient").val();
		if ( existIngredientById(sel_id) || sel_id == "0" ) { 
			$("#add_message").html( sel_id == "0" ? "{{ __('text.pizza.ingredient.select_0') }}" : "{{ __('text.pizza.ingredient.exist') }}");
		}
		else {
			let sel_name = $('#select_ingredient option:selected').attr('data-name');
			let sel_price = $('#select_ingredient option:selected').attr('data-price');
			let ingredient = {id: sel_id, name: sel_name, price: sel_price, order: 0};
			ingredients.push(ingredient);
			renderIngredients();
		}
	}

	function quitIngredient(id) {
		ingredients = ingredients.filter(function(el) { return el.id != id; });
		renderIngredients();
	}

	function renderIngredients() {
		let html = '';
		let pizza_cost = 0;
		let pizza_price = 0;
		let ingredientsLen = ingredients.length;
		for(let i = 0; i < ingredientsLen; i++ ) {
			ingredients[i].order = (i + 1);
			html += '<tr>';
			html += '	<td class="text-right">' + ingredients[i].order;
			html += ' 		<a href="#" onclick="order('+i+',1);return false;" class="btn btn-sm btn-secondary rounded-0 ' + ( (i == (ingredientsLen - 1) ) ? "disabled" : "" ) + '">+</a> ';
			html += ' 		<a href="#" onclick="order('+i+',0);return false;" class="btn btn-sm btn-secondary rounded-0 ' + ( (i == 0) ? "disabled" : "" ) + '">-</a>';
			html += '	</td>';
			html += '	<td class="text-left" >' + ingredients[i].name + '</td>';
			html += '	<td class="text-right">' + ingredients[i].price + '</td>';
			html += '	<td class="text-right">';
			html += '		<a href="#" onclick="quitIngredient('+ingredients[i].id+');return false;" class="btn btn-sm btn-secondary rounded-0">Quit</a>';
			html += '	</td>';
			html += '</tr>';
			pizza_cost += parseFloat(ingredients[i].price);
		}
		if ( ingredientsLen == 0 )
			html = "<tr><td class=\"text-center text-muted\" colspan=\"4\">{{__('text.ingredient.data.empty')}}</td></tr>";
		$("#ingredients_list").html(html);
		pizza_price = pizza_cost + (pizza_cost / 2);
		$("#price").html(pizza_price.toFixed(2));
	}
	
	function order(index,val) {
		let rpl_index = ( val == 0 ) ? ( index - 1 ) : ( index + 1 );
		let sel_ingredient = ingredients[index];
		let rpl_ingredient = ingredients[rpl_index];
		ingredients[rpl_index] = sel_ingredient;
		ingredients[index] = rpl_ingredient;
		renderIngredients();
	}
	
	function existIngredientById(value) {
		for (let i=0; i < ingredients.length; i++) {
			if (ingredients[i].id == value) {
				return true;
			}
		}
		return false;
	}
	
	function saveError(data){
		let pizza_messages = $("#pizza_messages").removeClass("alert-secondary");			
		pizza_messages.addClass("alert-danger").html(data.responseJSON);
	}			
			
	function getData(id, action) {
		let ingredients_id = ingredients.map(function(el){return el.id});
		ingredients_id = ingredients_id.join(",");
		return {
			id: id,
			name: $("#pizza_name").val(),  
			ingredients_id: ingredients_id,
			 "_token": "{{ csrf_token() }}",
		};
	}
	
	function getUrl(action) {
		return (( action === "delete" ) ? "{{ route('pizza.delete') }}" : "{{ route('pizza.save') }}");
	}

</script>