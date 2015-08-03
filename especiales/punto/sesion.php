<?php
//sesion.php
include_once("control.php");

function muestraContenido()
{
	//verifico si ya está logueado
	if(isset($_SESSION['idUsuarioLuva']) && isset($_SESSION["token"]))
	{		 
		//checamos que exista
		liberar_bd();
      	$queryExist = '	SELECT
							*
						FROM
							_usuarios usua
						WHERE
							usua.id_usuario = '.$_SESSION["idUsuarioLuva"].'
						AND 
							usua.token_usuario = "'.$_SESSION["token"].'"';
		
		$existUser = consulta($queryExist);
		$ctaExistUser = cuenta_registros($existUser);
		if($ctaExistUser == 1)
		{
			$_SESSION['token'] = md5(rand().$_SESSION['idUsuarioLuva']);
			//ACTUALIZAMOS TOKEN DEL USUARIO
			liberar_bd();
			$updateToken = 'UPDATE 
								_usuarios
							SET
								token_usuario = "'.$_SESSION["token"].'"
							WHERE
								id_usuario = '.$_SESSION["idUsuarioLuva"];
			
			$updateTok = consulta($updateToken);				
			//se carga el sistema				
			$cuerpo = muestra_punto();
		}
		else
		{
			$_SESSION['primerAcceso'] = 1;
			cerrarSesion();
			$cuerpo = '<meta http-equiv="refresh" content="0;url=http://www.cbiz.mx/ferreobra/punto">';
		}			
	}
	elseif(trim($_POST['txtUsuario'])!="" && trim($_POST['txtPassword'])!="")
	{
		//Se está intentando loguear y valido contra la base de datos
		liberar_bd();
		$sql="	SELECT u.id_usuario as id,
				u.nombre_usuario as usuario ,
				u.login_usuario as login,
				u.id_perfil as idPerfil,
				p.nombre_perfil as perfil
				FROM _usuarios u
				INNER JOIN _perfiles p ON u.id_perfil = p.id_perfil
				AND login_usuario = '".trim(strtoupper($_POST['txtUsuario']))."'
				AND password_usuario=md5('".trim(strtoupper($_POST['txtPassword']))."')";
		
		$resultado = consulta($sql);
		$ctaUsuarioActivo = cuenta_registros($resultado);
		if($ctaUsuarioActivo == 1)
		{
			$fila = siguiente_registro($resultado);
			$_SESSION['idUsuarioLuva'] = $fila['id'];
			$_SESSION['token'] = md5(rand().$_SESSION['idUsuarioLuva']);
			$_SESSION['usuario'] = utf8_convertir($fila['usuario']);
			$_SESSION['login'] = utf8_convertir($fila['login']);
			$_SESSION['idPerfil'] = $fila['idPerfil'];
			$_SESSION['perfil'] = $fila['perfil'];	
			//ACTUALIZAMOS TOKEN DEL USUARIO
			liberar_bd();
			$updateToken = 'UPDATE 
								_usuarios
							SET
								token_usuario = "'.$_SESSION["token"].'"
							WHERE
								id_usuario = '.$_SESSION["idUsuarioLuva"];
			
			$updateTok = consulta($updateToken);													
			$cuerpo = muestra_punto();
		}
		else
		{
			$err='Los datos introducidos fueron incorrectos';
			$cuerpo = muestra_login($err);
		}
		//$cuerpo = $sql;
	}
	else
	{
		$_SESSION['primerAcceso'] = 1;
		//primera vez que entra al sitio
		$cuerpo = muestra_login();	
	}
	
	echo $cuerpo;
}
?>