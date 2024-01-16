// as
function setRenderRangeText(cal) {
    var renderRange = document.getElementById('renderRange');
    var options = cal.getOptions();
    var viewName = cal.getViewName();
    var html = [];

    if (viewName === 'day') {
        html.push(moment(cal.getDate().getTime()).format('LL'));
    } else if (viewName === 'month' && (!options.month.visibleWeeksCount || options.month.visibleWeeksCount > 4)) {
        var mes = moment(cal.getDate().getTime()).format('MMMM');
        var anio = moment(cal.getDate().getTime()).format('YYYY');
        html.push(mes+" de "+anio);
    } else {
        var fechaIni = moment(cal.getDateRangeStart().getTime()).format('DD/MM/YY');
        var fechaFin = moment(cal.getDateRangeEnd().getTime()).format('DD/MM/YY');
        html.push(fechaIni);
        html.push(' ~ ');
        html.push(fechaFin);
    }
    renderRange.innerHTML = html.join('');
}


function setEventListener(cal, _token, route, filters) {
    $('.dropdown-menu a[role="menuitem"]').on('click', function (e) {
        var target = $(e.target).closest('a[role="menuitem"]')[0];
        var action = target.dataset ? target.dataset.action : target.getAttribute('data-action');
        var options = cal.getOptions();
        var viewName = '';

        switch (action) {
            case 'toggle-daily':
                viewName = 'day';
            break;
            case 'toggle-weekly':
                options.month.visibleWeeksCount = 1;
                viewName = 'month';
            break;
            case 'toggle-monthly':
                options.month.visibleWeeksCount = 0;
                viewName = 'month';
            break;
            case 'toggle-weeks2':
                options.month.visibleWeeksCount = 2;
                viewName = 'month';
            break;
            case 'toggle-weeks3':
                options.month.visibleWeeksCount = 3;
                viewName = 'month';
            break;
            case 'toggle-narrow-weekend':
                options.month.narrowWeekend = !options.month.narrowWeekend;
                options.week.narrowWeekend = !options.week.narrowWeekend;
                viewName = cal.getViewName();

                target.querySelector('input').checked = options.month.narrowWeekend;
            break;
            case 'toggle-start-day-1':
                options.month.startDayOfWeek = options.month.startDayOfWeek ? 0 : 1;
                options.week.startDayOfWeek = options.week.startDayOfWeek ? 0 : 1;
                viewName = cal.getViewName();

                target.querySelector('input').checked = options.month.startDayOfWeek;
            break;
            case 'toggle-workweek':
                options.month.workweek = !options.month.workweek;
                options.week.workweek = !options.week.workweek;
                viewName = cal.getViewName();

                target.querySelector('input').checked = !options.month.workweek;
            break;
            default:
            break;
        }

        cal.setOptions(options, true);
        cal.changeView(viewName, true);

        setDropdownCalendarType(cal);
        setRenderRangeText(cal);
        setSchedules(cal, _token, route, filters);
    });

    var dtTimer;
    $('#menu-navi').on('click',function (e) {
        cal.clear();
        var target = e.target;
        var action = target.dataset ? target.dataset.action : target.getAttribute('data-action');
        switch (action) {
            case 'move-prev':
                cal.prev();
            break;
            case 'move-next':
                cal.next();
            break;
            case 'move-today':
                cal.today();
            break;
            default:return;
        }
        $("#contenedor_carga_calendar").show();
        setRenderRangeText(cal);
        cal.clear();
        clearTimeout(dtTimer);
        dtTimer = setTimeout(function(){
            setSchedules(cal, _token, route, filters);
        }, 700);
    });
}

function setSchedules(cal, _token, route, filters) {
    cal.clear();
    var start = moment(cal.getDateRangeStart().getTime()).format('YYYY-MM-DD HH:mm Z');
    var end = moment(cal.getDateRangeEnd().getTime()).format('YYYY-MM-DD HH:mm Z');
    $.ajax({
        url: route,
        method: "POST",
        data: { start: start, end: end, filters: filters, _token: _token},
        beforeSend: function(){
            $(".btnAction").attr('disabled','disabled');
            $("#contenedor_carga_calendar").show();
        },
        success: function (salida) {
            $(".btnAction").removeAttr('disabled','disabled');
            $("#contenedor_carga_calendar").hide();
            cal.createSchedules(salida);
        }
    });
}


function setDropdownCalendarType(cal) {
    var calendarTypeName = document.getElementById('calendarTypeName');
    var calendarTypeIcon = document.getElementById('calendarTypeIcon');
    var options = cal.getOptions();
    var type = cal.getViewName();
    var iconClassName;

    if (type === 'day') {
        type = 'Día';
        iconClassName = 'calendar-icon ic_view_day';
    } else if (options.month.visibleWeeksCount === 1) {
        type = 'Semana';
        iconClassName = 'calendar-icon ic_view_week';
    } else if (options.month.visibleWeeksCount === 2) {
        type = '2 semanas';
        iconClassName = 'calendar-icon ic_view_week';
    } else if (options.month.visibleWeeksCount === 3) {
        type = '3 semanas';
        iconClassName = 'calendar-icon ic_view_week';
    } else {
        type = 'Mes';
        iconClassName = 'calendar-icon ic_view_month';
    }

    calendarTypeName.innerHTML = type;
    calendarTypeIcon.className = iconClassName;
}
