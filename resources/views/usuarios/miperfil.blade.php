@extends('layouts.principal')

@section('titulo', "Mi perfil")

@section('contenido')

<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">Mi perfil</div>

			<div class="panel-body">

				<form class="form-horizontal">
					{{ csrf_field() }}

					<div class="form-group">
						<label for="nombre" class="col-md-4 control-label">Nombre *</label>

						<div class="col-md-6">
							<input value="{{ Auth::user()->nombre }}" id="nombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required autofocus>
						</div>
					</div>

					<div class="form-group">
						<label for="apellidoPaterno" class="col-md-4 control-label">Apellido paterno *</label>

						<div class="col-md-6">
							<input value="{{ Auth::user()->apellido_paterno }}" id="apellidoPaterno" type="text" class="form-control" name="apellidoPaterno" placeholder="Apellido paterno" required>
						</div>
					</div>

					<div class="form-group">
						<label for="apellidoMaterno" class="col-md-4 control-label">Apellido materno *</label>

						<div class="col-md-6">
							<input value="{{ Auth::user()->apellido_materno }}" id="apellidoMaterno" type="text" class="form-control" name="apellidoMaterno" placeholder="Apellido materno" required>
						</div>
					</div>

					<div class="form-group">
						<label for="sexo" class="col-md-4 control-label">Sexo *</label>

						<div class="col-md-6">
							@if (Auth::user()->sexo == 'm')
								<label class="radio-inline"><input checked="checked" type="radio" name="sexo" id="radioSexoH" value="m">Masculino </label>

								<label class="radio-inline"><input type="radio" name="sexo" id="radioSexoM" value="f">Femenino</label>

							@else
								<label class="radio-inline"><input type="radio" name="sexo" id="radioSexoH" value="m">Masculino</label>	
								
								<label class="radio-inline"><input checked="checked" type="radio" name="sexo" id="radioSexoM" value="f">Femenino</label>
							@endif
						</div>
					</div>

					<div class="form-group">
						<label for="ocupacion" class="col-md-4 control-label">Ocupación</label>

						<div class="col-md-6">
							<input value="{{ Auth::user()->ocupacion }}" type="text" class="form-control" id="ocupacion" name="ocupacion" placeholder="Ocupación" title="Indique su ocupacion.">
						</div>
					</div>
					
					<div class="form-group">
						<label for="procedencia" class="col-md-4 control-label">Instituto/Dependencia</label>

						<div class="col-md-6">
							<input value="{{ Auth::user()->procedencia }}" type="text" class="form-control" id="instituto-dependencia" name="instituto-dependencia" placeholder="Instituto o Dependencia" title="Indique a que Instituto o Dependencia pertenece.">
						</div>
					</div>

					<div class="form-group">
						<label for="correo" class="col-md-4 control-label">Correo electrónico *</label>

						<div class="col-md-6">
							<input value="{{ Auth::user()->email }}" type="email" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" required>
						</div>
					</div>

					<div class="form-group">
						<label for="telefono" class="col-md-4 control-label">Teléfono celular</label>

						<div class="col-md-6">
							<input value="{{ Auth::user()->telefono}}" type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
						</div>
					</div>

					<div class="form-group">
						<label for="password" class="col-md-4 control-label">Contraseña **</label>

						<div class="col-md-6">
							<input type="password" class="form-control" id="contrasenia" name="contrasenia" placeholder="Contraseña">
						</div>
					</div>

					<div class="form-group">
						<label for="contrasenia_confirmacion" class="col-md-4 control-label">Confirmar contraseña **</label>

						<div class="col-md-6">
							<input type="password" class="form-control" id="contrasenia_confirmacion" name="contrasenia_confirmacion" placeholder="Confirmar contraseña">
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-6 col-md-offset-4">
							<button type="submit" class="btn btn-primary btn-block" name='btnActualizar' onclick="event.preventDefault(); actualizarUsuario($(this), {{Auth::user()->id}});" id='btnActualizar'><span class="glyphicon glyphicon-user"></span> Actualizar información</button> 
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-6 col-md-offset-4">
							<span class="help-block">* Campos obligatorios.</span>
						</div>
						<div class="col-md-6 col-md-offset-4">
							<span class="help-block">** Llene estos campos sólo si desea cambiar su contraseña.</span>
						</div>
					</div>	

				</form>

				<div class="alert" name="alert"></div>

			</div>
		</div>
	</div>
</div>
@endsection

@section('modal','')




