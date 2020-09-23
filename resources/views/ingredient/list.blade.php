@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-transparent border-0">
                <div class="card-header bg-transparent h5">
					{{ __('text.ingredient.title') }}: {{ __('text.ingredient.subTitle') }}
				</div>
                <div class="card-body bg-white">
					<a href="{{ route('ingredient.new') }}" class="btn btn-secondary rounded-0">{{ __('text.ingredient.button.new') }}</a>
				</div>
				<div class="card-footer bg-white p-0">
					<div class="container-fluid m-0 p-0">
						<table class="table table-hover">
							<thead class="bg-light text-secondary">
								<tr>							
									<th class="text-center">{{ __('text.ingredient.data.column.id') }}</th>
									<th class="text-left">{{ __('text.ingredient.data.column.name') }}</th>
									<th class="text-center">{{ __('text.ingredient.data.column.price') }}</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@forelse ($ingredients as $ingredient)
									<tr>
										<td class="text-right">{{ $ingredient->id }}</td>
										<td>{{ $ingredient->name }}</td>
										<td class="text-right">{{ $ingredient->price }}</td>
										<td class="text-right">
											<a href="{{ route('ingredient.edit', ['id' => $ingredient->id]) }}" class="btn btn-sm btn-secondary rounded-0">{{ __('text.ingredient.button.edit') }}</a>
										</td>
									</tr>
								@empty
									<tr><td class="text-center text-muted" colspan="4">{{ __('text.ingredient.data.empty') }}</td></tr> 
								@endforelse										
							</tbody>
						</table>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
@endsection
