@extends('layouts.principal')

@section('titulo', "Acerca de")

@section('descripcion', 'Acerca del sistema de registro y control de asistentes de actividades en eventos.')

@section('keywords', 'Acerca de, ITTG, Eventos ITTG')

@section('contenido')

<h3 class="text-justify">Sistema desarrollado como residencia profesional en la carrera de Ingeniería en Sistemas Computacionales del Instituto Tecnológico de Tuxtla Gutiérrez, Chiapas.</h3>
<hr>
<h4>Datos del proyecto:</h4>
<div class="row">
	<div class="col-sm-2">
		<label>ID:</label> 
		<p>701-08</p> 
	</div>
	<div class="col-sm-10 text-justify">
		<label>Titulo:</label> 
		<p>Sistema de registro y control de asistentes de actividades en eventos (talleres, congresos, conferencias, etc.)</p> 
	</div>
</div>

<h4>Datos de los residentes:</h4> 
<div class="row">
	<div class="col-sm-4">
		<label>No. Control:</label> 
		<p>13270624</p> 
	</div>
	<div class="col-sm-4">
		<label>Nombre:</label> 
		<p>Luis Javier Valencia Ramírez</p> 
	</div>
	<div class="col-sm-4">
		<label>Correo:</label> 
		<p> - </p> 
	</div>
</div>

<div class="row">
	<div class="col-sm-4">
		<label>No. Control:</label> 
		<p>13270576</p> 
	</div>
	<div class="col-sm-4">
		<label>Nombre:</label> 
		<p>Néstor Guzmán Sánchez</p> 
	</div>
	<div class="col-sm-4">
		<label>Correo:</label> 
		<p>nestor.guz@outlook.com</p> 
	</div>
</div>

<h4>Datos del asesor del proyecto:</h4> 
<div class="row">
	<div class="col-sm-6">
		<label>Nombre:</label> 
		<p>M.C.P. Jorge Octavio Guzmán Sánchez</p> 
	</div>
	<div class="col-sm-6">
		<label>Correo:</label> 
		<p>jogs78@gmail.com</p> 
	</div>
</div>

@endsection

@section('modal', '')