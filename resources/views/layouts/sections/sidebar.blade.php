<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark sticky-top" >
    <div class="container-fluid">
        {{-- ========================================================================================= --}}
        {{--                           HEADER PARA PANTALLAS PEQUEÑAS MENU VERTICAL                    --}}
        {{-- ========================================================================================= --}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand ">
            <img src="{{ asset('logo.png') }}" alt="Amper SRL" class="navbar-brand-image">
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

                {{-- =================================================================================================================== --}}
                {{--                                              ÓRDENES DE TRABAJO                                                     --}}
                {{-- =================================================================================================================== --}}
                <li class="nav-item {!!strstr($item,'.',true)=='1'?'active':'';!!}">
                    <a class="dropdown-item {!!strstr($item,'.',true)=='1'?'text-yellow font-weight-bold':'';!!}" href="/work_orders" >
                        <span class="nav-link-icon  d-lg-inline-block">
                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><rect x="9" y="3" width="6" height="4" rx="2" /><line x1="9" y1="12" x2="9.01" y2="12" /><line x1="13" y1="12" x2="15" y2="12" /><line x1="9" y1="16" x2="9.01" y2="16" /><line x1="13" y1="16" x2="15" y2="16" /></svg>
                        </span>
                        <span class="nav-link-title">
                            Órdenes de trabajo &ensp;
                        </span>
                    </a>
                </li>

                {{-- =================================================================================================================== --}}
                {{--                                                    ACTIVOS                                                          --}}
                {{-- =================================================================================================================== --}}
                <li class="nav-item {!!strstr($item,'.',true)=='2'?'active':'';!!}">
                    <a class="dropdown-item {!!strstr($item,'.',true)=='2'?'text-yellow font-weight-bold':'';!!}" href="/assets" >
                        <span class="nav-link-icon  d-lg-inline-block">
                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"></polyline>
                                <line x1="12" y1="12" x2="20" y2="7.5"></line>
                                <line x1="12" y1="12" x2="12" y2="21"></line>
                                <line x1="12" y1="12" x2="4" y2="7.5"></line>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Activos
                        </span>
                    </a>
                </li>

                {{-- =================================================================================================================== --}}
                {{--                                                    CLIENTES                                                         --}}
                {{-- =================================================================================================================== --}}
                <li class="nav-item {!!strstr($item,'.',true)=='3'?'active':'';!!}">
                    <a class="dropdown-item {!!strstr($item,'.',true)=='3'?'text-yellow font-weight-bold':'';!!}" href="/clients" >
                        <span class="nav-link-icon  d-lg-inline-block">
                            <i class="far fa-building icon"></i>
                        </span>
                        <span class="nav-link-title">
                            Clientes
                        </span>
                    </a>
                </li>

                {{-- =================================================================================================================== --}}
                {{--                                                     INVENTARIO                                                      --}}
                {{-- =================================================================================================================== --}}
                <li class="nav-item dropdown {!!strstr($item,'.',true)=='4'?'active':'';!!}">
                    <a class="dropdown-item dropdown-toggle" href="#navbar-inventario" data-toggle="dropdown" role="button" aria-expanded="false">
                        <span class="nav-link-icon  d-lg-inline-block">
                            <img src="{{asset('imagenes/screw.svg')}}" width="20" height="20" style="margin-bottom:20px;filter: brightness(0) invert(1);">
                        </span>
                        <span class="nav-link-title">
                            Inventario
                        </span>
                    </a>

                    <div class="dropdown-menu {!!strstr($item,'.',true)=='4.0'?'show':'';!!}">
                        <a class="dropdown-item {!!strstr($item,':',true)=='4.0'?'active font-weight-bold':'';!!}" href="/inventory">
                            <span class="nav-link-icon  d-lg-inline-block">
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="9" y1="6" x2="20" y2="6" /><line x1="9" y1="12" x2="20" y2="12" /><line x1="9" y1="18" x2="20" y2="18" /><line x1="5" y1="6" x2="5" y2="6.01" /><line x1="5" y1="12" x2="5" y2="12.01" /><line x1="5" y1="18" x2="5" y2="18.01" /></svg>
                            </span>
                            <span class="nav-link-title">
                                Ver materiales
                            </span>
                        </a>


                        <a class="dropdown-item {!!strstr($item,':',true)=='4.1'?'active font-weight-bold':'';!!}" href="/outcomes">
                            <span class="nav-link-icon  d-lg-inline-block">
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
                                    <path d="M7 12h14l-3 -3m0 6l3 -3"></path>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Solicitudes
                            </span>
                        </a>

                        @if (permisoAdminJefe())
                            <a class="dropdown-item {!!strstr($item,':',true)=='4.2'?'active font-weight-bold':'';!!}" href="/incomes">
                                <span class="nav-link-icon  d-lg-inline-block">
                                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M20 12h-13l3 -3m0 6l-3 -3" /></svg>
                                </span>
                                <span class="nav-link-title">
                                    Ingresos
                                </span>
                            </a>
                        @endif

                        @if (permisoAdminJefe())
                            <a class="dropdown-item {!!strstr($item,':',true)=='4.3'?'active font-weight-bold':'';!!}" href="/transfers">
                                <span class="nav-link-icon  d-lg-inline-block">
                                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M14 21l-11 -11"></path>
                                        <path d="M3 14v-4h4"></path>
                                        <path d="M17 14h4v-4"></path>
                                        <line x1="10" y1="3" x2="21" y2="14"></line>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Traspasos
                                </span>
                            </a>
                        @endif
                    </div>
                </li>

                {{-- =================================================================================================================== --}}
                {{--                                            CONFIGURAR FORMULARIOS                                                   --}}
                {{-- =================================================================================================================== --}}
                @if (permisoAdminJefe())
                    <li class="nav-item {!!strstr($item,'.',true)=='5'?'active':'';!!}">
                        <a class="dropdown-item {!!strstr($item,'.',true)=='5'?'text-yellow font-weight-bold':'';!!}" href="/forms" >
                            <span class="nav-link-icon  d-lg-inline-block">
                                <i class="fas fa-clipboard-list icon"></i>
                            </span>
                            <span class="nav-link-title">
                                Formularios
                            </span>
                        </a>
                    </li>
                @endif
                {{-- =================================================================================================================== --}}
                {{--                                                        USUARIOS                                                     --}}
                {{-- =================================================================================================================== --}}
                @if (permisoAdminJefe())
                <li class="nav-item {!!strstr($item,'.',true)=='6'?'active':'';!!}">
                    <a class="dropdown-item @if(strstr($item,'.',true)=='6') text-yellow font-weight-bold @endif" href="/users" >
                        <span class="nav-link-icon  d-lg-inline-block">
                            &nbsp;<i class="fa fa-users"></i>
                        </span>
                        <span class="nav-link-title">
                            Usuarios
                        </span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</aside>
