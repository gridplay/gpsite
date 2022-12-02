<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #{{ env('APP_COLOUR') }};">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ url('logo.png') }}" class="img-fluid" style="max-height: 50px;"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        @foreach(config('menu.main') as $name => $uri)
          @if (!is_array($uri))
            <li class="nav-item">
              <a class="nav-link" href="{{ url($uri) }}">{{ $name }}</a>
            </li>
          @elseif (is_array($uri))
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="dd{{ $name }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ $name }}
              </a>
              <ul class="dropdown-menu" aria-labelledby="dd{{ $name }}">
                @foreach($uri as $subname => $suburi)
                  <li><a class="dropdown-item" href="{{ url($suburi) }}">{{ $subname }}</a></li>
                @endforeach
              </ul>
            </li>
          @endif
        @endforeach
      </ul>
      <ul class="navbar-nav px-3 d-flex">
        <li class="nav-item text-nowrap">
          @if(Auth::check())
            @include('layout.auth')
          @else
            <a class="nav-link" href="{{ url('auth/login') }}">
              <span class="is-large">Login with GridPlay.net</span>
            </a>
          @endif
        </li>
      </ul>
    </div>
  </div>
</nav>