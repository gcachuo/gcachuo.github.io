<?php
	function conectarSistema()
	{		
		$usuario = "e11_segurosDev";
		$pass = "e1I536ur0S";
		$bd = "e11_segurosDev";
		$servidor = "iopik.com";
		
		$conexion = mysqli_connect($servidor, $usuario, $pass, $bd); 
	
		if(mysqli_connect_errno())
		{
			echo "La conexión falló: " . mysqli_connect_error();
		}
		else
		{
			$_SESSION['conexion'] = $conexion;			
		}
	
	}
	
	function liberar_bd()
	{
		$conexion = $_SESSION['conexion'];
		while(mysqli_more_results($conexion))
		{
			if(mysqli_next_result($conexion))
			{
				$resultado = mysqli_use_result($conexion);
				mysql_free_result($resultado);
			}
		}
	}	

	function consulta($txtConsulta)
	{
		$consulta = mysqli_query($_SESSION['conexion'],$txtConsulta);
		return $consulta;
	}
	
	function cuenta_registros($consulta)
	{
		$ctaConsulta = mysqli_num_rows($consulta);	
		return $ctaConsulta;
	}
	
	function siguiente_registro($consulta)
	{
		$arregloConsulta = mysqli_fetch_assoc($consulta);	
		return $arregloConsulta;
	}
	
	function ultimo_id()
	{
		return mysqli_insert_id($_SESSION["conexion"]);	
	}
	
	function inicia_transaccion()
	{
		mysqli_autocommit($_SESSION['conexion'], FALSE);
	}
	
	
	function aplica_transaccion()
	{
		mysqli_commit($_SESSION['conexion']);
		mysqli_autocommit($_SESSION['conexion'], TRUE);
	}
	
	function cancela_transaccion()
	{
		mysqli_rollback($_SESSION['conexion']);
		mysqli_autocommit($_SESSION['conexion'], TRUE);
	}
	
?>