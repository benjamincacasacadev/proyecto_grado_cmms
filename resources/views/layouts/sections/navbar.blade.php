{{-- Cabecera donde se encuentran el logo de empresa y menu de usuario - MENU VERTICAL --}}
<style>
    #scrollDropdown{
        max-height:250px; overflow:auto;
    }
</style>
<div class="sticky-top">
    <header class="navbar navbar-expand-md navbar-dark d-none d-lg-flex d-print-none" style="background-color: rgba(247,166,0, 0.8) !important">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-nav flex-row order-md-last">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown ">
                        <a class=" text-dark nav-link @if(Gate::check('configuracion.temporadas')) dropdown-toggle @endif" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <rect x="4" y="5" width="16" height="16" rx="2"></rect>
                                    <line x1="16" y1="3" x2="16" y2="7"></line>
                                    <line x1="8" y1="3" x2="8" y2="7"></line>
                                    <line x1="4" y1="11" x2="20" y2="11"></line>
                                    <line x1="11" y1="15" x2="12" y2="15"></line>
                                    <line x1="12" y1="15" x2="12" y2="18"></line>
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Gestión: <b>{{tempoSelect()}}</b>
                            </span>
                        </a>
                        @if(Gate::check('configuracion.temporadas'))
                            <div class="dropdown-menu" id="scrollDropdown">
                                <a class="dropdown-item " style="border-bottom: 1px solid rgba(53, 64, 82, .5184) !important; margin-bottom:10px;">
                                    <div style="font-weight: bold; text-align:center">
                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <rect x="4" y="5" width="16" height="16" rx="2"></rect>
                                            <line x1="16" y1="3" x2="16" y2="7"></line>
                                            <line x1="8" y1="3" x2="8" y2="7"></line>
                                            <line x1="4" y1="11" x2="20" y2="11"></line>
                                            <line x1="11" y1="15" x2="12" y2="15"></line>
                                            <line x1="12" y1="15" x2="12" y2="18"></line>
                                        </svg>
                                        Seleccione una temporada
                                    </div>
                                </a>
                                @foreach (temporadas() as $temporada)
                                    <center>
                                        <a class="dropdown-item optTemporada cursor-pointer" id="{{$temporada->gestion}}" style="justify-content: center; !important">
                                            @if ($temporada->gestion == tempoSelect())
                                                <b class="text-primary">
                                                    {{$temporada->gestion}}&nbsp;<i class="fa fa-check-circle"></i>
                                                </b>
                                            @else
                                                {{$temporada->gestion}}
                                            @endif
                                        </a>
                                    </center>
                                @endforeach

                                {{Form::Open(array('action'=>array('ConfiguracionController@updateSessionTemporada'),'method'=>'post','autocomplete'=>'off','id'=>'formSessionTemporada'))}}
                                    <input type="text" id="temporadaValue" name="gestionSession" hidden>
                                {{Form::Close()}}
                            </div>
                        @endif
                    </li>
                </ul> &ensp;

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


@push('scripts')
    <script>
    $('.optTemporada').click(function () {
        var antiguo = "{{tempoSelect()}}";
        var value = $(this).attr('id');
        $("#temporadaValue").val(value);
        if(antiguo != value){
            $("#formSessionTemporada").submit();
        }
    });
    </script>
@endpush