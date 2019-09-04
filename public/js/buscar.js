function buscar(valor, en){
	$('[name="sinResultados"]').remove();
	valor = valor.toLowerCase();
	en.filter(function() {
	    $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
	});

	if(en.filter('[style="display: none;"]').length ==  en.length){
	    en.parent().append('<h4 name="sinResultados">Sin resultados para <strong>'+valor+'</strong></h4>')
	}
}