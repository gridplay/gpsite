<div class="card card-body">
	<ul class="nav justify-content-center">
		@foreach(App\Models\Admin::$menu as $page => $name)
			<li class="nav-item">
				<a class="nav-link" href="{{ url('admin/'.$page) }}">{{ $name }}</a>
			</li>
		@endforeach
	</ul>
</div>