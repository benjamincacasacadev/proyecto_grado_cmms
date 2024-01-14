<link rel="stylesheet" href="{{asset('/templates/tabler/dist/css/tabler.min.css?1')}}" />
<link rel="stylesheet" href="{{asset('/templates/tabler/dist/css/icons-fe.css')}}" />
<link rel="stylesheet" href="{{asset('/templates/tabler/dist/libs/introjs/introjs.css')}}"/>
<link rel="stylesheet" href="{{asset('/plugins/bootstrap/dist/css/gliphycons.css')}}">
<link rel="stylesheet" href="{{asset('/plugins/font-awesome1/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{asset('/plugins/font-awesome/css/all.css')}}">
<link rel="stylesheet" href="{{asset('/plugins/datatables.net-bs/css/dataTables.bootstrap.min.css?1')}}">
<link rel="stylesheet" href="{{asset('/plugins/datatables.net-bs/css/datatables.mark.min.css')}}">
<link rel="stylesheet" href="{{asset('/plugins/datatables.net-bs/css/fixedHeader.dataTables.min.css')}}">
<link rel="stylesheet" href="{{asset('/plugins/datatables.net-bs/css/fixedColumns.dataTables.min.css')}}">
<link rel="stylesheet" href="{{asset('/plugins/datatables.net-bs/css/buttons.dataTables.min.css?1')}}">
<link rel="stylesheet" href="{{asset('/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css?1')}}">
<link rel="stylesheet" href="{{asset('/plugins/select2/dist/css/select2.min.css?1')}}">
<link rel="stylesheet" href="{{asset('/dist/css/colors_Datetime.css?1')}}">
<link rel="stylesheet" href="{{asset('/dist/css/carga.css?2')}}">
<link rel="stylesheet" href="{{asset('/dist/css/loader.css?1')}}">
<link rel="stylesheet" href="{{asset('/dist/css/general.css?1')}}">
<link rel="stylesheet" href="{{asset('/dist/css/generalLineaGrafica.css?1')}}">
<link rel="stylesheet" href="{{asset('/plugins/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('/dist/css/datetimepicker.css')}}">

<style>
    .offcanvas {
        background-color: #f8fafc !important;
    }
    /* ESTILOS MOSTRAR OCULTAR COLUMNA DATATABLE TEMA CLARO*/
    button.dt-button{
        color: #212529;
        border-color: #d2d5da;
        background: #f8f9fa !important;
        font-weight: 500;
    }

    button.dt-button:hover{
        background-color: #e2e6ea !important;
        color: #212529;
        border-color: #d2d5da !important;
    }
    .fl-main-container {
        max-width: 50em !important;
        position: relative;
        bottom: 800px !important;
    }
</style>

<style>
    a.scroll-top {
        color: #fff;
        background: #1f1f1f;
        border: 1px solid hsla(0, 0%, 78%, 0.3)
    }
</style>

<style>
    .cke_notification_close{
        filter: invert(100%) sepia(0%) saturate(6316%) hue-rotate(204deg) brightness(123%) contrast(97%) !important;
    }
    /* estilos para validar input */
    .is-invalid-select{
        border-color: #cd201f;
        padding-right: calc(2.1em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23dadcde' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"), url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23d63939' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cline x1='18' y1='6' x2='6' y2='18'%3e%3c/line%3e%3cline x1='6' y1='6' x2='18' y2='18'%3e%3c/line%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right .75rem center, center right 2.25rem;
        background-size: 16px 12px, calc(.7142857em + .4375rem) calc(.7142857em + .4375rem)
    }
    .is-valid-select{
        border-color: #2fb344;
        padding-right: 4.125rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23dadcde' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"), url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%232fb344' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='20 6 9 17 4 12'%3e%3c/polyline%3e%3c/svg%3e");
        background-position: right .75rem center, center right 2.25rem;
        background-size: 16px 12px, calc(.7142857em + .4375rem) calc(.7142857em + .4375rem)
    }
</style>