@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-transparent border-0">
                <div class="card-header bg-transparent h5">
					{{ __('text.pizza.title') }}: {{ __('text.pizza.subTitle') }}
				</div>
                <div class="card-body bg-white">
					<a href="{{ route('pizza.new') }}" class="btn btn-secondary rounded-0">{{ __('text.pizza.button.new') }}</a>
                </div>
				<div class="card-footer bg-white p-0">
					<div class="container-fluid m-0 p-0">
						<table class="table table-hover">
							<thead class="bg-light text-secondary">
								<tr>							
									<th class="text-center">{{ __('text.pizza.data.column.id') }}</th>
									<th class="text-left">{{ __('text.pizza.data.column.name') }}</th>
									<th class="text-center">{{ __('text.pizza.data.column.price') }}</th>
									<th></th>
								</tr>
							</thead>
								@forelse ($pizzas as $pizza)
									<tr>
										<td class="text-right">{{ $pizza->id }}</td>
										<td>{{ $pizza->name }}</td>
										<td class="text-right">{{ $pizza->price + ($pizza->price/2) }}</td>
										<td class="text-right">
											<a href="{{ route('pizza.edit', ['id' => $pizza->id]) }}" class="btn btn-sm btn-secondary rounded-0">{{ __('text.pizza.button.edit') }}</a>
										</td>
									</tr>
								@empty
									<tr><td class="text-center text-muted" colspan="4">{{ __('text.pizza.data.empty') }}</td></tr> 
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