<?php
//sesion.php
include_once("control.php");

function muestraContenido()
{
	//verifico si ya está logueado
	if(isset($_SESSION[$varIdUser]) && isset($_SESSION["token"]))
	{		 
		//checamos que exista
		liberar_bd();
      	$queryExist = '	SELECT
							*
						FROM
							_usuarios usua
						WHERE
							usua.id_usuario = '.$_SESSION[$varIdUser].'
						AND 
							usua.token_usuario = "'.$_SESSION["token"].'" 
						AND usua.estado_usuario = 1';
		
		$existUser = consulta($queryExist);
		$ctaExistUser = cuenta_registros($existUser);
		if($ctaExistUser == 1)
		{
			$_SESSION['token'] = md5(rand().$_SESSION[$varIdUser]);
			//ACTUALIZAMOS TOKEN DEL USUARIO
			liberar_bd();
			$updateToken = 'UPDATE 
								_usuarios
							SET
								token_usuario = "'.$_SESSION["token"].'"
							WHERE
								id_usuario = '.$_SESSION[$varIdUser];
			
			$updateTok = consulta($updateToken);				
			//se carga el sistema				
			$cuerpo = muestra_sistema();
		}
		else
		{
			cerrarSesion();
			$cuerpo = '<meta http-equiv="refresh" content="0;url=http://ruizquezada.com/sistema">';
		}			
	}
	elseif(trim($_POST['txtUsuario'])!="" && trim($_POST['txtPassword'])!="")
	{
		//Se está intentando loguear y valido contra la base de datos
		liberar_bd();
		$sql="	SELECT 
					u.id_usuario as id,
					u.nombre_usuario as usuario ,
					u.login_usuario as login,
					u.id_perfil as idPerfil,
					p.nombre_perfil as perfil,
					p.tipo_perfil AS tipo
				FROM 
					_usuarios u
				INNER JOIN _perfiles p ON u.id_perfil = p.id_perfil
				AND login_usuario = '".trim(strtoupper($_POST['txtUsuario']))."'
				AND password_usuario=md5('".trim(strtoupper($_POST['txtPassword']))."')
				AND estado_usuario = 1";
		
		$resultado = consulta($sql);
		$ctaUsuarioActivo = cuenta_registros($resultado);
		if($ctaUsuarioActivo == 1)
		{
			$fila = siguiente_registro($resultado);	
			$_SESSION[$varIdUser] = $fila['id'];
			$_SESSION['token'] = md5(rand().$_SESSION[$varIdUser]);
			$_SESSION['usuario'] = utf8_convertir($fila['usuario']);
			$_SESSION['login'] = utf8_convertir($fila['login']);
			$_SESSION['idPerfil'] = $fila['idPerfil'];
			$_SESSION['perfil'] = $fila['perfil'];
			$_SESSION['tipoPerfil'] = $fila['tipo'];
			
			if($_SESSION['tipoPerfil'] == 2)
			{
				//DATOS DEL SUB AGENTE
				liberar_bd();
				$selectDatosSubAgente = 'CALL sp_sistems_select_subagente_id_usuario('.$_SESSION[$varIdUser].');';
				$datosSubAgente = consulta($selectDatosSubAgente);
				$datSubAge = siguiente_registro($datosSubAgente);
				$_SESSION['idSubAgenteActual'] = $datSubAge["id"];
					
				//DATOS DEL AGENTE
				liberar_bd();
				$selectDatosAgente = 'CALL sp_sistems_select_agente_id_usuario('.$_SESSION[$varIdUser].');';
				$datosAgente = consulta($selectDatosAgente);
				$datAge = siguiente_registro($datosAgente);
				$_SESSION['idAgenteActual'] = $datAge["id"];				
			}
			else
			{
				if($_SESSION['tipoPerfil'] == 3)
				{
					//DATOS DEL SUB AGENTE
					liberar_bd();
					$selectDatosSubAgente = 'CALL sp_sistems_select_subagente_id_usuario('.$_SESSION[$varIdUser].');';
					$datosSubAgente = consulta($selectDatosSubAgente);
					$datSubAge = siguiente_registro($datosSubAgente);
					$_SESSION['idSubAgenteActual'] = $datSubAge["id"];
					
					//AGENTE DEL SUB AGENTE
					liberar_bd();
					$selectAgenteSubagente = 'CALL sp_sistema_select_agente_subagente('.$_SESSION['idSubAgenteActual'].');';
					$agenteSubagente = consulta($selectAgenteSubagente);
					$agenSubAgen = siguiente_registro($agenteSubagente);
					
					$_SESSION['idAgenteActual'] = $agenSubAgen["idAgente"];
				}
				else
				{
					//AGENTE DEL USUARIO
					liberar_bd();
					$selectAgenteUsuario = 'CALL sp_sistema_select_agente_usuario('.$_SESSION[$varIdUser].');';
					$agenteUsuario = consulta($selectAgenteUsuario);
					$agenUser = siguiente_registro($agenteUsuario);
					
					$_SESSION['idAgenteActual'] = $agenUser["idAgente"];
				}
			}
			
			//ACTUALIZAMOS TOKEN DEL USUARIO
			liberar_bd();
			$updateToken = 'UPDATE 
								_usuarios
							SET
								token_usuario = "'.$_SESSION["token"].'"
							WHERE
								id_usuario = '.$_SESSION[$varIdUser];
			
			$updateTok = consulta($updateToken);
																					
			$cuerpo = muestra_sistema();
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