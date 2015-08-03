<?php
	session_start();
	include_once('perfiles_funciones.php');
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
			$modulo .= perfiles_formularioPerfil('nuevo');
			break;
		
		case 'Editar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= perfiles_formularioPerfil('editar');
			break;
			
		case 'Lista Perfiles':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= perfiles_muestraPerfiles();
			break;
		
		case 'Guardar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .=perfiles_guardaPerfil('nuevo');
			break;
		
		case 'GuardarEdit':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .=perfiles_guardaPerfil('editar');
			break;
		
		case 'Eliminar perfil':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= perfiles_eliminarPerfil();
		break;
		
		case 'Permisos':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= perfiles_permisosPerfil();
			break;
		
		case 'Guarda Permisos':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= perfiles_permisosGuardarPerfil();
			break;
			
		default:
			$modulo .= perfiles_menuInicio();
			$regresar = '';
		break;
	}
	
?>