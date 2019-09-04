@extends('layouts.principal')

@section('titulo', "Registrarse")

@section('descripcion', 'Registrate para poder inscribirte en eventos y subeventos.')

@section('keywords', 'Registro, Registrarse, ITTG, Eventos, Eventos ITTG')

@section('contenido')

@if(request()->has('from') && request()->from == 'inscripcion')
	<div class="alert alert-info">
	  <strong>Registrate o inicia sesión!</strong> Antes de poder inscribirte a algún evento o subevento primero tienes que registrarte, o si ya posees una cuenta inicia sesión.
	</div>
@endif

<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">Registrarse</div>

			<div class="panel-body">

				<form class="form-horizontal">
					{{ csrf_field() }}

					<div class="form-group">
						<label for="nombre" class="col-md-4 control-label">Nombre *</label>

						<div class="col-md-6">
							<input id="nombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required autofocus title="Por favor ingrese su nombre">
						</div>
					</div>

					<div class="form-group">
						<label for="apellidoPaterno" class="col-md-4 control-label">Apellido paterno *</label>

						<div class="col-md-6">
							<input id="apellidoPaterno" type="text" class="form-control" name="apellidoPaterno" placeholder="Apellido paterno" required title="Por favor ingrese su apellido paterno">
						</div>
					</div>

					<div class="form-group">
						<label for="apellidoMaterno" class="col-md-4 control-label">Apellido materno *</label>

						<div class="col-md-6">
							<input id="apellidoMaterno" type="text" class="form-control" name="apellidoMaterno" placeholder="Apellido materno" required title="Por favor ingrese su apellido materno">
						</div>
					</div>

					<div class="form-group">
						<label for="sexo" class="col-md-4 control-label">Sexo *</label>

						<div class="col-md-6">
							<label class="radio-inline"><input type="radio" name="sexo" id="radioSexoH" value="m" required title="Por favor indique su sexo">Masculino</label>
							<label class="radio-inline"><input type="radio" name="sexo" id="radioSexoM" value="f">Femenino</label>
						</div>
					</div>
					
					<div class="form-group">
						<label for="ocupacion" class="col-md-4 control-label">Ocupación</label>

						<div class="col-md-6">
							<input type="text" class="form-control" id="ocupacion" name="ocupacion" placeholder="Ocupación" title="Indique su ocupacion.">
						</div>
					</div>
					
					<div class="form-group">
						<label for="instituto-dependencia" class="col-md-4 control-label">Instituto/Dependencia</label>

						<div class="col-md-6">
							<input type="text" class="form-control" id="instituto-dependencia" name="instituto-dependencia" placeholder="Instituto o Dependencia" title="Indique a que Instituto o Dependencia pertenece.">
						</div>
					</div>

					<div class="form-group">
						<label for="correo" class="col-md-4 control-label">Correo electrónico *</label>

						<div class="col-md-6">
							<input type="email" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" required title="Por favor ingrese su correo electrónico">
						</div>
					</div>

					<div class="form-group">
						<label for="telefono" class="col-md-4 control-label">Teléfono celular</label>

						<div class="col-md-6">
							<input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
						</div>
					</div>

					<div class="form-group">
						<label for="password" class="col-md-4 control-label">Contraseña *</label>

						<div class="col-md-6">
							<input type="password" class="form-control" id="contrasenia" name="contrasenia" placeholder="Contraseña" required title="Por favor ingrese una contraseña">
						</div>
					</div>

					<div class="form-group">
						<label for="contrasenia_confirmacion" class="col-md-4 control-label">Confirmar contraseña *</label>

						<div class="col-md-6">
							<input type="password" class="form-control" id="contrasenia_confirmacion" name="contrasenia_confirmacion" placeholder="Confirmar contraseña" required title="Por favor repita la contraseña">
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-6 col-md-offset-4">
							<button type="submit" class="btn btn-primary btn-block" name='btnRegistrarse' id='btnRegistrarse' onclick="event.preventDefault(); registrarUsuario($(this))"><span class="glyphicon glyphicon-user"></span> Registrarse</button> 
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-6 col-md-offset-4">
							<span class="help-block">* Campos obligatorios.</span>
						</div>
					</div>	

				</form>

				<div class="alert" name="alert"></div>

			</div>
		</div>
	</div>
</div>
@endsection

@section('modal', '')