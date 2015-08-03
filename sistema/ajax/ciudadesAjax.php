<?php
	session_start();
	include("../motor/conexionSitio.php");
	conectarSistema();
	if ($_POST["elegido"]) 
	{
		//LISTA DE CIUDADES
		liberar_bd();
		$selectCiudadesAjax = '	SELECT city.id_ciudades AS id
									 , city.nombre_ciudades AS nombre
								FROM
								  ciudades city
								WHERE
								  city.id_estados = '.$_POST["elegido"].';';
								  
		$ciudadesAjax = consulta($selectCiudadesAjax);
		while($ciuAjax = siguiente_registro($ciudadesAjax))
		{
			echo '<option value="'.$ciuAjax["id"].'">'.utf8_convertir($ciuAjax["nombre"]).'</option>';
		}
	}
?>