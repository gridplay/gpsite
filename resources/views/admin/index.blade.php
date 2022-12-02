@extends('layout.app')
@section('title', 'Admin Panel')
@section('content')
@include('admin.adminmenu')
<div class="row">
	<div class="col text-center">
		<div class="card-group">
			<div class="card">
				<a href="{{ url('admin/users') }}">
					<div class="card-body">
						<h3 class="card-subtitle">Users</h3>
						<h2 class="card-title">
							{{ number_format(App\Models\User::count()) }}
						</h2>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>
@endsection