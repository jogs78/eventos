function dateToString(date){
	var months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
	date = date.split("-");;

	const anio = date[0];
	const mes = date[1];
	const diaHora = date[2].split(" ");
	const dia = diaHora[0];
	const hora = diaHora[1]? diaHora[1]: "";
	
	var dateString = dia+" de "+months[Number(mes)-1]+" de "+anio;

	if(hora){
		dateString += " a las "+hora;
	}

	return dateString;
}

function fechaEventoDisponible(fechaActualServidor, fechaFinEvento, now = false){
	var horaFinEvento = " 23:59:59"; 
	const horaServidor = ( ( (fechaActualServidor.split("-") )[2] ).split(" ") )[1];

	if(now){

		horaFinEvento = " "+horaServidor;
	}
	
	fechaActualServidor = new Date(fechaActualServidor);
	fechaFinEvento = new Date(fechaFinEvento+horaFinEvento);

	if(fechaActualServidor >= fechaFinEvento){
		return false;
	}

	return true;
}

function fechaSubeventoDisponible(fechaActualServidor, fechaSubevento){

	fechaActualServidor = new Date(fechaActualServidor);
	fechaSubevento = new Date(fechaSubevento);
	//Utilice setDate si es necesario mostrar por más tiempo el subevento
	//fechaSubevento.setDate(fechaSubevento.getDate() + 1);

	if(fechaActualServidor > fechaSubevento){
		return false;
	}

	return true;
}