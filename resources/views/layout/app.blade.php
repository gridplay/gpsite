<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:title" content="@yield('title') | {{ env('APP_NAME') }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:image" content="{{ url('favicon.png') }}" />
    <meta property="og:description" content="{{ env('APP_DESC') }}" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta name="description" content="{{ env('APP_DESC') }}" />
    <meta name="keywords" content="virtual worlds, second life" />
    <meta name="author" content="chrisx84@live.ca" />
    <meta name="theme-color" content="#{{ env('APP_COLOUR') }}">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#{{ env('APP_COLOUR') }}">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#{{ env('APP_COLOUR') }}">
    <!-- icons -->
    <link rel="apple-touch-icon" href="{{ url('favicon.png') }}" />
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}" />

    <title>@yield('title') | {{ env('APP_NAME') }}</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ url('app.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />
    <script src="https://cdn.tiny.cloud/1/nxuzzjpdz5k2fiopzrje6qzv91ax922tt7o3ipv84o4tvh7e/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body>
    <header class="">
        @include('layout.navbar')
    </header>
    <div class="container">
      @yield('content')
    </div>
    @include('layout.footer')
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js" data-cfasync="false"></script>
<script>
window.cookieconsent.initialise({
  "palette": {
    "popup": {
      "background": "#252e39"
    },
    "button": {
      "background": "#14a7d0"
    }
  },
  "position": "bottom-left"
});
tinymce.init({
    selector: 'textarea#htmleditor',
    skin: "oxide-dark",
    content_css: "dark",
    height: 300,
    plugins: "image, lists advlist, anchor, autolink, autosave, charmap, code, link, imagetools, insertdatetime, media, table, preview, toc, wordcount, lists",
    menubar: "insert table view",
    quickbars_insert_toolbar: true,
    image_caption: true
});
</script>
  </body>
</html>