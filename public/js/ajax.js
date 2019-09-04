function myAjax(method, data, url, onSucces, onError){
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("[name='_token']").first().val()},
        type: method,
        data: data,
        url : url,
        dataType: 'json',
        success: function(result){
            if(onSucces){
                onSucces(result);
            }   
        },
        error: function(jqXHR, textStatus, errorThrown){
            if(!jqXHR.responseJSON){
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                jqXHR.responseJSON = {
                    error: "Error desconocido",
                }
            }

            if(onError){
                onError(jqXHR.responseJSON.errors ? jqXHR.responseJSON.errors : jqXHR.responseJSON.error );
            }
        },
    });
}

function ajaxRequest(btn, method, url, data, onSucces, onError, validarFormulario, onComplete){
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("[name='_token']").first().val()},
        type: method,
        data: data,
        url : url,
        dataType: 'json',
        beforeSend: function(xhr){
            var validate = (function(){
                if(validarFormulario){
                    return validarFormulario(btn);
                }
                return false;
            })();
            if(validate){
                return false;
            }
            else if(btn){
                btn.attr("disabled", "disabled");
                btn.addClass("animate-blink");
            }
        },
        success: function(result){
            if(btn){
                btn.removeAttr("disabled");
                btn.removeClass("animate-blink");
            }
            if(onSucces){
                onSucces(result);
            } 
        },
        error: function(jqXHR, textStatus, errorThrown){
            if(btn){
                btn.removeAttr("disabled");
                btn.removeClass("animate-blink");
            }
            if(!jqXHR.responseJSON){
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                jqXHR.responseJSON = {
                    error: "Error desconocido",
                }
            }

            if(onError){
                onError(jqXHR.responseJSON.errors ? jqXHR.responseJSON.errors : jqXHR.responseJSON.error );
            }
        },
        complete: function (xhr,status){
            if(btn){
                btn.removeAttr("disabled");
                btn.removeClass("animate-blink");
            }

            if(onComplete){
                onComplete();
            }  
        },
    });
}

function ajaxFormData(btn, method, url, data, onSucces, onError, validarFormulario){
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("[name='_token']").first().val()},
        url: url,
        dataType: 'json',
        type: method,
        contentType:false,
        data: data,
        processData:false,
        cache:false,
        beforeSend: function(xhr){
            var validate = (function(){
                if(validarFormulario){
                    return validarFormulario(btn);
                }
                return false;
            })();
            if(validate){
                return false;
            }
            else if(btn){
                btn.attr("disabled", "disabled");
                btn.addClass("animate-blink");
            }
        },
        success: function(result){
            if(btn){
                btn.removeAttr("disabled");
                btn.removeClass("animate-blink");
            }
            if(onSucces){
                onSucces(result);
            }   
        },
        error: function(jqXHR, textStatus, errorThrown){
            if(btn){
                btn.removeAttr("disabled");
                btn.removeClass("animate-blink");
            }
            if(!jqXHR.responseJSON){
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                jqXHR.responseJSON = {
                    error: "Error desconocido",
                }
            }

            if(onError){
                onError(jqXHR.responseJSON.errors ? jqXHR.responseJSON.errors : jqXHR.responseJSON.error );
            }
        },
    });
}

/*
function ajaxFormData(method, data, url, onSucces, onError){
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
        url: url,
        dataType: 'json',
        type: method,
        contentType:false,
        data: data,
        processData:false,
        cache:false,
        success: function(result){
            if(onSucces){
                onSucces(result);
            }   
        },
        error: function(jqXHR, textStatus, errorThrown){
            if(onError){
                onError(jqXHR.responseJSON.error ? jqXHR.responseJSON.error : jqXHR.responseJSON.errors);
            }
        },
        complete: function(xhr, status){
            
        }
    });
}
*/