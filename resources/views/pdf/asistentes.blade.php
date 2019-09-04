<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Asistentes del {{isset($datosExtra["subevent"]) ? "subevento ".$datosExtra["subevent"]->nombre : "evento ".$datosExtra["event"]->nombre}} </title>
  <link rel="stylesheet" href="http://front.end/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <center><img src="{{ asset('/img/header.png') }}" style="width: 100%; position: absolute;"></center>

    <h2>Asistentes del {{isset($datosExtra["subevent"]) ? "subevento ".$datosExtra["subevent"]->nombre : "evento ".$datosExtra["event"]->nombre}}</h2>
    
    @isset($asistentes)
      <table class="table table-striped table-bordered">
        <thead class="bg-primary">
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Correo</th>
          </tr>
        </thead>
        <tbody>
          @foreach($asistentes as $asistente)
            <tr>
              <td class="text-center">{{$loop->iteration}}</td>
              <td>{{$asistente['nombre']." ".$asistente['apellidoPaterno']." ".$asistente['apellidoMaterno']}}</td>
              <td>{{$asistente['correo']}}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <h2>No hay asistentes inscritos en este {{isset($datosExtra["subevent"]) ? "subevento" : "evento"}}.</h2>
    @endisset

  </div>
</body>
</html>