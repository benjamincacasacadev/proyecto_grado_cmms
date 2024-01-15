{{-- Cabecera donde se encuentran el logo de empresa y menu de usuario - MENU VERTICAL --}}
<style>
    #scrollDropdown{
        max-height:250px; overflow:auto;
    }
</style>
<div class="sticky-top">
    <header class="navbar navbar-expand-md navbar-light d-none d-lg-flex d-print-none" >
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-nav flex-row order-md-last">
                <div class="nav-item dropdown text-dark">
                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm " style="background-image: url({{ imageRouteAvatar(auth()->user()->avatar,1) }})"></span>
                        <div class="d-none d-xl-block ps-2">
                            <div class="font-weight-bold">{{ Auth::user()->name." ".Auth::user()->ap_paterno}}</div>
                            <div class="d-flex mt-2 small text-secondary font-weight-bold">
                                <div class="mr-auto">{{ Auth::user()->rolUser->name}}</div>
                                <div>
                                    <svg class="icon icon-avatar" width="12" height="12" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </div>
                            </div>
                        </div>
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
                    </div>
                </div>
            </div>

            <div class="collapse navbar-collapse" id="navbar-menu">

            </div>
        </div>
    </header>
</div>