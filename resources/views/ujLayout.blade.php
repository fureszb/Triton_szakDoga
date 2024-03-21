<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>TRITON SECURITY</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css'>
    <link rel="stylesheet" href="{{ asset('/css/layoutStyle.css') }}">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src="{{ asset('/js/layoutScript.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('style.css') }}">


</head>

<body>

    <div class='dashboard'>
        <div class="dashboard-nav">
            <header><a class="menu-toggle" id="mobileMenu"><i class="fas fa-bars"></i></a><a
                    href="{{ route('home.index') }}" class="brand-logo"><img src="{{ asset('logo.png') }}"
                        width="60px" alt="LOGO" style="width: 60px !important"> <span>TRITON</span></a></header>
                        <div class="nav-item-divider"></div>
            <nav class="dashboard-nav-list"><a href="#" class="dashboard-nav-item"><i class="fas fa-home"></i>
                    Kezdőlap </a>
                <!--<a href="#" class="dashboard-nav-item active"><i class="fas fa-tachometer-alt"></i>Dashboard</a>-->
                <div class='dashboard-nav-dropdown'><a href="#!"
                        class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i class="fas fa-photo-video"></i>
                        Ügyfelek </a>
                    <div class='dashboard-nav-dropdown-menu'><a href="{{ route('ugyfel.index') }}"
                            class="dashboard-nav-dropdown-item">Összes</a><a href="{{ route('ugyfel.create') }}"
                            class="dashboard-nav-dropdown-item">Új létrehozás</a></div>
                </div>
                <div class='dashboard-nav-dropdown'><a href="#!"
                        class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i class="fas fa-users"></i>
                        Megrendelések </a>
                    <div class='dashboard-nav-dropdown-menu'><a href="{{ route('megrendeles.index') }}"
                            class="dashboard-nav-dropdown-item">Összes</a><a href="{{ route('megrendeles.create') }}"
                            class="dashboard-nav-dropdown-item">Új létrehozás</a></div>
                </div>
                <div class='dashboard-nav-dropdown'><a href="#!"
                        class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i class="fas fa-money-check-alt"></i>
                        Anyagok </a>
                        <div class='dashboard-nav-dropdown-menu'><a href="#"
                            class="dashboard-nav-dropdown-item">Összes</a><a href="#"
                            class="dashboard-nav-dropdown-item">Új létrehozás</a></div>
                </div>
                <a href="#" class="dashboard-nav-item"><i class="fas fa-cogs"></i> Szerelők </a><a href="#"
                    class="dashboard-nav-item"><i class="fas fa-user"></i> Profile </a>
                <div class="nav-item-divider"></div>
                <a href="#" class="dashboard-nav-item"><i class="fas fa-sign-out-alt"></i> Kijelentkezés </a>
            </nav>
        </div>
        <div class='dashboard-app'>
            <header class='dashboard-toolbar'><a href="#!" class="menu-toggle-dashboard"><i
                        class="fas fa-bars"></i></a>
            </header>
            <div class="nav-item-divider"></div>
            <div class='dashboard-content'>
                <div class='container'>
                    <div class='card'>
                        <!--<div class='card-header'>
                            <h1>Üdvözlünk {{ auth()->user()->nev }}!</h1>
                        </div>-->
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
