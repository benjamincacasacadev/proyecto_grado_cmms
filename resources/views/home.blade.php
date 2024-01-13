@extends ('layouts.admin', ['title_template' => "Seg Tech AMPER"])
@section('extracss')
<style>

</style>
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

</div>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
