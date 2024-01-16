<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .footerTable {
            width: 100%;
            font-size: 14px;
        }
        .footerTable td.linsup {
            border-top: 0.5px solid black;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ public_path('/css/comprobante.css')}}" media="all">
</head>

<html>
    <body style="margin: 0px;" onload="subst()">
        <div>
            @if (file_exists("storage/general/piepagina.png"))
                <img src="{{ public_path('/storage/general/piepagina.png')}}" alt="pie de página" style="width: 900px; height: 70px; padding-left: 0px; padding-top: 20px;">
            @endif
        </div>
    </body>
</html>
<script>
    function subst() {
        var vars={};
        var x=document.location.search.substring(1).split('&');
        for(var i in x) {var z=x[i].split('=',2);vars[z[0]] = unescape(z[1]);}
        var x=['frompage','topage','page','webpage','section','subsection','subsubsection'];
        for(var i in x) {
            var y = document.getElementsByClassName(x[i]);
            var signature_div = document.getElementsByClassName('signature')[0];
                if (vars['page'] == vars['topage']) {
                    signature_div.style.visibility="visible";
                }
            for(var j=0; j<y.length; ++j) y[j].textContent = vars[x[i]];
        }
    }
</script>
