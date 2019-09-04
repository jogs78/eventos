@extends('layouts.principal')

@section('titulo', "Eventos ITTG")

@section('descripcion', 'Sistema de registro y control de asistentes de actividades en eventos.')

@section('keywords', 'Eventos, Congresos, Talleres, Conferencias, Eventos ITTG, ITTG')

@push('scripts')
	<script src="{{ asset('/js/fecha.js') }}"></script>
	<script src="{{ asset('/js/evento.js') }}"></script>
	<script src="{{ asset('/js/subevento.js') }}"></script>
	<script src="{{ asset('/js/asistente.js') }}"></script>
@endpush

@section('contenido')
	<div id="imagenEventos"></div>
	<div class="panel-group" id="accordionEventos"></div>
@endsection

@section('script')
//<script type="text/javascript">
const today = "{{date("Y-m-d H:i:s")}}";
var autenticado = Boolean({{Auth::check()}});
var autenticadoId = String({{Auth::check() ? Auth::user()->id : ""}});

function inscribir(inscripcionID, tipo){ 
	$("#imagenEventos").prev(".alert").remove();
    if(autenticado){
    	var ruta = "{{ url('assistants') }}/"+autenticadoId+"/";
    	var datos = {};
    	if(tipo == 'evento'){
    		ruta += "events";
    		datos.evento_id = inscripcionID;
    	}
    	else{
    		ruta += "subevents";
    		datos.subevento_id = inscripcionID;
    	}

    	var onSuccess = (function(result){
    		//$(location).attr("href", "{{ url("asistente") }}/"+autenticadoId+"/inscripciones");
    		$(location).attr("href", "{{ route('asistente.inscripciones') }}");
    	});

    	var onError = (function(jsonError){
        	$("html, body").animate({ scrollTop: 0 }, 600);
        	$("#imagenEventos").before('<div class="alert alert-danger"></div>');

            if(jsonError instanceof Object){
                $.each(jsonError, function(campo, errores){
                    $.each(errores,function(i, error){
                        $("#imagenEventos").prev(".alert").append('<p>'+error+'</p>');
                    });
                });
            }
            else{
            	$("#imagenEventos").prev(".alert").append('<p>'+jsonError+'</p>');
            }
    	});

    	myAjax("POST", datos, ruta, onSuccess, onError);

	}
	else{
		$(location).attr("href", "{{ route('usuarios.registro') }}/?from=inscripcion");	
	}

}

/*myAjax("GET", null, "{{ url ("events") }}?visible=1", 
	(function(result){

	}), 
	(function(jsonError){

	})
);*/

myAjax("GET", null, "{{ url ("events") }}?visible=1&sortByDesc=fechaFin", 
	(function(result){
    	var subeventosCollapsed = "collapse in";
    	var btnSubeventosCollapsed = "";
    	var mostrarSubeventos = "cargado";
    	if(result.data.length){
	    	$.each(result.data, function(i, evento){
				$("#imagenEventos").append(
				'<div id="modal-img-'+evento.clave+'" class="w3-modal w3-animate-zoom" onclick="this.style.display=\'none\'" style="z-index: 2000;">'+

					'<img class="w3-modal-content img-responsive" src="'+evento.imagen+'">'+

				'</div>'
				);

	    		$("#accordionEventos").append(

				'<div class="panel panel-default w3-hover-shadow" id="panelEvento'+evento.clave+'">'+

					'<div class="panel-heading">'+

						'<div class="row">'+

							'<div class="col-sm-12 text-center">'+

								'<h3 class="font-weight-bold">'+evento.titulo+'</h3>'+

							'</div>'+

						'</div>'+

						'<div class="row">'+

							'<div class="col-sm-4 text-center">'+

								'<img src="'+evento.imagen+'" alt="Imágen del evento" class="img-responsive" onclick="document.getElementById(\'modal-img-'+evento.clave+'\').style.display=\'block\'">'+

							'</div>'+

							'<div class="col-sm-4">'+

								'<label>Descripción:</label>'+

								'<p>'+evento.detalles.replace(/\n/g, "<br>")+'</p>'+

								'<label>Fecha del evento:</label>'+

								'<p>Del '+dateToString(evento.fechaInicio)+' al '+dateToString(evento.fechaFin)+'</p>'+

								'<label name="organizador">Organizador:</label>'+

								'<label name="masInformacion">Mas información en:</label>'+
								
								'<p name="masInformacion"><a href="'+evento.masInformacion+'" target="_blank">'+evento.masInformacion+'</a></p>'+
								
							'</div>'+

							'<div class="col-sm-4" name="precioInscripcion">'+

								'<label id="labelPreciosE'+evento.clave+'">Costo de inscripción:</label>'+

								'<label>Subeventos elegibles:</label>'+

								'<p>'+evento.subeventosElegibles+'</p>'+

							'</div>'+

						'</div> '+



						'<div class="row" name="rowButtonInscripcionE">'+

							'<div class="col-sm-offset-8 col-sm-4">'+

								'<button class="btn btn-success btn-block" name="btnInscripcionEvento" onclick="inscribir('+evento.clave+', \'evento\')">Inscribirse</button>'+

							'</div>'+

						'</div>'+



						'<div class="row">'+

							'<div class="col-sm-12">'+

								'<a data-toggle="collapse" data-parent="#accordion" href="#collapse'+evento.clave+'" class="btn show-more-less pull-right w3-hover-none w3-hover-text-blue '+btnSubeventosCollapsed+'" title="Mostrar/Ocultar subeventos" onclick="cargarSubeventos(true,'+evento.clave+', '+evento.precioInscripcion+', '+evento.organizador+')" value="'+mostrarSubeventos+'"></a>'+

							'</div>'+

						'</div> '+

					'</div>'+

					'<div id="collapse'+evento.clave+'" class="panel-collapse '+subeventosCollapsed+'">'+

						'<div class="panel-body" id="subeventos'+evento.clave+'">'+

						'</div>'+

					'</div>'+

				'</div>'
				);

	    		if(!fechaEventoDisponible(today, evento.fechaFin) || evento.precioInscripcion == null || (autenticado && autenticadoId == evento.organizador)){
	    			$("#panelEvento"+evento.clave).find('[name="precioInscripcion"]').parent().children().not(":first").removeClass("col-sm-4").addClass("col-sm-8");
	    			$("#panelEvento"+evento.clave).find('[name="precioInscripcion"]').remove();

	    			removeBtnInscripcionEvento(evento.clave);
	    		}
	    		else if(autenticado){
	    			estaInscrito(autenticadoId, 'evento', evento.clave);
	    		}

	    		if(evento.masInformacion == null){
	    			$("#panelEvento"+evento.clave).find('[name="masInformacion"]').remove();
	    		}

	    		if(i == 0){
	    			cargarSubeventos(false, evento.clave, evento.precioInscripcion, evento.organizador);
	    		}

	    		if(evento.precioInscripcion != null){
	    			cargarEventoPrecios(evento.clave, (function(result){
	    					$.each(result.data, function(index, precio){
	    						$("#labelPreciosE"+evento.clave).after(
	    							'<p>'+precio.descripcion+' $'+precio.precio+'</p>'
	    						);
	    					});
	    				})
	    			);
	    		}

	    		subeventosCollapsed = "collapse";
	    		btnSubeventosCollapsed = "collapsed";
	    		mostrarSubeventos = "cargar";
	    		cargarOrganizador(evento.clave);
	    	});
    	}
    	else{
    		$("#accordionEventos").append('<h3 class="text-center animate-blink">No hay eventos.</h3>')
    	}
	}), 
	(function(jsonError){

	})
);


function cargarOrganizador(evento){
	myAjax("GET", null, "{{ url ("events") }}/"+evento+"/organizers", 
		(function(result){
	    	var organizador = result.data;
	    	$("#panelEvento"+evento)
	    		.find('[name="organizador"]')
	    		.after(
	    			'<p>'+
	    				usuarioNombreCompleto(organizador.nombre, organizador.apellidoPaterno, organizador.apellidoMaterno)+
	    			'</p>'+
	    			'<p>'+
	    				'<a href="mailto:'+organizador.correo+'">'+organizador.correo+'</a>'+
	    			'</p>'+
	    			(organizador.telefono ? '<p>'+organizador.telefono+'</p>' : "") 

	    		);
		}), 
		(function(jsonError){

		})
	);
}

function esColaborador(subevento, colaborador){
	myAjax("GET", null, "{{ url('subevents') }}/"+subevento.clave+"/collaborators?clave="+colaborador, 
		(function(result){
			if(result.data.length){
				removeBtnInscripcionSubevento(subevento.clave);
			}
			else{
				estaInscrito(autenticadoId, 'subevento', subevento.clave);
			}
		})
	);
}

function removeBtnInscripcionSubevento(subeventoClave){
	$("#colBtnInsSub"+subeventoClave).parent().children(":first").removeClass("col-sm-6").addClass("col-sm-12");
	$("#colBtnInsSub"+subeventoClave).remove();
}

function removeBtnInscripcionEvento(eventoClave){
	$("#panelEvento"+eventoClave).find('[name="rowButtonInscripcionE"]').remove();
}

function cargarSubeventos(btn, eventoClave, eventoPrecioInscripcion, eventoOrganizador){
	var cargar = true;

	if(btn){
		var btnMasMenos = $("#panelEvento"+eventoClave).find('[href="#collapse'+eventoClave+'"]');
		if(btnMasMenos.attr("value") == "cargar"){
			btnMasMenos.attr("value", "cargado");
		}
		else{
			cargar = false;
		}
	}
	if(cargar){
		$.ajax({
			headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
		    type: 'GET',
		    url : "{{ url ("events") }}/"+eventoClave+"/subevents",
		    dataType: 'json',
		    success: function(result){
		    	$.each(result.data, function(i, subevento){
		    		$("#subeventos"+eventoClave).append(
						'<div id="e'+eventoClave+'sub'+subevento.clave+'" class="w3-card w3-padding w3-hover-shadow">'+

							'<div class="row">'+

								'<div class="col-sm-3 text-center">'+

									'<h5>'+subevento.titulo+'</h5>'+

								'</div>'+

								'<div class="col-sm-3">'+

									'<label>Lugar y Fecha:</label>'+

									'<p>En '+subevento.lugar+' el '+dateToString(subevento.fechaHora)+'</p>'+

								'</div>'+

								'<div class="col-sm-3">'+

									'<label>Cupos disponibles:</label>'+

									'<p>'+(subevento.cuposDisponibles == null ? "Sin límite" : subevento.cuposDisponibles)+'</p>'+

								'</div>'+

								'<div class="col-sm-3">'+

									'<label id="labelPreciosSub'+subevento.clave+'">Costo de inscripción:</label>'+

									'<p>'+( eventoPrecioInscripcion != null ? "Pago por evento" : subevento.precioInscripcion == null ? "Gratis" : "...")+'</p>'+

								'</div>'+

							'</div>'+

							'<div class="row w3-padding-small">'+

								'<div class="col-sm-6 w3-padding">'+

									'<button class="btn btn-primary btn-block" name="btnVerMasSubevento" onclick="modalSubevento('+subevento.clave+','+eventoClave+')" >Más información</button>'+

								'</div>'+

								'<div class="col-sm-6 w3-padding" name="colButtonInscripcionS" id="colBtnInsSub'+subevento.clave+'">'+

									'<button class="btn btn-success btn-block" name="btnInscripcionSubevento" onclick="inscribir('+subevento.clave+', \'subevento\')">Inscribirse</button>'+

								'</div>'+

							'</div>'+

						'</div>'
		    		);

		    		if(!fechaSubeventoDisponible(today, subevento.fechaHora) || eventoPrecioInscripcion != null || (subevento.cuposDisponibles != null && subevento.cuposDisponibles == 0) || (autenticado && autenticadoId == eventoOrganizador)){
		    			removeBtnInscripcionSubevento(subevento.clave);
		    		}
		    		else if(autenticado){
		    			esColaborador(subevento, autenticadoId);
		    		}

		    		if(subevento.precioInscripcion != null){
						$("#labelPreciosSub"+subevento.clave).next("p").remove();
		    			cargarSubeventoPrecios(subevento.clave, (function(result){
		    					$.each(result.data, function(index, precio){
		    						$("#labelPreciosSub"+subevento.clave).after(
		    							'<p>'+precio.descripcion+' $'+precio.precio+'</p>'
		    						);
		    					});
		    				})
		    			);
		    		}
		    	});
		    },
		    error: function(jqXHR, textStatus, errorThrown){

		    }
		});
	}
}



@endsection