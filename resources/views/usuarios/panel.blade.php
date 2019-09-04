@extends('layouts.principal')
@section('titulo', 'Usuarios')
@section('contenido')
<div class="container">
    <div class="text-right">
        <button type="button" class="btn btn-lg btn-primary" name="btnNuevoUsuario" id="btnNuevoUsuario"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo Usuario</button>

    </div>
    <div class="row">
        <div class="col-md-4">
            <h4>Filtrar por tipo de usuario</h4>
            <select class="form-control" id="selectTipoUsuario" name="selectTipoUsuario" data-filtro="tipo">
                <option value="">Todos los tipos</option>
                <option value="0">Administrador</option>
                <option value="1">Staff</option>
                <option value="2">General</option>
            </select>
        </div>

        <div class="col-md-4">
            <h4>Ordenar por</h4>
            <div class="dropdown">
                <p id="sortByNombre" name="sortBy" role="button" class="col-md-2 dropdown-toggle" value="nombre">Nombre</p>
            </div>
            <div class="dropdown">
                <p id="sortByCorreo" name="sortBy" role="button" class="col-md-2 col-md-offset-2 dropdown-toggle" value="correo">Correo</p>
            </div>

            <div class="dropdown">
                <p id="sortByTipo" name="sortBy" role="button" class="col-md-2 col-md-offset-2 dropdown-toggle" value="tipo">Tipo</p>
            </div>

        </div>

        <div class="col-md-4">
            <h4>Buscar</h4>
            <div class="form-group has-feedback" title="Buscar Usuario">
                <input type="text" class="form-control" id="buscarUsuario" onkeyup="buscar($(this).val(), $('#listadoUsuarios div[class~=\'panel\']'))">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
        </div>

    </div>

    <div class="row" id="listadoUsuarios"></div>

</div>
@endsection

@section('script')
//<script type="text/javascript">
    
    function tipoUsuarioToString(index){
        switch(index){
            case 0 : return 'Administrador';        
            case 1 : return 'Staff';
            case 2 : return 'Usuario general';
        }
    }

    function usuarioFullName(nombre, apellidoPaterno, apellidoMaterno){
        return nombre.concat(" ", apellidoPaterno, " ", apellidoMaterno);
    }


    function cargarUsuarios(filtro = ""){     
        
        if(filtro != ""){
            filtro = "?"+filtro;

            if(filtro.search("sortBy") == -1 && filtro.search("sortByDesc") == -1){
                filtro += "&sortBy=nombre";                
            }
        }
        else{
            filtro = "?sortBy=nombre";
        }


        //filtro = "?sortBy=nombre"+filtro;

        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'GET',
            url : "{{ url('users') }}"+filtro,
            dataType: 'json',
            success: function(result){
                $("#listadoUsuarios").empty();
                if(result.data.length > 0){
                    $.each(result.data, function (i, usuario) {
                        if({{ Auth::user()->id }} == usuario.clave){
                            return true;
                        }

                        const usuarioNombreCompleto = usuarioFullName(usuario.nombre, usuario.apellidoPaterno, usuario.apellidoMaterno);

                         $("#listadoUsuarios").append(
                            '<div class="panel panel-default" id="contenedor'+usuario.clave+'">'+
                                '<div class="panel-body">'+
                                    '<div class="row">'+
                                        '<div class="col-sm-4 text-center">'+
                                            '<label>'+usuarioNombreCompleto+'</label>'+
                                        '</div>'+
                                        '<div class="col-sm-4 text-center">'+
                                            '<p>'+usuario.correo+'</p>'+
                                        '</div>'+

                                        '<div class="col-sm-4 text-center">'+
                                            '<p>'+tipoUsuarioToString(usuario.tipo)+'</p>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="row">'+
                                        '<div class="col-sm-3">'+
                                            '<button type="button" class="btn btn-primary btn-block" name="btnMensaje" id="'+usuario.clave+'" value="'+usuarioNombreCompleto+'">Enviar mensaje</button>'+ 
                                        '</div>'+
                                        '<div class="col-sm-3">'+
                                            '<button type="button" class="btn btn-success btn-block" name="btnVerMas" id="'+usuario.clave+'" value="'+usuarioNombreCompleto+'">Ver más</button>'+ 
                                        '</div>'+
                                        '<div class="col-sm-3">'+
                                            '<button type="button" class="btn btn-primary btn-block" name="btnEditar" id="'+usuario.clave+'" value="'+usuarioNombreCompleto+'">Editar</button>'+ 
                                        '</div>'+
                                        '<div class="col-sm-3">'+
                                            '<button type="button" class="btn btn-danger btn-block" name="btnEliminar" id="'+usuario.clave+'" value="'+usuarioNombreCompleto+'">Eliminar</button>'+ 
                                        '</div>'+
                                    '</div>' +
                                '</div>'+
                                '<div class="panel-footer" style="display:none;"></div>'+
                            '</div>'
                        );
                    });
                }

            },
            error: function(jqXHR, textStatus, errorThrown){

            }
        });
    }

    cargarUsuarios();

    function crearFormularioUsuario(){
        $("#myModal")
        .find('.modal-body')
        .empty()
        .append(
            '<form id="formularioUsuario">'+
                '<div class="form-group">'+
                    '<label for="nombre" class="control-label">Nombre</label>'+
                    '<input id="nombre" type="text" class="form-control" name="nombre" placeholder="Nombre" required autofocus>'+
                '</div>'+
                '<div class="form-group">'+
                    '<label for="apellidoPaterno" class="control-label">Apellido paterno</label>'+
                    '<input id="apellidoPaterno" type="text" class="form-control" name="apellidoPaterno" placeholder="Apellido paterno" required>'+
                '</div>'+
                '<div class="form-group">'+
                    '<label for="apellidoMaterno" class="control-label">Apellido materno</label>'+
                    '<input id="apellidoMaterno" type="text" class="form-control" name="apellidoMaterno" placeholder="Apellido materno" required>'+
                '</div>'+

                '<div class="form-group">'+
                    '<label for="sexo" class="control-label">Sexo</label>'+
                    '<div class="form-group">'+
                        '<label class="radio-inline"><input type="radio" name="sexo" id="radioSexoM" value="m">Masculino</label>'+
                        '<label class="radio-inline"><input type="radio" name="sexo" id="radioSexoF" value="f">Femenino</label>'+
                    '</div>'+
                '</div>'+

                '<div class="form-group">'+
                    '<label for="correo" class="control-label">Correo electrónico</label>'+
                    '<input type="text" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" required>'+
                '</div>'+

                '<div class="form-group">'+
                    '<label for="telefono" class="control-label">Teléfono</label>'+
                    '<input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" required>'+
                '</div>'+

                 '<div class="form-group">'+
                    '<label for="password" class="control-label">Contraseña</label>'+
                    '<input type="password" class="form-control" id="contrasenia" name="contrasenia" placeholder="Contraseña">'+
                '</div>'+

                 '<div class="form-group">'+
                    '<label for="contrasenia_confirmacion" class="control-label">Confirmar contraseña</label>'+
                    '<input type="password" class="form-control" id="contrasenia_confirmacion" name="contrasenia_confirmacion" placeholder="Confirmar contraseña">'+
                '</div>'+

                 '<div class="form-group">'+
                    '<label for="tipo" class="control-label">Tipo de usuario:</label>'+
                    '<select class="form-control" name="tipo" id="tipo">'+
                        '<option value="">Seleccione un tipo de usuario</option>'+
                        '<option value="0">Administrador</option>'+
                        '<option value="1">Staff</option>'+
                        '<option value="2">General</option>'+
                    '</select>'+
                '</div>'+
            '</form> '
        );
    }

    $("#selectTipoUsuario").change(function(){

        var filtro = "";

        if($(this).val() != ""){
            filtro = $(this).attr("data-filtro")+"="+$(this).val();
        }
        
        if($('[data-sort]').length > 0){
            if(filtro != ""){
                filtro += "&";
            }

            filtro += $('[data-sort]').attr("data-sort")+"="+$('[data-sort]').attr("value");
        }

        cargarUsuarios(filtro);
    });

    $('[name="sortBy"]').click(function(){
        if($(this).parent().hasClass("open")){
            $(this).attr("data-sort", "sortByDesc");
        }
        else{
            $(this).attr("data-sort", "sortBy");   
        }

        $(this).parent().toggleClass("open");
        $('[name="sortBy"]').not($(this)).parent().removeClass("open");
        $('[name="sortBy"]').not($(this)).removeAttr("data-sort");

        var filtro = $(this).attr("data-sort")+"="+$(this).attr("value");
        
        if($("#selectTipoUsuario").val()){
            filtro += "&"+$("#selectTipoUsuario").attr("data-filtro")+"="+$("#selectTipoUsuario").val();
        }
        cargarUsuarios(filtro);
    });


    /*Evento click para el botón ver más*/
    $("#listadoUsuarios").on("click", "[name='btnVerMas']", function(){
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
        .append('<h4 class="modal-title">'+$(this).val()+'</h4>');


        $("#myModal")
        .find('.modal-footer')
        .empty()
        .append(
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'
            );

        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'GET',
            url : "{{ url('users') }}/"+$(this).attr("id"),
            dataType: 'json',
            success: function(result){
                usuario = result.data;
                const campo = 
                    '<div class="form-group">'+
                        '<label class="control-label">$campo</label>'+
                        '<p>$valor</p>'+
                    '</div>';

                const nombre = 
                        (campo.replace("$campo", "Nombre:"))
                        .replace("$valor", usuarioFullName(usuario.nombre, usuario.apellidoPaterno, usuario.apellidoMaterno));

                const sexo = 
                        (campo.replace("$campo", "Sexo:"))
                        .replace("$valor", usuario.sexo == 'm' ? "Masculino" : "Femenino");
                
                var ocupacion = "";
                if(usuario.ocupacion){
                    ocupacion = 
                        (campo.replace("$campo", "Ocupación:"))
                        .replace("$valor", usuario.ocupacion);   
                }

                var instituto_dependencia = "";
                if(usuario['instituto-dependencia']){
                    instituto_dependencia = 
                        (campo.replace("$campo", "Instituto/Dependencia:"))
                        .replace("$valor", usuario['instituto-dependencia']);   
                }

                var telefono = "";
                if(usuario.telefono){
                    telefono = 
                        (campo.replace("$campo", "Teléfono:"))
                        .replace("$valor", usuario.telefono);   
                }

                const correo = 
                        (campo.replace("$campo", "Correo electrónico:"))
                        .replace("$valor", usuario.correo);   

                const tipo = 
                    (campo.replace("$campo", "Tipo de usuario:"))
                    .replace("$valor", tipoUsuarioToString(usuario.tipo)); 


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
                    tipo,
                );
            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#myModal")
                    .find('.modal-body')
                    .empty()
                    .append(
                       '<h4>'+jqXHR.responseJSON.error+'</h4>' 
                    );
            }
        });
    });

    /*Función para agregar o actualizar un usuario*/
    function CUUsuario(usuario){
        var datos = {
            nombre: $("#nombre").val(),
            apellidoPaterno: $("#apellidoPaterno").val(),
            apellidoMaterno: $("#apellidoMaterno").val(),
            sexo: $("[name='sexo']:checked").val(),
            correo: $("#correo").val(),
            tipo: $("#tipo").val(),
            telefono : $("#telefono").val(),
        } 

        const contrasenia = $("#contrasenia").val();
        const contrasenia_confirmacion = $("#contrasenia_confirmacion").val();

        if(!usuario || (usuario && (contrasenia.length>1 || contrasenia_confirmacion.length>1)) ){
            datos.contrasenia = contrasenia;
            datos.contrasenia_confirmacion = contrasenia_confirmacion;
        }

        var metodo = "POST";
        var ruta = "{{ url('users') }}";
        if(usuario){
            metodo = "PUT";
            ruta += "/"+usuario;
        }
        $("#btnAgregarUsuario , #btnActualizarUsuario").attr("disabled", "disabled").addClass("animate-blink");
        $('.form-group').removeClass('has-error');
        $('span').remove('.errors');
        $('[name="alert"]').empty().removeClass("alert-success alert-danger");

        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            url: ruta,
            type: metodo,
            dataType: 'json',
            data: datos,
            success: function(result){
                $("#btnAgregarUsuario , #btnActualizarUsuario").removeClass("animate-blink");
                cargarUsuarios();
                var mensaje = "Usuario agregado correctamente";
                if(usuario){
                    mensaje = "Cambios realizados correctamente";
                    $("#btnAgregarUsuario , #btnActualizarUsuario").removeAttr("disabled");
                }

                $("[name=alert]").addClass("alert-success").append("<p>"+mensaje+"</p>").fadeIn();

                setTimeout(function(){$("[name=alert]").fadeOut();}, 3000);

            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#btnAgregarUsuario , #btnActualizarUsuario").removeAttr("disabled").removeClass("animate-blink");
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

    /*Evento click para el boton nuevo usuario*/
    $("#btnNuevoUsuario").click(function(){

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
        .append('<h4 class="modal-title">Nuevo usuario</h4>');




        $("#myModal")
        .find('.modal-footer')
        .empty()
        .append(
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
            '<button type="button" class="btn btn-primary" onclick="CUUsuario()" id="btnAgregarUsuario">Agregar</button>'
            );

        crearFormularioUsuario();

    });

    /*Evento click para el botón editar*/
    $("#listadoUsuarios").on("click", "[name='btnEditar']", function(){

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
            '<button type="button" class="btn btn-primary" onclick="CUUsuario('+$(this).attr("id")+')" id="btnActualizarUsuario">Actualizar</button>'
            );

        crearFormularioUsuario();

        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'GET',
            url : "{{ url('users') }}/"+$(this).attr("id"),
            dataType: 'json',
            success: function(result){
                usuario = result.data;
                $("#nombre").val(usuario.nombre);
                $("#apellidoPaterno").val(usuario.apellidoPaterno);
                $("#apellidoMaterno").val(usuario.apellidoMaterno);

                usuario.sexo == "m" ? $("#radioSexoM").attr("checked","true") : $("#radioSexoF").attr("checked","true"); 

                $("#correo").val(usuario.correo);

                if(usuario.telefono){
                    $("#telefono").val(usuario.telefono);
                }

                $('#tipo option[value="'+usuario.tipo+'"]').attr("selected", true);
            },
            error: function(jqXHR, textStatus, errorThrown){

            }
        });

    });

    function eliminarUsuario(usuario){
        var ruta = "{{ url('users') }}/"+usuario; 
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'DELETE',
            url : ruta,
            dataType: 'json',
            success: function(result){
                $("#myModal").modal("hide");
                $("#contenedor"+usuario)
                    .find('.panel-footer')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .html('<strong>Usuario eliminado correctamente</strong>')
                    .fadeIn();
                    

                setTimeout(function(){$("#contenedor"+usuario).fadeOut().remove();}, 3000);
            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#myModal").modal("hide");

                $("#contenedor"+usuario)
                    .find('.panel-footer')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html('<strong>'+jqXHR.responseJSON.error+'</strong>')
                    .fadeIn();
            }
        });
    }

    /*Evento click para el botón eliminar*/
    $("#listadoUsuarios").on("click", "[name='btnEliminar']", function(){

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
            .append('<h4 class="modal-title">Eliminar usuario</h4>');

        $("#myModal")
            .find('.modal-body')
            .empty()
            .append('<p>¿Realmente quiere eliminar al usuario <strong>'+$(this).val()+'</strong>?, esta acción no se puede deshacer.</p>');

        $("#myModal")
            .find('.modal-footer')
            .empty()
            .append(
                '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>'+
                '<button type="button" class="btn btn-danger" onclick="eliminarUsuario('+$(this).attr("id")+')">Eliminar</button>'
                );

    });

    function enviarMensaje(usuario){
        $("#btnEnviarMensaje").attr("disabled", "disabled").addClass("animate-blink");
        $('.form-group').removeClass('has-error');
        $('span').remove('.errors');
        $('[name="alert"]').empty().removeClass("alert-success alert-danger");
        
        var ruta = "{{ url('users') }}/"+usuario+"/mail"; 
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("[name='_token']").val()},
            type: 'POST',
            url : ruta,
            data: {
                asunto  : $("#asunto").val(),
                mensaje : $("#mensaje").val(),
            },
            dataType: 'json',
            success: function(result){
                $("#btnEnviarMensaje").removeClass("animate-blink");

                $("[name=alert]").addClass("alert-success").append("<p>"+result.data+"</p>").fadeIn();

                setTimeout(function(){$("[name=alert]").fadeOut();}, 3000);

            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#btnEnviarMensaje").removeAttr("disabled").removeClass("animate-blink");
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


    function crearFormularioMensaje(){
        var formulario = 
            '<form id="formularioMensaje">'+
                '<div class="form-group">'+
                    '<label for="asunto" class="control-label">Asunto:</label>'+
                    '<input id="asunto" type="text" class="form-control" name="asunto" placeholder="Asunto" required autofocus>'+
                '</div>'+
                '<div class="form-group">'+
                    '<label for="Mensaje" class="control-label">Mensaje:</label>'+
                    '<textarea class="form-control" rows="5" id="mensaje" name="mensaje"></textarea>'+
                '</div>'+
            '</form> ';

        return formulario;
    }

    /*Evento click para el botón enviar mensaje*/
    $("#listadoUsuarios").on("click", "[name='btnMensaje']", function(){

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
        .append('<h4 class="modal-title">Enviar mensaje a '+$(this).val()+'</h4>');


        $("#myModal")
        .find('.modal-footer')
        .empty()
        .append(
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
            '<button type="button" class="btn btn-primary" onclick="enviarMensaje('+$(this).attr("id")+')" id="btnEnviarMensaje">Enviar</button>'
            );

        $("#myModal")
            .find('.modal-body')
            .empty()
            .append(crearFormularioMensaje());

    });



@endsection
