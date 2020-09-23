@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-transparent border-0">
                <div class="card-header bg-transparent h5">
					Message
				</div>
                <div class="card-body bg-white">
					{{ session('message.text') }}
                </div>
				<div class="card-footer text-right">
					<a class="btn btn-secondary rounded-0" href="{{ session('message.a.href') }}">{{ session('message.a.text') }}</a>
				</div>
            </div>
        </div>
    </div>
</div>
@endsection
