<?php
	session_start();
	include_once("../../sistema/motor/conexionSitio.php");
	include_once("../../sistema/motor/globales.php");
	conectarSistema();
	
	$listaCiudades = '';
	if(isset($_POST["elegido"])) 
	{
		//LISTA DE CIUDADES
		liberar_bd();
		$selectCiudadesAjax = 'CALL sp_sistema_lista_ciudades_edoId('.$_POST["elegido"].');';								  
		$ciudadesAjax = consulta($selectCiudadesAjax);
		while($cityAjax = siguiente_registro($ciudadesAjax))
		{
			$listaCiudades.='<option value="'.$cityAjax["id"].'">'.utf8_convertir($cityAjax["nombre"]).'</option>';
		}
	}
	
	$ciudades = ' <label for="id_ciudad" class="col-sm-4 control-label">Ciudad:</label>
				  <div class="col-sm-8">
					  <select id="id_ciudad" name="id_ciudad" style="width:100% !important" class="selectSerch">	
						  <option value="0">Seleccione una ciudad</option>
						  '.$listaCiudades.'
					  </select>
				  </div>';
				  
	echo $ciudades;
?>