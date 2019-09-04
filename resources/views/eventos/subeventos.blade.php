@extends('layouts.principal')
@section('titulo', $evento->nombre." - Subeventos")
@push('css')
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-datetimepicker.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('/js/fecha.js') }}"></script>
    <script src="{{ asset('/js/evento.js') }}"></script>
    <script src="{{ asset('/js/subevento.js') }}"></script>
    <script src="{{ asset('/js/colaborador.js') }}"></script>
    <script src="{{ asset('/js/asistente.js') }}"></script>
    <script src="{{ asset('/js/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap-datetimepicker.min.js') }}"></script>
@endpush

@section('contenido')
<div class="container">

    <ul class="breadcrumb">
        <li><a href="{{ route('eventos') }}">Eventos</a></li>
        <li><a href="#" onclick="event.preventDefault(); modalEvento({{$evento->id}})">{{$evento->nombre}}</a></li>
        <li class="active">Subeventos</li>
    </ul>

    <div class="row">    
        <div class="col-md-4">
            <h4>Buscar</h4>
            <div class="form-group has-feedback" title="Buscar Subevento">
                <input type="text" class="form-control" id="buscarSubevento" onkeyup="buscar($(this).val(), $('#listadoSubeventos div[class~=\'panel\']'))">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
        </div>

        <div class="col-md-4 col-md-offset-4 text-right hidden">
            <button type="button" class="btn btn-lg btn-primary" name="btnNuevoSubevento" id="btnNuevoSubevento"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo Subevento</button>

        </div>

    </div>

    <div class="row" id="listadoSubeventos"></div>

</div>
@endsection

@section('script')
//<script type="text/javascript">
    const pagoPorEvento = {{ $evento->esPagoPorEvento() ? 1 : 0 }};
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

    function cargarSubeventos(){
        var ruta = "{{ route('events.subevents.index', $evento->id)}}";
        var colaborador = {{Auth::user()->id != $evento->organizador_id ? 1 : 0}};
        if(colaborador){
            ruta = "{{ url('collaborators') }}/{{Auth::user()->id}}/subevents?evento={{$evento->id}}";
        }

        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'GET',
            url : ruta,
            dataType: 'json',
            success: function(result){
                $("#listadoSubeventos").empty();
                if(result.data.length > 0){
                    $.each(result.data, function (i, subevento) {
                         $("#listadoSubeventos").append(
                            '<div class="panel panel-default" id="contenedor'+subevento.clave+'">'+
                                '<div class="panel-body">'+
                                    '<div class="row">'+
                                        '<div class="col-sm-4">'+
                                            '<label>'+subevento.titulo+'</label>'+
                                        '</div>'+
                                        '<div class="col-sm-4">'+
                                            '<p id="asistentesPorAprobar'+subevento.clave+'">Asistentes por aprobar: </p>'+
                                        '</div>'+

                                        '<div class="col-sm-4">'+
                                            '<p id="cuposDisponibles'+subevento.clave+'">Cupos disponibles: </p>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row">'+
                                        '<div class="col-sm-2 col-sm-offset-2">'+
                                            '<a role="button" href="{{ url('evento') }}/{{ $evento->id }}/subevento/'+subevento.clave+'/asistentes" class="btn btn-primary btn-block" name="btnAsistentes" id="btnAsistentes'+subevento.clave+'" value="'+subevento.titulo+'">Asistentes <span class="badge">'+subevento.numeroAsistentes+'</span></a>'+ 
                                        '</div>'+
                                        '<div class="col-sm-2 hidden">'+
                                            '<button type="button" class="btn btn-primary btn-block" name="btnColaboradores" id="'+subevento.clave+'" value="'+subevento.titulo+'">Colaboradores</button>'+ 
                                        '</div>'+
                                        '<div class="col-sm-2">'+
                                            '<button type="button" class="btn btn-success btn-block" name="btnVerMas" id="'+subevento.clave+'" value="'+subevento.titulo+'" onclick="modalSubevento('+subevento.evento+','+subevento.clave+')">Ver más</button>'+ 
                                        '</div>'+
                                        '<div class="col-sm-2 hidden">'+
                                            '<button type="button" class="btn btn-primary btn-block" name="btnEditar" id="'+subevento.clave+'" value="'+subevento.titulo+'">Editar</button>'+ 
                                        '</div>'+
                                        '<div class="col-sm-2 hidden">'+
                                            '<button type="button" class="btn btn-danger btn-block" name="btnEliminar" onclick="modalEliminarSubevento('+subevento.clave+',$(this))">Eliminar</button>'+ 
                                        '</div>'+
                                    '</div>' +
                                '</div>'+
                                '<div class="panel-footer" style="display:none;"></div>'+
                            '</div>'
                        );

                        if(subevento.numeroAsistentes == 0){
                            $("#btnAsistentes"+subevento.clave).attr(
                                {
                                    disabled : 'disabled',
                                    href: "#",
                                    onclick : "event.preventDefault()",
                                }
                            );
                            $("#asistentesPorAprobar"+subevento.clave).append('0');
                        }
                        else{
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
                                type: 'GET',
                                url : "{{ url('subevents') }}"+"/"+subevento.clave+"/assistants?!estado="+ASISTENTE_APROBADO,
                                dataType: 'json',
                                success: function(result){
                                    $("#asistentesPorAprobar"+subevento.clave).append(result.data.length);
                                },
                            });
                        }

                        $("#cuposDisponibles"+subevento.clave)
                            .append(
                                subevento.cuposDisponibles != null ? subevento.cuposDisponibles : "sin limite"
                            );
                        
                    });

                }
                else{
                    $("#listadoSubeventos").append('<h2 class="text-center">No hay subeventos registrados</h2>');
                }
                
                if({{Auth::user()->id}} == {{$evento->organizador_id}}){
                    $('[name="btnEditar"], [name="btnEliminar"], [name="btnNuevoSubevento"], [name="btnColaboradores"]').parent().removeClass("hidden");
                }
                else{
                    $('[name="btnVerMas"], [name="btnAsistentes"]')
                        .parent()
                        .removeClass('col-sm-2 col-sm-offset-2')
                        .addClass('col-sm-6');
                }

            },
            error: function(jqXHR, textStatus, errorThrown){

            }
        });
    }

    cargarSubeventos();

    function eliminarSubevento(subevento){
        var ruta = "{{url('events')}}/{{$evento->id}}/subevents/"+subevento; 
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'DELETE',
            url : ruta,
            dataType: 'json',
            success: function(data){
                $("#myModal").modal("hide");
                //cargarSubeventos();
                $("#contenedor"+subevento)
                    .find('.panel-footer')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .html('<strong>Subevento eliminado correctamente</strong>')
                    .fadeIn();
                    

                setTimeout(function(){$("#contenedor"+subevento).fadeOut().remove();}, 3000);
            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#myModal").modal("hide");

                $("#contenedor"+subevento)
                    .find('.panel-footer')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html('<strong>'+jqXHR.responseJSON.error+'</strong>')
                    .fadeIn();

            }
        });
    }

    function CUSubevento(subevento){ //Create and Update Subevento
        var datos = new FormData();
        datos.append('titulo', $("#titulo").val());
        datos.append('detalles', $("#detalles").val());
        datos.append('fechaHora', $('#fechaHora').val() ? $('#fechaHora').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss") : "");
        datos.append('lugar', $("#lugar").val());

        if($("#imagen")[0].files[0]){
            datos.append('url_imagen', $("#imagen")[0].files[0]);
        }

        if($("#optCuota").is(":checked")){
            datos.append('precioInscripcion', $("#inputPrecio1").val());
            datos.append('informacionPago', $("#informacionPago").val());
        }
        else{
            datos.append('precioInscripcion', '');
        }

        if($("#optLS").is(":checked")){
            datos.append('cuposDisponibles', $("#cuposDisponibles").val());
        }
        else{
            datos.append('cuposDisponibles', '');
        }

        var ruta = "{{url('events')}}/{{$evento->id}}/subevents";
        if(subevento){
            ruta += "/"+subevento;
            datos.append('_method', 'put');
        }

        $("#btnAgregarSubevento , #btnActualizarSubevento").attr("disabled", "disabled").addClass("animate-blink");
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
                $("#btnAgregarSubevento , #btnActualizarSubevento").removeClass("animate-blink");
                cargarSubeventos();
                var mensaje = "Subevento agregado correctamente";
                if(subevento){
                    mensaje = "Cambios realizados correctamente";
                    $("#btnAgregarSubevento , #btnActualizarSubevento").removeAttr("disabled");
                }

                $("[name=alert]").addClass("alert-success").append("<p>"+mensaje+"</p>").fadeIn();

                setTimeout(function(){$("[name=alert]").fadeOut();}, 3000);

                if($("#optCuota").is(":checked")){
                //Se agregan los precios
                
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
                                ajaxRequest(null, 'DELETE', RUTA_SUBEVENTO+'/'+result.data.clave+'/prices/'+$(this).attr("data-claveprecio"), null, (function(resultP){$(element).remove()}));
                            }
                            //Si el precio existe
                            else if($(this).attr("data-claveprecio")){
                                ajaxRequest(null, 'PUT', RUTA_SUBEVENTO+'/'+result.data.clave+'/prices/'+$(this).attr("data-claveprecio"), datosPrecio);     
                            }
                            //Si no existe el precio
                            else{
                                ajaxRequest(null, 'POST', RUTA_SUBEVENTO+'/'+result.data.clave+'/prices', datosPrecio, (function(resultP){$(element).attr("data-claveprecio", resultP.data.clave)}));
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
                $("#btnAgregarSubevento , #btnActualizarSubevento").removeAttr("disabled").removeClass("animate-blink");
                if(jqXHR.responseJSON.errors instanceof Object){
                    $.each(jqXHR.responseJSON.errors, function(campo, errores){
                        $("[name="+campo+"]").parents('div').filter('.form-group').addClass('has-error');

                        //console.log(campo);
                        $.each(errores,function(i, error){
                            if($("#"+campo).parents("[class~='input-group']").length >= 1){
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

    function crearFormularioSubevento(){
        $("#myModal")
        .find('.modal-body')
        .empty()
        .append(
            '<form id="formularioSubevento">'+
                '<div class="form-group">'+
                    '<label for="titulo" class="control-label">Titulo:</label>'+
                    '<input type="text" class="form-control" name="titulo" id="titulo">'+
                '</div>'+
                '<div class="form-group">'+
                    '<label for="detalles" class="control-label">Descripción:</label>'+
                    '<textarea class="form-control" rows="5" id="detalles" name="detalles"></textarea>'+
                '</div>'+
                '<div class="form-group">'+
                    '<label for="imagen" class="control-label">Seleccione una imágen para el subevento:</label>'+
                    '<input type="file" name="imagen" id="imagen">'+
                '</div>'+

                '<div class="form-group">'+
                    '<label for="fechaHora" class="control-label">Fecha y hora:</label>'+
                    '<div class="input-group date" id="datetimeFechaHora">'+
                        '<input type="text" name="fechaHora" id="fechaHora" class="form-control" required title="Fecha y hora del subevento." style="border-radius: 0;" placeholder="AAAA-MM-DD HH:MM:SS">'+
                        '<span class="input-group-addon">'+
                            '<span class="glyphicon glyphicon-calendar"></span>'+
                        '</span>'+
                    '</div>'+

                '</div> <!--Fin form-group fechas-->'+

                '<div class="form-group">'+
                    '<label for="lugar" class="control-label">Lugar:</label>'+
                    '<textarea class="form-control" rows="5" id="lugar" name="lugar"></textarea>'+
                '</div>'+

                '<div class="form-group">'+
                    '<label for="optLimiteAsistentes" class="control-label">¿Limitar asistentes?:</label>'+
                    '<div class="form-group">'+
                        '<label class="radio-inline"><input type="radio" name="optLimiteAsistentes" id="optLS">Si</label>'+
                        '<label class="radio-inline"><input type="radio" name="optLimiteAsistentes" id="optLN" checked="checked">No</label>'+
                    '</div>'+
                '</div>'+


                '<div id="contenedorLimiteAsistentes" class="hidden">'+
                    '<div class="form-group">'+
                        '<label for="limiteAsistentes" class="control-label">Cupos disponibles:</label>'+
                        '<input type="number" class="form-control" name="cuposDisponibles" id="cuposDisponibles" min="0">'+
                    '</div>'+
                '</div>'+                

                '<div id="contenedorTipoInscripcion" class="form-group hidden">'+
                    '<label for="optTipoInscripcion" class="control-label">Tipo de inscripción:</label>'+
                    '<div class="form-group">'+
                        '<label class="radio-inline"><input type="radio" name="optTipoInscripcion" id="optCuota">Cuota</label>'+
                        '<label class="radio-inline"><input type="radio" name="optTipoInscripcion" id="optGratis" checked="checked">Gratis</label>'+
                    '</div>'+
                '</div>'+

                '<div id="contenedorFormularioPago" class="hidden">'+
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
                '</div>'+

            '</form> '
        );

        if(!pagoPorEvento){
            $("#contenedorTipoInscripcion").removeClass("hidden");
        }

        $('#fechaHora').datetimepicker({locale: 'es',format : 'DD/MM/YYYY HH:mm:ss',});
    }

    /*
        Acciones correspondientes para los colaboradores
    */

    const colaboradorResponsable = "R", colaboradorAyudante = "A";

    function buscarColaborador(claveColaborador){
        var nombreColaborador;
        $.each(usuariosStaff, function(i, usuario){
            if(usuario.clave == claveColaborador){
                nombreColaborador = usuarioNombreCompleto(usuario.nombre, usuario.apellidoPaterno, usuario.apellidoMaterno);
                return false;
            }
        });

        return nombreColaborador;
    }


    //Función para agregar un colaborador 
    function agregarColaborador(colaborador, tipo){
        const nombreColaborador = buscarColaborador(colaborador);
        $('#myModal [name="alert"]').before(
            '<div class="alert alert-info animate-blink" id="agregandoColaborador'+colaborador+'">'+
                'Agregando a '+nombreColaborador+' como colaborador del subevento.'+
            '</div>'
        );

        var subevento = $('#btnAgregarColaborador').val();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            data: {
                tipo: tipo,
            },
            type: 'PUT',
            url : "{{ url('subevents') }}/"+subevento+"/collaborators/"+colaborador,
            dataType: 'json',
            success: function(result){
                $('#agregandoColaborador'+colaborador).attr("class", "alert alert-success").html("Se ha agregado correctamente a "+nombreColaborador+" como colaborador del subevento");
            },
            error: function(jqXHR, textStatus, errorThrown){
                $('#agregandoColaborador'+colaborador).attr("class", "alert alert-danger")
                    .html(
                        "<p>No se pudo agregar a "+nombreColaborador+" como colaborador del subevento</p>"+
                        "<p>"+jqXHR.responseJSON.error+"</p>"
                    );
                
            },
            complete: function(xhr, status){
                setTimeout(function(){$('#agregandoColaborador'+colaborador).fadeOut().remove();}, 3000);
            }
        });
    }

    //Función para quitar un colaborador
    function quitarColaborador(colaborador){
        const nombreColaborador = buscarColaborador(colaborador);
        $('#myModal [name="alert"]').before(
            '<div class="alert alert-info animate-blink" id="agregandoColaborador'+colaborador+'">'+
                'Quitando a '+nombreColaborador+' como colaborador del subevento.'+
            '</div>'
        );
        var subevento = $('#btnAgregarColaborador').val();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'DELETE',
            url : "{{ url('subevents') }}/"+subevento+"/collaborators/"+colaborador,
            dataType: 'json',
            success: function(result){
                $('#agregandoColaborador'+colaborador).attr("class", "alert alert-success").html("Se ha quitado a "+nombreColaborador+" como colaborador del subevento");
            },
            error: function(jqXHR, textStatus, errorThrown){
                $('#agregandoColaborador'+colaborador).attr("class", "alert alert-danger")
                    .html(
                        "<p>No se pudo quitar a"+nombreColaborador+" como colaborador del subevento</p>"+
                        "<p>"+jqXHR.responseJSON.error+"</p>"
                    );
                
            },
            complete: function(xhr, status){
                setTimeout(function(){$('#agregandoColaborador'+colaborador).fadeOut().remove();}, 3000);
            }
        });
    }

    //Función para agregar opciones a un select Colaborador
    function optionColaboradores(select){
        /*
        //Revisa si el número de selects no supera el número de usuarios staff (omitiendo al organizador del evento)
        if($('[name="selectColaborador"]').length > usuariosStaff.length - 1){
            return select.parents('div.form-group').first().remove();
        }
        */

        select.append('<option value="">Seleccione a un usuario</option>');
        //Agregar un <option></option> por cada usuario del staff omitiendo al organizador del evento
        $.each(usuariosStaff, function(i, usuario){
            if(usuario.clave != {{$evento->organizador_id}}){
                select
                    .append(
                        '<option value="'+usuario.clave+'">'+
                            usuario.nombre+' '+
                            usuario.apellidoPaterno+' '+
                            usuario.apellidoMaterno+' '+
                        '</option>');
            }

        });

        
        //Remover en el select actual opciones que ya estan seleccionadas en los demás select
        $('[name="selectColaborador"]').not(select).each(function(index, element){
            if($(this).val()){
                select.children('option[value="'+$(this).val()+'"]').remove();
            }
        }); 

        /*
        if(usuarioSeleccionado && (omitirUsuario != usuarioSeleccionado)){
            select.children('option[value="'+usuarioSeleccionado+'"]').attr("selected", true);
        }

        $('[name="selectColaborador"]').not(select).each(function(index, element){
            if(select.val()){
                select.children('option[value="'+$(this).val()+'"]').remove();
            }
        });
        */



        //select.attr("data-old-value", select.val());
    }

    //Evento para el boton Agregar Colaborador
    $("#myModal").on("click", "[name='btnAgregarColaborador']", function(){
        //Si el número de select se iguala al número de usuarios staff (omitiendo al organizador) entonces ya no se agregan más elementos select
        if($('[name="selectColaborador"]').length == usuariosStaff.length - 1){
            return false;
        }

        //Agrega un select vacio
        $(this).before(
            '<div class="form-group">'+
                '<div class="row">'+
                    '<div class="col-sm-6">'+
                        '<select class="form-control" name="selectColaborador"></select>'+
                    '</div>'+
                    '<div class="col-sm-3">'+
                        '<label class="radio-inline"><input type="radio" name="optColaboradorResponsable">Responsable del subevento</label>'+
                       
                    '</div>'+
                    '<div class="col-sm-3">'+
                        '<button type="button" class="btn btn-danger btn-block" name="btnQuitarColaborador">Quitar</button>'+ 
                    '</div>'+
                '</div>'+
            '</div>');
        
        //Agrega las opciones correspondientes al select
        optionColaboradores($(this).prev().find('select'));
    });


    //Evento para los botones Quitar Colaborador
    $("#myModal").on("click", "[name='btnQuitarColaborador']", function(){
        var colaborador = $(this).parents('div.form-group').first().find("select").val();
        if(colaborador){
            quitarColaborador(colaborador);
        }
        $(this).parents('div.form-group').first().remove();
    });

    //Eventos para los selects Selecionar Colaborador 
    $("#myModal").on("focusin change", "[name='selectColaborador']", function(event){
        if($(this).val()){
            if(event.type == "change"){

                if($(this).attr("data-old-value") && $(this).attr("data-old-value") != $(this).val()){
                    quitarColaborador(  $(this).attr("data-old-value") );
                }

                const tipoColaborador = $(this).parents("div.form-group").first().find(":radio").is(":checked") ? colaboradorResponsable : colaboradorAyudante;

                agregarColaborador( $(this).val() ,  tipoColaborador);

                // Recrea aquellos select que no tiene una opción seleccionada (distinto de vacio) 
                //var omitirUsuario = $(this).val();
                $('[name="selectColaborador"]').not($(this)).each(function(index, element){
                    if(!$(this).val()){
                        //var usuarioSeleccionado = $(this).val();
                        $(this).empty();
                        //optionColaboradores($(this), omitirUsuario, usuarioSeleccionado);
                        optionColaboradores( $(this) );
                    }
                    
                });
            }
            $(this).attr("data-old-value", $(this).val());
        }
        
    });

    //Evento para el input radio Tipo de colaborador
    $("#myModal").on("change", ":radio", function(event){

        $(this).parents("div.form-group").first().find("select").change();
        
    });


    $("#myModal").on("change", "[name='optLimiteAsistentes']", function(){
        $(this).attr("id") === 'optLS' ? $("#contenedorLimiteAsistentes").removeClass("hidden") : $("#contenedorLimiteAsistentes").addClass("hidden");
    });


    $("#myModal").on("change", "[name='optTipoInscripcion']", function(){
        $(this).attr("id") === 'optCuota' ? $("#contenedorFormularioPago").removeClass("hidden") : $("#contenedorFormularioPago").addClass("hidden");
        
    });


    //Si es el organizador del evento, habilitar eventos de los botónes: nuevo subevento, editar y eliminar.
    //{{Auth::user()->id}} == {{$evento->organizador_id}}
    if({{Auth::user()->id}} == {{$evento->organizador_id}}){
        /*Evento click para el botón nuevo subevento*/
        $("#btnNuevoSubevento").click(function(){

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
            .append('<h4 class="modal-title">Nuevo Subevento</h4>');




            $("#myModal")
            .find('.modal-footer')
            .empty()
            .append(
                '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
                '<button type="button" class="btn btn-primary" id="btnAgregarSubevento" onclick="CUSubevento()">Agregar</button>'
                );

            crearFormularioSubevento();

        });

        /*Evento click para el botón editar*/
        $("#listadoSubeventos").on("click", "[name='btnEditar']", function(){

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
                '<button type="button" class="btn btn-primary" id="btnActualizarSubevento" onclick="CUSubevento('+$(this).attr("id")+')">Actualizar</button>'
                );

            crearFormularioSubevento();

            $.ajax({
                headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
                type: 'GET',
                url : "{{url('events')}}/{{$evento->id}}/subevents/"+$(this).attr("id"),
                dataType: 'json',
                success: function(result){
                    subevento = result.data;
                    $("#titulo").val(subevento.titulo);
                    $("#detalles").html(subevento.detalles);
                    //$("#fechaHora").val(subevento.fechaHora);
                    $('#fechaHora')
                        .data("DateTimePicker")
                        .date(moment(subevento.fechaHora).format("DD/MM/YYYY HH:mm:ss"));
                    $("#lugar").html(subevento.lugar);

                    if(subevento.cuposDisponibles != null){
                        $("#optLS").attr("checked","true").change();
                        $("#cuposDisponibles").val(subevento.cuposDisponibles);
                    }
                    if(subevento.precioInscripcion != null){
                        $("#optCuota").attr("checked","true").change();
                        //$("#precioInscripcion").val(subevento.precioInscripcion);
                        cargarSubeventoPrecios(subevento.clave,
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
                        $("#informacionPago").html(subevento.informacionPago);
                    }

                    if(subevento.numeroAsistentes > 0){
                        $('[name="optTipoInscripcion"]')
                            .attr("disabled", "disabled")
                            .parents(".form-group")
                                .first()
                                .before('<span class="help-block">No se puede editar el tipo de inscripción debido a que hay '+subevento.numeroAsistentes+' asistente(s) inscritos a este subevento.</span>');
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown){

                }
            });

        });


        /*Evento click para el botón colaboradores*/
        $("#listadoSubeventos").on("click", "[name='btnColaboradores']", function(){
            
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
            .find('.modal-body')
            .empty()
            .append(
                '<div class="form-group">'+
                    '<label for="colabores" class="control-label">Colaboradores:</label>'+
                    '<button type="button" class="btn btn-primary btn-block" id="btnAgregarColaborador" name="btnAgregarColaborador" value="'+$(this).attr("id")+'">Agregar colaborador</button>'+
                '</div>'
            );

            $("#myModal")
            .find('[name="modal-header"]')
            .empty()
            .append('<h4 class="modal-title">Colaboradores del subevento: '+$(this).val()+'</h4>');


            $("#myModal")
            .find('.modal-footer')
            .empty()
            .append(
                '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>');

            //Cargar colaboradores
            var subevento = $(this).attr("id");
            $.ajax({
                headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
                type: 'GET',
                url : "{{ url('subevents') }}/"+subevento+"/collaborators",
                dataType: 'json',
                success: function(result){
                    $.each(result.data, function (index, colaborador){
                        $("#btnAgregarColaborador").click();
                        $("#btnAgregarColaborador")
                            .prev()
                            .find('select option[value="'+colaborador.clave+'"]')
                            .attr("selected",true);
                        if(colaborador.tipo == colaboradorResponsable){
                            $("#btnAgregarColaborador").prev().find(":radio").attr("checked","true");
                        }  
                    });
                },
                error: function(jqXHR, textStatus, errorThrown){

                },
            });

        });

    }

@endsection
