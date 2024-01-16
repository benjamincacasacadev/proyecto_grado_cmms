<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Informes browsershot</title>

        <style type="text/css">
            .highcharts-figure, .highcharts-data-table table {
                min-width: 360px;
                max-width: 800px;
                margin: 1em auto;
            }

            .highcharts-container {
                margin: 0 auto;
            }
        </style>
	</head>
    <body>
        <script src="{{asset('/plugins/highchart/highcharts.js')}}"></script>
        <script src="{{asset('/plugins/highchart/modules/exporting.js')}}"></script>
        <script src="{{asset('/plugins/highchart/modules/heatmap.js')}}"></script>

        @php
            // $container_serie = base64_decode($container_serie);
            $script_serie = base64_decode($script_serie);
            $script_serie = str_replace("____"," ", $script_serie)
        @endphp


    <figure class="highcharts-figure">
        <div id="containerprueba"></div>
    </figure>


    <script>
        {!!$script_serie!!}
    </script>


	</body>
</html>
