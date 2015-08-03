<?php
	ini_set("display_errors",true);
	ini_set('upload_max_filesize','128M');
	ini_set( "memory_limit", "200M" ); 
	error_reporting(E_ERROR);
	session_start();//inicia o reanuda la sesión
	include_once("motor/sesion.php");
	include_once("motor/conexionSitio.php");
	conectarSistema();
	//NOMBRE DE VARIABLE DE USUARIO
	liberar_bd();
	$selectVariableIdUser = 'CALL sp_sistema_select_variable_id_usuario();';
	$variableIdUser = consulta($selectVariableIdUser);
	$varIdUser = siguiente_registro($variableIdUser);
	include_once("motor/globales.php");
	muestraContenido(); 	
?>
</html>