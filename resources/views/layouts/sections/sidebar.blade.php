<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark sticky-top" >
    <div class="container-fluid">
        {{-- ========================================================================================= --}}
        {{--                           HEADER PARA PANTALLAS PEQUEÑAS MENU VERTICAL                    --}}
        {{-- ========================================================================================= --}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand ">
            <img src="{{ asset('logo.png') }}" alt="Samaritan's Purse" style='width:96px; height:64px;' class="navbar-brand-image">
        </h1>


        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item dropdown d-none d-md-flex me-3">
                <a class="nav-link px-0" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 6a7.75 7.75 0 1 0 10 0" /><line x1="12" y1="4" x2="12" y2="12" />
                    </svg>
                </a>
            </div>

            <div class="nav-item dropdown drop-avatar">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url({{ imageRouteAvatar(auth()->user()->avatar,1) }})"></span>
                    <svg class="icon " width="12" height="12" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow ">
                    <a class="dropdown-item" id="infousersm" style="border-bottom: 1px solid rgba(53, 64, 82, .5184) !important; margin-bottom:10px;display:none">
                        <div style="font-weight: bold; text-align:center">
                            {{ Auth::user()->name." ".Auth::user()->ap_paterno}} <br>
                            <span class="small">{{ Auth::user()->cargo}} </span>
                        </div>
                    </a>
                    <a class="dropdown-item" href="/perfil_usuario">
                        <i class="fe fe-user icon dropdown-item-icon"></i>
                        Perfil de usuario
                    </a>
                    <a class="dropdown-item cursor-pointer bntCerrarSesion">
                        <i class="fe fe-log-out icon dropdown-item-icon iconCerrarSesion"></i>
                        <span class="textCerrarSesion">
                            Cerrar sesión
                        </span>
                    </a>
                    <div class="dropdown-divider"></div>
                </div>
            </div>
        </div>
        {{-- ========================================================================================= --}}
        {{--                                    MENU SIDEBAR                                           --}}
        {{-- ========================================================================================= --}}
        <div class="collapse navbar-collapse navbar-right" id="navbar-menu">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item {!!strstr($item,'.',true)=='0' ? 'active' : ''; !!}">
                    <a class="dropdown-item @if(strstr($item,'.',true)=='0') text-yellow font-weight-bold @endif" href="/">
                        <span class="nav-link-icon  d-lg-inline-block">
                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                        </span>
                        <span class="nav-link-title">
                            Inicio
                        </span>
                    </a>
                </li>

                <li class="nav-item {!!strstr($item,'.',true)=='1' ? 'active' : ''; !!}">
                    <a class="dropdown-item @if(strstr($item,'.',true)=='1') text-yellow font-weight-bold @endif" href="/">
                        <span class="nav-link-icon  d-lg-inline-block">
                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6"></path>
                                <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Estudiantes
                        </span>
                    </a>
                </li>

                <li class="nav-item {!!strstr($item,'.',true)=='2' ? 'active' : ''; !!}">
                    <a class="dropdown-item @if(strstr($item,'.',true)=='2') text-yellow font-weight-bold @endif" href="/">
                        <span class="nav-link-icon  d-lg-inline-block">
                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="15" cy="15" r="3"></circle>
                                <path d="M13 17.5v4.5l2 -1.5l2 1.5v-4.5"></path>
                                <path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -1 1.73"></path>
                                <line x1="6" y1="9" x2="18" y2="9"></line>
                                <line x1="6" y1="12" x2="9" y2="12"></line>
                                <line x1="6" y1="15" x2="8" y2="15"></line>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Cursos
                        </span>
                    </a>
                </li>

                <li class="nav-item {!!strstr($item,'.',true)=='3' ? 'active' : ''; !!}">
                    <a class="dropdown-item @if(strstr($item,'.',true)=='3') text-yellow font-weight-bold @endif" href="/">
                        <span class="nav-link-icon  d-lg-inline-block">
                            &nbsp;<i class=" fa fa-user-tie fa-lg"></i>
                        </span>
                        <span class="nav-link-title">
                            Maestros
                        </span>
                    </a>
                </li>

                <li class="nav-item {!!strstr($item,'.',true)=='4' ? 'active' : ''; !!}">
                    <a class="dropdown-item @if(strstr($item,'.',true)=='4') text-yellow font-weight-bold @endif" href="/">
                        <span class="nav-link-icon  d-lg-inline-block">
                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 18h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7.5"></path>
                                <path d="M3 6l9 6l9 -6"></path>
                                <path d="M15 18h6"></path>
                                <path d="M18 15l3 3l-3 3"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Correspondencia
                        </span>
                    </a>
                </li>

                {{-- =================================================================================================================== --}}
                {{--                                                        USUARIOS                                                     --}}
                {{-- =================================================================================================================== --}}
                {{-- @if (Gate::check('users.index') || Gate::check('users.myindex')) --}}
                    <li class="nav-item {!!strstr($item,'.',true)=='5'?'active':'';!!}">
                        <a class="dropdown-item @if(strstr($item,'.',true)=='5') text-yellow font-weight-bold @endif" href="/users" >
                            <span class="nav-link-icon  d-lg-inline-block">
                                &nbsp;<i class="fa fa-users"></i>
                            </span>
                            <span class="nav-link-title">
                                Usuarios
                            </span>
                        </a>
                    </li>
                {{-- @endif --}}


            </ul>
        </div>
    </div>
</aside>
