const RUTA_EVENTO = "/events";

function cargarEventos(onSuccess, onError){
	myAjax("GET", null, RUTA_EVENTO, onSuccess, onError);
}

function cargarEvento(claveEvento, onSuccess, onError, btn){
	ajaxFormData(btn, "GET", RUTA_EVENTO+"/"+claveEvento, null, onSuccess, onError);
}

function crearEvento(datos, onSuccess, onError){
	myAjax("POST", datos, RUTA_EVENTO, onSuccess, onError);
}

function actualizarEvento(claveEvento, datos, onSuccess, onError){
	datos._method = 'PUT';
	myAjax("POST", datos, RUTA_EVENTO+"/"+claveEvento, onSuccess, onError);
}

function eliminarEvento(claveEvento, onSuccess, onError, btn){
    ajaxFormData(btn, "DELETE", RUTA_EVENTO+"/"+claveEvento, null, onSuccess, onError);
}

function cargarEventoSubevento(claveEvento, claveSubevento, onSuccess, onError){
	myAjax("GET", null, RUTA_EVENTO+"/"+claveEvento+"/subevents/"+claveSubevento, onSuccess, onError);
}

function cargarEventoSubeventos(claveEvento, onSuccess, onError){
	myAjax("GET", null, RUTA_EVENTO+"/"+claveEvento+"/subevents", onSuccess, onError);
}

function cargarEventoOrganizador(claveEvento, onSuccess, onError){
	myAjax("GET", null, RUTA_EVENTO+"/"+claveEvento+"/organizers", onSuccess, onError);
}

function cargarEventoPrecios(claveEvento, onSuccess, onError){
    ajaxFormData(null, 'GET', RUTA_EVENTO+'/'+claveEvento+'/prices?sortBy=precio', null, onSuccess, onError);
}

function cargarEventoAsistente(claveEvento, claveAsistente, onSuccess, onError){
    ajaxFormData(null, 'GET', RUTA_EVENTO+'/'+claveEvento+"/assistants/"+claveAsistente, null, onSuccess, onError);
}

function cargarEventoAsistentes(claveEvento, onSuccess, onError){
    ajaxFormData(null, 'GET', RUTA_EVENTO+'/'+claveEvento+"/assistants", null, onSuccess, onError);
}


function aprobarEventoAsistente(claveEvento, claveAsistente, onSuccess, onError){
    ajaxFormData(null, 'PUT', RUTA_EVENTO+'/'+claveEvento+"/assistants/"+claveAsistente, null, onSuccess, onError);
}

function mostrarModalEvento(evento){
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
    .append('<h4 class="modal-title">'+evento.titulo+'</h4>');
    
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
            .replace("$valor", '<a href="'+evento.masInformacion+'" target="_blank">'+evento.masInformacion+'</a>');

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


    $("#myModal")
    .find('.modal-body')
    .empty()
    .append(
		imagenEvento,
		tituloEvento,
		detallesEvento,
		fechaInicioEvento,
		fechaFinEvento,
		organizadorEvento,
		campoInscripcionEvento,
        urlInformacionEvento,
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

}

function modalEvento(claveEvento, btn){
    cargarEvento(claveEvento, 
        (function(result){
            const evento = result.data;
            mostrarModalEvento(evento);
        }), 
        null, btn
    );
}

function modalEliminarEvento(claveEvento, btn){
    cargarEvento(claveEvento, 
        (function(result){
            const evento = result.data;
            mostrarModalEliminarEvento(evento);
        }),
        null,btn
    );
}

function mostrarModalEliminarEvento(evento){
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
        .append('<h4 class="modal-title">Eliminar evento</h4>');

    $("#myModal")
        .find('.modal-body')
        .empty()
        .append('<p>¿Realmente quiere eliminar el evento <strong>'+evento.titulo+'</strong>?, esta acción no se puede deshacer.</p>');

    $("#myModal")
        .find('.modal-footer')
        .empty()
        .append(
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>'+
            '<button type="button" class="btn btn-danger" name="btnEliminar">Eliminar</button>'
            );

    if(evento.numeroAsistentes == 0){
        $("#myModal").find('[name="btnEliminar"]').click(function(){
            const onSuccess = (function(result){
                $("#myModal").modal("hide");
                //cargarEventos();
                $("#contenedor"+evento.clave)
                    .find('.panel-footer')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .html('<strong>Evento eliminado correctamente</strong>')
                    .fadeIn();
                    

                setTimeout(function(){$("#contenedor"+evento).fadeOut().remove();}, 3000);
            });

            const onError = (function(error){
                $("#myModal").modal("hide");

                $("#contenedor"+evento.clave)
                    .find('.panel-footer')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html('<strong>'+error+'</strong>')
                    .fadeIn();
            });

            eliminarEvento(evento.clave, onSuccess, onError, $(this));

        });
    }
    else{
        $("#myModal")
            .find('[name="modal-header"]')
            .empty()
            .append('<h4 class="modal-title">No es posible eliminar el evento '+evento.titulo+'</h4>');

        $("#myModal")
            .find('.modal-body')
            .empty()
            .append(
                '<p class="text-info"> <strong> No puede eliminar este evento debido a que hay '+evento.numeroAsistentes+' asistentes inscritos en el. </strong></p>'
            );
        $("#myModal").find('[name="btnEliminar"]').attr("disabled", "disabled");
    }
}