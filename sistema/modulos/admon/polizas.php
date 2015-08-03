<?php

	session_start();
	include_once('polizas_funciones.php');
	//DATOS DEL MODULO
	liberar_bd();
	$selectDatosModulo = 'CALL sp_sistema_select_datos_modulo('.$_SESSION["mod"].');';
	$datosModulo = consulta($selectDatosModulo);
	$datMod = siguiente_registro($datosModulo);
	$_SESSION["moduloPadreActual"] = utf8_convertir($datMod["nombre"]);
    	switch($_POST['accion'])
	{
		case 'Ver polizas':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_verPolizas();
			break;
			
		case 'Nuevo':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_formularioNuevo();
			break;
		
		case 'Guardar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_guardar();
			break;
		
		case 'GuardarEdit':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_editar();
			break;
		
		case 'Editar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_formularioEditar();
			break;
		
		case 'Cancelar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_cancelar();
			break;
		
		case 'Ver detalles':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_detalles();
			break;
		
		case 'Agregar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_nuevoRecordatorio();
			break;
			
		case 'Recordatorios':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_recordatorios();
			break;
		
		case 'Ver archivos':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_archivos();
			break;
		
		case 'NuevoArchivo':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_nuevoArchivo();
			break;
			
		case 'GuardarArchivo':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_guardarArchivo();
			break;
			
		case 'EliminarArchivo':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_eliminarArchivo();
			break;
			
		case 'GuardarAgregar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_guardarRecordatorio();
			break;
		
		case 'EliminarAgregar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_eliminarRecordatorio();
			break;
		
		case 'Ver recordatorio':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_detallesRecordatorio();
			break;
		
		case 'Historial':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_historial();
			break;
		
		case 'Ver detalles hijos':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_detallesHijos();
			break;
		
		case 'RecordatoriosHijos':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_recordatoriosHijos();
			break;
			
		case 'Ver recordatorio hijos':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_detallesRecordatorioHijos();
			break;
			
		case 'Eliminar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= polizas_eliminar();
			break;
		
		default:
			$modulo .= polizas_menuInicio();
		break;
		
	}
	
?>