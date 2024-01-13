<html>
    <head>
        <meta charset="UTF-8">
        <title>Lista de usuarios</title>

        <style>
            table.tablaEjemplo td.conb ,th{
                border: solid 1px black;
                padding: 5px;
            }
            .conb{
                border: solid 1px black;
                padding: 5px;
            }
            .center{
                text-align:center;
            }
        </style>

    </head>
    <body >

        <div> {{-- DIV QUE ENCIERRA A TODO EL PDF --}}
            <div style="page-break-inside: avoid;">
                <table >
                    <tr>
                        <td  width="90%" style="font-size:18px">
                            <b>
                                {{nameEmpresa()}} <br>
                            </b>
                        </td>
                        <td width="10%"  style="padding-left:200px" >
                            <img src="{{public_path('/logo.png')}}" style="max-width: 100px; max-height: 100px">
                        </td>
                    </tr>
                </table>
                <br>
                <table style="font-size: 18px; width: 100%; " >
                    <tr>
                        <td colspan="8">
                            <span  style="font-size: 22px;"> <center> <b> LISTA DE USUARIOS </b> </center></span>
                        </td>
                    </tr>
                </table>
                <br><br>

                <table id="tablaEjemplo" style="width: 100%; border-collapse: collapse; border-spacing: 0;" >
                    <tr style="background-color: #F7A600; color:white">
                        <th class="text-center" width="5%">Nº</th>
                        <th class="text-center" width="30%">Nombre</th>
                        <th class="text-center" width="10%">E-mail</th>
                        <th class="text-center" width="10%">Estado</th>
                        <th class="text-center" width="20%">Rol</th>
                    </tr>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach ($users as $usr)
                            <tr class="conb">
                                <td class="conb center">{{$i}}</td>
                                <td class="conb">{{userFullName($usr->id)}}</td>
                                <td class="conb center">{{ isset($usr->email) ? $usr->email : '-'}}</td>
                                @php
                                    $estado = ($usr->active == 1 ) ? 'Activo' :'Inactivo';
                                @endphp
                                <td class="conb center">{{$estado}}</td>
                                <td class="conb center">{{ isset($usr->rolUser) ? $usr->rolUser->name : '-'}}</td>
                            </tr>
                            @php    $i++;   @endphp
                        @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </body>
    </html>


