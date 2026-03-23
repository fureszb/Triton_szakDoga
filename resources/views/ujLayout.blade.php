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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('/css/layoutStyle.css') }}">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <style>
        /* ── Select2 varos stílus ── */
        .select2-container--default .select2-selection--single {
            height: 38px; border: 1.5px solid #e2e8f0; border-radius: 8px;
            font-size: 13px; font-family: inherit;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px; color: #334155; padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #c9a97a; box-shadow: 0 0 0 3px rgba(201,169,122,0.15);
        }
        .select2-container { width: 100% !important; }
        .select2-dropdown { border: 1.5px solid #c9a97a; border-radius: 8px; font-size: 13px; }
        .select2-results__option--highlighted {
            background-color: #c9a97a !important;
        }
        /* ── Új város panel ── */
        .varos-ujvaros-panel {
            margin-top: 6px; padding: 12px 14px;
            background: rgba(201,169,122,0.06);
            border: 1px dashed rgba(201,169,122,0.4);
            border-radius: 8px;
        }
        .varos-ujvaros-row {
            display: flex; gap: 8px; align-items: flex-end; flex-wrap: wrap;
        }
        .varos-ujvaros-panel .f-input,
        .varos-ujvaros-panel .adat-edit-input {
            flex: 1; min-width: 80px;
        }
        .varos-ujvaros-panel .varos-uj-hiba {
            font-size: 11px; color: #dc2626; margin-top: 4px;
        }
        .varos-ujvaros-panel .btn-primary {
            background: #c9a97a; border-color: #c9a97a; color: #fff;
            font-size: 12px; font-weight: 600; white-space: nowrap;
        }
        .varos-ujvaros-panel .btn-primary:hover { background: #b8935f; border-color: #b8935f; }
    </style>
    <script>
    // ── Városválasztó Select2 + gyors hozzáadás ─────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        initVarosSelects();
    });

    function initVarosSelects() {
        $('.varos-select').each(function () {
            var $sel   = $(this);
            if ($sel.data('select2')) return; // már inicializálva
            var selId  = $sel.attr('id');
            var $panel = $('[data-for="' + selId + '"]');

            $sel.select2({
                placeholder: '— Keressen irányítószámra vagy városra —',
                allowClear: true,
                language: {
                    noResults: function() {
                        // Kattintható link a quick-add panel nyitásához
                        var $link = $('<a href="#" style="color:#c9a97a;text-decoration:underline;font-weight:600;">adja hozzá manuálisan</a>');
                        $link.on('mousedown', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            $sel.select2('close');
                            $panel.show();
                            $panel.find('.varos-uj-irsz').val('').focus();
                        });
                        return $('<span>Nincs találat — </span>').append($link).append('!');
                    },
                    searching: function() { return 'Keresés…'; }
                }
            });
        });
    }

    // Mentés gomb — event delegation
    $(document).on('click', '.varos-uj-mentes', function() {
        var $btn   = $(this);
        var $panel = $btn.closest('.varos-ujvaros-panel');
        var selId  = $panel.data('for');
        var $sel   = $('#' + selId);
        var irsz   = $panel.find('.varos-uj-irsz').val().trim();
        var nev    = $panel.find('.varos-uj-nev').val().trim();
        var $err   = $panel.find('.varos-uj-hiba');

        $err.hide();
        if (!irsz || !nev) {
            $err.text('Irányítószám és városnév megadása kötelező.').show();
            return;
        }
        if (!/^\d{4}$/.test(irsz)) {
            $err.text('Az irányítószám pontosan 4 számjegyű kell legyen.').show();
            return;
        }

        $btn.prop('disabled', true).text('Mentés…');
        $.ajax({
            url: '/varos',
            method: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                Irny_szam: parseInt(irsz, 10),
                nev: nev
            },
            success: function(resp) {
                var label  = resp.Irny_szam + ' ' + resp.nev;
                var option = new Option(label, resp.id, true, true);
                $sel.append(option).trigger('change');
                $panel.hide();
                $panel.find('.varos-uj-irsz, .varos-uj-nev').val('');
            },
            error: function(xhr) {
                var msg = 'Hiba történt.';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
                    } else if (xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                }
                $err.text(msg).show();
            },
            complete: function() {
                $btn.prop('disabled', false).text('Mentés');
            }
        });
    });

    // Mégse gomb — event delegation
    $(document).on('click', '.varos-uj-megsem', function() {
        var $panel = $(this).closest('.varos-ujvaros-panel');
        $panel.hide();
        $panel.find('.varos-uj-irsz, .varos-uj-nev').val('');
        $panel.find('.varos-uj-hiba').hide();
    });
    </script>
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
            left: 0;
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

                {{-- Értesítési csengő – Admin / Uzletkoto --}}
                @if(in_array($user->role, ['Admin', 'Uzletkoto']))
                    @php
                        $adminNotifDb = Auth::user()->unreadNotifications()->count();
                        $adminNotifLista = Auth::user()->unreadNotifications()->latest()->take(5)->get();
                    @endphp
                    <div class="topbar-notif-wrap" id="adminNotifWrap">
                        <button type="button"
                                class="topbar-notif-btn {{ $adminNotifDb > 0 ? 'has-notif' : '' }}"
                                id="adminNotifBtn"
                                title="{{ $adminNotifDb > 0 ? $adminNotifDb . ' olvasatlan értesítés' : 'Nincs új értesítés' }}"
                                onclick="toggleAdminNotif(event)">
                            <i class="fas fa-bell"></i>
                            @if($adminNotifDb > 0)
                                <span class="notif-badge">{{ $adminNotifDb > 9 ? '9+' : $adminNotifDb }}</span>
                            @endif
                        </button>
                        <div class="notif-dropdown" id="adminNotifDropdown">
                            <div class="notif-dropdown-header">
                                <i class="fas fa-bell"></i> Átutalás bejelentések
                                @if($adminNotifDb > 0)
                                    <span style="margin-left:auto;font-size:10px;opacity:0.7;cursor:pointer;"
                                          onclick="markAllRead()">Összes olvasott</span>
                                @endif
                            </div>
                            @forelse($adminNotifLista as $notif)
                                @php $d = $notif->data; @endphp
                                <a href="{{ $d['url'] ?? route('fizetes.index') }}"
                                   class="notif-item"
                                   onclick="markOneRead('{{ $notif->id }}')">
                                    <div class="notif-item-icon">🏦</div>
                                    <div class="notif-item-body">
                                        <div class="notif-item-title">{{ $d['ugyfel_nev'] ?? '—' }} átutalást jelentett be</div>
                                        <div class="notif-item-sub">
                                            {{ number_format($d['osszeg'] ?? 0, 0, ',', ' ') }} Ft
                                            · {{ $d['megrendeles_nev'] ?? '' }}
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div style="padding:16px;text-align:center;font-size:12px;color:#94a3b8;">
                                    <i class="fas fa-check-circle" style="margin-right:5px;color:#22c55e;"></i>Nincs új értesítés
                                </div>
                            @endforelse
                            <div class="notif-dropdown-footer">
                                <a href="{{ route('fizetes.index') }}">Összes fizetés megtekintése →</a>
                            </div>
                        </div>
                    </div>
                    <script>
                    function toggleAdminNotif(e) {
                        e.stopPropagation();
                        document.getElementById('adminNotifDropdown').classList.toggle('open');
                    }
                    document.addEventListener('click', function() {
                        document.getElementById('adminNotifDropdown')?.classList.remove('open');
                    });
                    function markAllRead() {
                        fetch('{{ route('notifications.markRead') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                        }).then(() => window.location.reload());
                    }
                    function markOneRead(id) {
                        fetch('{{ route('notifications.markRead') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: id })
                        });
                    }
                    </script>
                @endif

                {{-- Értesítési csengő – csak Ügyfél szerepkörnél --}}
                @if($user->role === 'Ugyfel')
                    @php
                        $notifUgyfel = Auth::user()->ugyfel;
                        $dijbekeroDb = $notifUgyfel
                            ? \App\Models\Szamla::where('ugyfel_id', $notifUgyfel->id)
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
                            <div class="profile-banner-name">{{ $ugyfelProfil->nev ?? $user->name }}</div>
                            <div class="profile-banner-sub">Ügyfelszám: {{ $ugyfelProfil->id ?? '—' }}</div>
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
