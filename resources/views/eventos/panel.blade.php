@extends('layouts.principal')
@section('titulo', 'Eventos')
@push('css')
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-datetimepicker.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('/js/fecha.js') }}"></script>
    <script src="{{ asset('/js/evento.js') }}"></script>
    <script src="{{ asset('/js/asistente.js') }}"></script>
    <script src="{{ asset('/js/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap-datetimepicker.min.js') }}"></script>
@endpush

@section('contenido')
<div class="container">
    <div class="row">    
        <div class="col-md-4">
            <h4>Buscar</h4>
            <div class="form-group has-feedback" title="Buscar evento">
                <input type="text" class="form-control" id="buscarEvento" onkeyup="buscar($(this).val(), $('#listadoEventos div[class~=\'panel\']'))">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
        </div>

        <div class="col-md-4 col-md-offset-4 text-right hidden">
            <button type="button" class="btn btn-lg btn-primary" name="btnNuevoEvento" id="btnNuevoEvento"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo evento</button>

        </div>

    </div>

    <div class="row" id="listadoEventos"></div>

</div>
@endsection

@section('script')
//<script type="text/javascript">
    var usuariosStaff;
    function cargarUsuariosStaff(){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'GET',
            url : "{{route('usuarios.staff')}}",
            dataType: 'json',
            success: function(result){
                usuariosStaff = result.data;
            },
            error: function(jqXHR, textStatus, errorThrown){

            }
        });

    }
    cargarUsuariosStaff();

    function eliminarEvento(evento){
        var ruta = "{{url('events')}}/"+evento; 
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'DELETE',
            url : ruta,
            dataType: 'json',
            success: function(data){
                $("#myModal").modal("hide");
                //cargarEventos();
                $("#contenedor"+evento)
                    .find('.panel-footer')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .html('<strong>Evento eliminado correctamente</strong>')
                    .fadeIn();
                    

                setTimeout(function(){$("#contenedor"+evento).fadeOut().remove();}, 3000);
            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#myModal").modal("hide");

                $("#contenedor"+evento)
                    .find('.panel-footer')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html('<strong>'+jqXHR.responseJSON.error+'</strong>')
                    .fadeIn();

            }
        });
    }

    function cargarEventos(rolUsuario, limpiarAnterior = false){
        var esAdministrador = false;
        var esOrganizador = false;
        var esColaborador = false;
        var ruta;

        switch(rolUsuario){
            case 'administrador':
                esAdministrador = true;
                ruta = "{{ route('events.index') }}";
                break;
            case 'organizador':
                esOrganizador = true;
                ruta = "{{ route('organizers.events.index', Auth::user()->id) }}";
                break;
            case 'colaborador':
                ruta = "{{ route('collaborators.events.index', Auth::user()->id) }}";
                esColaborador = true;
        }

        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'GET',
            url : ruta,
            dataType: 'json',
            success: function(data){
                if(limpiarAnterior){
                    $("#listadoEventos").empty();    
                }
                
                $.each(data.data, function (i, evento) {
                     $("#listadoEventos").append(
                        '<div class="panel panel-default" id="contenedor'+evento.clave+'">'+
                            '<div class="panel-body">'+
                                '<div class="row">'+
                                    '<div class="col-sm-6">'+
                                        '<label>'+evento.titulo+'</label>'+
                                    '</div>'+
                                    '<div class="col-sm-2">'+
                                        '<a href="{{ url('evento') }}/'+evento.clave+'/asistentes" role="button" class="btn btn-primary btn-block hidden" name="btnAsistentes" id="btnAsistentes'+evento.clave+'" value="'+evento.titulo+'">Asistentes <span class="badge">'+evento.numeroAsistentes+'</span></a>'+ 
                                    '</div>'+
                                    '<div class="col-sm-2">'+
                                        '<button type="button" class="btn btn-success btn-block" name="btnVerMas" onclick="modalEvento('+evento.clave+', $(this))">Ver más</button>'+ 
                                    '</div>'+
                                    '<div class="col-sm-2 hidden">'+
                                        '<button type="button" class="btn btn-primary btn-block" name="btnEditar" id="'+evento.clave+'" value="'+evento.titulo+'">Editar</button>'+ 
                                    '</div>'+
                                    '<div class="col-sm-2 hidden">'+
                                        '<button type="button" class="btn btn-danger btn-block" name="btnEliminar" onclick="modalEliminarEvento('+evento.clave+',$(this))">Eliminar</button>'+ 
                                    '</div>'+
                                    '<div class="col-sm-2">'+
                                        '<a href="{{ url('evento') }}/'+evento.clave+'/subeventos" role="button" class="btn btn-default btn-block" name="btnSubeventos" id="'+evento.clave+'" value="'+evento.titulo+'">Subeventos</a>'+ 
                                    '</div>'+
                                '</div>' +
                            '</div>'+
                            '<div class="panel-footer" style="display:none;"></div>'+
                        '</div>'
                    );

                    //evento.precioInscripcion != null && esOrganizador
                    if(esOrganizador){
                        $("#contenedor"+evento.clave)
                            .find(".row").children().first()
                            .removeClass("col-sm-6")
                            .addClass("col-sm-3")
                            .after(
                                '<div class="col-sm-3">'+
                                    '<p id="asistentesPorAprobar'+evento.clave+'">Asistentes por aprobar: </p>'+
                                    '<!--<p id="cuposDisponibles'+evento.clave+'">Cupos disponibles: </p>-->'+
                                '</div>'
                            );

                        $("#contenedor"+evento.clave)
                            .find('[name="btnAsistentes"]')
                            .first()
                            .removeClass("hidden");

                            if(evento.numeroAsistentes == 0){
                                $("#btnAsistentes"+evento.clave).attr(
                                    {
                                        disabled : 'disabled',
                                        href: "#",
                                        onclick : "event.preventDefault()",
                                    }
                                );
                                $("#asistentesPorAprobar"+evento.clave).append('0');
                            }
                            else{
                                $.ajax({
                                    headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
                                    type: 'GET',
                                    url : "{{ url('events') }}"+"/"+evento.clave+"/assistants?!estado="+ASISTENTE_APROBADO,
                                    dataType: 'json',
                                    success: function(result){
                                        $("#asistentesPorAprobar"+evento.clave).append(result.data.length);
                                    },
                                });
                            }

                    }
                });

                if(esAdministrador){
                    $('[name="btnSubeventos"], [name="btnAsistentes"]').parent().addClass("hidden");
                    $('[name="btnEditar"], [name="btnEliminar"], [name="btnNuevoEvento"]').parent().removeClass("hidden");
                    
                    if(data.data.length == 0){
                        $("#listadoEventos").html('<h2 class="text-center animate-blink">Sin eventos</h2>');
                        return false;
                    }


                    $('[name="btnEditar"]').click(function(){

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
                        .append('<h4 class="modal-title">'+$(this).val()+'</h4>');


                        $("#myModal")
                        .find('.modal-footer')
                        .empty()
                        .append(
                            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
                            '<button type="button" class="btn btn-primary" id="btnActualizarEvento" onclick="CUEvento('+$(this).attr("id")+')">Actualizar</button>'
                            );

                        crearFormularioEvento();

                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
                            type: 'GET',
                            url : "{{url('events')}}/"+$(this).attr("id"),
                            dataType: 'json',
                            success: function(data){
                                evento = data.data;
                                $("#titulo").val(evento.titulo);
                                $("#detalles").html(evento.detalles);
                                $("#masInformacion").val(evento.masInformacion);

                                $('#fechaInicio')
                                    .data("DateTimePicker")
                                    .date(moment(evento.fechaInicio).format("DD/MM/YYYY"));
                                $('#fechaFin')
                                    .data("DateTimePicker")
                                    .date(moment(evento.fechaFin).format("DD/MM/YYYY"));
                                
                                $("#organizador option[value=\""+evento.organizador+"\"]").attr("selected",true);  
                                

                                if(evento.precioInscripcion != null){
                                    $("#optS").attr("checked","true").change();
                                    /*$("#precioInscripcion").val(evento.precioInscripcion);
                                    */

                                    cargarEventoPrecios(evento.clave,
                                        (function(result){
                                            const precios = result.data;

                                            $.each(precios, function(index, precio){

                                                if(index > 0){
                                                    $("#btnAgregarPrecio").click();
                                                }
                                                $("#inputDescrPre"+(index+1)).val(precio.descripcion);
                                                $("#inputPrecio"+(index+1))
                                                    .val(precio.precio)
                                                    .parents('.form-group:first')
                                                    .attr('data-claveprecio', precio.clave);
                                            });

                                        })
                                    );

                                    $("#informacionPago").html(evento.informacionPago);
                                    $("#subeventosElegibles").val(evento.subeventosElegibles);

                                }
                                else{
                                    $("#optN").attr("checked","true").change();
                                }

                                if(evento.numeroAsistentes > 0){
                                    $('[name="optPagoxEvento"]')
                                        .attr("disabled", "disabled")
                                        .parents(".form-group")
                                            .first()
                                            .before('<span class="help-block">No se puede editar el tipo de inscripción debido a que hay '+evento.numeroAsistentes+' asistente(s) inscritos a este evento.</span>');
                                }
                                
                            },
                            error: function(jqXHR, textStatus, errorThrown){

                            }
                        });

                    });
                }
                else{
                    $('[name="btnEditar"], [name="btnEliminar"], [name="btnNuevoEvento"]').parent().remove();
                }

            },
            error: function(jqXHR, textStatus, errorThrown){

            }
        });
    }

    function rutaEventos(){
        if({{ Auth::user()->tipo }} == '0'){
            cargarEventos('administrador', true);
        }
        else{ 
            cargarEventos('organizador');
            cargarEventos('colaborador');
        }

    }

    rutaEventos();

    $("#btnNuevoEvento").click(function(){

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
        .append('<h4 class="modal-title">Nuevo evento</h4>');




        $("#myModal")
        .find('.modal-footer')
        .empty()
        .append(
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
            '<button type="button" class="btn btn-primary" id="btnAgregarEvento" onclick="CUEvento()">Agregar</button>'
            );

        crearFormularioEvento();

    });






    function CUEvento(evento){ //Create and Update Evento
        const esPagoPorEvento = $("#optS").is(":checked");
        var datos = new FormData();
        datos.append('titulo', $("#titulo").val());
        datos.append('detalles', $("#detalles").val());
        datos.append('masInformacion', $("#masInformacion").val());
        datos.append('fechaInicio', $('#fechaInicio').val() ? $('#fechaInicio').data("DateTimePicker").date().format("YYYY-MM-DD") : "");
        datos.append('fechaFin', $('#fechaFin').val() ? $('#fechaFin').data("DateTimePicker").date().format("YYYY-MM-DD") : "");
        datos.append('organizador', $("#organizador").val());

        if($("#imagen")[0].files[0]){
            datos.append('url_imagen', $("#imagen")[0].files[0]);
        }

        datos.append('precioInscripcion', '');
        if(esPagoPorEvento){
            datos.set('precioInscripcion', $("#inputPrecio1").val());
            datos.append('informacionPago', $("#informacionPago").val());
            datos.append('subeventosElegibles', $("#subeventosElegibles").val());
        }

        var ruta = "{{url('events')}}";
        if(evento){
            ruta += "/"+evento;
            datos.append('_method', 'put');
        }

        $("#btnAgregarEvento , #btnActualizarEvento").attr("disabled", "disabled").addClass("animate-blink");
        $('.form-group').removeClass('has-error');
        $('span').remove('.errors');
        $('[name="alert"]').empty().removeClass("alert-success alert-danger");

        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            url: ruta,
            dataType: 'json',
            type:'POST',
            contentType:false,
            data: datos,
            processData:false,
            cache:false,
            success: function(result){
                $("#btnAgregarEvento , #btnActualizarEvento").removeClass("animate-blink");
                var mensaje = "Evento agregado correctamente";
                if(evento){
                    $("#btnAgregarEvento , #btnActualizarEvento").removeAttr("disabled");
                    mensaje = "Cambios realizados correctamente";
                }

                $("[name=alert]").addClass("alert-success").append("<p>"+mensaje+"</p>").fadeIn();

                setTimeout(function(){$("[name=alert]").fadeOut();}, 3000);

                //Se agregan los precios
                if(esPagoPorEvento){
                    $('[name="contenedorPrecio"]').each(function(i, element){
                        const descripcionPrecio = $(this).find("input:first").val();
                        const precio = $(this).find("input:last").val();

                        if(descripcionPrecio != "" && precio != ""){
                            const datosPrecio = {
                                'descripcion' : descripcionPrecio,
                                'precio' : precio
                            };

                            //Si el precio existe y se oprimió el botón eliminar
                            if($(this).attr("data-eliminarprecio")){
                                ajaxRequest(null, 'DELETE', RUTA_EVENTO+'/'+result.data.clave+'/prices/'+$(this).attr("data-claveprecio"), null, (function(resultP){$(element).remove()}));
                            }
                            //Si el precio existe
                            else if($(this).attr("data-claveprecio")){
                                ajaxRequest(null, 'PUT', RUTA_EVENTO+'/'+result.data.clave+'/prices/'+$(this).attr("data-claveprecio"), datosPrecio);     
                            }
                            //Si no existe el precio
                            else{
                                ajaxRequest(null, 'POST', RUTA_EVENTO+'/'+result.data.clave+'/prices', datosPrecio, (function(resultP){$(element).attr("data-claveprecio", resultP.data.clave)}));
                            }
                            
                        }

                    });
                }
                else{
                    $('[name="contenedorPrecio"]').each(function(i, element){
                        $(this).removeAttr("data-claveprecio data-eliminarprecio");
                    });
                }

            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#btnAgregarEvento , #btnActualizarEvento").removeAttr("disabled").removeClass("animate-blink");
                if(jqXHR.responseJSON.errors instanceof Object){
                    $.each(jqXHR.responseJSON.errors, function(campo, errores){
                        $("[name="+campo+"]").parents('div').filter('.form-group').addClass('has-error');

                        //console.log(campo);
                        $.each(errores,function(i, error){
                            if($("#"+campo).parents(".input-group").length > 1){
                                $("#"+campo).parents(".input-group").last().after("<span class='help-block has-error errors'>"+error+"</span>");
                            }
                            else{
                                $("#"+campo).after("<span class='help-block has-error errors'>"+error+"</span>");    
                            }
                            
                        });
                    });
                }
                else{
                    $("[name=alert]").addClass("alert-danger").append("<p>"+jqXHR.responseJSON.error+"</p>").fadeIn();
                }
            },
            complete: function(xhr, status){
                setTimeout(function(){$("[name=alert]").fadeOut();}, 6000);
            }
        });

    }
    
    /*
        Remueve los campos que corresponden a la posición del boton oprimido.
    */
    function quitarPrecio(btn){
        const formgPrecio = btn.parents('.form-group:first');
        if(formgPrecio.attr("data-claveprecio")){
            formgPrecio
                .fadeOut()
                .attr("data-eliminarprecio", true);
        }   
        else{
            formgPrecio.remove();
        }
    }

    /*
        Agrega campos correpondientes a un nuevo precio antes del botón Agregar otro precio
    */
    function agregarCampoPrecio(){
        const numeroPrecio = $('[name="descripcionPrecio"]').length + 1;

        $("#btnAgregarPrecio").before(
            '<div class="form-group" name="contenedorPrecio">'+
                '<div class="row">'+
                    '<div class="col-sm-5">'+
                        '<input type="text" class="form-control" name="descripcionPrecio" id="inputDescrPre'+numeroPrecio+'" min="0" placeholder="Descripción">'+
                    '</div>'+
                    
                    '<div class="col-sm-5">'+
                        '<input type="number" class="form-control" name="precio" id="inputPrecio'+numeroPrecio+'" min="0" placeholder="Precio">'+
                    '</div>'+

                    '<div class="col-sm-2">'+
                        '<button type="button" class="btn btn-danger btn-block" name="btnQuitarPrecio" onclick="quitarPrecio($(this))">Quitar</button>'+ 
                    '</div>'+

                '</div>'+
            '</div>'
        );
    }

    function crearFormularioEvento(){
        $("#myModal")
        .find('.modal-body')
        .empty()
        .append(
            '<form id="formularioEvento">'+
                '<div class="form-group">'+
                    '<label for="titulo" class="control-label">Titulo:</label>'+
                    '<input type="text" class="form-control" name="titulo" id="titulo">'+
                '</div>'+
                '<div class="form-group">'+
                    '<label for="detalles" class="control-label">Descripción:</label>'+
                    '<textarea class="form-control" rows="5" id="detalles" name="detalles"></textarea>'+
                '</div>'+
                '<div class="form-group">'+
                    '<label for="masInformacion" class="control-label">URL para acceder a más información del evento:</label>'+
                    '<input type="text" class="form-control" name="masInformacion" id="masInformacion">'+
                '</div>'+
                '<div class="form-group">'+
                    '<label for="imagen" class="control-label">Seleccione una imágen para el evento:</label>'+
                    '<input type="file" name="imagen" id="imagen">'+
                '</div>'+

                '<div class="form-group">'+
                    '<label for="delimitacion_temporal" class="control-label">Delimitación temporal:</label>'+
                    '<div class="row">'+
                        '<div class="col-md-6">'+
                            '<div class="input-group">'+
                                '<span class="input-group-addon" id="basic-addon1">Fecha de inicio</span>'+
                                '<div class="input-group date" id="datetimeFechaIn">'+
                                    '<input type="text" name="fechaInicio" id="fechaInicio" class="form-control" required title="Seleccione una fecha de inicio." style="border-radius: 0;">'+
                                    '<span class="input-group-addon">'+
                                        '<span class="glyphicon glyphicon-calendar"></span>'+
                                    '</span>'+
                                '</div>'+
                            '</div> <!-- Fin input group -->'+
                        '</div> <!-- Fin col 6-->'+

                        '<div class="col-md-6">'+
                            '<div class="input-group">'+
                                '<span class="input-group-addon" id="basic-addon1">Fecha de finalización</span>'+
                                '<div class="input-group date" id="datetimeFechaFin">'+
                                    '<input type="text" name="fechaFin" id="fechaFin" class="form-control" required title="Seleccione una fecha de inicio." style="border-radius: 0;">'+
                                    '<span class="input-group-addon">'+
                                        '<span class="glyphicon glyphicon-calendar"></span>'+
                                    '</span>'+
                                '</div>'+
                            '</div> <!-- Fin input group -->'+
                        '</div> <!-- Fin col 6-->'+
                    '</div> <!--Fin row -->'+
                '</div> <!--Fin form-group fechas-->'+

                '<div class="form-group">'+
                    '<label for="organizador" class="control-label">Organizador:</label>'+
                    '<select class="form-control" id="organizador" name="organizador"></select>'+
                '</div>'+

                '<div class="form-group">'+
                    '<label for="optPagoxEvento" class="control-label" id="labelPagoPorEvento">¿Pago por evento?:</label>'+
                    '<div class="form-group">'+
                        '<label class="radio-inline"><input type="radio" name="optPagoxEvento" id="optS">Si</label>'+
                        '<label class="radio-inline"><input type="radio" name="optPagoxEvento" id="optN" checked="checked">No</label>'+
                    '</div>'+
                '</div>'+

                '<div id="formularioPago" class="hidden">'+
                    '<label class="control-label">Precios de inscripción:</label>'+
                    '<div class="form-group" name="contenedorPrecio">'+
                        '<div class="row">'+
                            '<div class="col-sm-6">'+
                                '<input type="text" class="form-control" name="descripcionPrecio" id="inputDescrPre1" min="0" placeholder="Descripción">'+
                            '</div>'+
                            
                            '<div class="col-sm-6">'+
                                '<input type="number" class="form-control" name="precio" id="inputPrecio1" min="0" placeholder="Precio">'+
                            '</div>'+

                        '</div>'+
                    '</div>'+

                    '<button type="button" class="btn btn-primary btn-block" id="btnAgregarPrecio" name="btnAgregarPrecio" onclick="agregarCampoPrecio()">Agregar otro precio</button>'+

                    '<div class="form-group">'+
                        '<label for="informacionPago" class="control-label">Información de pago:</label>'+
                        '<textarea class="form-control" rows="5" id="informacionPago" name="informacionPago"></textarea>'+
                    '</div>'+

                    '<div class="form-group">'+
                        '<label for="subeventosElegibles" class="control-label">Máximo de subeventos elegibles:</label>'+
                        '<input type="number" class="form-control" name="subeventosElegibles" id="subeventosElegibles" min="1">'+
                    '</div>'+
                '</div>'+

            '</form> '
        );

        
        $('#fechaInicio').datetimepicker({
            locale: 'es',
            format : 'DD/MM/YYYY',
        });
        
        $('#fechaFin').datetimepicker({
            locale: 'es',
            format : 'DD/MM/YYYY',
            useCurrent: false, 
        });

        $("[name='optPagoxEvento']").change(function(){
            $(this).attr("id") === 'optS' ? $("#formularioPago").removeClass("hidden") : $("#formularioPago").addClass("hidden");
        });
        
        $("#organizador")
            .empty()
            .append('<option value="">Seleccione a un organizador</option>');
        $.each(usuariosStaff, function(i, usuario){
            $("#organizador")
                .append(
                    '<option value="'+usuario.clave+'">'+
                        usuarioNombreCompleto(usuario.nombre, usuario.apellidoPaterno, usuario.apellidoMaterno)+
                    '</option>');
        });


    }
    /*Escuchadores de los dtp Fecha inicio y fecha fin. controla que la fecha inicio no supere a la de fecha fin, y fecha fin no sea anterior a la fecha inicio*/
    $("#myModal").on("dp.change", '#fechaInicio', function (e) {
        $('#fechaFin').data("DateTimePicker").minDate(e.date);
    });
    
    $("#myModal").on("dp.change", '#fechaFin', function (e) {
        $('#fechaInicio').data("DateTimePicker").maxDate(e.date);
    });

@endsection
