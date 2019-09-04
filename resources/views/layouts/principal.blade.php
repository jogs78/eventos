<!DOCTYPE html>

<html>

<head>

	<title>@yield('titulo')</title>

	<meta charset="utf-8">
	<meta name="application-name" content="Eventos ITTG">
	@hasSection('descripcion')
	<meta name="description" content="@yield('descripcion')">
	@endif
	@hasSection('keywords')
	<meta name="keywords" content="@yield('keywords')">
	@endif
	<meta name="author" content="Néstor Guz - nestor.guz@outlook.com">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

	<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	@stack('css')

	<script src="{{ asset('/js/ajax.js') }}"></script>
	
	<script src="{{ asset('/js/formulario.js') }}"></script>

	<script src="{{ asset('/js/buscar.js') }}"></script>

	<script src="{{ asset('/js/usuario.js') }}"></script>

	@stack('scripts')
	
	<style type="text/css">

		a[class^="w3-bar-item"]:link, a[class^="w3-bar-item"]:visited, a[class^="w3-bar-item"]:hover, a[class^="w3-bar-item"]:active {

			text-decoration: none;

		}

		@supports ((position: -webkit-sticky) or (position: sticky)) {

			.sticky-top {

				position: -webkit-sticky;

				position: sticky;

				top: 0;

				z-index: 1020;

			}

		}

		body.modal-open {
			overflow: visible;
		}

		.form-group .form-control-feedback {
			/*top: 0;
			right: 44px;
			pointer-events: initial; /* or - auto // or -  unset  */
			pointer-events: initial;
		}
		span.form-control-feedback.fas, span.form-control-feedback.far{
			padding-top: 10px;
		} 

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



		.font-weight-bold {

  			font-weight: 700 !important;

  			color: #646464;

		}



		@media screen and (max-width:600px) {

			.txtLogos{

				font-size: .3em;

			}

			.navbar-inverse .navbar-nav > .open:after{ 

				display: none;

			}



		}

		.navbar-inverse .navbar-nav > .open > a,

		.navbar-inverse .navbar-nav > .open > a:hover,

		.navbar-inverse .navbar-nav > .open > a:focus {

			color:#fff!important;

			background-color:#2196F3!important;	

			border-bottom:6px solid #2196F3!important;

		}



		.navbar-inverse .navbar-nav > .open:after{

			content: "";

			position: absolute;

			top: 100%;

			left: 50%;

			margin-left: -10px;

			margin-top: -9px;

			width: 0;

			height: 0;

			border-left: 10px solid transparent;

			border-right: 10px solid transparent;

			border-bottom: 10px solid white;

			z-index: 1000;

		}



		/*** Estilos personalizados al menú desplegable ***/

		.dropdown-menu {

			padding: 0;

		}



		/*Elementos a*/

		.dropdown-menu a {

			float: none;

			color: black;

			padding: 12px 16px;

			text-decoration: none;

			display: block;

			text-align: left;

			white-space: nowrap;
			
		}



		.dropdown-menu form{

			padding: 10px 10px;

		}



		/*Hover elementos a*/

		.dropdown-menu a:hover {

			background-color: #ddd;

		}



		/*Flecha arriba*/

		/*.caret.caret-up {

			border-top-width: 0;

			border-bottom: 4px solid #fff;

		}*/

		/*** Fin Menú desplegable ***/



		.col-container {

			display: table; /* Make the container element behave like a table */

			width: 100%; /* Set full-width to expand the whole page */

		}



		.col {

			display: table-cell; /* Make elements inside the container behave like table cells */

		}

		/*** Footer siempre hasta al final aunque haya poco contenido***/
		
		footer {
			height: 100%;
			display: block;
			background-color: #555555;
			color: white;
			padding: 15px;
		}

		html, body {
			height: 100%;
		}

		#main {
			min-height: 100%;
			height: auto !important;
			height: 100%;
			margin: 0 auto -60px;
		}
		
		#footer{
			height: auto !important;
			margin-top: 100px;
			background-color: #555555;
			color: white;
			font-size: 17px;
			text-align: center;
			width: 100%;
		}
		

		.animate-blink{

			animation:blink 3s infinite;

		}



		@keyframes blink{

			0%{opacity:.5}

			50%{opacity:1}

			100%{opacity:.5}

		}



		.navbar-toggle:after{

			font-family: "Glyphicons Halflings";

			content: "\e014";

		}

		.navbar-toggle.collapsed:after{

			content: "\e236";

		}



		.navbar-toggle,

		.navbar-toggle:focus{

			border: 0;

			border-style: none;

			color:black!important;

			border-bottom: 6px solid white!important;

			border-radius: 0;

			background-color:white!important;

		}



		.navbar-toggle:hover{

			color:#2196F3!important;

			border-bottom: 6px solid #2196F3!important;

		}





		.dropdown > .dropdown-toggle:after{

			font-family: 'Glyphicons Halflings';

			font-size: 0.5em;

			margin-left: 0.5em;

			content: "\e252";

		}



		.dropdown.open > .dropdown-toggle:after{

			content: "\e253";

		}



		.show-more-less:after{

			font-family: 'Glyphicons Halflings';

			content: "\2212";

		}



		.show-more-less.collapsed:after{

			font-family: 'Glyphicons Halflings';

			content: "\002b";

		}



		.navbar-item{

			color:#000!important;

			background-color:#fff!important;

			border-color:#fff!important;

			border-bottom:6px solid #fff!important;

		}



		.navbar-item:hover{

			background-color:transparent!important;

			border-color:#2196F3!important;

			color: #2196F3!important;

		}
		
		textarea{
			max-width: 100%;
		}

		.has-info .control-label{
		  color: #337ab7;
		}

	</style>

	<script type="text/javascript">

		$(document).ready(function(){

			

			/*Flecha arriba/abajo en menús desplegables*/

			/*

			$(".dropdown").on("hide.bs.dropdown", function(){

				$(this).find("span").removeClass("caret-up");

			});

			$(".dropdown").on("show.bs.dropdown", function(){

				$(this).find("span").addClass("caret-up");

			});

			*/

		});

	</script>

</head>

<body>

	<!--

	<div class="col-container w3-animate-zoom" style="background-color: #FAFAFA;">

		<div class="col">

			<img src="img/logo-sep.png" class="img-responsive" alt="Logo SEP">

		</div>

		<div class="col text-center">

			<h3 class="txtLogos font-weight-bold">TECNOLÓGICO NACIONAL DE MÉXICO</h3>

			<h4 class="txtLogos">INSTITUTO TECNOLÓGICO DE TUXTLA GUTIÉRREZ</h4>

			<h5 class="txtLogos">SISTEMA DE REGISTRO Y CONTROL DE ASISTENTES DE ACTIVIDADES EN EVENTOS</h5>

		</div>

		<div class="col">

			<img src="img/logo-mx.png" class="img-responsive" alt="Escudo México">

		</div>

		<div class="col">

			<img src="img/logo-ittg.png" class="img-responsive" alt="Logo ITTG">

		</div>

		

	</div>

	-->



	<div class="clearfix w3-animate-zoom" style="background-color: #FAFAFA;">
		
		<div class="logos">

			<img src="/img/logo-sep.png" class="img-responsive" alt="Logo SEP">

		</div>

		<div class="logos">

			<img src="/img/logo-mx.png" class="img-responsive" alt="Escudo México">

		</div>



		<div style="float: left; width: 60%; max-width: 60%;" class="text-center">

			<h3 class="txtLogos font-weight-bold">TECNOLÓGICO NACIONAL DE MÉXICO</h3>

			<h4 class="txtLogos">INSTITUTO TECNOLÓGICO DE TUXTLA GUTIÉRREZ</h4>

			<h5 class="txtLogos">SISTEMA DE REGISTRO Y CONTROL DE ASISTENTES DE ACTIVIDADES EN EVENTOS</h5>

		</div>


		<div class="logos">

			<img src="/img/logo-ittg.png" class="img-responsive" alt="Logo ITTG">

		</div>

		<div class="logos">

			<img src="/img/logo-tecmx.png" class="img-responsive" alt="Logo TEC MX">

		</div>
		
		<!-- CIME
		<img src="/img/logo-cime.png" class="img-responsive pull-left" alt="Logo CIME" style="display: inline; max-width: 50%; height: 93px;">

		<img src="/img/logo-ittg.png" class="img-responsive pull-right" alt="Logo ITTG" style="display: inline; max-width: 50%;">
		-->
	
	</div>



	<nav class="navbar navbar-inverse sticky-top w3-white w3-border">

		<div class="container-fluid w3-animate-left">

			<div class="navbar-header">

				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myNavbar">

					<!--<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>-->                        

				</button>



				<a class="w3-bar-item w3-button w3-hover-none w3-border-white w3-bottombar w3-hover-border-blue w3-hover-text-blue" href="/"><span class="glyphicon glyphicon-home"></span></a>

			</div>

			<div class="collapse navbar-collapse w3-border-0" id="myNavbar">

				<ul class="nav navbar-nav">

					<li><a class="navbar-item" href="{{ route('contacto.formulario') }}">Contacto</a></li>

					<li><a class="w3-white w3-hover-none w3-border-white w3-bottombar w3-hover-border-blue w3-hover-text-blue" href="{{ route('acerca-de') }}">Acerca de</a></li>

					@if(Auth::check() && Auth::user()->esAdministrador())
						<li><a class="w3-white w3-hover-none w3-border-white w3-bottombar w3-hover-border-blue w3-hover-text-blue" href="{{route('usuarios')}}">Usuarios</a></li>
					@endif

					@can('verPanelEventos', 'App\Evento')
						<li><a class="w3-white w3-hover-none w3-border-white w3-bottombar w3-hover-border-blue w3-hover-text-blue" href="{{route('eventos')}}">Eventos</a></li>
					@endcan

					@can('verInscripciones', 'App\Asistente')
						<li><a class="w3-white w3-hover-none w3-border-white w3-bottombar w3-hover-border-blue w3-hover-text-blue" href="{{route('asistente.inscripciones')}}">Inscripciones</a></li>
					@endcan
					
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@guest
						<li><a href="{{ route('usuarios.registro') }}" class="navbar-item">Registrarse</a></li>

						<li class="dropdown">
							<a class="dropdown-toggle navbar-item" data-toggle="dropdown" href="#">Entrar</a>

							<div class="dropdown-menu w3-animate-opacity">

								<form id="formlogin" class="form container-fluid" method="POST" action="{{ route('entrar') }}" autocomplete="on">

									{{ csrf_field() }}

									<div class="form-group has-feedback">

										<label for="email" class="control-label">Email:</label>

										<input class="form-control" name="email" type="text" id="email" placeholder="Correo electrónico" aria-describedby="basic-addon2" title="Por favor ingrese su correo electrónico." required>

										<span class="fas fa-at form-control-feedback"></span>

									</div>

									<div class="form-group has-feedback">

										<label for="password" class="control-label">Contraseña:</label>

										<input class="form-control" name="password" type="password" id="password" placeholder="Contraseña" aria-describedby="basic-addon2" title="Por favor ingrese su contraseña." required>

										<span class="fas fa-lock form-control-feedback" onclick="mostrarOcultarContrasenia()" title="Mostrar/Ocultar contraseña"></span>

									</div>

									<div class="form-group">
										<div class="checkbox">
											<label>
												<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="remember"> Recuérdame
											</label>
										</div>
									</div>

									<button type="submit" id="btnLogin" class="btn btn-block w3-hover-blue" onclick="event.preventDefault(); iniciarSesion($(this))">Entrar</button>

								</form>

								<div class="container-fluid">

									<a class="small" href="{{ route('password.request') }}">¿Olvidó su contraseña?</a>

								</div>

							</div>
						</li>
					@else
						<li class="dropdown">

							<a class="dropdown-toggle navbar-item" data-toggle="dropdown" href="#">{{ Auth::user()->nombre }}</a>

							<div class="dropdown-menu">
								<a href="{{ route('usuarios.miperfil') }}"><span class="fas fa-user-circle"></span> Mi perfil</a>

		                        <a href="{{ route('salir') }}"
		                            onclick="event.preventDefault();
		                                     document.getElementById('logout-form').submit();">
		                            <span class="fas fa-sign-out-alt"></span> Salir
		                        </a>
	                            <form id="logout-form" action="{{ route('salir') }}" method="POST" style="display: none;">
	                                {{ csrf_field() }}
	                            </form>
							</div>

						</li>

					@endguest
		
				</ul>

			</div>

		</div>

	</nav>

	<div class="container w3-animate-opacity" id="main">
		
		@section('modal')
		<!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<div name="modal-header"></div>
					</div>
					<div class="modal-body"></div>
					<div class="alert alert-success" name="alert" style="display: none;"></div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
		@show

		@auth
			@unless(Auth::user()->esVerificado())
				<div class="alert alert-info">
			  		<strong>¡Verifíca tu cuenta!</strong> Al registrarte se te envió un mensaje a tu correo con las indicaciones para verificar tu cuenta.
			  		<p><a href="#" onclick="event.preventDefault(); reenviarCorreoVerificacion($(this), {{ Auth::id() }} )">Si no te llegó el mensaje has clic aquí para reenviarlo.</a></p>
				</div>
			@endunless
		@endauth

		@if (session('estado'))
		    <div class="alert alert-{{session('estado')}}">
		        {{ session('mensaje') }}
		    </div>
		@endif

		{{ csrf_field() }}

		@yield('contenido')

	</div>



	<footer id="footer">

		<!--<i class="fab fa-500px animate-blink"></i>-->
		<p>Eventos ITTG (<a href="mailto:{{ config('mail.from.address') }}" class="w3-bar-item w3-hover-text-blue">{{ config('mail.from.address') }}</a>)</p>
		<p><a href="https://www.ittg.edu.mx" target="_blank" class="w3-bar-item w3-hover-text-blue">Instituto Tecnológico de Tuxtla Gutiérrez</a></p>
		<p>Carretera Panamericana Km. 1080, C.P. 29050, Apartado Postal: 599,</p>
		<p>Tel. (961)61 5 04 61 Fax: (961)61 5 16 87</p>
	</footer>

	<script type="text/javascript">
	@yield('script')
	</script>


</body>

</html>