@extends('layouts.principal')
@section('titulo', "$evento->nombre - $subevento->nombre - Asistentes")
@push('scripts')
    <script src="{{ asset('/js/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('/js/fecha.js') }}"></script>
    <script src="{{ asset('/js/colaborador.js') }}"></script>
    <script src="{{ asset('/js/evento.js') }}"></script>
    <script src="{{ asset('/js/subevento.js') }}"></script>
    <script src="{{ asset('/js/asistente.js') }}"></script>
@endpush
@section('contenido')
<div class="container">
    
    <ul class="breadcrumb">
        <li><a href="{{ route('eventos') }}">Eventos</a></li>
        <li><a href="#" onclick="event.preventDefault(); modalEvento({{$evento->id}})">{{$evento->nombre}}</a></li>
        <li><a href="{{ route('evento.subeventos', $evento->id) }}">Subeventos</a></li>
        <li><a href="#" onclick="event.preventDefault(); modalSubevento({{$evento->id}},{{$subevento->id}})">{{$subevento->nombre}}</a></li>
        <li class="active">Asistentes</li>
    </ul>

    <div class="text-right hidden-print" name="contenedorOpciones">
        <button type="button" class="btn btn-lg btn-primary" name="btnEnviarMensaje" id="btnEnviarMensaje" title="Enviar mensaje a todos los asistentes."> <span class="far fa-envelope" aria-hidden="true"></span> Mensaje a todos los asistentes</button>
        <a role="button" class="btn btn-lg btn-success" href="{{ route('asistentesSubeventoPDF', $subevento->id) }}" title="Descargar lista de asistentes aprobados."> <span class="far fa-file-pdf" aria-hidden="true"></span> Lista de asistentes aprobados</a>

    </div>
    <div class="row hidden-print" name="contenedorOpciones">
        <div class="col-md-4">
            <h4>Filtrar por estado de inscripción</h4>
            <select class="form-control" id="selectEstadoAsistente" name="selectEstadoAsistente" data-filtro="estado">
                <option value="">Todos los estados</option>
                <option value="0">Registrado</option>
                <option value="1">Por aprobar</option>
                <option value="2">Aprobado</option>
            </select>
        </div>

        <div class="col-md-4">
            <h4>Ordenar por</h4>
            <div class="dropdown">
                <p id="sortByNombre" name="sortBy" role="button" class="col-md-2 dropdown-toggle" value="nombre">Nombre</p>
            </div>
            <div class="dropdown">
                <p id="sortByCorreo" name="sortBy" role="button" class="col-md-2 col-md-offset-2 dropdown-toggle" value="correo">Correo</p>
            </div>

            <div class="dropdown">
                <p id="sortByEstado" name="sortBy" role="button" class="col-md-2 col-md-offset-2 dropdown-toggle" value="estado">Estado</p>
            </div>

        </div>

        <div class="col-md-4">
            <h4>Buscar</h4>
            <div class="form-group has-feedback" title="Buscar Usuario">
                <input type="text" class="form-control" id="buscarAsistente" onkeyup="buscar($(this).val(), $('#listadoAsistentes div[class~=\'panel\']'))">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
        </div>

    </div>

    <div class="row" id="listadoAsistentes"></div>

</div>
@endsection

@section('script')
//<script type="text/javascript">
    const asistenteRegistrado = 0;
    const asistentePorAprobar = 1;
    const asistenteAprobado = 2;
    const pagoPorEvento = {{ isset($evento->precio_inscripcion) ? 1 : 0 }};

    function cargarAsistentes(filtro = ""){     
        
        if(filtro != ""){
            filtro = "?"+filtro;

            if(filtro.search("sortBy") == -1 && filtro.search("sortByDesc") == -1){
                filtro += "&sortBy=estado";                
            }
        }
        else{
            filtro = "?sortBy=estado";
        }

        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'GET',
            url : "{{ url('subevents') }}/{{$subevento->id}}"+"/assistants"+filtro,
            dataType: 'json',
            success: function(result){
                $("#listadoAsistentes").empty();
                if(result.data.length > 0){
                    $.each(result.data, function (i, asistente) {
                        $("#listadoAsistentes").append(panelAsistente(asistente));
                        evaluarElementosPanelAsistente(asistente, {{$subevento->id}}, 'subevento');
                    });

                    $("#btnEnviarMensaje").removeAttr("disabled");
                }
                else{
                    $("#listadoAsistentes").append('<h2 class="text-center">Sin asistentes.</h2>');
                    //$('[name="contenedorOpciones"]').remove();
                    $("#btnEnviarMensaje").attr("disabled", "disabled");
                }

            },
            error: function(jqXHR, textStatus, errorThrown){

            }
        });
    }

    cargarAsistentes();


    $("#selectEstadoAsistente").change(function(){

        var filtro = "";

        if($(this).val() != ""){
            filtro = $(this).attr("data-filtro")+"="+$(this).val();
        }
        
        if($('[data-sort]').length > 0){
            if(filtro != ""){
                filtro += "&";
            }

            filtro += $('[data-sort]').attr("data-sort")+"="+$('[data-sort]').attr("value");
        }

        cargarAsistentes(filtro);
    });

    $('[name="sortBy"]').click(function(){
        if($(this).parent().hasClass("open")){
            $(this).attr("data-sort", "sortByDesc");
        }
        else{
            $(this).attr("data-sort", "sortBy");   
        }

        $(this).parent().toggleClass("open");
        $('[name="sortBy"]').not($(this)).parent().removeClass("open");
        $('[name="sortBy"]').not($(this)).removeAttr("data-sort");

        var filtro = $(this).attr("data-sort")+"="+$(this).attr("value");
        
        if($("#selectEstadoAsistente").val()){
            filtro += "&"+$("#selectEstadoAsistente").attr("data-filtro")+"="+$("#selectEstadoAsistente").val();
        }
        cargarAsistentes(filtro);
    });


    function eliminarAsistente(asistente){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'DELETE',
            url : "{{ url('subevents') }}/{{$subevento->id}}"+"/assistants/"+asistente,
            dataType: 'json',
            success: function(result){
                $("#myModal").modal("hide");
                $("#contenedor"+asistente)
                    .find('.panel-footer')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .html('<strong>Asistente eliminado correctamente</strong>')
                    .fadeIn();
                    

                setTimeout(function(){$("#contenedor"+asistente).fadeOut().remove();}, 3000);
            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#myModal").modal("hide");

                $("#contenedor"+asistente)
                    .find('.panel-footer')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html('<strong>'+jqXHR.responseJSON.error+'</strong>')
                    .fadeIn();
            }
        });
    }

    /*Evento click para el botón eliminar*/
    $("#listadoAsistentes").on("click", "[name='btnEliminar']", function(){

        $('[name="alert"]').nextAll('.alert').remove();

        $("#myModal").modal("show");

        $("#myModal")
            .find(".modal-content")
            .css({
                'border-color' : '#c9302c'
            });

        $("#myModal")
            .find(".modal-header")
            .css({
                'background-color' : '#d9534f',
                'color' : 'white'
            });



        $("#myModal")
            .find('[name="modal-header"]')
            .empty()
            .append('<h4 class="modal-title">Eliminar asistente</h4>');

        $("#myModal")
            .find('.modal-body')
            .empty()
            .append('<p>¿Realmente quiere eliminar al asistente <strong>'+$(this).val()+'</strong>?, esta acción no se puede deshacer.</p>');

        $("#myModal")
            .find('.modal-footer')
            .empty()
            .append(
                '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>'+
                '<button type="button" class="btn btn-danger" onclick="eliminarAsistente('+$(this).attr("id")+')">Eliminar</button>'
                );

    });

    function enviarMensaje(usuario){
        if(validarCampoMensaje()){
            return false;
        }
        $('[name="btnEnviarMsj"]').attr("disabled", "disabled").addClass("animate-blink");
        $('[name="alert"]').nextAll('.alert-danger').remove();
        var ruta = "{{ url('subevents') }}/{{$subevento->id}}/assistants/"+usuario+"/mail"; 
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'POST',
            url : ruta,
            data: {
                mensaje : $("#mensaje").val(),
            },
            dataType: 'json',
            success: function(result){
                $('[name="btnEnviarMsj"]').removeClass("animate-blink");
                $('[name="alert"]').after(
                    '<div class="alert alert-success">'+
                        result.data+
                    '</div>'
                );
            },
            error: function(jqXHR, textStatus, errorThrown){
                $('[name="btnEnviarMsj"]').removeAttr("disabled").removeClass("animate-blink");
                $('[name="alert"]').after(
                    '<div class="alert alert-danger">'+
                        jqXHR.responseJSON.error+
                    '</div>'
                );
            },
        });
    }


    function crearFormularioMensaje(){
        $('[name="alert"]').nextAll('.alert').remove();
        var formulario = 
            '<form id="formularioMensaje">'+
                '<div class="form-group">'+
                    '<label for="Mensaje" class="control-label">Mensaje:</label>'+
                    '<textarea class="form-control" rows="5" id="mensaje" name="mensaje" required></textarea>'+
                '</div>'+
            '</form> ';

        return formulario;
    }

    /*Evento click para el botón enviar mensaje*/
    $("#listadoAsistentes").on("click", "[name='btnMensaje']", function(){

        $("#myModal").modal("show");

        $("#myModal")
        .find(".modal-content")
        .css({
            'border-color' : '#337ab7'
        });

        $("#myModal")
        .find(".modal-header")
        .css({
            'background-color' : '#286090',
            'color' : 'white'
        });


        $("#myModal")
        .find('[name="modal-header"]')
        .empty()
        .append('<h4 class="modal-title">Enviar mensaje a '+$(this).val()+'</h4>');


        $("#myModal")
        .find('.modal-footer')
        .empty()
        .append(
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
            '<button type="button" class="btn btn-primary" onclick="enviarMensaje('+$(this).attr("id")+')" name="btnEnviarMsj">Enviar</button>'
            );

        $("#myModal")
            .find('.modal-body')
            .empty()
            .append(crearFormularioMensaje());

    });

    function enviarMensajeTodos(){
        if(validarCampoMensaje()){
            return false;
        }

        $('[name="asistenteNombre"]').each(function(){
            //Clave usuario -> $(this).attr("value");
            //Nombre usuario -> $(this).text();
            enviarMensaje($(this).attr("value"));
        });
    }

    function validarCampoMensaje(){
        var campoMensaje = document.getElementById("mensaje");
        $(campoMensaje).next('span.has-error').remove();
        $('.form-group').removeClass('has-error');
        if (!campoMensaje.checkValidity()) {
            $(campoMensaje).parents('div').filter('.form-group').addClass('has-error');
            $(campoMensaje).after("<span class='help-block has-error'>"+campoMensaje.validationMessage+"</span>");
            return true;
        }

        return false; 
    }

    /*Evento click para el botón enviar mensaje*/
    $("#btnEnviarMensaje").on("click", function(){

        $("#myModal").modal("show");

        $("#myModal")
        .find(".modal-content")
        .css({
            'border-color' : '#337ab7'
        });

        $("#myModal")
        .find(".modal-header")
        .css({
            'background-color' : '#286090',
            'color' : 'white'
        });


        $("#myModal")
        .find('[name="modal-header"]')
        .empty()
        .append('<h4 class="modal-title">Enviar mensaje a todos los asistentes</h4>');


        $("#myModal")
        .find('.modal-footer')
        .empty()
        .append(
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
            '<button type="button" class="btn btn-primary" onclick="enviarMensajeTodos()" name="btnEnviarMsj">Enviar</button>'
            );

        $("#myModal")
            .find('.modal-body')
            .empty()
            .append(crearFormularioMensaje())
            .append('<p class="text-info">Utilice el <strong>filtro por estado de inscripción</strong> si desea enviar el mensaje sólo a aquellos asistentes que poseen el estado seleccionado.</p>');

    });

@endsection
