<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            td.izq{text-align:left;}
            td.der{text-align:right;}
            .cen{text-align:center;}
        </style>
    </head>
    <body>
        <div id="header">
            {{-- IMAGEN DEL ENCABEZADO DE PAGINA --}}
            @if (file_exists("storage/general/piepagina.png"))
                <img src="{{ public_path('/storage/general/encabezado.png') }}" alt="encabezado de pagina" style="width: 900px; height: 70px; padding-left: 0px; padding-top: 100px;">
            @endif
        </div><br>
    </body>
</html>
