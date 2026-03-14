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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.querySelector('.topbar-notif-btn');
            const dropdown = document.getElementById('notifDropdown');
            if (btn && dropdown) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    dropdown.classList.toggle('open');
                });
                document.addEventListener('click', function (e) {
                    if (!e.target.closest('#notifWrap')) {
                        dropdown.classList.remove('open');
                    }
                });
            }
        });
    </script>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        /* ── Értesítési csengő (Ügyfél topbar) ── */
        .topbar-notif-wrap {
            position: relative;
            margin-right: 12px;
        }
        .topbar-notif-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            color: #94a3b8;
            font-size: 16px;
            text-decoration: none;
            position: relative;
            transition: background 0.2s, color 0.2s;
            cursor: pointer;
        }
        .topbar-notif-btn:hover,
        .topbar-notif-btn.has-notif {
            background: rgba(201,169,122,0.15);
            color: #c9a97a;
        }
        .topbar-notif-btn.has-notif {
            animation: bellRing 2.5s ease-in-out infinite;
        }
        @keyframes bellRing {
            0%,100% { transform: rotate(0deg); }
            10%      { transform: rotate(14deg); }
            20%      { transform: rotate(-10deg); }
            30%      { transform: rotate(8deg); }
            40%      { transform: rotate(-5deg); }
            50%      { transform: rotate(3deg); }
            60%      { transform: rotate(0deg); }
        }
        .notif-badge {
            position: absolute;
            top: 3px;
            right: 3px;
            background: #dc2626;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            min-width: 16px;
            height: 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            border: 2px solid #1e293b;
            line-height: 1;
        }
        /* Dropdown panel */
        .notif-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 300px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            border: 1px solid #e2e8f0;
            z-index: 9999;
            overflow: hidden;
        }
        .notif-dropdown.open { display: block; }
        .notif-dropdown-header {
            background: #1e293b;
            color: #c9a97a;
            font-size: 12px;
            font-weight: 700;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 7px;
            letter-spacing: 0.5px;
        }
        .notif-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            text-decoration: none;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
        }
        .notif-item:hover { background: #fdf6ee; }
        .notif-item-icon {
            font-size: 22px;
            flex-shrink: 0;
        }
        .notif-item-title {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }
        .notif-item-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }
        .notif-dropdown-footer {
            padding: 9px 14px;
            text-align: right;
            background: #f8fafc;
        }
        .notif-dropdown-footer a {
            font-size: 12px;
            color: #c9a97a;
            font-weight: 600;
            text-decoration: none;
        }
        .notif-dropdown-footer a:hover { text-decoration: underline; }
    </style>
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
                        <a href="{{ route('ugyfel.megrendelesek') }}"
                           class="dashboard-nav-item {{ $route === 'ugyfel.megrendelesek' ? 'nav-active' : '' }}">
                            <i class="fas fa-th-large"></i> Megrendeléseim
                        </a>
                        <a href="{{ route('ugyfel.szamlak') }}"
                           class="dashboard-nav-item {{ $route === 'ugyfel.szamlak' ? 'nav-active' : '' }}">
                            <i class="fas fa-file-invoice"></i> Számlák
                        </a>
                        <a href="{{ route('ugyfel.adataim') }}"
                           class="dashboard-nav-item {{ $route === 'ugyfel.adataim' ? 'nav-active' : '' }}">
                            <i class="fas fa-user"></i> Adataim
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'profile') ? 'nav-active' : '' }}">
                            <i class="fas fa-shield-alt"></i> Fiókom
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
                        <a href="{{ route('szamlak.index') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'szamlak') ? 'nav-active' : '' }}">
                            <i class="fas fa-file-invoice-dollar"></i> Számlák
                        </a>
                        <a href="{{ route('fizetes.index') }}"
                           class="dashboard-nav-item {{ $route === 'fizetes.index' ? 'nav-active' : '' }}">
                            <i class="fas fa-coins"></i> Fizetések
                        </a>
                        <a href="{{ route('emlekeztetok.index') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'emlekeztetok') ? 'nav-active' : '' }}">
                            <i class="fas fa-bell"></i> Emlékeztetők
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
                        <a href="{{ route('szamlak.index') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'szamlak') ? 'nav-active' : '' }}">
                            <i class="fas fa-file-invoice-dollar"></i> Számlák
                        </a>
                        <a href="{{ route('fizetes.index') }}"
                           class="dashboard-nav-item {{ $route === 'fizetes.index' ? 'nav-active' : '' }}">
                            <i class="fas fa-coins"></i> Fizetések
                        </a>
                        <a href="{{ route('emlekeztetok.index') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'emlekeztetok') ? 'nav-active' : '' }}">
                            <i class="fas fa-bell"></i> Emlékeztetők
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
                        <a href="{{ route('beallitasok.index') }}"
                           class="dashboard-nav-item {{ $route === 'beallitasok.index' ? 'nav-active' : '' }}">
                            <i class="fas fa-sliders-h"></i> Beállítások
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="dashboard-nav-item {{ str_starts_with($route, 'profile') ? 'nav-active' : '' }}">
                            <i class="fas fa-user-circle"></i> Fiókom
                        </a>
                    @break
                @endswitch

                <div class="nav-item-divider"></div>
                <form action="{{ route('logout') }}" method="post" class="form-delete" style="margin:2px 8px;">
                    @csrf
                    <button type="submit" class="dashboard-nav-item"
                        style="width:calc(100% - 0px);">
                        <i class="fas fa-sign-out-alt"></i> Kilépés
                    </button>
                </form>
            </nav>
        </div>

        <div class='dashboard-app'>
            <header class='dashboard-toolbar'>
                <a href="#!" class="menu-toggle-dashboard"><i class="fas fa-bars"></i></a>

                {{-- Értesítési csengő – csak Ügyfél szerepkörnél --}}
                @if($user->role === 'Ugyfel')
                    @php
                        $notifUgyfel = Auth::user()->ugyfel;
                        $dijbekeroDb = $notifUgyfel
                            ? \App\Models\Szamla::where('ugyfel_id', $notifUgyfel->Ugyfel_ID)
                                ->where('szamla_tipus', 'dijbekero')
                                ->whereIn('statusz', ['fuggoben', 'kesedelmes'])
                                ->count()
                            : 0;
                    @endphp
                    <div class="topbar-notif-wrap" id="notifWrap">
                        <a href="{{ route('ugyfel.szamlak') }}#dijbekero-section"
                           class="topbar-notif-btn {{ $dijbekeroDb > 0 ? 'has-notif' : '' }}"
                           title="{{ $dijbekeroDb > 0 ? $dijbekeroDb . ' függőben lévő díjbekérő' : 'Nincs új értesítés' }}">
                            <i class="fas fa-bell"></i>
                            @if($dijbekeroDb > 0)
                                <span class="notif-badge">{{ $dijbekeroDb > 9 ? '9+' : $dijbekeroDb }}</span>
                            @endif
                        </a>
                        {{-- Tooltip panel --}}
                        @if($dijbekeroDb > 0)
                        <div class="notif-dropdown" id="notifDropdown">
                            <div class="notif-dropdown-header">
                                <i class="fas fa-bell"></i> Fizetési értesítések
                            </div>
                            <a href="{{ route('ugyfel.szamlak') }}#dijbekero-section" class="notif-item">
                                <div class="notif-item-icon">💳</div>
                                <div class="notif-item-body">
                                    <div class="notif-item-title">{{ $dijbekeroDb }} függőben lévő díjbekérő</div>
                                    <div class="notif-item-sub">Kattintson a megtekintéshez és fizetéshez</div>
                                </div>
                            </a>
                            <div class="notif-dropdown-footer">
                                <a href="{{ route('ugyfel.szamlak') }}">Összes megtekintése →</a>
                            </div>
                        </div>
                        @endif
                    </div>
                @endif

                <div class="topbar-user">
                    <div class="topbar-user-info">
                        <div class="topbar-user-name">{{ $user->name ?? 'Felhasználó' }}</div>
                        <div class="topbar-user-role">{{ $roleLabel }}</div>
                    </div>
                    <div class="topbar-user-avatar">{{ $userInitial }}</div>
                </div>
            </header>

            {{-- Ugyfel profil banner (Netfone stílusú) --}}
            @if($user->role === 'Ugyfel')
                @php
                    $ugyfelProfil = Auth::user()->ugyfel;
                    $osszesMegr  = $ugyfelProfil ? $ugyfelProfil->megrendelesek->count() : 0;
                    $aktivMegr   = $ugyfelProfil ? $ugyfelProfil->megrendelesek->where('Statusz', 1)->count() : 0;
                    $befejMegr   = $ugyfelProfil ? $ugyfelProfil->megrendelesek->where('Statusz', 0)->count() : 0;
                @endphp
                <div class="profile-banner">
                    <div class="profile-banner-hero"></div>
                    <div class="profile-banner-body">
                        <div class="profile-banner-avatar">{{ $userInitial }}</div>
                        <div class="profile-banner-info">
                            <div class="profile-banner-name">{{ $ugyfelProfil->Nev ?? $user->name }}</div>
                            <div class="profile-banner-sub">Ügyfelszám: {{ $ugyfelProfil->Ugyfel_ID ?? '—' }}</div>
                        </div>
                        <div class="profile-banner-stats">
                            <div class="profile-stat">
                                <div class="profile-stat-val">{{ $osszesMegr }}</div>
                                <div class="profile-stat-lbl">Összes</div>
                            </div>
                            <div class="profile-banner-divider"></div>
                            <div class="profile-stat">
                                <div class="profile-stat-val">{{ $aktivMegr }}</div>
                                <div class="profile-stat-lbl">Aktív</div>
                            </div>
                            <div class="profile-banner-divider"></div>
                            <div class="profile-stat">
                                <div class="profile-stat-val">{{ $befejMegr }}</div>
                                <div class="profile-stat-lbl">Befejezett</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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
