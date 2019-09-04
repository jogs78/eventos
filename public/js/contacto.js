function enviarMensajeContacto(btn){
	var datos = new FormData();
	const form = btn.parents('form').first();
	const onSuccess = (function(result){
		form.find('[name="alert"]')
			.removeClass("alert-danger")
			.addClass("alert-success")
			.append(result.data);
		btn.attr("disabled", "disabled");
	});

	datos.append('correo', form.find('[name="correo"]').val());
	datos.append('asunto', form.find('[name="asunto"]').val());
	datos.append('mensaje', form.find('[name="mensaje"]').val());

	ajaxFormData(btn, "POST", "/contacto", datos, onSuccess, formError, validarFormulario);
}