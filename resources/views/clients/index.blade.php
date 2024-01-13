@extends ('layouts.admin', ['title_template' => "Clientes"])
@section('extracss')
@endsection

@section ('contenidoHeader')
    <div class="col">
        <div class="page-pretitle">
            {{ nameEmpresa() }}
        </div>
        <h1 class="titulomod">
            <i class=" fa fa-user-tie  icon-tabler"></i>
            Clientes
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="/maestros/create" title="Nuevo cliente">
                <button class="btn btn btn-yellow btn-pill" >
                    <i class="fa fa-plus fa-md" ></i> &nbsp;
                    <span class="d-none d-sm-inline-block">
                        Cliente
                    </span>
                </button>
            </a>
        </div>
    </div>
@endsection

@section ('contenido')
    <div class="table-responsive">
        <table class="table table-vcenter table-center table-sm table-hover" id="tablaClientes">
            <thead>
                <tr>
                    <th width="10%">ESTADO</th>
                    <th width="10%">ESTADO 2 </th>
                    @if (permisoAdmin())
                        <th width="3%">OP.</th>
                    @endif
                </tr>
            </thead>

            <thead role="row">
                <tr class="filters">
                    <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="nombreb"/></td>
                    <td>
                        <select class="form-control text-center" style="width: 100%" name="estadob">
                            <option selected value="t">Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </td>
                    @if (permisoAdmin())
                        <td></td>
                    @endif
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
@endsection