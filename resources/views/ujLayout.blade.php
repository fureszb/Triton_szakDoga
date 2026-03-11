<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>TRITON SECURITY</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css'>
    <link rel="stylesheet" href="{{ asset('/css/layoutStyle.css') }}">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src="{{ asset('/js/layoutScript.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>
    @php
        $user = Auth::user();
        $route = request()->route()->getName();
        $userInitial = strtoupper(substr($user->name ?? 'U', 0, 1));
        $roleLabel = match($user->role) {
            'Admin' => 'Adminisztrátor',
            'Uzletkoto' => 'Üzletkötő',
            'Ugyfel' => 'Ügyfél',
            default => $user->role,
        };
    @endphp

    <div class='dashboard'>
        <div class="dashboard-nav">

            @switch($user->role)
                @case('Ugyfel')
                    <header>
                        <a class="menu-toggle" id="mobileMenu"><i class="fas fa-bars"></i></a>
                        <a href="{{ route('ugyfel.megrendelesek') }}" class="brand-logo">
                            <img src="{{ asset('logo.png') }}" alt="LOGO">
                            <span>TRITON</span>
                        </a>
                    </header>
                @break
                @default
                    <header>
                        <a class="menu-toggle" id="mobileMenu"><i class="fas fa-bars"></i></a>
                        <a href="{{ route('home.index') }}" class="brand-logo">
                            <img src="{{ asset('logo.png') }}" alt="LOGO">
                            <span>TRITON</span>
                        </a>
                    </header>
            @endswitch

            <nav class="dashboard-nav-list">
                <div class="nav-item-divider"></div>

                @switch($user->role)
                    @case('Ugyfel')
                        <a href="{{ route('profile.edit') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'profile') ? 'nav-active' : '' }}">
                            <i class="fas fa-user-circle"></i> Fiókom
                        </a>
                        <a href="{{ route('ugyfel.megrendelesek') }}"
                           class="dashboard-nav-item {{ $route === 'ugyfel.megrendelesek' ? 'nav-active' : '' }}">
                            <i class="fas fa-clipboard-check"></i> Megrendeléseim
                        </a>
                    @break

                    @case('Uzletkoto')
                        <a href="{{ route('home.index') }}"
                           class="dashboard-nav-item {{ $route === 'home.index' ? 'nav-active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Kezdőlap
                        </a>
                        <a href="{{ route('ugyfel.index') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'ugyfel') ? 'nav-active' : '' }}">
                            <i class="fas fa-user-friends"></i> Ügyfelek
                        </a>
                        <a href="{{ route('megrendeles.index') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'megrendeles') ? 'nav-active' : '' }}">
                            <i class="fas fa-clipboard-list"></i> Megrendelések
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'profile') ? 'nav-active' : '' }}">
                            <i class="fas fa-user-circle"></i> Fiókom
                        </a>
                    @break

                    @case('Admin')
                        <a href="{{ route('home.index') }}"
                           class="dashboard-nav-item {{ $route === 'home.index' ? 'nav-active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Kezdőlap
                        </a>
                        <a href="{{ route('ugyfel.index') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'ugyfel') ? 'nav-active' : '' }}">
                            <i class="fas fa-user-friends"></i> Ügyfelek
                        </a>
                        <a href="{{ route('megrendeles.index') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'megrendeles') ? 'nav-active' : '' }}">
                            <i class="fas fa-clipboard-list"></i> Megrendelések
                        </a>
                        <a href="{{ route('anyagok.index') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'anyagok') ? 'nav-active' : '' }}">
                            <i class="fas fa-boxes"></i> Anyagok
                        </a>
                        <a href="{{ route('szerelok.index') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'szerelok') ? 'nav-active' : '' }}">
                            <i class="fas fa-tools"></i> Szerelők
                        </a>
                        <a href="{{ route('users.index') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'users') ? 'nav-active' : '' }}">
                            <i class="fas fa-user-cog"></i> Felhasználók
                        </a>
                        <a href="{{ route('cegadatok.edit') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'cegadatok') ? 'nav-active' : '' }}">
                            <i class="fas fa-building"></i> Cégadatok
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'profile') ? 'nav-active' : '' }}">
                            <i class="fas fa-user-circle"></i> Fiókom
                        </a>
                    @break
                @endswitch

                <div class="nav-item-divider"></div>
                <form action="{{ route('logout') }}" method="post" class="form-delete" style="margin:2px 10px;">
                    @csrf
                    <button type="submit" class="dashboard-nav-item"
                        style="width:calc(100% - 0px);">
                        <i class="fas fa-sign-out-alt"></i> Kijelentkezés
                    </button>
                </form>
            </nav>

            <div class="sidebar-user">
                <div class="sidebar-user-avatar">{{ $userInitial }}</div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ $user->name ?? 'Felhasználó' }}</div>
                    <div class="sidebar-user-role">{{ $roleLabel }}</div>
                </div>
            </div>
        </div>

        <div class='dashboard-app'>
            <header class='dashboard-toolbar'>
                <a href="#!" class="menu-toggle-dashboard"><i class="fas fa-bars"></i></a>
                <a style="display:none;" href="#" class="notification">
                    <i class="fa-solid fa-bell"><span class="badge">3</span></i>
                </a>
                <span class="toolbar-date">
                    {{ now()->locale('hu')->isoFormat('YYYY. MMMM D., dddd') }}
                </span>
            </header>
            <div class='dashboard-content'>
                <div>
                    <div class='card'>
                        <div class='card-body'>
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
