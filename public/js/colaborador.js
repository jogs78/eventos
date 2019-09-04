const RUTA_COLABORADOR = "/collaborators";
const COLABORADOR_RESPONSABLE = 'R';
const COLABORADOR_AYUDANTE = 'A';

function tipoColaborador(tipo){
	switch(tipo){
		case COLABORADOR_RESPONSABLE: return "Responsable del subevento";
		case COLABORADOR_AYUDANTE: return "Ayudante";
	}
}

