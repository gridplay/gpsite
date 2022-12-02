@extends('layout.app')
@section('title', 'Users')
@section('content')
@include('admin.adminmenu')
@if (App\Models\Admin::isAdmin())
	<h2>Users</h2>
	@if (request()->has('u') && $u = App\Models\User::find(request()->input('u')))
		<div class="card">
			<h4 class="card-header">Editing {{ $u->name }}</h4>
			<div class="card-body">
				{!! Form::open(['url' => 'admin/users', 'method' => 'PUT']) !!}
				{!! Form::hidden('uid', $u->id) !!}
				<div class="row">
					<div class="col-lg-3">
						<B>Is user admin?</B><br>
						{!! Form::select('isadmin', [0 => 'No', 1 => 'Yes'], $u->isadmin, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-success" name="act" value="save">
							<span class="icon"><i class="fas fa-folder-plus"></i></span>
							<span>Save</span>
						</button>
					</div>
					<div class="col">
						{!! Form::close() !!}
						{!! Form::open(['url' => 'admin/users', 'method' => 'DELETE']) !!}
						{!! Form::hidden('uuid', $u->slid) !!}
							<div class="buttons">
								<button type="submit" class="btn btn-danger">
									<span class="icon"><i class="fas fa-trash"></i></span>
									<span>DELETE</span>
								</button>
							</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	@else
		@php
		$s = "";
		$usersdb = App\Models\User::query();
		if (request()->has('s') && !empty(request()->input('s'))) {
			$s = request()->input('s');
			$userdb = $usersdb->where('name', 'LIKE', '%'.$s.'%');
			$userdb = $usersdb->orWhere('uuid', 'LIKE', '%'.$s.'%');
			$userdb = $usersdb->orWhere('gpid', 'LIKE', '%'.$s.'%');
			$userdb = $usersdb->orWhere('id', 'LIKE', '%'.$s.'%');
		}
		if (request()->has('o') && !empty(request()->input('o'))) {
			$o = request()->input('o');
			list($otype,$odir) = explode("-", $o);
		}else{
			$otype = "created_at";
			$odir = "desc";
		}
		$usersdb = $usersdb->orderBy($otype, $odir);
		$display = 20;
		if (request()->has('d') && !empty(request()->input('d'))) {
			$display = request()->input('d');
		}
		$usersdb = $usersdb->paginate($display);
		$paging = $usersdb->appends(request()->all())->links();
		@endphp
		<div class="card">
			<div class="card-header">
				{!! Form::open(['method' => 'GET']) !!}
		        {!! Form::text('s',$s,['class' => 'form-control', 'placeholder' => 'Search by name or UUID', 'onchange' => 'this.form.submit();']) !!}
		        {!! Form::close() !!}
		    </div>
		    <div class="card-body">
				{!! $paging !!}
				<table class="table">
					<thead>
						<tr>
							<th scope="col">Name / Joined</th>
							<th scope="col">ID / SL UUID</th>
							<th scope="col" class="text-center">Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach($usersdb as $u)
							<tr>
								<th scope="row"><a href="{{ url('u/'.str_replace(" ",".",$u->name)) }}">{{ $u->name }}</a><br>
									<small>{{ App\Models\Core::readabletimestamp($u->created_at) }}</small>
								</th>
								<td>{{ $u->id }}<br><small>{{ $u->uuid }}</small></td>
								<td class="text-center">
									<a href="{{ url('admin/users?u='.$u->id) }}" class="btn btn-primary">
									<i class="bi bi-pencil"></i></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{!! $paging !!}
			</div>
		</div>
	@endif
@endif
@endsection
