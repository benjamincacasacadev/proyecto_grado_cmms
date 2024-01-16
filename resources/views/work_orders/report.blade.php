@extends ('layouts.admin', ['title_template' => "Orden de trabajo $workorder->cod"])
@section('extracss')
    <style>
            .highcharts-container {
            margin: 0 auto;
        }
        button.accordion.active, button.accordion:hover {
            background-color: #DDDDDD;
            color: #111111;
        }
        .highcharts-tick{
            display: none;
        }

        .icon-btn {
            width: 28px !important;
            height: 28px !important;
            stroke-width: 1.25;
        }

        /* ESTILOS PARA EL FILE INPUT  */
        .file-drop-zone-title {
            padding: 0px !important;
        }
        .file-preview-frame{
            height: 150px;
        }
        .kv-file-content, .file-preview-other{
            height: 50px !important;
        }
        .file-other-icon{
            font-size: 3em !important
        }
        .krajee-default.file-preview-frame {
            left: 33%;
        }
        @media  (max-width: 991px){
            .krajee-default.file-preview-frame {
                left: 20%;
            }
        }
        @media  (max-width: 767px){
            .krajee-default.file-preview-frame {
                left: 0%;
            }
        }
        .file-caption-main, .kv-error-close{
            display: none !important;
        }

        #header_tabla{
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            font-size:15px;
        }

        .file-caption-name{
            margin-top:5px !important;
            margin-left:20px !important;
        }
        .file-caption-icon{
            margin-top:5px !important;
        }

        @media (max-width: 767px) {
            .form-inline .form-control {
                display: inline-block;
                width: auto;
                vertical-align: middle;
            }
        }
        @media (max-width: 767px) {
            .form-inline .form-group {
                display: inline-block;
                margin-bottom: 0;
                vertical-align: middle;
            }
        }
        .highchartsheatmap{
            min-width: 500px !important;
        }

        .sizeorden{
            font-size: 14px !important;
        }
        @font-face {
            font-family: 'digital-7';
            src:  url({{asset('templates/tabler/dist/fonts/feather/digital-7.ttf') }});
        }
        .relojNumeros{
            font-family: 'digital-7', sans-serif;
            /* display: none; */
        }
        .icon-tabler {
            width: 25px;
            height: 25px;
            stroke-width: 1.25;
            margin-bottom: 2px;
        }
        .icon-trip {
            width: 30px !important;
            height: 30px !important;
            stroke-width: 1.75 !important;
        }
        .icon-svg {
            width: 30px;
            height: 30px;
            stroke-width: 2.5;
            margin-bottom: 2px;
        }

        .div-description {
            word-break: break-word;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -moz-box-orient: vertical;
            -ms-box-orient: vertical;
            box-orient: vertical;
            -webkit-line-clamp: 6;
            -moz-line-clamp: 6;
            -ms-line-clamp: 6;
            line-clamp: 6;
            overflow: hidden;
            text-align: justify;
        }

        @media (max-width:1300px) {
            .ocultar{
                display: none !important;
            }
        }

        @media  (max-width: 665px){
            .mtr-inp{
                margin-top: 0.6rem !important;
            }
        }

        .disabledTag{
            pointer-events: none;
            cursor: not-allowed !important;
        }
    </style>
    <link href="{{asset('/plugins/fileinput/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/animate.min.css')}}">
@endsection

@section ('contenidoHeader')
    <div class="col">
        <div class="page-pretitle">
            {{ nameEmpresa() }}
        </div>
        <h1 class="titulomod">
            <i class="fas fa-clipboard-list fa-md"></i>&nbsp;
            Informe {{ $workorder->cod }}
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="/work_orders" class="btn btn-outline-secondary border-secondary font-weight-bold" title="Ver listado de órdenes de trabajo">
                <i class="far fa-list-alt fa-lg"></i> &nbsp;
                <span class="d-none d-sm-inline-block">
                    Ver órdenes de trabajo
                </span>
            </a>
        </div>
    </div>
@endsection

@section ('contenido')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                @php
                    $fechamin = \Carbon\Carbon::now()->subDays(10);
                    $fechamax = \Carbon\Carbon::now()->addDays(10);
                    $getResponsable = $workorder->responsableId;
                    $asset = $workorder->asset;
                @endphp
                @include('work_orders.show_header')
            </div>
        </div>
    </div>
</div>



    {{-- ================================================================================================================================================= --}}
    {{--                                                            Modal Tiempo de trabajo                                                                --}}
    {{-- ================================================================================================================================================= --}}
    {{Form::Open(array('action'=>array('WorkOrdersController@initTimeWork',code($workorder->id)),'method'=>'post','autocomplete'=>'off', 'id'=>'formTimeWork', 'onsubmit'=>'btnSubmitTimeWork.disabled = true; return true;'))}}
        <div class="modal modal-success fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalTime" data-backdrop="static">
            <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-green"></div>
                    <div class="modal-body text-center py-4">
                        <svg class="icon mb-2 text-green icon-xl" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                        <h3>¿Está seguro de iniciar el trabajo?</h3>
                        <div class="text-muted">
                            Una vez iniciado se medirá el tiempo total de trabajo.<br>
                            <i style="font-size:11px"> El reloj se detendrá cuando confirme el <b>ENVIAR INFORME A REVISIÓN</b> ó el <b>TERMINAR CON EDICIÓN</b> en la pestaña "Adjuntar Archivos".</i>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <a class="btn @if(themeMode() == 'D') btn-secondary @endif w-100" data-dismiss="modal">
                                        Cancelar
                                    </a>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-success w-100" name="btnSubmitTimeWork">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{Form::Close()}}

    <div class="modal modalYellow fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalPauseTime" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-yellow"></div>
                <div class="modal-body text-center py-4">
                    <svg class="icon mb-2 text-yellow icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                    <h3>¿Está seguro de poner en pausa el trabajo?</h3>
                    <div class="text-muted">
                        Tiempo de trabajo actual:<br>
                        <div style="font-size:20px">
                            <span class="relojNumeros hours" id="h_modal">{{str_pad($horas, 2, "0", STR_PAD_LEFT)}}</span>
                            <span class="relojNumeros dots">:</span>
                            <span class="relojNumeros minutes" id="m_modal">{{str_pad($mins, 2, "0", STR_PAD_LEFT)}}</span>
                            <span class="relojNumeros dots">:</span>
                            <span class="relojNumeros seconds" id="s_modal">{{str_pad($segs, 2, "0", STR_PAD_LEFT)}}</span>
                        </div>
                    </div>
                    <textarea id="reasonPauseTime" rows="3" class="form-control" style="width:100%; resize:none" placeholder="Escriba el motivo de la pausa" ></textarea>
                    <span class="text-red" id="reasonPauseTime-error"></span>
                </div>

                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a class="btn @if(themeMode() == 'D') btn-secondary @endif w-100" data-dismiss="modal">
                                    Cancelar
                                </a>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-yellow w-100" id="btnConfPauseTime">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section ('scripts')

@endsection