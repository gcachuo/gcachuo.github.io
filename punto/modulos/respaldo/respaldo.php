<?php

	session_start();
	include_once('punto_funciones.php');
	switch($_POST['accion'])
	{
		case 'Nuevo':
			$modulo .= ingresosformularioNuevo();
			break;
		
		case 'Guardar':
			$modulo .= ingresosguardar();
			break;
		
		case 'GuardarEdit':
			$modulo .= ingresoseditar();
			break;
		
		case 'Editar':
			$modulo .= ingresosformularioEditar();
			break;
		
		case 'Eliminar':
			$modulo .= ingresoseliminar();
			break;
		
		case 'Ver detalles':
			$modulo .= ingresos_detalles();
			break;
			
		default:
			$modulo .= ingresosmenuInicio();
		break;
		
	}
	
?>