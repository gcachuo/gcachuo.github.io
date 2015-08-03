<?php

	session_start();
	include_once('inicio_funciones.php');
	switch($_POST['accion'])
	{
		case 'Recordatorios':
			$modulo.=inicio_recordatorios();
			break;			
			
		case 'Enviar':
			$modulo.=inicio_recordatoriosEnviar();
			break;
						
		default:
			$modulo .= inicio_menuInicio();
			$regresar = '';
		break;
		
	}
	
?>