<?php header('Content-Type: text/html; charset=utf-8'); ?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">

    <title>Triton Security</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .fa {
            color: white;
            padding: 5px;
            font-size: 20px;
            width: 34px;
            text-decoration: none;
            margin: 5px 2px;
            border-radius: 50%;
            border: 1.5px solid white;
        }

        body {
            color: black;
            font-family: sans-serif;
            background-color: #f8f8f9;
        }

        main {
            background-color: white;

            margin: auto;
            max-width: 640px;
        }

        .menu {
            display: grid;
            grid-template-columns: 30% 70%;
        }

        nav hr {
            background-color: #ed1b24;
            height: 4px;
            border: 0;
        }

        .felirat p {
            direction: ltr;
            font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 4px;
            line-height: 120%;
            text-align: center;
            margin: 8px;
        }

        .logo img {
            height: auto;
            display: block;
            border: 0;
            max-width: 152px;
            width: 100%;
            margin: 5px;
            margin-top: 0;
        }

        nav h1 {
            color: #000000;
            font-size: 42px;
            font-weight: bold;
            font-family: Tahoma, Verdana, Segoe, sans-serif;
            padding-left: 30px;
            padding-top: 55px;
        }

        .social-media {
            display: grid;
            grid-template-columns: repeat(4, 55px);
            padding-bottom: 10px;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 5px;
            text-align: center;
            justify-content: center;
            justify-items: center;
        }

        .contact {
            padding-bottom: 10px;
            padding-left: 40px;
            padding-right: 40px;
            padding-top: 15px;
        }

        .contact p {
            font-size: 11px;
            font-style: italic;
            line-height: 1.9;
            text-align: left;
        }

        footer hr {
            background-color: white;
            height: 0.5px;
            border: 0;
            margin: 10px;
        }

        footer {
            background-color: #ed1b24;
            color: white;
            padding-bottom: 30px;
            padding-top: 20px;
        }

        .coppyright {
            text-align: center;
            font-size: 12px;
            letter-spacing: 3px;
            padding-top: 30px;
        }

        section {
            margin: 50px;
            padding: 10px;

        }

        .row h3 {
            padding-bottom: 1px;
        }

        .row hr {
            margin-bottom: 20px;
            background-color: #ed1b24;
            height: 1.5px;
            border: 0;
            border-radius: 100px;
        }

        .hr1 {
            width: 190px;
            margin-left: 5px;

        }

        .hr2 {
            width: 155px;
            margin-left: 1px;

        }

        .imageContainer {
            text-align: center;
        }

        .imageContainer img {
            margin-top: 10px;
            width: 75%;
            border: 1px solid;
            border-radius: 10px;
        }

        .sign {
            margin-top: 30px;
            margin-bottom: 50px;
            gap: 100px;
            display: grid;
            grid-template-columns: auto auto;
        }

        .col-md-6 {
            padding-top: 40px;
        }
    </style>
</head>

<body>
    <?php header('Content-Type: text/html; charset=UTF-8'); ?>
    <main>
        <nav>
            <div class="felirat">
                <hr>
                <p>Triton Security Kft</p>
            </div>
            <div class="menu">

                <div class="logo">
                    <img src="{{ asset('logo.png') }}" alt="logo" width="200">
                </div>
                <h1>Kontraktus</h1>

            </div>


        </nav>
        <section>
            <div>
                <div class="row">
                    <div class="col-md-6">
                        <h3>Ügyfél információk</h3>
                        <hr class="hr1">
                        <ul class="list-group">
                            <li class="list-group-item"><strong>UgyfelID:</strong> {{ $megrendeles->ugyfel->Ugyfel_ID }}
                            </li>
                            <li class="list-group-item"><strong>Név:</strong> {{ $megrendeles->ugyfel->Nev }}</li>
                            <li class="list-group-item"><strong>Email:</strong> {{ $megrendeles->ugyfel->Email }}</li>
                            <li class="list-group-item"><strong>Telefonszám:</strong>
                                {{ $megrendeles->ugyfel->Telefonszam }}</li>
                            <li class="list-group-item"><strong>Számlázási név:</strong>
                                {{ $megrendeles->ugyfel->Szamlazasi_Nev }}</li>
                            <li class="list-group-item"><strong>Számlázási cím:</strong>
                                {{ $megrendeles->ugyfel->varos->Irny_szam }} {{ $megrendeles->ugyfel->varos->Nev }},
                                {{ $megrendeles->ugyfel->Szamlazasi_Cim }}</li>
                            @if (!is_null($megrendeles->ugyfel->Adoszam))
                                <li class="list-group-item"><strong>Adószám:</strong>
                                    {{ $megrendeles->ugyfel->Adoszam }}</li>
                            @endif

                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h3>Általános információk</h3>
                        <hr class="hr1">
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Ügyfél neve:</strong>
                                {{ $megrendeles->ugyfel->Nev ?? '-' }}</li>
                            <li class="list-group-item"><strong>Megrendelo neve:</strong>
                                {{ $megrendeles->Megrendeles_Nev }}
                            </li>
                            <li class="list-group-item"><strong>Címe:</strong> {{ $megrendeles->varos->Irny_szam }}
                                {{ $megrendeles->varos->Nev }}, {{ $megrendeles->Utca_Hazszam }}
                            </li>
                            </li>
                            @foreach ($megrendeles->munkak as $munka)
                                <li class="list-group-item"><strong>Szolgáltatás:</strong>
                                    {{ $munka->szolgaltatas->Tipus ?? '-' }}
                                </li>
                                <li class="list-group-item"><strong>Szerelést végezte:</strong>
                                    {{ $munka->szerelo->Nev ?? '-' }}
                                </li>
                                <li class="list-group-item"><strong>Telefonszáma:</strong>
                                    {{ $munka->szerelo->Telefonszam ?? '-' }}
                                </li>
                                <li class="list-group-item"><strong>Leírás:</strong> {{ $munka->Leiras }}</li>
                                <li class="list-group-item"><strong>Munkakezdete:</strong>
                                    {{ $munka->Munkakezdes_Idopontja }}
                                </li>
                                <li class="list-group-item"><strong>Munkabefejezte:</strong>
                                    {{ $munka->Munkabefejezes_Idopontja }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h3>Felhasznált anyagok</h3>
                        <hr class="hr1">
                        @if ($megrendeles->felhasznaltAnyagok && count($megrendeles->felhasznaltAnyagok) > 0)
                            <ul class="list-group">
                                @foreach ($megrendeles->felhasznaltAnyagok as $anyag)
                                    <li class="list-group-item">
                                        {{ $anyag->anyag->Nev }}({{ $anyag->anyag->Mertekegyseg }}):
                                        {{ $anyag->Mennyiseg }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>Nincsenek felhasznált anyagok rögzítve.</p>
                        @endif
                    </div>
                </div>
            </div>
            <br>
            <div class="sign">
                <div class="imageContainer">
                    <h5>Szerelo aláírása:</h5>
                    <img src="http://127.0.0.1:8000/alaIrasokSzerelok/{{ $imgPathSzerelo }}" alt="alairasSzerelo">

                </div>
                <div class="imageContainer">
                    <h5>Ügyfél aláírása:</h5>
                    <img src="http://127.0.0.1:8000/alaIrasokUgyfel/alairas.png" alt="alairasUgyfel">

                </div>

            </div>

        </section>
        <footer>
            <div class="social-media">
                <a href="#" class="fa fa-facebook"></a>
                <a href="#" class="fa fa-twitter"></a>
                <a href="#" class="fa fa-instagram"></a>
                <a href="#" class="fa fa-rss"></a>
            </div>
            <div class="contact">
                <p>Ezúton küldjük Önnek a Triton Security Kft. által küldött automatikus e-mailt. Mellékelten megtalálja
                    a szerződés kötés online formátumában elkészített dokumentumot.<br>
                    Kérjük, vegye figyelembe, hogy az e-mailre nem kell válaszolni.<br>
                    Amennyiben bármilyen további kérdése merül fel, kérjük, lépjen kapcsolatba ügyfélszolgálatunkkal.
                </p>
            </div>
            <hr>
            <div class="coppyright">
                <p>Triton Security Kft Copyright © 2023</p>
            </div>
        </footer>
    </main>
</body>

</html>
