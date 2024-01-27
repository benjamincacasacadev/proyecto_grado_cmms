@extends ('layouts.admin', ['title_template' => "Seg Tech AMPER"])
@section('extracss')
<style>
    .highcharts-figure, .highcharts-data-table table {
        min-width: 220px;
    }
    table#table_lastassets td{
        font-size: 12px;
    }
    .modal-body{
        padding: 0.5rem
    }
    table#tableForms td{
        font-size: 12px;
    }
    table#tableForms th{
        text-align: center !important;
    }
    .leftTable{
        text-align: left !important;
    }
    .rightTable{
        text-align: right !important;
    }
    .tui-full-calendar-popup-section{
        min-height: 0px !important;
    }
    .dropCalendar{
        left: -105px !important;
    }
</style>
<link href="{{asset('/templates/tabler/dist/libs/tui.calendar/extra/tui-calendar.css')}}" rel="stylesheet"/>
<link href="{{asset('/templates/tabler/dist/libs/tui.calendar/css/icons.css')}}" rel="stylesheet"/>
@endsection
@section('contenidoHeader')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="page-pretitle">
                    Bienvenido
                </div>
                <h2 class="page-title">
                    {{ userFullName( auth()->user()->id )}}
                </h2>
            </div>
        </div>
    </div>
@endsection
@section('contenido')
<div class="row">
    {{-- Torta ordenes de trabajo --}}
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header-wflex py-2">
                <div class="d-flex">
                    <div class="mr-auto">
                        <h3>Órdenes de trabajo</h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <figure class="highcharts-figure">
                    <div id="workorders_chart" ></div>
                </figure>
            </div>
        </div>
    </div>

    {{-- Slider imagenes --}}
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div id="carousel-indicators" class="carousel slide" data-bs-ride="carousel">
            <ol class="carousel-indicators">
                @foreach (sliderImg() as $ki => $img)
                    <li data-bs-target="#carousel-indicators" data-bs-slide-to="{{ $ki }}" class="@if ($ki == 0) active @endif"></li>
                @endforeach
            </ol>
            <div class="carousel-inner">
                @foreach (sliderImg() as $ki => $img)
                    <div class="carousel-item @if ($ki == 0) active @endif"><img class="d-block w-100" src="{{ $img }}"></div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ======================================================================================================================= --}}
    {{--                                                        CALENDARIO                                                       --}}
    {{-- ======================================================================================================================= --}}
    <div class="col-lg-12" style="margin-top:20px">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cronograma de órdenes de trabajo</h3>
            </div>
            <div class="card-body">
                {{--  OPCIONES EXTRA CALENDARIO --}}
                <div class="row">
                    <div class="mb-3">
                        {{-- TIPO DE VISTA DE CALENDARIO --}}
                        <span class="nav-item dropdown pull-right">
                            <button id="dropdownMenu-calendarType" class="btn btn-default btn-darkcmms btn-pill dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i id="calendarTypeIcon" class="calendar-icon ic_view_month" style="margin-right: 4px;filter: invert(100%) sepia(100%) saturate(14%) hue-rotate(212deg) brightness(104%) contrast(104%) !important;"></i>
                                <span id="calendarTypeName" class="font-weight-bold">Mes</span>&nbsp;
                            </button>
                            <div class="dropdown-menu dropdown-menu-end dropCalendar dropdown-menu-arrow border border-secondary" style="width:230px;margin-right:100px" role="menu" aria-labelledby="dropdownMenu-calendarType">
                                <a class="dropdown-item" role="menuitem" data-action="toggle-monthly">
                                    <svg class="icon" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="5" cy="5" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="19" cy="5" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="19" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                        <circle cx="19" cy="19" r="1" />
                                    </svg> &nbsp;
                                    Mes
                                </a>
                                <a class="dropdown-item" role="menuitem" data-action="toggle-weekly">
                                    <svg class="icon" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="4" y1="6" x2="9.5" y2="6" />
                                        <line x1="4" y1="10" x2="9.5" y2="10" />
                                        <line x1="4" y1="14" x2="9.5" y2="14" />
                                        <line x1="4" y1="18" x2="9.5" y2="18" />
                                        <line x1="14.5" y1="6" x2="20" y2="6" />
                                        <line x1="14.5" y1="10" x2="20" y2="10" />
                                        <line x1="14.5" y1="14" x2="20" y2="14" />
                                        <line x1="14.5" y1="18" x2="20" y2="18" />
                                    </svg> &nbsp;
                                    Semana
                                </a>
                                <a class="dropdown-item" role="menuitem" data-action="toggle-weeks2">
                                    <svg class="icon" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="4" y1="6" x2="9.5" y2="6" />
                                        <line x1="4" y1="10" x2="9.5" y2="10" />
                                        <line x1="4" y1="14" x2="9.5" y2="14" />
                                        <line x1="4" y1="18" x2="9.5" y2="18" />
                                        <line x1="14.5" y1="6" x2="20" y2="6" />
                                        <line x1="14.5" y1="10" x2="20" y2="10" />
                                        <line x1="14.5" y1="14" x2="20" y2="14" />
                                        <line x1="14.5" y1="18" x2="20" y2="18" />
                                    </svg> &nbsp;2 semanas
                                </a>
                                <a class="dropdown-item" role="menuitem" data-action="toggle-weeks3">
                                    <svg class="icon" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="4" y1="6" x2="9.5" y2="6" />
                                        <line x1="4" y1="10" x2="9.5" y2="10" />
                                        <line x1="4" y1="14" x2="9.5" y2="14" />
                                        <line x1="4" y1="18" x2="9.5" y2="18" />
                                        <line x1="14.5" y1="6" x2="20" y2="6" />
                                        <line x1="14.5" y1="10" x2="20" y2="10" />
                                        <line x1="14.5" y1="14" x2="20" y2="14" />
                                        <line x1="14.5" y1="18" x2="20" y2="18" />
                                    </svg> &nbsp;3 semanas
                                </a>
                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" role="menuitem" data-action="toggle-workweek">
                                    <input class="form-check-input" id="toggle-workweek" type="checkbox" checked> &nbsp;Mostrar fines de semana
                                </a>
                                <a class="dropdown-item" role="menuitem" data-action="toggle-start-day-1">
                                    <input class="form-check-input" id="toggle-start-day-1" type="checkbox"> &nbsp;Iniciar semana en lunes
                                </a>
                                <a class="dropdown-item" role="menuitem" data-action="toggle-narrow-weekend">
                                    <input class="form-check-input" id="toggle-narrow-weekend" type="checkbox" checked> &nbsp;Fines de semana estrechos
                                </a>
                            </div>
                        </span>

                        {{-- NAVEGACION Y FILTROS --}}
                        <span id="menu-navi">
                            <button type="button" class="btn btn-darkcmms move-day text-center btnAction" disabled data-action="move-prev">
                                <svg class="icon text-white" data-action="move-prev" width="44" height="44" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="15 6 9 12 15 18" />
                                </svg>
                            </button>
                            <button type="button" class="btn btn-darkcmms move-day text-center btnAction" disabled data-action="move-next">
                                <svg class="icon text-white" data-action="move-next" width="44" height="44" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <polyline points="9 6 15 12 9 18" />
                                </svg>
                            </button>
                            <button type="button" class="btn btn-darkcmms move-today font-weight-bold btnAction" disabled data-action="move-today">HOY</button>
                        </span> &ensp;
                        <b id="renderRange" class="render-range  text-center" style="font-size:20px"></b>
                    </div>
                    <div class="table-responsive">
                        <div id="calendar" style="overflow-y: hidden;min-width:900px"></div>
                        <div id="contenedor_carga_calendar" style="display: none">
                            <div id="carga">
                                <i class="cog cog-lg glyphicon glyphicon-cog"></i>
                                <i class="cog cog-counter cog-md glyphicon glyphicon-cog"></i>
                                <i class="cog cog-sm glyphicon glyphicon-cog"></i>
                                <b style="font-size: 1.2em">Cargando...</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('/plugins/highchart/highcharts.js')}}"></script>
<script src="{{asset('/plugins/highchart/modules/exporting.js')}}"></script>
<script src="{{asset('/plugins/highchart/modules/heatmap.js')}}"></script>
<script src="{{asset('/plugins/moment/moment.js')}}"></script>
<script src="{{asset('/plugins/moment/locale/es.js')}}"></script>

<script>
    $(window).resize(function() {
        graficaWorkOrders();
    })
    $(document).ready(function() {
        graficaWorkOrders();
    });

    function graficaWorkOrders() {
        Highcharts.chart('workorders_chart', {
            colors: ['#76884a','#deb355','#ecd160','#f59f00'],
            chart: {
                type: 'pie',
                spacingBottom: 0,
                spacingTop: 0,
                spacingLeft: 0,
                spacingRight: 0,
                height: 290
            },
            title: {
                text: null
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            exporting: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.percentage:.1f} %',
                        // connectorColor: 'transparent',
                        connectorPadding: 0,
                        distance: 5
                    },
                    showInLegend: true
                }
            },
            legend: {
                itemStyle: {
                    fontSize: '11px',
                },
                itemWidth: 100,
            },

            credits: {
                enabled: false
            },
            series: [{
                name: 'Órdenes de trabajo',
                colorByPoint: true,
                innerSize: '50%',
                data: {!! $jsonDataOT !!}
            }]
        });
    }
</script>

<script src="{{asset('/templates/tabler/dist/libs/tui.calendar/js/tui-code-snippet.min.js')}}"></script>
<script src="{{asset('/templates/tabler/dist/libs/tui.calendar/js/chance.min.js')}}"></script>
<script src="{{asset('/templates/tabler/dist/libs/tui.calendar/extra/tui-calendar.js')}}"></script>
<script src="{{asset('/templates/tabler/dist/libs/tui.calendar/js/data/calendars.js')}}"></script>
<script src="{{asset('/templates/tabler/dist/libs/tui.calendar/js/data/schedules.js')}}"></script>
<script src="{{asset('dist/js/tui-calendar-functions.js?2')}}" ></script>
<script>

    // Ocultar botones de de edit y delete en popover de fechas
    document.getElementById("calendar").addEventListener("click", hideButtons);
    function hideButtons(){
        var buttons = document.getElementsByClassName("tui-full-calendar-section-button")[0];
        var datePopover = document.getElementsByClassName("tui-full-calendar-popup-detail-date")[0];
        if(buttons){
            buttons.style.display = "none";
        }
        if(datePopover){
            datePopover.style.display = "none";
        }
    }

    document.addEventListener('DOMContentLoaded', function () {

        // parametros de filtro
        var client = $('#selectClient').find(':selected').val();
        var user = $('#selectUser').find(':selected').val();
        var program = $('#selectProgram').find(':selected').val();
        var priority = $('#selectPrioridad').find(':selected').val();
        var type = $('#selectType').find(':selected').val();
        var area = $('#selectArea').find(':selected').val();
        var state = $('#selectStateOt').find(':selected').val();
        var home = '1';

        var filters = [client, user, program, priority, type, area, state, home];

        var themeDark = {
            'common.border': '1px solid #666666',
            'common.backgroundColor': '#1f1f1f',
            'common.holiday.color': '#d63417',
            'common.saturday.color': '#f4f6fa',
            'common.dayname.color': '#f4f6fa',
            'common.today.color': '#f4f6fa',
            'month.holidayExceptThisMonth.color': 'rgba(214, 52, 23, 0.4)',
            'month.dayExceptThisMonth.color': 'rgba(255, 255, 255, 0.4)',
            'month.weekend.backgroundColor': 'inherit',
            'month.day.fontSize': '14px',
        };

        var templates = {
            dayGridTitle: function(viewName) {
                var title = '';
                switch(viewName) {
                    case 'allday':
                        title = '<span class="tui-full-calendar-left-content">Todo el día</span>';
                    break;
                }
                return title;
            },
            popupDetailLocation: function(schedule) {
                return '<b>Fecha programada:</b> ' + schedule.location;
            },
        };

        var calendarNew = new tui.Calendar('#calendar', {
            defaultView: 'month',
            taskView: false,
            disableClick: true,
            isReadOnly: true,
            scheduleView: ['allday'],
            disableDblClick: true,
            useDetailPopup: true,
            template: templates,
            @if(themeMode() == 'D')
                theme: themeDark,
            @endif
            month: {
                daynames: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                narrowWeekend: true,
            },
            week: {
                daynames: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                showTimezoneCollapseButton: true,
                timezonesCollapsed: false,
            },
        });

        var start = moment(calendarNew.getDateRangeStart().getTime()).format('YYYY-MM-DD HH:mm Z');
        var end = moment(calendarNew.getDateRangeEnd().getTime()).format('YYYY-MM-DD HH:mm Z');
        var _token = $('input[name="_token"]').val();
        var route = "{{ route('calendario.ots') }}";
        $.ajax({
            url: route,
            method: "POST",
            data: { start: start, end: end, filters: filters, _token: _token},
            beforeSend: function(){
                $("#contenedor_carga_calendar").show();
            },
            success: function (salida) {
                $(".btnAction").removeAttr('disabled','disabled');
                calendarNew.createSchedules(salida);
                $("#contenedor_carga_calendar").hide();
            }
        });

        setRenderRangeText(calendarNew);
        setEventListener(calendarNew, _token, route, filters);
    });
</script>

@endsection
