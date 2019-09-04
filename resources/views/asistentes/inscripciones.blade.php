@extends('layouts.principal')
@section('titulo', 'Inscripciones')
@push('scripts')
    <script src="{{ asset('/js/fecha.js') }}"></script>
    <script src="{{ asset('/js/evento.js') }}"></script>
    <script src="{{ asset('/js/subevento.js') }}"></script>
    <script src="{{ asset('/js/asistente.js') }}"></script>
    <script src="{{ asset('/js/colaborador.js') }}"></script>
    <script src="{{ asset('/js/buscar.js') }}"></script>

@endpush
@section('contenido')
<div class="container">
    <div class="row">    
        <div class="col-md-4">
            <h4>Buscar</h4>
            <div class="form-group has-feedback" title="Buscar evento">
                <input type="text" class="form-control" id="buscarEvento" onkeyup="buscar($(this).val(), $('#listadoInscripciones div[class~=\'panel\']'))">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
        </div>

        <div class="col-md-4 col-md-offset-4 text-right hidden">
            <button type="button" class="btn btn-lg btn-primary" name="btnNuevoEvento" id="btnNuevoEvento"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo evento</button>

        </div>

    </div>

    <div class="row" id="listadoInscripciones"></div>

</div>
@endsection

@section('script')
//<script type="text/javascript">
    const today = "{{date("Y-m-d H:i:s")}}";
    const asistenteId = {{Auth::user()->id}};

    function cargarInscripciones(){
        $("#listadoInscripciones").empty();
        cargarAsistenteEventos(asistenteId, 
            (function(result){
                $.each(result.data, function (i, evento) {
                    $("#listadoInscripciones").append(agregarPanelEvento(evento));
                    evaluarElementosPanelEvento(evento);
                });
            })

        );
    }

    cargarInscripciones();
    
    /*
        @param Object evento
        @return string
    */
    function agregarPanelEvento(evento){
        const panelEvento =
            '<div class="panel panel-default" id="panelEvento'+evento.clave+'" name="panelEvento">'+
                '<div class="panel-heading">'+
                    '<div class="row">'+
                        '<div class="col-sm-4">'+
                            '<label class="control-label">Evento:</label> '+evento.titulo+
                        '</div>'+
                        '<div class="col-sm-4">'+
                            '<label class="control-label">Fecha de registro:</label> '+dateToString(evento.fechaRegistro)+
                        '</div>'+
                        '<div class="col-sm-4">'+
                            '<label class="control-label">Estado:</label> '+estadoAsistente(evento.estado)+
                        '</div>'+
                    '</div>' +
                    '<div class="row">'+
                        '<div class="col-sm-3 col-sm-offset-3">'+
                            '<button type="button" class="btn btn-primary btn-block" name="btnSubirBaucher" id="btnBaucherEvento'+evento.clave+'"> <span class="fas fa-upload" aria-hidden="true"></span> Subir baucher</button>'+ 
                        '</div>'+
                        '<div class="col-sm-3">'+
                            '<button type="button" class="btn btn-success btn-block" name="btnVerMas" id="'+evento.clave+'" value="'+evento.titulo+'" onclick="modalAsistenteEvento('+evento.clave+','+asistenteId+')"> <span class="fas fa-info-circle" aria-hidden="true"></span> Más información</button>'+ 
                        '</div>'+
                        '<div class="col-sm-3">'+
                            '<button type="button" class="btn btn-danger btn-block" id="btnDesincripcionEvento'+evento.clave+'"> <span class="fas fa-eraser" aria-hidden="true"></span> Desinscribirse</button>'+ 
                        '</div>'+
                    '</div>' +
                    '<div name="datosDeposito">'+
                        '<p class="text-info">Podrá seleccionar hasta '+evento.subeventosElegibles+' subeventos después de ser aprobado, pero primero deberá proporcionar el comprobante de pago.</p>'+
                        '<label>Datos de depósito:</label>'+
                        '<div class="row">'+
                            '<div class="col-sm-3">'+
                                '<label id="labelPreciosE'+evento.clave+'">Monto:</label>'+
                            '</div>'+
                            '<div class="col-sm-4">'+
                                '<label>Detalles:</label>'+
                                '<p>'+(evento.informacionPago != null ? evento.informacionPago.replace(/\n/g, "<br>") : evento.informacionPago)+'</p>'+
                            '</div>'+
                            '<div class="col-sm-3">'+
                                '<label>Referencia:</label>'+
                                '<p>'+evento.referencia+'</p>'+
                            '</div>'+
                            '<div class="col-sm-2">'+
                                '<a role="button" href="{{ url('assistants') }}/'+asistenteId+'/events/'+evento.clave+'/datosDeposito" class="btn btn-default"> <span class="far fa-file-pdf" aria-hidden="true"></span> Recursos</a>'+
                            '</div>'+
                        '</div>' +
                    '</div>' +
                '</div>'+
                '<div class="panel-body">'+
                    '<div class="list-group"></div>'+
                '</div>'+
                '<div class="panel-footer panel-info">'+
                    '<p><label>Fecha del evento:</label> Del '+dateToString(evento.fechaInicio)+', al '+dateToString(evento.fechaFin)+'</p>'+
                '</div>'+
            '</div>'
        ;
        
        return panelEvento;
    }


    function evaluarElementosPanelEvento(evento){
        if(fechaEventoDisponible(today, evento.fechaFin, true)){
            $("#btnDesincripcionEvento"+evento.clave).click(function(){
                mostrarModalDesincripcion(asistenteId, evento);
            });

            $("#btnBaucherEvento"+evento.clave).click(function(){
                mostrarModalSubirBaucher(asistenteId, evento);
            });
        }
        else{
            $("#panelEvento"+evento.clave).find("button, a").not('[name="btnVerMas"]').attr("disabled", "disabled");
        }

        if(evento.estado == ASISTENTE_REGISTRADO){
            $("#panelEvento"+evento.clave).removeClass("panel-default").addClass("panel-warning");
        }
        else if(evento.estado == ASISTENTE_EN_VERIFICACION){
             $("#panelEvento"+evento.clave).removeClass("panel-default").addClass("panel-info");
        }
        else{
            $("#panelEvento"+evento.clave).removeClass("panel-default").addClass("panel-success").find('[name="btnSubirBaucher"] , [name="datosDeposito"]').remove();
        }

        /*Si el evento no tiene precio o si se ha alcanzado el limite de subeventos permitidos, se cargan directamente las inscripciones*/
        if(evento.precioInscripcion == null || evento.subeventosElegibles == evento.subeventosElegidos){
            const filtro = "evento="+evento.clave;
            cargarAsistenteSubeventos(asistenteId, filtro , 
                (function(result){
                    $("#panelEvento"+evento.clave).find(".list-group").before(
                        '<h6>Subeventos en los que está inscrito</h6>'
                    );
                    const subeventos = result.data;
                    agregarSubeventos(evento, subeventos);
                })
            );
        }
        /*Es pago por evento y aún no se ha alcanzado el limite de subeventos elegibles*/
        else{
            cargarEventoSubeventos(evento.clave,
                (function(result){
                    $("#panelEvento"+evento.clave).find(".list-group").before(
                        '<h6>Puede inscribirse como máximo en '+evento.subeventosElegibles+(evento.subeventosElegibles > 1 ? " subeventos" : " subevento")+'.</h6>'
                    ); 
                    var subeventos = result.data;
                    const filtro = "evento="+evento.clave;
                    if(evento.subeventosElegidos > 0){
                        cargarAsistenteSubeventos(asistenteId, filtro , 
                            (function(result){
                                const subeventosElegidos = result.data;
                                $.each(subeventosElegidos,  function(i, subeventoElegido){
                                    $.each(subeventos, function(j, subevento){
                                        if(subevento.clave == subeventoElegido.clave){
                                            subeventos[j] = subeventoElegido;
                                            return false;
                                        }
                                    });
                                });

                                agregarSubeventos(evento, subeventos);
                            })
                        );
                    }
                    else{
                        agregarSubeventos(evento, subeventos);
                    }
                })
            );

            cargarEventoPrecios(evento.clave, (function(result){
                    $.each(result.data, function(index, precio){
                        $("#labelPreciosE"+evento.clave).after(
                            '<p>'+precio.descripcion+' $'+precio.precio+'</p>'
                        );
                    });
                })

            );
        }
    }

    function agregarSubeventos(evento, subeventos){
        $.each(subeventos, function (i, subevento) {
            $("#panelEvento"+evento.clave).find(".list-group").append(agregarItemSubevento(evento, subevento));
            evaluarElementosItemSubevento(evento, subevento);
        });
    }
    
    function evaluarElementosItemSubevento(evento, subevento){
        if(!fechaSubeventoDisponible(today, subevento.fechaHora)){
            $("#itemSubevento"+subevento.clave).find("button, a").not('[name="btnVerMas"]').attr("disabled", "disabled");
        }
        //Cargar los precios del subevento
        if(subevento.precioInscripcion != null){
            cargarSubeventoPrecios(subevento.clave, (function(result){
                    $.each(result.data, function(index, precio){
                        $("#labelPreciosSub"+subevento.clave).after(
                            '<p>'+precio.descripcion+' $'+precio.precio+'</p>'
                        );
                    });
                })

            );
        }

        const btnOperacionSubevento = $("#btnOperacionSubevento"+subevento.clave);

        if(subevento.estado != null){
            btnOperacionSubevento.click(function(){
                mostrarModalSubirBaucher(asistenteId, evento, subevento);
            });

            $('#btnDesincripcionSubevento'+subevento.clave).click(function(){
                mostrarModalDesincripcion(asistenteId, evento, subevento);
            });

            if(subevento.estado == ASISTENTE_REGISTRADO){
                //$("#itemSubevento"+subevento.clave).addClass("list-group-item-warning");
                $("#itemSubevento"+subevento.clave).find('[name="columnaEstado"]').addClass("has-warning");
            }
            else if(subevento.estado == ASISTENTE_EN_VERIFICACION){
                //$("#itemSubevento"+subevento.clave).addClass("list-group-item-info");
                $("#itemSubevento"+subevento.clave).find('[name="columnaEstado"]').addClass("has-info");
            }
            else{
                //$("#itemSubevento"+subevento.clave).addClass("list-group-item-success");
                $("#itemSubevento"+subevento.clave).find('[name="columnaEstado"]').addClass("has-success");
                btnOperacionSubevento.remove();
                $("#itemSubevento"+subevento.clave).find('[name="datosDeposito"]').remove();
            }
        }
        else{
            $('#btnDesincripcionSubevento'+subevento.clave).parent().remove();
            btnOperacionSubevento
                .empty()
                .text("Inscribirse")
                .prepend('<span class="fas fa-pencil-alt" aria-hidden="true"></span> ')
                .parent()
                .removeClass("col-sm-offset-3")
                .addClass("col-sm-offset-6");

            if(subevento.cuposDisponibles != null && subevento.cuposDisponibles == 0){
                btnOperacionSubevento
                    .addClass("disabled")
                    .text("Sin cupos")
                    .prepend('<span class="fas fa-frown" aria-hidden="true"></span> ');

            }
            else if(evento.estado == ASISTENTE_APROBADO){
                btnOperacionSubevento.click(function(){
                    $("#itemSubevento"+subevento.clave).find(".alert").remove();
                    inscripcionSubevento(asistenteId, subevento.clave, 
                        (function(result){
                            const oldItem = $("#itemSubevento"+subevento.clave);
                            oldItem.fadeOut().before(agregarItemSubevento(evento, result.data));
                            oldItem.remove();
                            evaluarElementosItemSubevento(evento, result.data);
                        }), 
                        (function(error){
                            $("#itemSubevento"+subevento.clave).append(
                                '<div class="alert alert-danger alert-dismissable fade in">'+
                                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+error+
                                '</div>'
                            );
                        })
                    );
                });
            }
        }
    }

    function agregarItemSubevento(evento, subevento){
        var item = 
        '<div class="list-group-item" id="itemSubevento'+subevento.clave+'" name="itemSubeventoE'+evento.clave+'">'+
            '<div class="row list-group-item-heading">'+
                '<div class="col-sm-4">'+
                    '<label class="control-label">'+subevento.titulo+'</label>'+
                '</div>'+
                (subevento.fechaRegistro?
                '<div class="col-sm-4">'+
                    '<label class="control-label">Fecha de registro:</label> '+dateToString(subevento.fechaRegistro)+
                '</div>'+
                '<div class="col-sm-4" name="columnaEstado">'+
                    '<label class="control-label">Estado:</label> '+estadoAsistente(subevento.estado)+
                '</div>' : "")+
            '</div>' +
            '<div class="list-group-item-text">'+
                '<div class="row">'+
                   '<div class="col-sm-offset-3 col-sm-3">'+
                        '<button type="button" class="btn btn-primary btn-block'+(evento.estado != ASISTENTE_APROBADO ? " disabled" : "")+'" id="btnOperacionSubevento'+subevento.clave+'"> <span class="fas fa-upload" aria-hidden="true"></span> Subir baucher</button>'+ 
                    '</div>'+
                    '<div class="col-sm-3">'+
                        '<button type="button" class="btn btn-success btn-block" name="btnVerMas" id="'+subevento.clave+'" value="'+subevento.titulo+'" onclick="'+
                            (subevento.fechaRegistro 
                                ? 'modalAsistenteSubevento('+subevento.evento+','+ subevento.clave+','+asistenteId+')'
                                : 'modalSubevento('+subevento.evento+','+ subevento.clave+')')+
                            '"> <span class="fas fa-info-circle" aria-hidden="true"></span> Más información</button>'+ 
                    '</div>'+
                    '<div class="col-sm-3">'+
                        '<button type="button" class="btn btn-danger btn-block" id="btnDesincripcionSubevento'+subevento.clave+'"> <span class="fas fa-eraser" aria-hidden="true"></span> Desinscribirse</button>'+ 
                    '</div>'+
                '</div>' +
                (subevento.precioInscripcion ?  
                '<div name="datosDeposito">'+
                    '<p class="text-info">Deberá proporcionar el comprobante de depósito para que su inscripción sea aprobada.</p>'+
                    '<label>Datos de depósito:</label>'+
                    '<div class="row">'+
                        '<div class="col-sm-3">'+
                            '<label id="labelPreciosSub'+subevento.clave+'">Monto:</label>'+
                        '</div>'+
                        '<div class="col-sm-4">'+
                            '<label>Detalles:</label>'+
                            '<p>'+(subevento.informacionPago != null ? subevento.informacionPago.replace(/\n/g, "<br>") : subevento.informacionPago)+'</p>'+
                        '</div>'+
                        '<div class="col-sm-3">'+
                            '<label>Referencia:</label>'+
                            '<p>'+subevento.referencia+'</p>'+
                        '</div>'+
                        '<div class="col-sm-2">'+
                            '<a role="button" href="{{ url('assistants') }}/'+asistenteId+'/subevents/'+subevento.clave+'/datosDeposito" class="btn btn-default"> <span class="far fa-file-pdf" aria-hidden="true"></span> Recursos</a>'+
                        '</div>'+
                    '</div>' +
                '</div>' : "")+
                '<p class="list-group-item-text"><label>Lugar y fecha del subevento:</label> En '+subevento.lugar+' el '+dateToString(subevento.fechaHora)+'</p>'+
            '</div>'+
        '</div>';

        return item;
    }

@endsection
