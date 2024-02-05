@php
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
@endphp

<!doctype html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Seg Tech AMPER" />
    <meta name="description" content="Seg Tech AMPER" />
    <meta name="author" content="Benjamin Cacasaca" />
    <meta name="viewport" content="width=device-width, initial-scale=1">


    @if (!empty($title_template))
        <title>{{ $title_template }}</title>
    @else
        <title>Seg Tech AMPER</title>
    @endif
    <link rel="shortcut icon" href="{{asset('favicon.png?r='.rand())}}" />
    @include('layouts.assets.css')
    <style>
        a.scroll-top {
            display: none;
            width: 40px;
            height: 50px;
            position: fixed;
            z-index: 1000;
            bottom: 50px;
            right: 30px;
            padding-right:20px;
            font-size: 25px;
            border-radius: 3px !important;
            text-align: center;
        }
        a.scroll-top i {
            position: relative;
            top: 5px;
            left: 2px;
            padding-bottom:20px;
        }
        @media  (max-width: 1203px){
            a.scroll-top i {
                position: relative;
                top: 5px;
                padding-left:5px;
            }
        }
    </style>

    @yield('extracss')
    @stack('extracss')
    @show
    @php
        $item=\Session::get('item');
    @endphp
</head>
<body class="antialiased">
    <div id="contenedor_carga">
        <div id="loader-container">
            <p id="loadingText">Cargando...</p>
        </div>
    </div>
    <div class="page">
        <div class="flex-fill">
            @auth
                @include('layouts.sections.sidebar')
                @include('layouts.sections.navbar')
            @endauth
            <div class="content">
                <div class="container-lg">
                    <div class="page-header d-print-none">
                        <div class="row align-items-center">
                            @yield('contenidoHeader')
                        </div>
                    </div>
                    <div class="row row-deck row-cards" id="div_gral_contenido">
                        @yield('contenido')
                        <a href="#" class="scroll-top" title="Ir arriba">
                            <i class="fa fa-angle-up"></i>
                        </a>
                    </div>
                </div>
                @include('layouts.sections.footer')
            </div>
        </div>
    </div>

    <!-- Libs JS -->
    @include('layouts.assets.js')
    <script>
        $(window).scroll(function () {
            if ($(this).scrollTop() > 300) {
                $('a.scroll-top').fadeIn('slow');
            } else {
                $('a.scroll-top').fadeOut('slow');
            }
        }); $('a.scroll-top').click(function (event) {
            event.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 600);
        });

        var pathname = window.location.pathname;
        $(document).ready(function() {
            // if(pathname != '/' && pathname != '/schedule/calendar'){
                $('.divIconLogout').show();
            // }
        });

        $(function () {
            $('[data-toggle="tooltipMenu"]').tooltip({
                html: true,
                "placement": "top",
                "container": "body",
            })
        });

        $('.logoutModal').on('click', function(){
            $("#modalCerrarSesion").modal('show');
        })

        $( ".bntCerrarSesion" ).click(function(event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        });
        $(function () {
            $('[data-toggle="tooltipLogout"]').tooltip({
                html: true,
                "placement": "bottom",
                "container": "body",
            });
        });

        function goBack() {
            window.history.back();
        }
    </script>

    @stack('plugin-scripts')
    @yield('scripts')
    @stack('scripts')
</body>
</html>
