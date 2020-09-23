@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-transparent border-0">
                <div class="card-header bg-transparent h5"><a href="{{ route('ingredient.list') }}" class="btn btn-sm btn-secondary rounded-0">{{ __('text.ingredient.button.list') }}</a> | {{ __('text.ingredient.title') }}</div>
                <div class="card-body bg-white">
					<div class="alert alert-secondary border-0 rounded-0" id="ingredient_messages">{{ $isView }} {{ __('text.ingredient.title') }}..</div>
					<div class="mb-3">
						<label for="ingredient_name">{{ __('text.ingredient.label.name') }}</label>
						<input type="text" class="form-control" id="ingredient_name" placeholder="{{ __('text.ingredient.placeholder.name') }}" value="{{$ingredient->name}}" required="">
					</div>
					<div class="mb-3">
						<label for="ingredient_price">{{ __('text.ingredient.label.price') }}</label>
						<input type="text" class="form-control" id="ingredient_price" placeholder="{{ __('text.ingredient.placeholder.price') }}" value="{{$ingredient->price}}" required="">
					</div>
                </div>
				<div class="card-footer bg-transparent text-right">
					<a href="#" id="btnSave" class="btn btn-secondary btn-block rounded-0 mb-2" onclick="saveIngredient({{$ingredient->id}},'post'); return false;">{{ __('text.ingredient.button.save') }}</a>
					<a href="{{ route('ingredient.list') }}" class="btn btn-sm btn-secondary rounded-0">{{ __('text.ingredient.button.cancel') }}</a>
					@if ( $isView == "Edit" ) <a href="#" class="btn btn-sm btn-secondary rounded-0" onclick="deleteIngredient({{$ingredient->id}}); return false;">{{ __('text.ingredient.button.delete') }}</a> @endif
				</div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
	function saveIngredient(id, action) {	
		$.ajax({url: getUrl(action),method: action, data: getData(id), success: saveSuccess, error: saveError });
	}

	function deleteIngredient(id) {
		let ingredient_messages = $("#ingredient_messages").removeClass("alert-secondary");
		let btnDelete = "<a href=\"#\" class=\"btn btn-sm btn-danger rounded-0\" onclick=\"saveIngredient({{$ingredient->id}},\'delete\');return false;\">{{ __('text.ingredient.button.delete') }}</a>";
		let btnCancel = "<a href=\"#\" class=\"btn btn-sm btn-secondary rounded-0\" onclick=\"window.location.href=window.location.href;return false;\">{{ __('text.ingredient.button.cancel') }}</a>";		
		let html = "{{ __('text.ingredient.confirm.delete') }} " + btnCancel + " " + btnDelete + "";
		ingredient_messages.addClass("alert-danger").html(html);
	}

	function saveSuccess(data, textStatus, jqXHR ){
		if ( jqXHR.status === {{$status_code->VALIDATED}} ) $("#ingredient_messages").removeClass("alert-secondary").addClass("alert-danger").html(data.join("<br/>"));
		else {
			$(".btn").addClass("disabled");
			if ( jqXHR.status === {{$status_code->DELETED}} ) window.location.href = "{{ route('ingredient.list') }}";
			else {
				$("#ingredient_messages").removeClass("alert-secondary").removeClass("alert-danger").addClass("alert-success").html(data.message);
				setTimeout(function() { window.location.href = ("{{ route('ingredient.edit', ['id' => ':id' ]) }}").split(":id").join(data.id); }, 2000);
			}			
		}
	}
	
	function saveError(data){
		let ingredient_messages = $("#ingredient_messages").removeClass("alert-secondary");			
		ingredient_messages.addClass("alert-danger").html(data.responseJSON);
	}			
			
	function getData(id, action) {
		return {
			id: id,
			name: $("#ingredient_name").val(),  
			price: $("#ingredient_price").val(),
			 "_token": "{{ csrf_token() }}",
		};
	}
	
	function getUrl(action) {
		return (( action === "delete" ) ? "{{ route('ingredient.delete') }}" : "{{ route('ingredient.save') }}");
	}
</script>