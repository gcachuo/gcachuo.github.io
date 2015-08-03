<?php
	ini_set("display_errors",true);
	ini_set('upload_max_filesize','128M');
	ini_set( "memory_limit", "200M" ); 
	error_reporting(E_ERROR);
	session_start();
	
	include_once("motor/conexionSitio.php");
	//CONECTAMOS LA BD	
	conectarSistema();
	
	//CHECAMOS SI ES PRIMER ACCESO
	if(isset($_SESSION["primerAcceso"]) && $_SESSION["primerAcceso"] != 1)
	{
		//NOMBRE DE VARIABLE DE SESSION DE SISTEMA
		liberar_bd();
		$selectVariableSistema = 'CALL sp_sistema_select_variable_id_usuario();';
		$variableSistema = consulta($selectVariableSistema);
		$varSistema = siguiente_registro($variableSistema);
		$varSis = $varSistema["nombre"];
		$_SESSION[$varSis] = 1;		
	}
	
	include_once("motor/globales.php");
	include_once("motor/image.class.php");
	
	//CARGAMOS EL CONTENIDO DEL SISTEMA	
	include_once("motor/control.php");
	
	//CHECAMOS SI SE INTENTA LOGUEAR
	if(trim($_POST['txtUsuario'])!="" && trim($_POST['txtPassword'])!="")
	{
		//CHECAMOS SI EL USUARIO INTENTADO LOGUEO EXISTE
		liberar_bd();
		$selectDatosUserLogin = 'CALL sp_sistema_select_datos_user_login("'.trim(strtoupper($_POST["txtUsuario"])).'", "'.trim(strtoupper($_POST["txtPassword"])).'");';		
		$datosUserLogin = consulta($selectDatosUserLogin);
		$ctaDatosUserLogin = cuenta_registros($datosUserLogin);
		if($ctaDatosUserLogin == 1)
		{
			$userLogin = siguiente_registro($datosUserLogin);	
			$_SESSION[$varIdUser] = $userLogin['id'];
			$_SESSION['token'] = md5(rand().$_SESSION[$varIdUser]);
			$_SESSION['usuario'] = utf8_convertir($userLogin['usuario']);
			$_SESSION['login'] = utf8_convertir($userLogin['login']);
			$_SESSION['idPerfil'] = $userLogin['idPerfil'];
			$_SESSION['perfil'] = $userLogin['perfil'];	
			$_SESSION['tipoPerfil'] = $userLogin['tipo'];
			
			//ES UN AGENTE
			switch($_SESSION['tipoPerfil'])
			{
				case 1:
					//ES AGENCIA
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
				break;
				case 2:
					//ES AGENTE
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
				break;
				case 3:
					//ES EJECUTIVO
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
				break;
				case 4:
					//ES CLIENTE
					//AGENTE DEL USUARIO
					liberar_bd();
					$selectAgenteUsuario = 'CALL sp_sistema_select_agente_usuario('.$_SESSION[$varIdUser].');';
					$agenteUsuario = consulta($selectAgenteUsuario);
					$agenUser = siguiente_registro($agenteUsuario);
					
					$_SESSION['idAgenteActual'] = $agenUser["idAgente"];
				break;		
			}
			
			//ACTUALIZAMOS TOKEN DEL USUARIO
			liberar_bd();
			$updateToken = 'CALL sp_sistema_update_token_usuario("'.$_SESSION["token"].'", '.$_SESSION[$varIdUser].');';			
			$updateTok = consulta($updateToken);
			
			//CARGAMOS EL CONTENIDO DEL SISTEMA	
			$_SESSION["primerAcceso"] = 1;												
			$cuerpoSistema = muestra_sistema();
		}
		else
		{
			//CARGAMOS EL CONTENIDO DEL FORMULARIO DE LOGUEO CON ERROR DE CONEXION
			$_SESSION["primerAcceso"] = 0;
			$cuerpoSistema = muestra_login(2);
		}
	}
	elseif(isset($_SESSION[$varSis]) && isset($_SESSION[$varIdUser]) && isset($_SESSION["token"]))
	{	
		//CHECAMOS SI SIGUE LOGUEODO	 
		//CHECAMOS SI EXISTE
		liberar_bd();
		$selectUsuarioLogueado = 'CALL sp_sistema_select_usuario_logueado('.$_SESSION[$varIdUser].', "'.$_SESSION["token"].'");';		
		$usuarioLogueado = consulta($selectUsuarioLogueado);
		$ctaUsuarioLogueado = cuenta_registros($usuarioLogueado);
		if($ctaUsuarioLogueado == 1)
		{
			$_SESSION['token'] = md5(rand().$_SESSION[$varIdUser]);
			//ACTUALIZAMOS TOKEN DEL USUARIO
			liberar_bd();
			$updateToken = 'CALL sp_sistema_update_token_usuario("'.$_SESSION["token"].'", '.$_SESSION[$varIdUser].');';			
			$updateTok = consulta($updateToken);				
			//CARGAMOS EL CONTENIDO DEL SISTEMA		
			$_SESSION["primerAcceso"] = 1;		
			$cuerpoSistema = muestra_sistema();
		}
		else
		{
			//CERRAMOS SESION
			cerrarSesion();
			$_SESSION["primerAcceso"] = 0;
			conectarSistema();
			$cuerpoSistema = muestra_login(3);
		}			
	}
	else
	{
		//ES SU PRIMER ACCESO
		//CARGAMOS EL CONTENIDO DEL FORMULARIO DE LOGUEO
		$_SESSION["primerAcceso"] = 0;	
		$cuerpoSistema = muestra_login(1);	
	}
	
	echo $cuerpoSistema;
	
?>