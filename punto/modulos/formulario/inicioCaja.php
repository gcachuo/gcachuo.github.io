<?php

	session_start();
	include_once('inicioCaja_funciones.php');
	switch($_POST['accion'])
	{
		case 'Guardar':
			$modulo .= inicioCaja_guardar();
			break;
			
		default:
			$modulo .= inicioCaja_menuInicio();
		break;
		
	}
	
?>