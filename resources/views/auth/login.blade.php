<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/brand/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/style_login.css') }}">
</head>

<body>
    <div class="login-container">
        <div class="login-info-container">

            <u class="line">
                <h1 class="title">ACCEDER</h1>
            </u>

            <form class="inputs-container" method="POST" action="{{ route('login') }}">
                @csrf

                <input id="rut" class="input" type="text" placeholder="Rut" name="rut" value="{{ old('rut') }}" required>
                <span>Ejemplo:12345678-9</span>
                @error('rut')
                    <span>
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <input class="input" type="password" placeholder="Contraseña" name="password" required>
                @error('password')
                    <span>
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <button type="submit" class="btn">Acceder</button>
            </form>

        </div>
        <img class="image" src="{{ asset('assets/img/fondo_login.jpeg') }}" alt="">
    </div>
    <footer>
        <div class="container">

            <div class="row footerrow">
                <div class="col-3">
                    <div>
                        <img class="logo"
                            src="{{ asset('assets/img/brand/FCG_LOGO_WHITE_BACKGROUND-1024x512.png') }}" alt="Logo Fiabilis">
                    </div>

                    <h1 class="h1">Expertos en Optimización de Costos Laborales</h1>
                </div>
                <div class="col-3">
                    <div class="elementor-column-wrap elementor-element-populated">
                        <div class="elementor-widget-wrap">
                            <div class="elementor-element elementor-element-6717bd4f elementor-list-item-link-inline elementor-icon-list--layout-traditional elementor-widget elementor-widget-icon-list"
                                data-id="6717bd4f" data-element_type="widget" data-widget_type="icon-list.default">
                                <div class="elementor-widget-container">
                                    <ul class="elementor-icon-list-items">
                                        <li class="elementor-icon-list-item">
                                            <a href="https://www.fiabilis.cl/"> <span
                                                    class="elementor-icon-list-text">Home</span>
                                            </a>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <a href="https://www.fiabilis.cl/quienes-somos/"> <span
                                                    class="elementor-icon-list-text">Quienes Somos</span>
                                            </a>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <a href="https://www.fiabilis.cl/servicios/"> <span
                                                    class="elementor-icon-list-text">Servicios</span>
                                            </a>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <a href="https://www.fiabilis.cl/blog-noticias/"> <span
                                                    class="elementor-icon-list-text">Blog - Noticias</span>
                                            </a>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <a href="https://www.fiabilis.cl/contacto/"> <span
                                                    class="elementor-icon-list-text">Contacto</span>
                                            </a>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <a href="https://www.fiabilis.cl/acceso-clientes/"> <span
                                                    class="elementor-icon-list-text">Acceso Clientes</span>
                                            </a>
                                        </li>
                                        <li class="elementor-icon-list-item">
                                            <a href="https://www.fiabilis.cl/vacante-para-posicion-de-kam/"> <span
                                                    class="elementor-icon-list-text">Trabaja con nosotros</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="elementor-widget-wrap info-contacto">
                        <div class="elementor-element elementor-element-47f7c923 elementor-widget elementor-widget-heading"
                            data-id="47f7c923" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h6 class=" elementor-heading-title elementor-size-default eael-heading-content"
                                    id="4-eael-table-of-content">Contacto</h6>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-3bd1e0b2 elementor-widget elementor-widget-heading"
                            data-id="3bd1e0b2" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <p class="elementor-heading-title elementor-size-default">Edificio Pamplona Av.
                                    Providencia 1208, Providencia, Región Metropolitana.</p>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-277d8a00 elementor-widget elementor-widget-heading"
                            data-id="277d8a00" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <p class="elementor-heading-title elementor-size-default">Comercial &amp; Marketing:
                                    <br>
                                    Tel. +56 9 50939245
                                    comunicaciones@fiabiliscg.com
                                </p>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-6c1c244 elementor-widget elementor-widget-heading"
                            data-id="6c1c244" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <p class="elementor-heading-title elementor-size-default">Recursos Humanos: <br>
                                    Tel: + 56 9 3570 8969 <br>
                                    rrhh@fiabiliscg.com </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">

                    <h6 class="h6 elementor-heading-title elementor-size-default eael-heading-content"
                        id="5-eael-table-of-content">Síguenos en RR.SS</h6>
                    <div class="row boton_red_social">
                        <div class="col">
                            <a class=" social-icon elementor-icon elementor-social-icon elementor-social-icon-linkedin-in elementor-repeater-item-14aa280"
                                href="https://www.linkedin.com/company/fiabilis-consulting-chile/" target="_blank">
                                <span class="elementor-screen-only"></span>
                                <i class="fa fa-linkedin"></i>
                                <a class=" social-icon elementor-icon elementor-social-icon elementor-social-icon-twitter elementor-repeater-item-896f7f4"
                                    href="https://twitter.com/FiabilisC" target="_blank">
                                    <span class="elementor-screen-only"></span>
                                    <i class="fa fa-twitter"></i>
                        </div>
                        <div class="col">
                            <a class=" social-icon elementor-icon elementor-social-icon elementor-social-icon-instagram elementor-repeater-item-5261d3e"
                                href="https://www.instagram.com/fiabiliscg/?hl=es-la" target="_blank">
                                <span class="elementor-screen-only"></span>
                                <i class="fa fa-instagram"></i>
                                <a class=" social-icon elementor-icon elementor-social-icon elementor-social-icon-envelope elementor-repeater-item-a5129fd"
                                    href="http://comunicaciones@fiabiliscg.com/" target="_blank">
                                    <span class="elementor-screen-only"></span>
                                    <i class="fa fa-envelope"></i>
                        </div>




                    </div>
                </div>
            </div>
            <div class="row footer_row">
                <div class="col-4">
                    <img class="flag" src="{{ asset('assets/img/flag/pl.svg') }}" alt="">
                    <img class="flag" src="{{ asset('assets/img/flag/br.svg') }}" alt="">
                    <img class="flag" src="{{ asset('assets/img/flag/cl.svg') }}" alt="">
                    <img class="flag" src="{{ asset('assets/img/flag/be.svg') }}" alt="">
                </div>
                <div class="col-4">
                    <p class="elementor-heading-title elementor-size-default">Fiabilis Consulting Group - Chile - 2021
                    </p>
                </div>
                <div class="col-4">
                    <img class="flag" src="{{ asset('assets/img/flag/es.svg') }}" alt="">
                    <img class="flag" src="{{ asset('assets/img/flag/fr.svg') }}" alt="">
                    <img class="flag" src="{{ asset('assets/img/flag/Flag_of_Germany.svg') }}" alt="">
                    <img class="flag" src="{{ asset('assets/img/flag/it.svg') }}" alt="">
                </div>
            </div>
    </footer>
</body>

</html>
