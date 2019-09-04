const RUTA_USUARIO = "/users";
const RUTA_INICIO_SESION = "/login";
const USUARIO_ADMINISTRADOR = 0;
const USUARIO_STAFF = 1;
const USUARIO_GENERAL = 3;

const onSuccessActualizarUsuario = 
	(function(result){
		successFormMessage("Datos actualizados correctamente");
	});

const successRegistroUsuario = 
	(function(result){
		location.reload();
	});

const successRegistroUsuarioAdmin = 
	(function(result){
		successFormMessage("Usuario registrado correctamente");
	});

const onErrorActualizarUsuario = 
	(function(jsonError){
		if(jsonError instanceof Object){
			$.each(jsonError, function(campo, errores){
				const div_grandparent = $("[name="+campo+"]").parents('div').filter('.form-group');
				div_grandparent.addClass('has-error');

				const div_parent = div_grandparent.children('div');
				div_parent.append('<div class="errors"></div>');
				//console.log(campo);
				$.each(errores,function(i, error){
					div_parent.children('div').append("<span class='help-block'>"+error+"</span>");
				});
			});
		}
		else{
			$("[name=alert]").addClass("alert-danger").append(jsonError);
		}
	});


function tipoUsuario(tipo){
	switch(tipo){
		case USUARIO_ADMINISTRADOR: return "Usuario administrador";
		case USUARIO_STAFF: return "Usuario staff";
		case USUARIO_GENERAL: return "Usuario general";
	}
}

function usuarioNombreCompleto(nombre, apellidoPaterno, apellidoMaterno){
	
	return nombre.concat(" ", apellidoPaterno, " ", apellidoMaterno);
}

function actualizarUsuario(btn, claveUsuario, datos = new FormData(), onSuccess = onSuccessActualizarUsuario, onError = onErrorActualizarUsuario){
	limpiarErroresForm();
	datos.append('nombre' , $("#nombre").val());
	datos.append('apellidoPaterno' , $("#apellidoPaterno").val());
	datos.append('apellidoMaterno' , $("#apellidoMaterno").val());
	datos.append('sexo' , $("[name='sexo']:checked").val());
	datos.append('ocupacion' , $("#ocupacion").val());
	datos.append('instituto-dependencia' , $("#instituto-dependencia").val());
	datos.append('correo' , $("#correo").val());
	datos.append('telefono', $("#telefono").val());
	
	if($("#contrasenia").val().length > 1){
		datos.append('contrasenia' , $("#contrasenia").val());
		datos.append('contrasenia_confirmacion' , $("#contrasenia_confirmacion").val());
	}

	datos.append('_method', 'PUT');

	ajaxFormData(btn, "POST", RUTA_USUARIO+"/"+claveUsuario, datos 
		,onSuccess
		,formError
		,validarFormulario	
	);
}

function registrarUsuario(btn, datos = new FormData(), onSuccess = successRegistroUsuario){
	limpiarErroresForm();
	datos.append('nombre' , $("#nombre").val());
	datos.append('apellidoPaterno' , $("#apellidoPaterno").val());
	datos.append('apellidoMaterno' , $("#apellidoMaterno").val());
	datos.append('sexo' , $("[name='sexo']:checked").val());
	datos.append('ocupacion' , $("#ocupacion").val());
	datos.append('instituto-dependencia' , $("#instituto-dependencia").val());
	datos.append('correo' , $("#correo").val());
	datos.append('telefono', $("#telefono").val());
	datos.append('contrasenia' , $("#contrasenia").val());
	datos.append('contrasenia_confirmacion' , $("#contrasenia_confirmacion").val());

	ajaxFormData(btn, "POST", RUTA_USUARIO, datos, 
		onSuccess,
		formError,
		validarFormulario	
	);
}

function iniciarSesion(btn){
	var datos = {
		email: $("#email").val(),
		password: $("#password").val(),
	} 

	if($('#remember').is(':checked')){
		datos.remember = 'on';	
	}

	//limpiarErroresForm();
	ajaxRequest(btn, "POST", RUTA_INICIO_SESION, datos, 
		(function(result){
			location.reload();
		})
		,formError
		,validarFormulario
	);	
}

function mostrarOcultarContrasenia() {
	$("#password").nextAll("span.form-control-feedback").toggleClass("fa-unlock");
	if ($("#password").attr("type") === "password") {
		$("#password").attr("type", "text");//.next("span").removeClass("fa-lock").addClass("fa-unlock");
	} 
	else{
		$("#password").attr("type", "password");//.next("span").removeClass("fa-unlock").addClass("fa-lock");
	}
} 

function reenviarCorreoVerificacion(btn, claveUsuario){
	ajaxRequest(btn, "GET", RUTA_USUARIO+"/"+claveUsuario+"/resend", null, 
		(function(result){
			const mensaje = result.data;
			btn.parents(".alert").first().empty().attr("class", "alert alert-success").append('<p>'+mensaje+'</p>');
		}),
		(function(error){
			btn.parents(".alert").first().empty().attr("class", "alert alert-danger").append('<p>'+error+'</p>');
		})
	);	
}