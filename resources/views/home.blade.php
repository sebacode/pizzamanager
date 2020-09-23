@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-transparent border-0">
                <div class="card-header bg-transparent h5">{{ __('text.title') }}</div>
 
				<div class="card-footer bg-white p-0">

					<div class="container-fluid m-0 p-0">
						<table class="table">
							<tbody>
								<tr>
									<td><a href="{{ route('pizza.list') }}" class="btn btn-link rounded-0">{{ __('text.module.pizza.title') }}</a></td>
									<td class="text-right"><a href="{{ route('pizza.list') }}" class="btn btn-sm btn-secondary rounded-0">{{ __('text.module.access') }}</a></td>
								</tr>
								<tr>
									<td><a href="{{ route('ingredient.list') }}" class="btn btn-link rounded-0">{{ __('text.module.ingredients.title') }}</a></td>									
									<td class="text-right"><a href="{{ route('ingredient.list') }}" class="btn btn-sm btn-secondary rounded-0">{{ __('text.module.access') }}</a></td>
								</tr>										
							</tbody>
						</table>
					</div>
					
				</div>
            </div>
        </div>
    </div>
</div>
@endsection
