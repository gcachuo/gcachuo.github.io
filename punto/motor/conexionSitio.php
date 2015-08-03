<?php
	function conectarSistema()
	{		
		$usuario = "e11_cbizsys";
		$pass = "c8!z5Ys";
		$bd = "e11_cbizsys";
		$servidor = "localhost";
		
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

	function consulta($sql)
	{
		$resultado = mysqli_query($_SESSION['conexion'],$sql);
		return $resultado;
	}
	
	function cuenta_registros($resultado)
	{
		return mysqli_num_rows($resultado);	
	}
	
	function siguiente_registro($resultado)
	{
		return mysqli_fetch_assoc($resultado);	
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
	
?>