<!DOCTYPE html>

<html>

<head>

	<title>@yield('titulo')</title>
	<meta name="application-name" content="Eventos ITTG">
	<meta name="author" content="Néstor Guz - nestor.guz@outlook.com">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!--<link rel="stylesheet" href="http://front.end/bootstrap/3.3.7/css/bootstrap.min.css">-->

	<style type="text/css">

		.clearfix::after {

			display: block;

			clear: both;

			content: "";

		}

		.logos{

			float: left; 

			height: 10%; 

			max-height: 10%; 

			width: 10%; 

			max-width: 10%;

		}


		.txtLogos{

			line-height: 1;

		}

	    .page-break {
	        page-break-after: always;
	    }
	    
	</style>

</head>

<body>
	
	<div class="clearfix">

		<div class="logos">

			<img src="{{ asset('/img/logo-tecmx.png') }}" class="img-responsive" alt="Logo TEC MX">

		</div>

		<div style="float: left; width: 80%; max-width: 80%;" class="text-center">

			<h3 class="txtLogos">TECNOLÓGICO NACIONAL DE MÉXICO</h3>

			<h4 class="txtLogos">INSTITUTO TECNOLÓGICO DE TUXTLA GUTIÉRREZ</h4>

			<h5 class="txtLogos">SISTEMA DE REGISTRO Y CONTROL DE ASISTENTES DE ACTIVIDADES EN EVENTOS</h5>

		</div>


		<div class="logos">

			<img src="{{ asset('/img/logo-ittg.png') }}" class="img-responsive" alt="Logo ITTG">

		</div>

	</div>

	

	<div class="container">
		@yield('contenido')
	</div>


</body>

</html>