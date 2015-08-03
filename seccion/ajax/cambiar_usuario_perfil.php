<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	if($_POST["newUsuario"] != "")
	{
		$newUsuario = (strtoupper($_POST["newUsuario"]));
		
		liberar_bd();
		$selectLoginUsuario =  "CALL sp_sistema_select_usuario_login('".$newUsuario."');";			
		$loginUsuario = consulta($selectLoginUsuario);
		$ctaloginUsuario = cuenta_registros($loginUsuario);
		if($ctaloginUsuario == 0)
		{
			liberar_bd();
			$updatePerfil = ' CALL sp_sistema_update_usuario_perfil("'.$newUsuario.'", '.$_SESSION[$varIdUser].');';
									
			$update = consulta($updatePerfil);
		}
	}
	
	//DATOS GENERALES
	liberar_bd();
	$selectPerfil='CALL sp_sistema_select_datos_mi_perfil('.$_SESSION[$varIdUser].');';
	$perfil = consulta($selectPerfil);	
	$per = siguiente_registro($perfil);	
	$_SESSION['usuario'] = utf8_convertir($per['nombre']);
	$_SESSION['login'] = utf8_convertir($per['login']);
		
?>