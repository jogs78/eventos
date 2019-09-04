function limpiarErroresForm(){
	$('.form-group').removeClass('has-error');
	$('span').remove('.errors');
	$("[name=alert]").empty().removeClass("alert-success alert-danger");
}

function successFormMessage($message){
	$("[name=alert]").addClass("alert-success").append($message);
}

const formError = (function(jsonError){
	if(jsonError instanceof Object){
		$.each(jsonError, function(campo, errores){
		    $("[name="+campo+"]").parents('div').filter('.form-group').addClass('has-error');

		    $.each(errores,function(i, error){
		        if($("#"+campo).parents("[class~='input-group']").length >= 1){
		            $("#"+campo).parents(".input-group").last().after("<span class='help-block has-error errors'>"+error+"</span>");
		        }
		        else if( $('[name="'+campo+'"]').first().attr("type") == "radio"){
		        	$('[name="'+campo+'"]').first().parents("div").first().append("<span class='help-block has-error errors'>"+error+"</span>");
		        }
		        else{
		            $("#"+campo).after("<span class='help-block has-error errors'>"+error+"</span>");    
		        }
		        
		    });
		});
	}
	else{
	    $("[name=alert]").addClass("alert-danger").append("<p>"+jsonError+"</p>").fadeIn();
	}
});

const validarFormulario = (function(btn, from){
	var withErrors = false;
	var form = btn.parents("form").first();
	
	form.find('.form-group').removeClass('has-error');
	form.find('span').remove('.errors');
	form.find("[name=alert]").empty().removeClass("alert-success alert-danger");

	if(form.length == 0){
		form = btn.parents(".modal").first().find('form').first();
	}

	form.find("input, textarea, select").each(function(i,e){
        if(!e.checkValidity()){
        	$(e).parents('div').filter('.form-group').addClass('has-error');
	        if($(e).parents("[class~='input-group']").length >= 1){
	            $(e).parents(".input-group").last().after("<span class='help-block has-error errors'>"+($(e).attr("title") ? $(e).attr("title") : e.validationMessage)+"</span>");
	        }
	        else if( $(e).attr("type") == "radio"){
	        	if($(e).parents("div").first().find('span').length == 0){
	        		$(e).parents("div").first().append("<span class='help-block has-error errors'>"+($(e).attr("title") ? $(e).attr("title") : e.validationMessage)+"</span>");
	        	}
	        }
	        else{
	            $(e).after(
	            	"<span class='help-block has-error errors'>"+
	            		($(e).attr("title") ? $(e).attr("title") : e.validationMessage)+
	            	"</span>");    
	        }
        	withErrors = true;
        }
    });

    return withErrors;
});