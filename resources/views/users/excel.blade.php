
<table>
    <thead>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th>LISTA DE USUARIOS</th>
        </tr>
        <tr>
            <th>Nº</th>
            <th>NOMBRE</th>
            <th>E-MAIL</th>
            <th>ESTADO</th>
            <th>ROL</th>
        </tr>
    </thead>
    <tbody>
        @php $i=1; @endphp
        @foreach ($usuarios as $usr)
            <tr>
                <td class="conb cen">{{$i}}</td>
                <td class="conb">{{userFullName($usr->id)}}</td>
                <td class="conb">{{ isset($usr->email) ? $usr->email : '-'}}</td>
                @php
                    $estado = ($usr->active == 1 ) ? 'Activo' :'Inactivo';
                @endphp
                <td class="conb">{{$estado}}</td>
                <td class="conb">{{ isset($usr->rolUser) ? $usr->rolUser->name : '-'}}</td>
            </tr>
            @php    $i++;   @endphp
        @endforeach
    </tbody>
</table>
