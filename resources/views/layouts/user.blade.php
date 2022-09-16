<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/layout.css')}}">
    <link rel="stylesheet" href="{{asset('/css/images.css')}}">
    @yield('css')
    <title>{{ config('title', 'Chat app') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="https://kit.fontawesome.com/3b6ca7993a.js" crossorigin="anonymous"></script>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @php
    $auth_user = auth()->user();
    @endphp
</head>
<body id="body-pd">
<header class="header" id="header">
    <div class="header_toggle"><i class="fa-solid fa-bars" id="header-toggle"></i></div>
</header>
<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div>
            <a class="nav_logo"> <i class="fa-solid fa-comments"></i> <span
                    class="nav_logo-name">Chat app</span>
            </a>
            <div class="nav_list">
                <a href="{{route('home')}}" class="nav_link @if(request()->is('home')) active @endif">
                    <i class="fa-solid fa-house"></i>
                    <span class="nav_name">Home</span>
                </a>
                <a href="{{route('get_friends')}}" class="nav_link @if(request()->is('friendships/friends')) active @endif">
                    <i class="fa-solid fa-user"></i>
                    <span class="nav_name">Friends</span>
                </a>
                <a href="{{{route('get_friend_requests')}}}" class="nav_link @if(request()->is('friendships/friend-requests')) active @endif">
                    <i class="fa-solid fa-user-plus"></i>
                    <span class="nav_name">Friend requests</span>
                </a>
                <a href="{{route('conversations.index')}}" class="nav_link @if(request()->is('conversations*')) active @endif">
                    <i class="fa-solid fa-message"></i>
                    <span class="nav_name">Conversations</span>
                </a>
                <a href="{{route('users.show',["user"=>auth()->user()])}}" class="nav_link @if(request()->is("users/$auth_user->id")) active @endif ">
                    <div class="circular--profile--icon">
                        <img src="{{$auth_user->profile_picture}}" alt="user"
                        >
                    </div>
                    <span class="nav_name">Profile</span>
                </a>
                <a class="nav_link"
                   href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();"><i class="fa-solid fa-right-from-bracket"></i>
                    {{ __('Logout') }}> </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

    </nav>
</div>
<main class="py-4 mt-5">
    @yield('content')
</main>
</div>

<script src="{{ asset('js/layout.js') }}"></script>
@yield('scripts')
</body>
</html>
