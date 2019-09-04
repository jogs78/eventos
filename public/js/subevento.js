const RUTA_SUBEVENTO = "/subevents";

function cargarSubeventos(onSuccess, onError){
	myAjax("GET", null, RUTA_SUBEVENTO, onSuccess, onError);
}


function cargarSubevento(claveSubevento, onSuccess, onError, btn){
    ajaxFormData(btn, "GET", RUTA_SUBEVENTO+"/"+claveSubevento, null, onSuccess, onError);
}

function crearSubevento(datos, onSuccess, onError){
	myAjax("POST", datos, RUTA_SUBEVENTO, onSuccess, onError);
}

function actualizarSubevento(claveSubevento, datos, onSuccess, onError){
	datos._method = 'PUT';
	myAjax("POST", datos, RUTA_SUBEVENTO+"/"+claveSubevento, onSuccess, onError);
}


function eliminarSubevento(claveSubevento, onSuccess, onError, btn){
    ajaxFormData(btn, "DELETE", RUTA_SUBEVENTO+"/"+claveSubevento, null, onSuccess, onError);
}


function cargarSubeventoAsistentes(claveSubevento, onSuccess, onError){
	myAjax("GET", null, RUTA_SUBEVENTO+"/"+claveSubevento+"/assistants", onSuccess, onError);
}

function cargarSubeventoAsistente(claveSubevento, claveAsistente, onSuccess, onError){
	myAjax("GET", null, RUTA_SUBEVENTO+"/"+claveSubevento+"/assistants/"+claveAsistente, onSuccess, onError);
}

function cargarSubeventoColaboradores(claveSubevento, onSuccess, onError){
	myAjax("GET", null, RUTA_SUBEVENTO+"/"+claveSubevento+"/collaborators?sortByDesc=tipo", onSuccess, onError);
}

function cargarSubeventoColaborador(claveSubevento, claveColaborador, onSuccess, onError){
	myAjax("GET", null, RUTA_SUBEVENTO+"/"+claveSubevento+"/collaborators/"+claveColaborador, onSuccess, onError);
}

function cargarSubeventoPrecios(claveSubevento, onSuccess, onError){
    ajaxFormData(null, 'GET', RUTA_SUBEVENTO+'/'+claveSubevento+'/prices?sortBy=precio', null, onSuccess, onError);
}

function aprobarSubeventoAsistente(claveSubevento, claveAsistente, onSuccess, onError){
    ajaxFormData(null, 'PUT', RUTA_SUBEVENTO+'/'+claveSubevento+"/assistants/"+claveAsistente, null, onSuccess, onError);
}

function mostrarModalSubevento(evento, subevento){
    $("#myModal").modal("show");

    $("#myModal")
    .find(".modal-content")
    .css({
        'border-color' : '#5cb85c'
    });

    $("#myModal")
    .find(".modal-header")
    .css({
        'background-color' : '#449d44',
        'color' : 'white'
    });


    $("#myModal")
    .find('.modal-footer')
    .empty()
    .append(
        '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'
        );

    $("#myModal")
    .find('[name="modal-header"]')
    .empty()
    .append('<h4 class="modal-title">'+subevento.titulo+'</h4>');
    
    const campo = 
        '<div class="form-group">'+
            '<label class="control-label">$campo</label>'+
            '<p>$valor</p>'+
        '</div>';

    /*Información del evento*/
    var imagenEvento = "";
    if(evento.imagen){
		imagenEvento = '<br><center><img src="'+evento.imagen+'" class="img-responsive" alt="Imágen del evento"></center><br>'
    }

    const tituloEvento = 
            (campo.replace("$campo", "Titulo:"))
            .replace("$valor", evento.titulo);


    const detallesEvento = 
            (campo.replace("$campo", "Descripción:"))
            .replace("$valor", evento.detalles.replace(/\n/g, "<br>"));

    const fechaInicioEvento = 
            (campo.replace("$campo", "Fecha de inicio:"))
            .replace("$valor", dateToString(evento.fechaInicio));

    const fechaFinEvento = 
            (campo.replace("$campo", "Fecha de finalización:"))
            .replace("$valor", dateToString(evento.fechaFin));               

    const organizadorEvento = 
            (campo.replace("$campo", "Datos del organizador:"))
            .replace("<p>$valor</p>", '<p name="organizadorNombre">'+evento.organizador+'</p>');
            

    var urlInformacionEvento = "";
    if(evento.masInformacion){
        urlInformacionEvento = 
            (campo.replace("$campo", "Más información en:"))
            .replace("$valor", '<a href="'+evento.masInformacion+'" target="_new">'+evento.masInformacion+'</a>');

    }

    var campoInscripcionEvento = "";
    if(evento.precioInscripcion != null){
        campoInscripcionEvento = 
            (campo.replace("$campo", "Precios de inscripción:"))
            .replace("<p>$valor</p>", '<p id="mostrarPrecios">...</p>');

        campoInscripcionEvento += 
            (campo.replace("$campo", "Información de pago:"))
            .replace("$valor", evento.informacionPago.replace(/\n/g, "<br>"));
       	
       	if(evento.referencia){
       		campoInscripcionEvento += 
	            (campo.replace("$campo", "Referencia:"))
	            .replace("$valor", evento.referencia);

            if(evento.baucher){
                campoInscripcionEvento +=
                    '<div class="form-group">'+
                        '<label class="control-label">Comprobante de depósito:</label>'+
                        '<a href="'+evento.baucher+'" target="_blank">'+
                            '<img src="'+evento.baucher+'" alt="Imágen del baucher" class="img-responsive">'+
                        '</a><br>'
                    '</div>';
            }
            else{
                campoInscripcionEvento += 
                    (campo.replace("$campo", "Comprobante de depósito:"))
                    .replace("$valor", "El comprobante aún no se ha subido."); 
            }
       	}
       	
        campoInscripcionEvento += 
            (campo.replace("$campo", "Subeventos elegibles:"))
            .replace("$valor", evento.subeventosElegibles);
    }


    /*Información del subevento*/
    var imagen = "";
    if(subevento.imagen){
    	imagen = '<br><center><img src="'+subevento.imagen+'" class="img-responsive" alt="Imágen del subevento"></center><br>'
	}

    const titulo = 
            (campo.replace("$campo", "Titulo:"))
            .replace("$valor", subevento.titulo);


    const detalles = 
            (campo.replace("$campo", "Descripción:"))
            .replace("$valor", subevento.detalles.replace(/\n/g, "<br>"));

    const fechaHora = 
            (campo.replace("$campo", "Fecha y hora:"))
            .replace("$valor", dateToString(subevento.fechaHora));

    const lugar = 
            (campo.replace("$campo", "Lugar:"))
            .replace("$valor", subevento.lugar.replace(/\n/g, "<br>"));               

    const cupos = subevento.cuposDisponibles == null 
        ? "Sin limite de asistentes" : subevento.cuposDisponibles;        

    const cuposDisponibles = 
        (campo.replace("$campo", "Cupos disponibles:"))
        .replace("$valor", cupos); 

    var precioInscripcion = "";

    if(evento.precioInscripcion != null){
        precioInscripcion = "Pago por evento";
    }
    else if(subevento.precioInscripcion){
        precioInscripcion = "$"+subevento.precioInscripcion;
    }
    else{
        precioInscripcion = "Gratis";
    }

    var campoPrecioInscripcion = 
        (campo.replace("$campo", "Precio de inscripción:"))
        .replace("<p>$valor</p>", '<p id="mostrarPreciosSub">'+precioInscripcion+'</p>');
    
    if(subevento.informacionPago){
        campoPrecioInscripcion += 
            (campo.replace("$campo", "Información de pago:"))
            .replace("$valor", subevento.informacionPago.replace(/\n/g, "<br>"));
    }

    var campoReferencia = "";
    var baucher = "";
    if(subevento.referencia){
        campoReferencia += 
            (campo.replace("$campo", "Referencia:"))
            .replace("$valor", subevento.referencia);

	    if(subevento.baucher){
	        baucher +=
	            '<div class="form-group">'+
	                '<label class="control-label">Comprobante de depósito:</label>'+
	                '<a href="'+subevento.baucher+'" target="_blank">'+
	                    '<img src="'+subevento.baucher+'" alt="Imágen del baucher" class="img-responsive">'+
	                '</a>'
	            '</div>';
	    }
	    else{
	        baucher += 
	            (campo.replace("$campo", "Comprobante de depósito:"))
	            .replace("$valor", "El comprobante aún no se ha subido."); 
	    }
    }

 
    const informacionEvento = 
		'<button data-toggle="collapse" data-target="#infoEvento" class="btn btn-success btn-block">Información del evento</button>'+
		'<div id="infoEvento" class="collapse">'+
			imagenEvento+
			tituloEvento+
			detallesEvento+
			fechaInicioEvento+
			fechaFinEvento+
			organizadorEvento+
			campoInscripcionEvento+
            urlInformacionEvento+
		'</div>';
	
	const campoColaboradores = '<div name="contenedorColaboradores"></div>';

    $("#myModal")
    .find('.modal-body')
    .empty()
    .append(
        imagen,
        titulo, 
        detalles,
        fechaHora,
        lugar,
        cuposDisponibles,
 		campoColaboradores,
        campoPrecioInscripcion,
        campoReferencia,
        baucher,
        informacionEvento,
    );

    /*Cargar organizador del evento*/
    cargarEventoOrganizador(evento.clave, (
    	function (result){
    		const organizador = result.data;
    		$("#myModal")
    			.find('[name="organizadorNombre"]')
    			.html(
    				'<p>'+usuarioNombreCompleto(organizador.nombre, organizador.apellidoPaterno, organizador.apellidoMaterno)+'</p>'+
    				'<p><a href="mailto:'+organizador.correo+'">'+organizador.correo+'</a></p>'+
    				(organizador.telefono ? '<p>'+organizador.telefono+'</p>' : '')
    			);

    	})
    );

    /*Cargar los precios de inscripción del evento*/
    if(evento.precioInscripcion != null){
        cargarEventoPrecios(evento.clave, (
            function(result){
                $.each(result.data, function(index, precio){
                    $("#mostrarPrecios").before(
                        '<p>'+precio.descripcion+' $'+precio.precio+'</p>'
                    );
                });
                $("#mostrarPrecios").remove();
            })
        )
    }

    /*Cargar los precios de inscripción del subevento*/
    if(subevento.precioInscripcion != null){
        cargarSubeventoPrecios(subevento.clave, (
            function(result){
                $.each(result.data, function(index, precio){
                    $("#mostrarPreciosSub").before(
                        '<p>'+precio.descripcion+' $'+precio.precio+'</p>'
                    );
                });
                $("#mostrarPreciosSub").remove();
            })
        )
    }

    /*Cargar colaboradores*/
    cargarSubeventoColaboradores(subevento.clave, 
        (function(result){
            if(result.data.length > 0){
                var colaboradores = 
                    '<div class="form-group">'+
                        '<label class="control-label">Colaboradores:</label>';
                $.each(result.data, function (index, colaborador){
                    colaboradores +=
                        '<p>'+
                            usuarioNombreCompleto(colaborador.nombre, colaborador.apellidoPaterno, colaborador.apellidoMaterno)+
                            ' ('+tipoColaborador(colaborador.tipo)+') '+        
                            '(<a href="mailto:'+colaborador.correo+'">'+colaborador.correo+'</a>)'+
                        '</p>'
                    ;
                });

                colaboradores += 
                    "</div>";

                $("#myModal")
                    .find('[name="contenedorColaboradores"]')
                    .html(colaboradores);
            }
        })
    );
}

function modalSubevento(claveEvento, claveSubevento){
    cargarEvento(claveEvento, 
        (function(result){
            const evento = result.data;
            cargarSubevento(claveSubevento,
                (function(result){
                	const subevento = result.data;
                	mostrarModalSubevento(evento, subevento);
                })
            );

        })
    );
}

function modalEliminarSubevento(claveSubevento, btn){
    cargarSubevento(claveSubevento,
        (function(result){
            const subevento = result.data;
            mostrarModalEliminarSubevento(subevento);
        })
        ,null,btn
    );
}

function mostrarModalEliminarSubevento(subevento){
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
        .append('<h4 class="modal-title">Eliminar subevento</h4>');

    $("#myModal")
        .find('.modal-body')
        .empty()
        .append('<p>¿Realmente quiere eliminar el subevento <strong>'+subevento.titulo+'</strong>?, esta acción no se puede deshacer.</p>');

    $("#myModal")
        .find('.modal-footer')
        .empty()
        .append(
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>'+
            '<button type="button" class="btn btn-danger" name="btnEliminar">Eliminar</button>'
            );

    if(subevento.numeroAsistentes == 0){
        $("#myModal").find('[name="btnEliminar"]').click(function(){
            const onSuccess = (function(result){
                $("#myModal").modal("hide");
                //cargarEventos();
                $("#contenedor"+subevento.clave)
                    .find('.panel-footer')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .html('<strong>Subevento eliminado correctamente</strong>')
                    .fadeIn();
                    

                setTimeout(function(){$("#contenedor"+subevento).fadeOut().remove();}, 3000);
            });

            const onError = (function(error){
                $("#myModal").modal("hide");

                $("#contenedor"+subevento.clave)
                    .find('.panel-footer')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html('<strong>'+error+'</strong>')
                    .fadeIn();
            });

            eliminarSubevento(subevento.clave, onSuccess, onError, $(this));

        });
    }
    else{
        $("#myModal")
            .find('[name="modal-header"]')
            .empty()
            .append('<h4 class="modal-title">No es posible eliminar el subevento '+subevento.titulo+'</h4>');

        $("#myModal")
            .find('.modal-body')
            .empty()
            .append(
                '<p class="text-info"> <strong> No puede eliminar este subevento debido a que hay '+subevento.numeroAsistentes+' asistentes inscritos en el. </strong></p>'
            );
        $("#myModal").find('[name="btnEliminar"]').attr("disabled", "disabled");
    }
}