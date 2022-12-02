<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="dduser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    {{ Auth::user()->name }}
  </a>
  <ul class="dropdown-menu" aria-labelledby="dduser">
    @foreach(config('menu.auth') as $an => $au)
      <li><a class="dropdown-item" href="{{ url($au) }}">{{ $an }}</a></li>
    @endforeach
    @if (App\Models\Admin::isAdmin())
      <li><a class="dropdown-item" href="{{ url('admin') }}">Admin</a></li>
    @endif
    <li><a class="dropdown-item" href="{{ url('auth/logout') }}">Logout</a></li>
  </ul>
</li>