<?php
	session_start();
	include_once('perfil_funciones.php');
	//DATOS DEL MODULO
	liberar_bd();
	$selectDatosModulo = 'CALL sp_sistema_select_datos_modulo('.$_SESSION["mod"].');';
	$datosModulo = consulta($selectDatosModulo);
	$datMod = siguiente_registro($datosModulo);
	$_SESSION["moduloPadreActual"] = utf8_convertir($datMod["nombre"]);
	
	switch($_POST['accion'])
	{
		default:
			$modulo .= perfiles_menuInicio();
			$regresar = '';
		break;
	}
	
?>