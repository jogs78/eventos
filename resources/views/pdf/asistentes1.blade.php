@extends('layouts.pdf')

@section('titulo', 'Asistentes del '.(isset($subevento) ? "subevento $subevento->nombre": "evento $evento->nombre"))

@section('contenido')

  <h3>Asistentes del {{isset($subevento) ? "subevento $subevento->nombre": "evento $evento->nombre"}}</h3>
  
  @isset($asistentes)
    <table class="table table-striped table-bordered table-condensed">
      <thead class="bg-primary">
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Teléfono</th>
        </tr>
      </thead>
      <tbody>
        @foreach($asistentes as $asistente)
          <tr>
            <td class="text-center">{{$loop->iteration}}</td>
            <td>{{"$asistente->nombre $asistente->apellido_paterno $asistente->apellido_materno"}}</td>
            <td>{{$asistente->email}}</td>
            @isset ($asistente->telefono)
              <td>{{$asistente->telefono}}</td>
            @else
              <td class="text-center"> - </td>
            @endisset
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <h4>No hay asistentes aprobados en este {{isset($subevento) ? "subevento": "evento"}}.</h4>
  @endisset
@endsection
