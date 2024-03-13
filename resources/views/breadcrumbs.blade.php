<style>
    .my-breadcrumbs .container {
        margin-top: 250px;
    }

    .my-breadcrumbs .breadcrumb>li+li:before {
        content: "" !important;
    }

    .my-breadcrumbs .breadcrumb {
        padding: 23px;
        font-size: 14px;
        color: #aaa;
        background: white;
        border-radius: 5px;
        list-style: none;
        display: flex;
        box-shadow: 6px 10px 20px 2px #0000003d;
    }


    .my-breadcrumbs .first-1 {
        background-color: white !important;
    }


    .my-breadcrumbs a {
        text-decoration: none !important;
        color: #aaa !important;
    }

    .my-breadcrumbs a:focus,
    .my-breadcrumbs a:active {
        outline: none !important;
        box-shadow: none !important;
    }

    .my-breadcrumbs img {
        vertical-align: middle;
        opacity: 0.5;
    }

    .first span {
        color: red;
    }

    .first a:hover {
        color: black !important;
    }

    .active-1 span:hover {
        color: white !important;
    }

    .active-1 {
        padding: 12px 25px;

        border-radius: 200px;

        background-color: red;

        color: white;

        font-size: 13px;
    }

    .szoveg span {
        color: white !important;
    }

    .breadcrumb-item {
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: -2px 3px 20px #00000069;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        padding: 0 10px;
    }


    @media (max-width: 767px) {
        .breadcrumb {
            font-size: 10px;
            letter-spacing: 1px;
        }

        .breadcrumb-item+.breadcrumb-item {
            padding-left: 0;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            padding: 0 5px;
            /* Kisebb padding mobil nézeten */
        }

        .my-breadcrumbs img {
            width: 11px;
            height: 11px;
            vertical-align: middle;
        }

        .active-1 {
            font-size: 10px;
            /* Kisebb betűméret az aktív elemen mobil nézeten */
            padding: 10px;
            /* Kisebb padding az aktív elemen */
            border-radius: 100px;
            width: 100% ! important;
        }

        .breadcrumb {
            letter-spacing: 1px !important;
        }

        .breadcrumb>* div {
            max-width: 60px;
        }

    }
</style>


@if (count($breadcrumbs = Breadcrumbs::generate()) > 0)
    <div class="container container-md-fluid my-breadcrumbs">
        <div class="row">
            <div class="col-auto col-md-10">
                <nav aria-label="breadcrumb" class="first d-md-flex">
                    <ol class="breadcrumb indigo lighten-6 first-1 shadow-lg mb-5">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if ($breadcrumb->url && !$loop->last)
                                <li class="breadcrumb-item font-weight-bold">
                                    <a class="black-text text-uppercase" href="{{ $breadcrumb->url }}">
                                        <span>{{ $breadcrumb->title }}</span>
                                    </a>
                                    <img class="ml-md-3" src="https://img.icons8.com/offices/30/000000/double-right.png"
                                        width="20" height="20">
                                </li>
                            @else
                                <li class="breadcrumb-item font-weight-bold mr-0 pr-0">
                                    <a class="black-text active-1 szoveg" href="#">
                                        <span>{{ $breadcrumb->title }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endif
