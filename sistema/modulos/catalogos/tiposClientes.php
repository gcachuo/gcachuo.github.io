<?php

	session_start();
	include_once('tiposClientes_funciones.php');
	//DATOS DEL MODULO
	liberar_bd();
	$selectDatosModulo = 'CALL sp_sistema_select_datos_modulo('.$_SESSION["mod"].');';
	$datosModulo = consulta($selectDatosModulo);
	$datMod = siguiente_registro($datosModulo);
	$_SESSION["moduloPadreActual"] = utf8_convertir($datMod["nombre"]);
	switch($_POST['accion'])
	{
		case 'Nuevo':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= clientes_formularioNuevo();
			break;
		
		case 'Editar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= clientes_formularioEditar();
			break;
		
		case 'Guardar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= clientes_guardar();
			break;
			
		case 'GuardarEdit':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= clientes_editar();
			break;
			
		case 'Eliminar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo.=clientes_eliminar();
			break;
			
		case 'Descuentos':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo.=clientes_descuentos();
			break;
		
		case 'Agregar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo.=clientes_agregar();
			break;
		
		default:
			$modulo .= clientes_menuInicio();
		break;
		
	}
	
?>