<style>
    .cd-nugget-info {
        text-align: center;
        position: absolute;
        width: 100%;
        height: 50px;
        line-height: 50px;
        top: 0;
        left: 0;
    }

    .cd-nugget-info a {
        position: relative;
        font-size: 14px;
        color: #96c03d;
        -webkit-transition: all 0.2s;
        -moz-transition: all 0.2s;
        transition: all 0.2s;
    }

    .no-touch .cd-nugget-info a:hover {
        opacity: .8;
    }

    .cd-nugget-info span {
        vertical-align: middle;
        display: inline-block;
    }

    .cd-nugget-info span svg {
        display: block;
    }

    .cd-nugget-info .cd-nugget-info-arrow {
        fill: #96c03d;
    }

    /* --------------------------------

Basic Style

-------------------------------- */
    .cd-breadcrumb,
    .cd-multi-steps {
        width: 100%;
        padding: 0.5em 1em;
        border-radius: .25em;
        box-shadow: 4px 8px 14px 1px #0000000a;
    }

    .cd-breadcrumb::after,
    .cd-multi-steps::after {
        clear: both;
        content: "";
        display: table;
    }

    .cd-breadcrumb li,
    .cd-multi-steps li {
        display: inline-block;
        float: left;
        margin: 0.5em 0;
    }

    .cd-breadcrumb li::after,
    .cd-multi-steps li::after {
        /* this is the separator between items */
        display: inline-block;
        content: '\00bb';
        margin: 0 .6em;
        color: #959fa5;
    }

    .cd-breadcrumb li:last-of-type::after,
    .cd-multi-steps li:last-of-type::after {
        /* hide separator after the last item */
        display: none;
    }

    .cd-breadcrumb li>*,
    .cd-multi-steps li>* {
        /* single step */
        display: inline-block;
        font-size: 0.81rem;
        color: #2c3f4c;
    }

    .cd-breadcrumb li.current>*,
    .cd-multi-steps li.current>* {
        /* selected step */
        color: #96c03d;
    }

    .no-touch .cd-breadcrumb a:hover,
    .no-touch .cd-multi-steps a:hover {
        /* steps already visited */
        color: #96c03d;
    }

    .cd-breadcrumb.custom-separator li::after,
    .cd-multi-steps.custom-separator li::after {
        /* replace the default separator with a custom icon */
        content: '';
        height: 16px;
        width: 16px;
        background: url(https://codyhouse.co/demo/breadcrumbs-multi-steps-indicator/img/cd-custom-separator.svg) no-repeat center center;
        vertical-align: middle;
    }

    .cd-breadcrumb.custom-icons li>*::before,
    .cd-multi-steps.custom-icons li>*::before {
        /* add a custom icon before each item */
        content: '';
        display: inline-block;
        height: 20px;
        width: 20px;
        margin-right: .4em;
        margin-top: -2px;
        background: url(https://codyhouse.co/demo/breadcrumbs-multi-steps-indicator/img/cd-custom-icons-01.svg) no-repeat 0 0;
        vertical-align: middle;
    }

    .cd-breadcrumb.custom-icons li:not(.current):nth-of-type(2)>*::before,
    .cd-multi-steps.custom-icons li:not(.current):nth-of-type(2)>*::before {
        /* change custom icon using image sprites */
        background-position: -20px 0;
    }

    .cd-breadcrumb.custom-icons li:not(.current):nth-of-type(3)>*::before,
    .cd-multi-steps.custom-icons li:not(.current):nth-of-type(3)>*::before {
        background-position: -40px 0;
    }

    .cd-breadcrumb.custom-icons li:not(.current):nth-of-type(4)>*::before,
    .cd-multi-steps.custom-icons li:not(.current):nth-of-type(4)>*::before {
        background-position: -60px 0;
    }

    .cd-breadcrumb.custom-icons li.current:first-of-type>*::before,
    .cd-multi-steps.custom-icons li.current:first-of-type>*::before {
        /* change custom icon for the current item */
        background-position: 0 -20px;
    }

    .cd-breadcrumb.custom-icons li.current:nth-of-type(2)>*::before,
    .cd-multi-steps.custom-icons li.current:nth-of-type(2)>*::before {
        background-position: -20px -20px;
    }

    .cd-breadcrumb.custom-icons li.current:nth-of-type(3)>*::before,
    .cd-multi-steps.custom-icons li.current:nth-of-type(3)>*::before {
        background-position: -40px -20px;
    }

    .cd-breadcrumb.custom-icons li.current:nth-of-type(4)>*::before,
    .cd-multi-steps.custom-icons li.current:nth-of-type(4)>*::before {
        background-position: -60px -20px;
    }

    @media only screen and (min-width: 768px) {

        .cd-breadcrumb,
        .cd-multi-steps {
            padding: 0.3em 1.2em;
        }

        .cd-breadcrumb li,
        .cd-multi-steps li {
            margin: 0.4em 0;
        }

        .cd-breadcrumb li::after,
        .cd-multi-steps li::after {
            margin: 0 1em;
        }

        .cd-breadcrumb li>*,
        .cd-multi-steps li>* {
            font-size: 0.84rem;
        }
    }

    /* --------------------------------

Triangle breadcrumb

-------------------------------- */
    @media only screen and (min-width: 768px) {
        .cd-breadcrumb.triangle {
            /* reset basic style */
            background-color: transparent;
            padding: 0;
        }

        .cd-breadcrumb.triangle li {
            position: relative;
            padding: 0;
            margin: 4px 4px 4px 0;
        }

        .cd-breadcrumb.triangle li:last-of-type {
            margin-right: 0;
        }

        .cd-breadcrumb.triangle li>* {
            position: relative;
            padding: 1em .8em 1em 2.5em;
            color: #2c3f4c;
            background-color: #edeff0;
            /* the border color is used to style its ::after pseudo-element */
            border-color: #edeff0;
        }

        .cd-breadcrumb.triangle li.current>* {
            /* selected step */
            color: #ffffff;
            background-color: #96c03d;
            border-color: #96c03d;
        }

        .cd-breadcrumb.triangle li:first-of-type>* {
            padding-left: 1.6em;
            border-radius: .25em 0 0 .25em;
        }

        .cd-breadcrumb.triangle li:last-of-type>* {
            padding-right: 1.6em;
            border-radius: 0 .25em .25em 0;
        }

        .no-touch .cd-breadcrumb.triangle a:hover {
            /* steps already visited */
            color: #ffffff;
            background-color: #2c3f4c;
            border-color: #2c3f4c;
        }

        .cd-breadcrumb.triangle li::after,
        .cd-breadcrumb.triangle li>*::after {
            /*
    li > *::after is the colored triangle after each item
    li::after is the white separator between two items
    */
            content: '';
            position: absolute;
            top: 0;
            left: 100%;
            content: '';
            height: 0;
            width: 0;
            /* 48px is the height of the <a> element */
            border: 24px solid transparent;
            border-right-width: 0;
            border-left-width: 20px;
        }

        .cd-breadcrumb.triangle li::after {
            /* this is the white separator between two items */
            z-index: 1;
            -webkit-transform: translateX(4px);
            -moz-transform: translateX(4px);
            -ms-transform: translateX(4px);
            -o-transform: translateX(4px);
            transform: translateX(4px);
            border-left-color: #ffffff;
            /* reset style */
            margin: 0;
        }

        .cd-breadcrumb.triangle li>*::after {
            /* this is the colored triangle after each element */
            z-index: 2;
            border-left-color: inherit;
        }

        .cd-breadcrumb.triangle li:last-of-type::after,
        .cd-breadcrumb.triangle li:last-of-type>*::after {
            /* hide the triangle after the last step */
            display: none;
        }

        .cd-breadcrumb.triangle.custom-separator li::after {
            /* reset style */
            background-image: none;
        }

        .cd-breadcrumb.triangle.custom-icons li::after,
        .cd-breadcrumb.triangle.custom-icons li>*::after {
            /* 50px is the height of the <a> element */
            border-top-width: 25px;
            border-bottom-width: 25px;
        }

        @-moz-document url-prefix() {

            .cd-breadcrumb.triangle li::after,
            .cd-breadcrumb.triangle li>*::after {
                /* fix a bug on Firefix - tooth edge on css triangle */
                border-left-style: dashed;
            }
        }
    }

    /* --------------------------------

Custom icons hover effects - breadcrumb and multi-steps

-------------------------------- */
    @media only screen and (min-width: 768px) {

        .no-touch .cd-breadcrumb.triangle.custom-icons li:first-of-type a:hover::before,
        .cd-breadcrumb.triangle.custom-icons li.current:first-of-type em::before,
        .no-touch .cd-multi-steps.text-center.custom-icons li:first-of-type a:hover::before,
        .cd-multi-steps.text-center.custom-icons li.current:first-of-type em::before {
            /* change custom icon using image sprites - hover effect or current item */
            background-position: 0 -40px;
        }

        .no-touch .cd-breadcrumb.triangle.custom-icons li:nth-of-type(2) a:hover::before,
        .cd-breadcrumb.triangle.custom-icons li.current:nth-of-type(2) em::before,
        .no-touch .cd-multi-steps.text-center.custom-icons li:nth-of-type(2) a:hover::before,
        .cd-multi-steps.text-center.custom-icons li.current:nth-of-type(2) em::before {
            background-position: -20px -40px;
        }

        .no-touch .cd-breadcrumb.triangle.custom-icons li:nth-of-type(3) a:hover::before,
        .cd-breadcrumb.triangle.custom-icons li.current:nth-of-type(3) em::before,
        .no-touch .cd-multi-steps.text-center.custom-icons li:nth-of-type(3) a:hover::before,
        .cd-multi-steps.text-center.custom-icons li.current:nth-of-type(3) em::before {
            background-position: -40px -40px;
        }

        .no-touch .cd-breadcrumb.triangle.custom-icons li:nth-of-type(4) a:hover::before,
        .cd-breadcrumb.triangle.custom-icons li.current:nth-of-type(4) em::before,
        .no-touch .cd-multi-steps.text-center.custom-icons li:nth-of-type(4) a:hover::before,
        .cd-multi-steps.text-center.custom-icons li.current:nth-of-type(4) em::before {
            background-position: -60px -40px;
        }
    }

    /* --------------------------------

Multi steps indicator

-------------------------------- */
    @media only screen and (min-width: 768px) {
        .cd-multi-steps {
            /* reset style */
            background-color: transparent;
            padding: 0;
            text-align: center;
        }

        .cd-multi-steps li {
            position: relative;
            float: none;
            margin: 0.4em 40px 0.4em 0;
        }

        .cd-multi-steps li:last-of-type {
            margin-right: 0;
        }

        .cd-multi-steps li::after {
            /* this is the line connecting 2 adjacent items */
            position: absolute;
            content: '';
            height: 4px;
            background: #edeff0;
            /* reset style */
            margin: 0;
        }

        .cd-multi-steps li.visited::after {
            background-color: #96c03d;
        }

        .cd-multi-steps li>*,
        .cd-multi-steps li.current>* {
            position: relative;
            color: #2c3f4c;
        }

        .cd-multi-steps.custom-separator li::after {
            /* reset style */
            height: 4px;
            background: #edeff0;
        }

        .cd-multi-steps.text-center li::after {
            width: 100%;
            top: 50%;
            left: 100%;
            -webkit-transform: translateY(-50%) translateX(-1px);
            -moz-transform: translateY(-50%) translateX(-1px);
            -ms-transform: translateY(-50%) translateX(-1px);
            -o-transform: translateY(-50%) translateX(-1px);
            transform: translateY(-50%) translateX(-1px);
        }

        .cd-multi-steps.text-center li>* {
            z-index: 1;
            padding: .6em 1em;
            border-radius: .25em;
            background-color: #edeff0;
        }

        .no-touch .cd-multi-steps.text-center a:hover {
            background-color: #2c3f4c;
        }

        .cd-multi-steps.text-center li.current>*,
        .cd-multi-steps.text-center li.visited>* {
            color: #ffffff;
            background-color: #96c03d;
        }

        .cd-multi-steps.text-center.custom-icons li.visited a::before {
            /* change the custom icon for the visited item - check icon */
            background-position: 0 -60px;
        }

        .cd-multi-steps.text-top li,
        .cd-multi-steps.text-bottom li {
            width: 80px;
            text-align: center;
        }

        .cd-multi-steps.text-top li::after,
        .cd-multi-steps.text-bottom li::after {
            /* this is the line connecting 2 adjacent items */
            position: absolute;
            left: 50%;
            /* 40px is the <li> right margin value */
            width: calc(100% + 40px);
        }

        .cd-multi-steps.text-top li>*::before,
        .cd-multi-steps.text-bottom li>*::before {
            /* this is the spot indicator */
            content: '';
            position: absolute;
            z-index: 1;
            left: 50%;
            right: auto;
            -webkit-transform: translateX(-50%);
            -moz-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            -o-transform: translateX(-50%);
            transform: translateX(-50%);
            height: 12px;
            width: 12px;
            border-radius: 50%;
            background-color: #edeff0;
        }

        .cd-multi-steps.text-top li.visited>*::before,
        .cd-multi-steps.text-top li.current>*::before,
        .cd-multi-steps.text-bottom li.visited>*::before,
        .cd-multi-steps.text-bottom li.current>*::before {
            background-color: #96c03d;
        }

        .no-touch .cd-multi-steps.text-top a:hover,
        .no-touch .cd-multi-steps.text-bottom a:hover {
            color: #96c03d;
        }

        .no-touch .cd-multi-steps.text-top a:hover::before,
        .no-touch .cd-multi-steps.text-bottom a:hover::before {
            box-shadow: 0 0 0 3px rgba(150, 192, 61, 0.3);
        }

        .cd-multi-steps.text-top li::after {
            /* this is the line connecting 2 adjacent items */
            bottom: 4px;
        }

        .cd-multi-steps.text-top li>* {
            padding-bottom: 20px;
        }

        .cd-multi-steps.text-top li>*::before {
            /* this is the spot indicator */
            bottom: 0;
        }

        .cd-multi-steps.text-bottom li::after {
            /* this is the line connecting 2 adjacent items */
            top: 3px;
        }

        .cd-multi-steps.text-bottom li>* {
            padding-top: 20px;
        }

        .cd-multi-steps.text-bottom li>*::before {
            /* this is the spot indicator */
            top: 0;
        }
    }

    /* --------------------------------

Add a counter to the multi-steps indicator

-------------------------------- */
    .cd-multi-steps.count li {
        counter-increment: steps;
    }

    .cd-multi-steps.count li>*::before {
        content: counter(steps) " - ";
    }

    @media only screen and (min-width: 768px) {

        .cd-multi-steps.text-top.count li>*::before,
        .cd-multi-steps.text-bottom.count li>*::before {
            /* this is the spot indicator */
            content: counter(steps);
            height: 26px;
            width: 26px;
            line-height: 26px;
            font-size: 1.4rem;
            color: #ffffff;
        }

        .cd-multi-steps.text-top.count li:not(.current) em::before,
        .cd-multi-steps.text-bottom.count li:not(.current) em::before {
            /* steps not visited yet - counter color */
            color: #2c3f4c;
        }

        .cd-multi-steps.text-top.count li::after {
            bottom: 11px;
        }

        .cd-multi-steps.text-top.count li>* {
            padding-bottom: 34px;
        }

        .cd-multi-steps.text-bottom.count li::after {
            top: 11px;
        }

        .cd-multi-steps.text-bottom.count li>* {
            padding-top: 34px;
        }
    }
</style>


@if (count($breadcrumbs = Breadcrumbs::generate()) > 0)
    <div class="container container-md-fluid my-breadcrumbs">
        <div class="row">
            <div class="col-auto col-md-10">
                <nav aria-label="breadcrumb" class="first d-md-flex">
                    <ol class="cd-breadcrumb custom-separator custom-icons">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if ($breadcrumb->url && !$loop->last)
                                <li class="breadcrumb-item font-weight-bold">
                                    <a class="black-text text-uppercase" href="{{ $breadcrumb->url }}">
                                        <span>{{ $breadcrumb->title }}</span>
                                    </a>
                                </li>
                            @else
                                <li class="current">
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
