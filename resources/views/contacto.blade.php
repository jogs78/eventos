@extends('layouts.principal')

@section('titulo', "Contacto")

@section('descripcion', 'Contactanos utilizando el siguiente formulario.')

@section('keywords', 'Contacto, ITTG, Eventos ITTG')


@push('scripts')
	<script src="{{ asset('/js/contacto.js') }}"></script>
@endpush

@section('contenido')

<h4>Contactanos llenando el siguiente formulario, o bien, puedes mandar tu mensaje al correo electrónico <strong>{{ config('mail.from.address') }}</strong></h4>

<form action="{{ route('contacto.mensaje') }}" method="POST">
	
	{{ csrf_field() }}

	<div class="form-group">
		<label for="email">Correo electrónico:</label>
		<input type="email" class="form-control" id="correo" name="correo" placeholder="Tu correo electrónico" required="required">
	</div>

	<div class="form-group">
		<label for="asunto">Asunto:</label>
		<input type="text" class="form-control" id="asunto" name="asunto" placeholder="Asunto del mensaje" required="required">
	</div>

	<div class="form-group">
		<label for="mensaje">Mensaje:</label>
		<textarea class="form-control" id="mensaje" name="mensaje" placeholder="Lo que nos quieres hacer saber" rows="8" required="required"></textarea>

	</div>
	
	<div class="alert" name="alert"></div>

	<button type="submit" class="btn btn-default pull-right" onclick="event.preventDefault(); enviarMensajeContacto($(this))">Enviar</button>

</form> 

@endsection

@section('modal', '')