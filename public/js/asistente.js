const RUTA_ASISTENTE = "/assistants";
const ASISTENTE_REGISTRADO = 0;
const ASISTENTE_EN_VERIFICACION = 1;
const ASISTENTE_APROBADO = 2;

function estadoAsistente(estado){
	switch(estado){
		case ASISTENTE_REGISTRADO:
			return "Registrado, esperando baucher";
		case ASISTENTE_EN_VERIFICACION:
			return "Baucher recibido, en verificación";
		case ASISTENTE_APROBADO:
			return "Aprobado";
	}
}

function cargarAsistenteEvento(claveAsistente, claveEvento, onSuccess, onError){
	myAjax("GET", null, RUTA_ASISTENTE+"/"+claveAsistente+"/events/"+claveEvento, onSuccess, onError);
}

function cargarAsistenteEventos(claveAsistente, onSuccess, onError){
	myAjax("GET", null, RUTA_ASISTENTE+"/"+claveAsistente+"/events?sortByDesc=fechaRegistro", onSuccess, onError);
}

function cargarAsistenteSubevento(claveAsistente, claveSubevento, onSuccess, onError){
	myAjax("GET", null, RUTA_ASISTENTE+"/"+claveAsistente+"/subevents/"+claveSubevento, onSuccess, onError);
}

function cargarAsistenteSubeventos(claveAsistente, filtros, onSuccess, onError){
	myAjax("GET", null, RUTA_ASISTENTE+"/"+claveAsistente+"/subevents"+(filtros?"?"+filtros:""), onSuccess, onError);
}

function inscripcionSubevento(claveAsistente, claveSubevento, onSuccess, onError){
	var datos = { subevento_id : claveSubevento };

	myAjax("POST", datos, RUTA_ASISTENTE+"/"+claveAsistente+"/subevents", onSuccess, onError);
}


function modalAsistenteSubevento(claveEvento, claveSubevento, claveAsistente){
    cargarEvento(claveEvento, 
        (function(result){
            const evento = result.data;
            cargarAsistenteSubevento(claveAsistente, claveSubevento,
                (function(result){
                	const subevento = result.data;
                	mostrarModalSubevento(evento, subevento);
                })
            );

        })
    );
}

function modalAsistenteEvento(claveEvento, claveAsistente){
    cargarAsistenteEvento(claveAsistente, claveEvento, 
        (function(result){
            const evento = result.data;
            mostrarModalEvento(evento);
        })
    );
}

function modalSubeventoAsistente(claveSubevento, claveAsistente){
    cargarSubeventoAsistente(claveSubevento, claveAsistente,
        (function(result){
            const asistente = result.data;
            mostrarModalAsistente(asistente);
        })
    );
}

function modalEventoAsistente(claveEvento, claveAsistente){
    cargarEventoAsistente(claveEvento, claveAsistente, 
        (function(result){
            const asistente = result.data;
            mostrarModalAsistente(asistente);
        }),
        (function(jError){
            $("#myModal")
                .find('.modal-body')
                .empty()
                .append(
                   '<h4>'+jError+'</h4>' 
                );
        })
    );
}

function panelAsistente(asistente){
    const asistenteNombreCompleto = usuarioNombreCompleto(asistente.nombre, asistente.apellidoPaterno, asistente.apellidoMaterno);
    const panel =
        '<div class="panel panel-default" id="contenedor'+asistente.clave+'">'+
            '<div class="panel-body">'+
                '<div class="row">'+
                    '<div class="col-sm-4 text-center">'+
                        '<label name="asistenteNombre" value="'+asistente.clave+'">'+asistenteNombreCompleto+'</label>'+
                    '</div>'+
                    '<div class="col-sm-4 text-center">'+
                        '<p>'+asistente.correo+'</p>'+
                    '</div>'+

                    '<div class="col-sm-4 text-center">'+
                        '<p>'+estadoAsistente(asistente.estado)+'</p>'+
                        '<p>Fecha de registro: '+(moment(asistente.fechaRegistro).format("DD/MM/YYYY HH:mm:ss"))+'</p>'+
                    '</div>'+
                '</div>'+
                '<div class="row hidden-print">'+
                    '<div class="col-sm-3">'+
                        '<button type="button" class="btn btn-primary btn-block" name="btnMensaje" id="'+asistente.clave+'" value="'+asistenteNombreCompleto+'"> <span class="far fa-envelope" aria-hidden="true"></span> Enviar mensaje</button>'+ 
                    '</div>'+
                    '<div class="col-sm-3">'+
                        '<button type="button" class="btn btn-success btn-block" name="btnVerMas"> <span class="fas fa-info-circle" aria-hidden="true"></span> Más información</button>'+ 
                    '</div>'+
                    '<div class="col-sm-3">'+
                        '<button type="button" class="btn btn-primary btn-block" name="btnAprobar">Aprobar</button>'+ 
                    '</div>'+
                    '<div class="col-sm-3">'+
                        '<button type="button" class="btn btn-danger btn-block" name="btnEliminar" id="'+asistente.clave+'" value="'+asistenteNombreCompleto+'">Eliminar</button>'+ 
                    '</div>'+
                '</div>' +
            '</div>'+
            '<div class="panel-footer" style="display:none;"></div>'+
        '</div>';

    return panel;
}

function evaluarElementosPanelAsistente(asistente, claveTipo, tipo){
    const btnVerMas = $("#contenedor"+asistente.clave).find('[name="btnVerMas"]');
    const btnAprobar = $("#contenedor"+asistente.clave).find('[name="btnAprobar"]');

    if(tipo == 'evento'){
        btnVerMas.click(function(){
            modalEventoAsistente(claveTipo, asistente.clave);
        });

        btnAprobar.click(function(){
            modalAprobarEventoAsistente(claveTipo, asistente.clave);
        });
    }
    else{
        btnVerMas.click(function(){
            modalSubeventoAsistente(claveTipo, asistente.clave);
        });

        btnAprobar.click(function(){
            modalAprobarSubeventoAsistente(claveTipo, asistente.clave);
        });
    }

    //Remueve las opciones aprobar y eliminar dependiendo del estado del asistente
    if(asistente.estado == asistenteAprobado || asistente.estado == asistenteRegistrado){
        $("#contenedor"+asistente.clave).find('[name="btnAprobar"]').parent().remove();

        $("#contenedor"+asistente.clave).find('button').parent().attr("class", "col-sm-4");
        
        if(asistente.estado == asistenteAprobado){
            $("#contenedor"+asistente.clave).find('[name="btnEliminar"]').parent().remove();
            $("#contenedor"+asistente.clave).find('button').parent().attr("class", "col-sm-6");
        }
    }
}


function aprobarAsistente(claveAsistente, claveTipo, tipo){

    const panelFooterAsistente =  $("#contenedor"+claveAsistente).find('.panel-footer');
    panelFooterAsistente.empty().removeClass('alert-success alert-danger');
    $("#myModal").modal("hide");

    const onSuccess = (function(result){
        const asistente = result.data;
        const oldPanel = $("#contenedor"+asistente.clave);
            oldPanel.fadeOut().before(panelAsistente(asistente));
            oldPanel.remove();
            evaluarElementosPanelAsistente(asistente, claveTipo, tipo);
        /*
        panelFooterAsistente
            .addClass('alert-success')
            .html('<label class="control-label">Asistente aprobado correctamente</label>')
            .fadeIn();
        */    
    });

    const onError = (function(jError){
        panelFooterAsistente.addClass("alert-danger");
        if(jError instanceof Object){
            $.each(jError, function(i, error){
                panelFooterAsistente.append('<label class="control-label">'+error+'</label>')
            });
        }
        else{
            panelFooterAsistente.html('<strong>'+jError+'</strong>')
        }
        panelFooterAsistente.fadeIn();
    });

    if(tipo == 'evento'){
        aprobarEventoAsistente(claveTipo, claveAsistente, onSuccess, onError);
    }
    else{
        aprobarSubeventoAsistente(claveTipo, claveAsistente, onSuccess, onError);
    }
    
}

function modalAprobarEventoAsistente(claveEvento, claveAsistente){
    cargarEventoAsistente(claveEvento, claveAsistente, 
        (function(result){
            const asistente = result.data;
            mostrarModalAprobarAsistente(asistente, claveEvento, 'evento');
        })
    );
}

function modalAprobarSubeventoAsistente(claveSubevento, claveAsistente){
    cargarSubeventoAsistente(claveSubevento, claveAsistente, 
        (function(result){
            const asistente = result.data;
            mostrarModalAprobarAsistente(asistente, claveSubevento, 'subevento');
        })
    );
}


function mostrarModalAprobarAsistente(asistente, claveTipo, tipo){
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
        .append('<h4 class="modal-title">Aprobar asistente</h4>');

    $("#myModal")
        .find('.modal-body')
        .empty()
        .append('<p>¿Realmente quiere aprobar al asistente <strong>'+usuarioNombreCompleto(asistente.nombre, asistente.apellidoPaterno, asistente.apellidoPaterno)+'</strong>?</p>');

    $("#myModal")
        .find('.modal-footer')
        .empty()
        .append(
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>'+
            '<button type="button" class="btn btn-primary" onclick="aprobarAsistente('+asistente.clave+','+claveTipo+',\''+tipo+'\')">Aprobar</button>'
            );
}

function mostrarModalAsistente(asistente){
    $('[name="alert"]').nextAll('.alert').remove();
    
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
    .find('[name="modal-header"]')
    .empty()
    .append('<h4 class="modal-title">'+asistente.nombre+'</h4>');


    $("#myModal")
    .find('.modal-footer')
    .empty()
    .append(
        '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'
        );

    const campo = 
        '<div class="form-group">'+
            '<label class="control-label">$campo</label>'+
            '<p>$valor</p>'+
        '</div>';

    const nombre = 
            (campo.replace("$campo", "Nombre:"))
            .replace("$valor", usuarioNombreCompleto(asistente.nombre, asistente.apellidoPaterno, asistente.apellidoMaterno));

    const sexo = 
            (campo.replace("$campo", "Sexo:"))
            .replace("$valor", asistente.sexo == 'm' ? "Masculino" : "Femenino");

    var ocupacion = "";
    if(asistente.ocupacion){
        ocupacion = 
            (campo.replace("$campo", "Ocupación:"))
            .replace("$valor", asistente.ocupacion);   
    }

    var instituto_dependencia = "";
    if(asistente['instituto-dependencia']){
        instituto_dependencia = 
            (campo.replace("$campo", "Instituto/Dependencia:"))
            .replace("$valor", asistente['instituto-dependencia']);   
    }

    var telefono = "";
    if(asistente.telefono){
        telefono = 
            (campo.replace("$campo", "Teléfono:"))
            .replace("$valor", asistente.telefono);   
    }

    const correo = 
            (campo.replace("$campo", "Correo electrónico:"))
            .replace("$valor", asistente.correo);   
    
    const estado = 
        (campo.replace("$campo", "Estado de inscripción:"))
        .replace("$valor", estadoAsistente(asistente.estado)); 

    
    var referencia = "";
    var baucher = ""; 
    if(asistente.referencia){
        referencia = 
            (campo.replace("$campo", "Referencia de pago:"))
            .replace("$valor", asistente.referencia); 

        if(asistente.baucher){
            baucher =
                '<div class="form-group">'+
                    '<label class="control-label">Comprobante de depósito:</label>'+
                    '<a href="'+asistente.baucher+'" target="_blank">'+
                        '<img src="'+asistente.baucher+'" alt="Imágen del baucher" class="img-responsive">'+
                    '</a>'
                '</div>';
        }
        else{
            baucher = 
                (campo.replace("$campo", "Comprobante de depósito:"))
                .replace("$valor", "El asistente aún no ha subido el comprobante."); 

        }
    }


    $("#myModal")
    .find('.modal-body')
    .empty()
    .append(
        nombre,
        sexo,
        ocupacion,
        instituto_dependencia,
        telefono,
        correo,
        estado,
        referencia,
        baucher,
    );
}


function subirBaucherSubevento(btn, claveAsistente, claveSubevento, datos, onSuccess, onError){
	datos.append("_method", "PUT");
	ajaxFormData(btn, "POST", RUTA_ASISTENTE+"/"+claveAsistente+"/subevents/"+claveSubevento, datos, onSuccess, onError, validarFormulario);
}

function subirBaucherEvento(btn, claveAsistente, claveEvento, datos, onSuccess, onError){
	datos.append("_method", "PUT");
	ajaxFormData(btn, "POST", RUTA_ASISTENTE+"/"+claveAsistente+"/events/"+claveEvento, datos, onSuccess, onError, validarFormulario);
}


function subirBaucher(btn, claveAsistente, evento, subevento){
    var datos = new FormData();

    if($("#url_baucher")[0].files[0]){
        datos.append('url_baucher', $("#url_baucher")[0].files[0]);
    }

    $('.form-group').removeClass('has-error');
    $('span').remove('.errors');
    $('[name="alert"]').empty().removeClass("alert-success alert-danger");

   	const onSuccess = (function(result){
   		const datos = result.data;
        $("[name=alert]").addClass("alert-success").append("<p>Archivo subido</p>").fadeIn();
        setTimeout(function(){$("[name=alert]").fadeOut();}, 3000);
		$("#imagenBaucher").fadeIn("fast").attr('src',datos.baucher).attr("alt","Debería visualizar la imágen subida, de lo contrario probablemente la imágen haya subido con errores, por favor suba nuevamente el archivo.");
   	    if(subevento){
            const oldItem = $("#itemSubevento"+subevento.clave);
            oldItem.fadeOut().before(agregarItemSubevento(evento, datos));
            oldItem.remove();
            evaluarElementosItemSubevento(evento, datos);
        }
        else{
            const oldPanel = $("#panelEvento"+evento.clave);
            oldPanel.fadeOut();
            oldPanel.before(agregarPanelEvento(datos));
            oldPanel.remove();
            evaluarElementosPanelEvento(datos);
        }
    });

   	const onError = (function(JSONError){
	    if(JSONError instanceof Object){
	        $.each(JSONError, function(campo, errores){
	            $("[name="+campo+"]").parents('div').filter('.form-group').addClass('has-error');

	            //console.log(campo);
	            $.each(JSONError,function(i, error){
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
	        $("[name=alert]").addClass("alert-danger").append("<p>"+JSONError+"</p>").fadeIn();
	    }
	    setTimeout(function(){$("[name=alert]").fadeOut();}, 3000);
   	});

   	if(subevento){
   		subirBaucherSubevento(btn, claveAsistente, subevento.clave, datos, onSuccess, onError);
   	}
   	else{
   		subirBaucherEvento(btn, claveAsistente, evento.clave, datos, onSuccess, onError);
   	}
}



function mostrarModalSubirBaucher(claveAsistente, evento, subevento){

    const tipo = subevento ? 'subevento' : 'evento';
    const titulo = subevento ? subevento.titulo :  evento.titulo;

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
	    .append('<h4 class="modal-title">Subir comprobante de depósito para el '+tipo+' '+titulo+'</h4>');


    $("#myModal")
	    .find('.modal-footer')
	    .empty()
	    .append(
        	'<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
        	'<button type="button" class="btn btn-primary" name="btnSubirBaucher">Subir</button>'
        );

    $("#myModal")
        .find('.modal-body')
        .empty()
        .append(
            '<form id="formularioBaucher">'+
                '<div class="form-group">'+
                    '<label for="url_baucher" class="control-label">Seleccione la imágen del comprobante de depósito:</label>'+
                    '<input type="file" name="url_baucher" id="url_baucher" title="Seleccione la imágen del comprobante de depósito" required>'+
                '</div>'+
                '<div class="form-group">'+
                    '<label for="info" class="control-label">Importante:</label>'+
                    '<p>Una vez que haya presionado el boton subir, se intentará cargar la imágen, verifique que al terminar se logre visualizar la imágen, de lo contrario vuelva a intentarlo.</p>'+
                '</div>'+
                '<div class="form-group">'+
                    '<img src="" class="img-responsive" id="imagenBaucher" />'+
                '</div>'+
            '</form> '
        );

    $("#myModal").find('[name="btnSubirBaucher"]').click(function(){
        subirBaucher($(this), claveAsistente, evento, subevento);
    });
}

function mostrarModalInscripcion(tipo, tituloTipo, claveTipo, claveAsistente, from){
	const action = from && from == "home" ? 'inscribir('+claveTipo+',\''+tipo+'\')' : 'inscripcionSubevento('+claveTipo+','+claveTipo+')';
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
	    .append('<h4 class="modal-title">Inscripción al '+tipo+' '+tituloTipo+'</h4>');


    $("#myModal")
	    .find('.modal-footer')
	    .empty()
	    .append(
        	'<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
        	'<button type="button" class="btn btn-primary" onclick="'+action+'">Inscribirme</button>'
        );

    $("#myModal")
        .find('.modal-body')
        .empty()
        .append(
            '<div class="form-group">'+
                '<p>¿Está seguro de inscribirse al '+tipo+' <strong>'+tituloTipo+'</strong>?<p>'+
            '</div>'
        );
}

function desincripcionEvento(btn, claveAsistente, claveEvento, onSuccess, onError){
    ajaxFormData(null, "DELETE", RUTA_EVENTO+"/"+claveEvento+"/assistants/"+claveAsistente, null, onSuccess, onError);
}

function desincripcionSubevento(btn, claveAsistente, claveSubevento, onSuccess, onError){
    ajaxFormData(null, "DELETE", RUTA_SUBEVENTO+"/"+claveSubevento+"/assistants/"+claveAsistente, null, onSuccess, onError);
}

function desincribirse(btn, claveAsistente, evento, subevento){
    const tipo = subevento ? 'subevento': 'evento';
    const titulo = subevento ? subevento.titulo : evento.titulo;

    $('[name="alert"]').empty().removeClass("alert-success alert-danger");
    btn.attr("disabled", "disabled").addClass("animate-blink");

    const onSuccess = (function(result){
        btn.removeClass("animate-blink");
        $("[name=alert]").addClass("alert-success").append('<p>Se ha desincrito del '+tipo+' '+titulo+'.</p>').fadeIn();
        
        setTimeout(function(){$("[name=alert]").fadeOut();}, 3000);
        if(subevento){
            delete subevento.fechaRegistro;
            delete subevento.estado;
            delete subevento.referencia;
            delete subevento.baucher;

            if(evento.precioInscripcion){
                const oldItem = $("#itemSubevento"+subevento.clave);
                oldItem.fadeOut().before(agregarItemSubevento(evento, subevento));
                oldItem.remove();
                evaluarElementosItemSubevento(evento, subevento);   
            }
            else{
                $("#itemSubevento"+subevento.clave).fadeOut().remove();
                if($('[name="itemSubeventoE'+evento.clave+'"]').length == 0){
                    $("#panelEvento"+evento.clave).fadeOut().remove();
                }
            }
        }
        else{
            $("#panelEvento"+evento.clave).fadeOut().remove();
        }

        setTimeout(function(){
            $("#myModal").modal('hide');
            if($('[name="panelEvento"]').length == 0){
                $(location).attr("href", "/");
            }
        }, 4000);

    });

    const onError = (function(JSONError){
        btn.removeAttr("disabled").removeClass("animate-blink");
        $("[name=alert]").addClass("alert-danger").append("<p>"+JSONError+"</p>").fadeIn();
        setTimeout(function(){$("[name=alert]").fadeOut();}, 3000);
    });

    if(subevento){
        desincripcionSubevento(btn, claveAsistente, subevento.clave, onSuccess, onError);
    }
    else{
        desincripcionEvento(btn, claveAsistente, evento.clave, onSuccess, onError);
    }

}

function mostrarModalDesincripcion(claveAsistente, evento, subevento){
    const tipo = subevento ? 'subevento': 'evento';
    const titulo = subevento ? subevento.titulo : evento.titulo;

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
        .append('<h4 class="modal-title">Desincripción del '+tipo+' '+titulo+'</h4>');

    $("#myModal")
        .find('.modal-body')
        .empty()
        .append(
            '<p>¿Realmente quiere desincribirse del '+tipo+' <strong>'+titulo+'</strong>?</p>'+
            (!subevento ? '<p class="text-info"><strong>Se desinscribirá de este evento y de sus subeventos en los que se encuentre inscrito.</strong></p>': "")
        );

    $("#myModal")
        .find('.modal-footer')
        .empty()
        .append(
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
            '<button type="button" class="btn btn-danger" id="btnDesinscribirme">Desinscribirme</button>'
            );

    $("#myModal").find('#btnDesinscribirme').click(function(){
        desincribirse($(this), claveAsistente, evento, subevento);
    });
}

function estaInscrito(claveAsistente, tipo, claveTipo){
    if(tipo == 'evento'){
        tipo = "events";
    }
    else{
        tipo = "subevents";
    }

    ajaxFormData(null,"GET", RUTA_ASISTENTE+"/"+claveAsistente+"/"+tipo+"?clave="+claveTipo, null, 
        (function(result){
            if(result.data.length){
                if(tipo == 'events'){
                    removeBtnInscripcionEvento(claveTipo)
                }
                else{
                    removeBtnInscripcionSubevento(claveTipo);
                }
            }
        })
    );
}
