<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Utmutato</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="{{ asset('css/guide.css')}}" rel="stylesheet">

    </head>
    <body>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<section class="timeline_area section_padding_130">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-lg-6">
                <div class="section_heading text-center">
                    <div class="line"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="apland-timeline-area">
                    <div class="single-timeline-area">
                        <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                            <p>Admin</p>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fa fa-address-card" aria-hidden="true"></i></div>
                                    <a href="{{ route('szerelok.create') }}"><div class="timeline-text">
                                        <h6>Szerelők felvétele </h6>
                                        <p>Elöször vegyél fel szerelőket, akik elvégzik a rájuk bízott munkákat!</p>
                                    </div></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fa fa-archive" aria-hidden="true"></i></div>
                                    <a href="{{ route('anyagok.create') }}"><div class="timeline-text">
                                        <h6>Anyagok felvétele</h6>
                                        <p>Ezután vegyél fel anyagokat, amik szükségesek a munka elvégzéséhez!</p>
                                    </div></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fa fa-address-book" aria-hidden="true"></i></div>
                                    <a href="{{ route('users.create') }}"><div class="timeline-text">
                                        <h6>Profilok felvétele</h6>
                                        <p>Ha szükséges, vigyél fel további admin vagy üzletkőtő profilokat!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-timeline-area">
                        <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                            <p>Üzletkötö</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fa fa-briefcase" aria-hidden="true"></i></div>
                                    <a href="{{ route('ugyfel.create') }}"><div class="timeline-text">
                                        <h6>Ügyfelek létrehozása</h6>
                                        <p>Vigyél fel új ügyfelet a nyilvántartásunkba!</p>
                                    </div></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInLeft;">
                                    <div class="timeline-icon"><i class="fa fa-desktop" aria-hidden="true"></i></div>
                                    <a href="{{ route('megrendeles.create') }}"><div class="timeline-text">
                                        <h6>Megrendelések létrehozása</h6>
                                        <p>Ügyfél létrejötte után, tudsz létrehozni megrendelést adott szollgáltatásra!</p>
                                    </div></a>
                                </div>
                            </div>
                        </div>
                    </div>

        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
</html>
