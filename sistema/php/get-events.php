<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../motor/conexionSitio.php");
	conectarSistema();	
	
	if (!isset($_GET['start']) || !isset($_GET['end'])) 
	{
		die("Please provide a date range.");
	}
	else
	{
		$dateIni = gmdate("Y-m-d H:i:s", $_GET['start']);
		$dateFin = gmdate("Y-m-d H:i:s", $_GET['end']);
		
		liberar_bd();
		$selectDatosCalendario = 'CALL sp_sistema_select_datos_calendario_id_user('.$_SESSION[$varIdUser].', "'.$dateIni.'", "'.$dateFin.'");';
		$datosCalendario = consulta($selectDatosCalendario);
		$ctaDatosCalendario = cuenta_registros($datosCalendario);
		if($ctaDatosCalendario != 0)
		{
			// Read and parse our events JSON file into an array of event data arrays.
			while($info=siguiente_registro($datosCalendario))
				$json[]=$info;
				
			echo json_encode($json);
		}
	}	
?>