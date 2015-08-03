<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	$btnAlta = false;
	$btnEdita = false;
	$btnElimina = false;
	$btnEdita = false;
	//PREMISOS DE ACCIONES
	liberar_bd();
	$selectPermisosAcciones = 'CALL sp_sistema_select_permisos_acciones_modulo('.$_SESSION["idPerfil"].', '.$_SESSION["mod"].');';
	$permisosAcciones = consulta($selectPermisosAcciones);
	while($acciones = siguiente_registro($permisosAcciones))
	{
		switch(utf8_convertir($acciones["accion"]))
		{
			case 'Alta':
				$btnAlta = true;					
			break;
			case 'Ver detalles':
				$btnDetalles = true;
			break;
			case 'Modificación':
				$btnEdita = true;					
			break;
			case 'Eliminación':
				$btnElimina = true;
			break;
		}
	}
		
	$listaContactos = '';
	
	//LISTA DE CONTACTOS DE AGENDA
	liberar_bd();
	$selectContactos = 'SELECT
							agen.id_agenda AS id,
							agen.prefijo_agenda AS prefijo,
							agen.nombre_agenda AS nombre,
							agen.segundo_nombre_agenda AS segNombre,
							agen.apellido_agenda AS apellido,
							agen.sufijo_agenda AS sufijo,
							agen.opcion_contacto AS opcion,
							agen.otro_agenda AS otro
						FROM
							agenda AS agen
						WHERE
							agen.id_agente = '.$_SESSION['idAgenteActual'].'
						AND agen.estatus_agenda = 1';
						
	$contactos = consulta($selectContactos);
	
	while($cont = siguiente_registro($contactos))
	{
		$cliente = '';
		$proveedor = '';
		$contacto = '';
		
		switch($cont["opcion"])
		{
			case 1:
				//CLIENTE
				liberar_bd();
				$selectContactoCliente = 'CALL sp_sistema_select_cliente_contacto('.$cont["id"].');';
				$contactoCliente = consulta($selectContactoCliente);
				$conCli = siguiente_registro($contactoCliente);
				$cliente = utf8_convertir($conCli["nombre"]);
			break;
			case 2:
				//PROVEEDOR
				liberar_bd();
				$selectContactoProveedor = 'CALL sp_sistema_select_proveedor_contacto('.$cont["id"].');';
				$contactoProveedor = consulta($selectContactoProveedor);
				$conProv = siguiente_registro($contactoProveedor);
				$proveedor = utf8_convertir($conProv["nombre"]);
			break;
			case 3:
				$contacto = utf8_convertir($cont["otro"]);
			break;
		}
		
		//CHECAMOS CORREO
		liberar_bd();
		$selectCorreoContacto = 'CALL sp_sistema_select_lista_correo_agenda_id('.$cont["id"].');';
		$correoContacto = consulta($selectCorreoContacto);
		$ctaCorreoContacto = cuenta_registros($correoContacto);
		if($ctaCorreoContacto > 1)
		{
			mysqli_data_seek($correoContacto,0);
			$correoCon = siguiente_registro($correoContacto);
			$ctaCorreo = $ctaCorreoContacto-1;
			$correoPrincipal = $correoCon["nombre"].'(+'.$ctaCorreo.')';														
		}
		else
		{
			if($ctaCorreoContacto == 1)
			{
				$correoCon = siguiente_registro($correoContacto);
				$ctaCorreo = $ctaCorreoContacto;
				$correoPrincipal = $correoCon["nombre"];	
			}
			else
				$correoPrincipal = '';	
		}
		
		//CHECAMOS TELEFONO
		liberar_bd();
		$selectTelefonoContacto = 'CALL sp_sistema_select_lista_telefono_agenda_id('.$cont["id"].');';
		$telefonoContacto = consulta($selectTelefonoContacto);
		$ctaTelefonoContacto = cuenta_registros($telefonoContacto);
		if($ctaTelefonoContacto > 1)
		{
			mysqli_data_seek($telefonoContacto,0);
			$telefonoCon = siguiente_registro($telefonoContacto);
			$ctaTelefono = $ctaTelefonoContacto-1;
			$telefonoPrincipal = $telefonoCon["telefono"].'(+'.$ctaTelefono.')';														
		}
		else
		{
			if($ctaTelefonoContacto == 1)
			{
				$telefonoCon = siguiente_registro($telefonoContacto);
				$ctaTelefono = $ctaTelefonoContacto;
				$telefonoPrincipal = $telefonoCon["telefono"];	
			}
			else
				$telefonoPrincipal = '';	
		}
		
		//CHECAMOS DIRECCION
		liberar_bd();
		$selectDireccionContacto = 'CALL sp_sistema_select_lista_direccion_agenda_id('.$cont["id"].');';
		$direccionContacto = consulta($selectDireccionContacto);
		$ctaDireccionContacto = cuenta_registros($direccionContacto);
		if($ctaDireccionContacto > 1)
		{
			mysqli_data_seek($direccionContacto,0);
			$direccionCon = siguiente_registro($direccionContacto);
			$ctaDireccion = $ctaDireccionContacto-1;
			$direccionPrincipal = utf8_convertir($direccionCon["direccion"]).'(+'.$ctaDireccion.')';														
		}
		else
		{
			if($ctaDireccionContacto == 1)
			{
				$direccionCon = siguiente_registro($direccionContacto);
				$ctaDireccion = $ctaDireccionContacto;
				$direccionPrincipal = utf8_convertir($direccionCon["direccion"]);	
			}
			else
				$direccionPrincipal = '';	
		}
		
		if($cont["prefijo"] == "-")
			$prefijo = "";
		else
			$prefijo = utf8_convertir($cont["prefijo"]);
		
		if($cont["segNombre"] == "-")
			$segundoNombre = "";
		else
			$segundoNombre = utf8_convertir($cont["segNombre"]);
		
		if($cont["apellido"] == "-")
			$apellido = "";
		else
			$apellido = utf8_convertir($cont["apellido"]);
		
		if($cont["subfijo"] == "-")
			$sufijo = "";
		else
			$sufijo = utf8_convertir($cont["subfijo"]);
			
		$nombreContacto = $prefijo.' '.utf8_convertir($cont["nombre"]).' '.$segundoNombre.' '.$apellido.' '.$sufijo;
		
		$listaContactos.= ' <tr>
								<td>'.$nombreContacto.'</td>
								<td>'.$correoPrincipal.'</td>
								<td>'.$telefonoPrincipal.'</td>																	
								<td>'.$direccionPrincipal.'</td>
								<td>'.$cliente.'</td>
								<td>'.$proveedor.'</td>
								<td>'.$contacto.'</td>
								<td class="tdAcciones">';
								if($btnEdita)
									$listaContactos.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idAgenda.value='.$cont["id"].';navegar(\'Editar\');">
															<i title="Editar" style="cursor:pointer;" class="fa fa-pencil"></i>
														</a>';
								if($btnDetalles)
									$listaContactos.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idAgenda.value='.$cont["id"].';navegar(\'Detalles\');">
															<i title="Ver detalles" style="cursor:pointer;" class="fa fa fa-eye"></i>
														</a>';
								if($btnElimina)
									$listaContactos.= '	<a class="btn btn-default-alt btn-sm" onClick="if(confirm(\'Desea eliminar este contacto de la agenda\')){document.frmSistema.idAgenda.value='.$cont["id"].'; navegar(\'Eliminar\');}">	
															<i title="Eliminar" style="cursor:pointer;" class="fa fa-trash-o"></i>	
														</a>';																	
		$listaContactos.= '	  	</td>											
							</tr>';	
	}
	
	echo $listaContactos;	
	
?>