@extends('layouts.pdf')

@section('titulo', 'Datos de depósito del '. ( isset($subevent) ? "subevento ".$subevent["titulo"] : "evento ".$event["titulo"]) )

@section('contenido')
  <h3>Inscripción al {{isset($subevent) ? "subevento ".$subevent['titulo'] : "evento ".$event['titulo']}}</h3>
  
  @isset($event)
    <h4><strong>Datos del evento:</strong> </h4>
    <p><strong>Nombre:</strong> {{$event["titulo"]}}</p>
    <p><strong>Detalles:</strong> {{$event["detalles"]}}</p>
    <p><strong>Fecha:</strong> del {{$event['fechaInicio']}} al {{$event['fechaFin']}}</p>
    <p><strong>Organizador:</strong></p>
    <table class="table table-condensed">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Correo electrónico</th>
          @isset($organizer->telefono)
              <th>Teléfono</th>
          @endisset
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{"$organizer->nombre $organizer->apellido_paterno $organizer->apellido_materno"}}</td>
          <td>{{$organizer->email}}</td>
          @isset($organizer->telefono)
              <td>{{$organizer->telefono}}</td>
          @endisset
        </tr>
      </tbody>
    </table>
  @endisset

  @isset($subevent)
    <h4><strong>Datos del subevento:</strong> </h4>
    <p><strong>Nombre:</strong> {{$subevent["titulo"]}}</p>
    <p><strong>Detalles:</strong> {{$subevent["detalles"]}}</p>
    <p><strong>Lugar:</strong> {{$subevent["lugar"]}}</p>
    <p><strong>Fecha y hora:</strong> {{$subevent['fechaHora']}}</p>
    
    @isset($collaborators)
      <p><strong>Colaboradores del subevento:</strong></p>
      <table class="table table-condensed">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo electrónico</th>
            <th>Teléfono</th>
            <th>Cargo en el subevento</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($collaborators as $collaborator)
          <tr>
            <td>{{"$collaborator->nombre $collaborator->apellido_paterno $collaborator->apellido_materno"}}</td>
            <td>{{$collaborator->email}}</td>
            @isset($collaborator->telefono)
              <td>{{$collaborator->telefono}}</td>
            @else
              <td class="text-center">-</td>
            @endisset
            <td>{{$collaborator->pivot->tipo == "R" ? "Responsable" : "Ayudante"}}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
      <div class="page-break"></div>
    @endisset
  @endisset

  <h4><strong>Datos del asistente:</strong></h4>
  <p><strong>Nombre:</strong> {{"$assistant->nombre $assistant->apellido_paterno $assistant->apellido_materno"}}</p>
  <p><strong>Correo:</strong> {{$assistant->email}}</p>
  <p><strong>Fecha de registro:</strong> {{isset($subevent) ? $subevent['fechaRegistro'] : $event['fechaRegistro']}}</p>

  @isset($assistant->telefono)
    <p><strong>Teléfono:</strong> {{$assistant->telefono}}</p>
  @endisset

  <hr>
  
  <h4><strong>Datos de depósito:</strong></h4>
  <table class="table">
    <thead>
      <tr>
        <th>Monto:</th>
        <th>Detalles:</th>
        <th>Referencia:</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          @foreach (isset($subevent) ? $subevent['precioInscripcion'] : $event['precioInscripcion'] as $precio)
            {{"$precio->descripcion $$precio->precio"}}
            <br>
          @endforeach
        </td>
        <td>{!!str_replace("\n", "<br>",isset($subevent) ? $subevent['informacionPago'] : $event['informacionPago'])!!}</td>
        <td>{{isset($subevent) ? $subevent['referencia'] : $event['referencia']}}</td>
      </tr>
    </tbody>
  </table>

@endsection