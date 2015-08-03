<?php

	function ingresosmenuInicio()
	{
		$btnPagoCuenta = false;
		$btnEdita = false;
		$btnElimina = false;
		//PREMISOS DE ACCIONES
		liberar_bd();
		$selectPermisosAcciones = 'CALL sp_sistema_select_permisos_acciones_modulo('.$_SESSION["idPerfil"].', 5001);';
		$permisosAcciones = consulta($selectPermisosAcciones);
		while($acciones = siguiente_registro($permisosAcciones))
		{
			switch(utf8_convertir($acciones["accion"]))
			{
				case 'Pago a cuentas de clientes':
					$btnPagoCuenta = true;					
				break;
			}
		}
		
		$fechaFormtHoy = date('Y-m-d');
		//ACTUALIZAMOS LAS PROMOCIONES ANTIGUAS
		liberar_bd();
		$updatePromocionesOld = 'CALL sp_sistema_update_promocion_estatus_old("'.$fechaFormtHoy.'");';
		$promocionesOld = consulta($updatePromocionesOld);
		
		//CONFIGURACIONES PUNTO DE VENTA
		liberar_bd();
		$selectDatosConfiguracion = 'CALL sp_sistema_select_datos_configuracion_venta();';
		$datosConfiguracion = consulta($selectDatosConfiguracion);
		$config = siguiente_registro($datosConfiguracion);
		$_SESSION["razonVenta"] = $config["razon"];
		$_SESSION["rfcVenta"] = $config["rfc"];
		$_SESSION["domicilioVenta"] = $config["domicilio"];
		$_SESSION["monedaVenta"] = $config["moneda"];
		$_SESSION["papelVenta"] = $config["papel"];
		$_SESSION["ivaVenta"] = $config["iva"];
		$_SESSION["factorIvaVenta"] = $config["iva"]/100;
		$_SESSION["incIvaVenta"] = $config["incIva"];
		$_SESSION["idVentaActual"] = '';
		$_SESSION["totalDescActual"] = '';
		$_SESSION["idTipoClienteVenta"] = 0;
		
		//LISTA DE ESTADOS
		liberar_bd();
		$selectEstados = 'CALL sp_sistema_lista_estados();';
		$estados = consulta($selectEstados);
		while($est = siguiente_registro($estados))
		{
			$selecEdo = '';
			if($est["id"] == 12)
				$selecEdo = 'selected="selected"';
			$optEstados .= '<option '.$selecEdo.' value="'.$est["id"].'">'.utf8_convertir($est["nombre"]).'</option>';
		}
		
		//LISTA DE CIUDADES
		liberar_bd();
		$selectCiudades = 'CALL sp_sistema_lista_ciudades_edoId(12);';
		$ciudades = consulta($selectCiudades);
		while($ciu = siguiente_registro($ciudades))
		{
			$selecMpo = '';
			if($ciu["id"] == 462)
				$selecMpo = 'selected="selected"';
			$optCiudades .= '<option '.$selecMpo.' value="'.$ciu["id"].'">'.utf8_convertir($ciu["nombre"]).'</option>';
		}
		
		//LISTA DE TIPOS DE CLIENTE
		liberar_bd();
		$selectTiposCliente = 'CALL sp_sistema_lista_tipos_cliente();';
		$tiposCliente = consulta($selectTiposCliente);
		while($cliente = siguiente_registro($tiposCliente))
		{
			$optTipos .= '<option value="'.$cliente["id"].'">'.utf8_convertir($cliente["nombre"]).'</option>';
		}
		
		//LISTA DE UNIDADES
		liberar_bd();
		$selectUnidades = "CALL sp_sistema_lista_unidades();";
		$unidades = consulta($selectUnidades);
		while($uni = siguiente_registro($unidades))
		{
			$optUnidades .= '<option value="'.$uni["id"].'">'.utf8_convertir($uni["nombre"]).'</option>';
		}
		
		//LISTA DE PRODUCTOS
		liberar_bd();
		$selectProductos = "CALL sp_sistema_lista_productos();";
		$productos = consulta($selectProductos);
		while($prod = siguiente_registro($productos))
		{
			$optProductos .= '<option value="'.$prod["id"].'">'.utf8_convertir($prod["nombre"]).'</option>';
		}
		
		//LISTA DE KITS
		liberar_bd();
		$selectListaKits = "CALL sp_sistema_lista_kits();";
		$listaKits = consulta($selectListaKits);
		while($kits = siguiente_registro($listaKits))
		{
			$optProductos .='<option value="'.$kits["id"].'_kit">'.$kits["nombre"].'</option>';
		}
		
		//LISTA DE CLIENTES
		liberar_bd();
		$selectClientes='CALL sp_sistema_select_lista_clientes()';
		$clientes=consulta($selectClientes);
		$optClientes = '<option value="0">Selecccione cliente</option>';
		while($cli = siguiente_registro($clientes)) 
		{	
			$optClientes .= '<option value="'.$cli["id"].'">'.utf8_convertir($cli["nombre"]).'</option>';														
		}
		
		//CHECAMOS SI SE VENDERA CON UBICACIÓN
		if($_SESSION['prodUbica'] == 1)
		{
			$ubicacion = '	<fieldset title="Paso 3">
								  <legend>Ubicaci&oacute;n</legend>                                                 
								  <div class="form-horizontal">														
									  <div class="form-group">
										  <label for="id_pasillo" class="col-sm-3 control-label">Pasillo:</label>
										  <div class="col-sm-6" id="divListaPas">
											  <select id="id_pasillo" name="id_pasillo" style="width:100% !important" class="selectSerch">
											  	  <option value="0">Seleccione pasillo</option>
												  '.$optPasillos.'
											  </select>
										  </div>
									  </div>
									  <div class="form-group">
										  <label for="id_planta" class="col-sm-3 control-label">Planta:</label>
										  <div class="col-sm-6" id="divListaPlan">
											  <select id="id_planta" name="id_planta" style="width:100% !important" class="selectSerch">
											  	  <option value="0">Seleccione planta</option>
												  '.$optPlantas.'
											  </select>
										  </div>
									  </div>
									  <div class="form-group">
										  <label for="id_anaquel" class="col-sm-3 control-label">Anaquel:</label>
										  <div class="col-sm-6" id="divListaAna">
											  <select id="id_anaquel" name="id_anaquel" style="width:100% !important" class="selectSerch">
											  	  <option value="0">Seleccione anaquel</option>
												  '.$optAnaqueles.'
											  </select>
										  </div>
									  </div>
								  </div>	
							  </fieldset>';
		}
		
		$pagina = ' <input type="hidden" name="factorIvaVenta" id="factorIvaVenta" class="form-control" readonly="readonly" value="'.$_SESSION["factorIvaVenta"].'"/>
					<input type="hidden" disabled="disabled" class="form-control" id="txtDescAplica" name="txtDescAplica" maxlength="100" value="0.00"/>
					<input type="hidden" disabled="disabled" class="form-control" id="txtPorcAplica" name="txtPorcAplica" maxlength="100" value="0.00"/>
					<input type="hidden" disabled="disabled" class="form-control" id="txtFormaPago" name="txtFormaPago" maxlength="3"/>
					<input type="hidden" disabled="disabled" class="form-control" id="txtNewCat" name="txtNewCat" maxlength="1"/>
					<input type="hidden" disabled="disabled" class="form-control" id="idProdActual" name="idProdActual"/>
					<input type="hidden" disabled="disabled" class="form-control" id="idKitActual" name="idKitActual"/>
					<input type="hidden" disabled="disabled" class="form-control" id="idClientePago" name="idClientePago"/>
					<input type="hidden" disabled="disabled" class="form-control" id="idClienteCtaPago" name="idClienteCtaPago"/>
					<input type="hidden" disabled="disabled" class="form-control" id="idTipoPago" name="idTipoPago" maxlength="100" value="1"/>
					<input type="hidden" disabled="disabled" class="form-control" id="idTipoDoc" name="idTipoDoc" maxlength="100" value="1"/>
					<div class="container">												
						<div class="row">
							<div class="col-md-8">
								<div class="panel panel-danger">
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table" style="margin-bottom: 0px;">
												<thead>
													<tr>
														<th class="col-xs-1 col-sm-1"  style="text-align:center;">CANTIDAD</th>
														<th class="col-xs-6 col-sm-6"  style="text-align:center;">C&Oacute;DIGO INTERNO/BARRAS</th>
														<th class="col-xs-5 col-sm-5"  style="text-align:center;">PRODUCTO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input name="cantidadProd" id="cantidadProd" class="form-control" type="text" value="1">
														</td>
														<td>
															<input name="codigoProd" id="codigoProd" class="form-control codigoProd" type="text" autocomplete="off">
														</td>
														<td>
															<select id="idProd" name="idProd" style="width:100% !important" class="selectSerch" onchange="seleccionar">
																<option value="0" selected="selected">Seleccione producto</option>
																'.$optProductos.'
															</select>
														</td>
													</tr>																	
												</tbody>																		
											</table>
										</div>	
										<div class="row breadcrumb" style="padding-bottom: 10px;">
											<div class="col-sm-6">
											  <h4 id="nombreCliente">Cliente General</h4>
											  <h6 id="carga_descripcion"></h6>
											  <h6 id="carga_precio"></h6>
											  <input type="hidden" name="idCli" id="idCli" class="form-control" readonly="readonly" value="1"/>
											  <input type="hidden" name="idTipoCli" id="idTipoCli" class="form-control" readonly="readonly" value="1"/>	
											  <input type="hidden" name="limitCredCli" id="limitCredCli" class="form-control" readonly="readonly" value="0.00"/>	
											  <input type="hidden" name="precioProd" id="precioPrdo" class="form-control" readonly="readonly"/>														  															 
											</div>
											<div class="col-sm-2">
											  <h4>Subtotal:$</h4>
											   <h4>IVA:$</h4>
											   <h2>$ </h4>
											</div>
											<div class="col-sm-4" style="text-align:right;">
											  <h4 id="subTxt">0.00</h4>
											  <h4 id="ivaTxt">0.00</h4>
											  <h2 id="totalTxt">0.00</h4>
											  <input type="hidden" name="subTotal" id="subTotal" class="form-control" readonly="readonly" value="0.00"/>
											  <input type="hidden" name="iva" id="iva" class="form-control" readonly="readonly" value="0.00"/>
											  <input type="hidden" name="total" id="total" class="form-control" readonly="readonly" value="0.00"/>
											</div>
										</div>
										</br>
										<div class="row" style="padding-bottom: 10px;">
											<div class="col-sm-12">
												<i id="btnEliminar" class="btn btn-primary btn-lg btnEliminar" onclick="if(confirm(\'Est&aacute; seguro que desea borrar el pedido\')){eliminar_salida();}" disabled="disabled">Borrar</i>
												<!--<i id="btnDesuento" href="#myModal5" class="btn btn-primary btn-lg btnDesuento" data-toggle="modal" disabled="disabled">Descuento</i>
												<i class="btn btn-primary btn-lg" disabled="disabled">Aparcar</i>-->
												<i id="btnPagar" href="#myModal6" style="float:right;" class="btn btn-success btn-lg btnPagar" id="bootbox-demo-5" data-toggle="modal" disabled="disabled" onclick="restaura_forma_pago()">Pagar</i>
												<i id="btnCotizar" href="#myModal7" style="float:right;" class="btn btn-success btn-lg btnCotizar" id="bootbox-demo-5" data-toggle="modal" disabled="disabled" onclick="restaura_forma_pago()">Cotizar</i>
											</div>
										</div>	
										<div class="row" style="padding-bottom: 10px;">
											<div class="col-md-12">
												<div class="tab-pane active" id="inline">
													<div class="table-responsive" id="" style="height:197px;">
														<table class="table table-bordered table-striped table-hover">
															<thead>
																<th class="tdNombre">NOMBRE DEL ART&Iacute;CULO</th>
																<th class="tdPrecio">PRECIO</th>
																<th class="tdCant">CANTIDAD</th>
																<th class="tdTotal">TOTAL</th>
																<th class="tdAccion">ELIMINAR</th>
															</thead>
															<tbody id="ticket">
															</tbody>
														</table>
													</div>
												 </div>
											</div>
										</div>													
									</div>
								</div>											
							</div>
							<div class="col-md-4">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4>
											<ul class="nav nav-tabs">
												<li class="active">
													<a href="#tabArticulos" data-toggle="tab">Productos</a>
												</li>
												<li>
													<a href="#tabServicios" data-toggle="tab">Servicios</a>
												</li>
												<li>
													<a href="#tabClientes" data-toggle="tab">Clientes</a>
												</li>
												<li>
													<a href="#tabKits" data-toggle="tab"><i class="fa fa-suitcase"></i></a>
												</li>														  		
											</ul>
										</h4>
									</div>
									<div class="panel-body panelDer">
										<div class="tab-content">
											<div class="tab-pane active tabDer" id="tabArticulos" style="height:505px;">
												
											</div>
											<div class="tab-pane tabDer" id="tabServicios" style="height:505px;">
												
											</div>
											<div class="tab-pane tabDer" id="tabClientes" style="height:505px;">
																											
											</div>
											<div class="tab-pane tabDer" id="tabKits" style="height:505px;">
																											
											</div>
										</div>
									</div>
								</div>
							</div>											
						</div>
					</div>								
					<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Nuevo cliente</h4>
								</div>
								<div class="modal-body">
									<div class="form-horizontal">
										<div class="form-group">
											<label for="nombreCli" class="col-sm-3 control-label">Nombre comercial:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="nombreCli" name="nombreCli" maxlength="100" />
											</div>
										</div>
										<div class="form-group">
											<label for="id_tipos" class="col-sm-3 control-label">Tipo de cliente:</label>
											<div class="col-sm-6">
												<select id="id_tipos" name="id_tipos" style="width:100% !important" class="selectSerch">
													<option value="0">Seleccione tipo de cliente</option>
													'.$optTipos.'
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="razon" class="col-sm-3 control-label">Razón social:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="razon" name="razon" maxlength="100"/>
											</div>
										</div>
										<div class="form-group">
											<label for="rfcCliente" class="col-sm-3 control-label">RFC:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="rfcCliente" name="rfcCliente" maxlength="13"/>
											</div>
										</div>
										<div class="form-group">
											<label for="calleCliente" class="col-sm-3 control-label">Calle:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="calleCliente" name="calleCliente" maxlength="100"/>
											</div>
										</div>
										<div class="form-group">
											<label for="numExtCliente" class="col-sm-3 control-label">Num Ext:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="numExtCliente" name="numExtCliente" maxlength="100"/>
											</div>
										</div>
										<div class="form-group">
											<label for="numIntCliente" class="col-sm-3 control-label">Num Int:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="numIntCliente" name="numIntCliente" maxlength="100"/>
											</div>
										</div>
										<div class="form-group">
											<label for="coloniaCliente" class="col-sm-3 control-label">Colonia:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="coloniaCliente" name="coloniaCliente" maxlength="100"/>
											</div>
										</div>
										<div class="form-group">
											<label for="cpCliente" class="col-sm-3 control-label">CP:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="cpCliente" name="cpCliente" maxlength="5"/>
											</div>
										</div>	
										<div class="form-group">
											<label for="id_estado" class="col-sm-3 control-label">Estado:</label>
											<div class="col-sm-6">
												<select id="id_estado" name="id_estado" style="width:100% !important" class="selectSerch">
													'.$optEstados.'
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="id_ciudad" class="col-sm-3 control-label">Ciudad:</label>
											<div class="col-sm-6">
												<span id="city_spn" >
													<select id="id_ciudad" name="id_ciudad" style="width:100% !important" class="selectSerch">
														'.$optCiudades.'
													</select>
												</span>
											</div>
										</div>
										<div class="form-group">
											<label for="localidad" class="col-sm-3 control-label">Localidad:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="localidad" name="localidad" maxlength="100" />
											</div>
										</div>
										<div class="form-group">
											<label for="nombreContactoCliente" class="col-sm-3 control-label">Nombre de contacto:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="nombreContactoCliente" name="nombreContactoCliente" maxlength="100"/>
											</div>
										</div>
										<div class="form-group">
											<label for="correoCliente" class="col-sm-3 control-label">Correo:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control frmCorreo" id="correoCliente" name="correoCliente" maxlength="255"/>
											</div>
										</div>
										<div class="form-group">
											<label for="ladaCliente" class="col-sm-3 control-label">Tel&eacute;fono:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="ladaCliente" name="ladaCliente" style="width:15%; float:left;" maxlength="3"/>
												<input type="text" class="form-control" id="telCliente" name="telCliente" style="width:80%;" maxlength="8"/>
											</div>
										</div>
										<div class="form-group">
											<label for="saldo" class="col-sm-3 control-label">Saldo inicial:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="saldo" name="saldo" maxlength="100"/>
											</div>
										</div>
										<div class="form-group">
											<label for="credito" class="col-sm-3 control-label">Monto m&aacute;ximo de cr&eacute;dito:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="credito" name="credito" maxlength="100"/>
											</div>
										</div>								
									</div>
								</div>
								<div class="modal-footer">									
									<i class="btn-danger btn" onclick="" data-dismiss="modal">Cancelar</i>
									<i class="btn-success btn" onclick="nuevo_cliente()" data-dismiss="modal">Guardar</i>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Nuevo producto</h4>
								</div>
								<div class="modal-body">
									<div class="tab-content">
										<div class="tab-pane active" id="domwizard">                                                 
											<fieldset title="Paso 1">
												<legend>Información General</legend>
												<div class="form-horizontal">
													<div class="form-group">
														<label for="nombreProducto" class="col-sm-3 control-label">Nombre:</label>
														<div class="col-sm-6">
															<input type="text" class="form-control" id="nombreProducto" name="nombreProducto" maxlength="100"/>
														</div>
													</div>
													<div class="form-group">
														<label for="codigo" class="col-sm-3 control-label">C&oacute;digo de barras:</label>
														<div class="col-sm-6">
															<input type="text" class="form-control" id="codigo" name="codigo" maxlength="100"/>
														</div>
													</div>
													<div class="form-group">
														<label for="codigoInt" class="col-sm-3 control-label">C&oacute;digo interno:</label>
														<div class="col-sm-6">
															<input type="text" class="form-control" id="codigoInt" name="codigoInt" maxlength="100"/>
														</div>
													</div>
													<div class="form-group">
														<label for="stockProducto" class="col-sm-3 control-label">Stock actual:</label>
														<div class="col-sm-6">
															<input type="text" class="form-control" id="stockProducto" name="stockProducto" maxlength="20"/>
														</div>
													</div> 	
													<div class="form-group">
														<label for="stockMinProducto" class="col-sm-3 control-label">Stock m&iacute;nimo:</label>
														<div class="col-sm-6">
															<input type="text" class="form-control" id="stockMinProducto" name="stockMinProducto" maxlength="20"/>
														</div>
													</div> 
												</div>                                                       
											</fieldset>
											<fieldset title="Paso 2">
												<legend>Clasificaci&oacute;n</legend>
												<div class="form-horizontal">
													<div class="form-group">
														<label for="id_marca" class="col-sm-3 control-label">Marca:</label>
														<div class="col-sm-6">
															<select id="id_marca" name="id_marca" style="width:100% !important" class="selectSerch">
																<option value="0">Seleccione marca</option>
																'.$optMarcas.'
															</select>
														</div>
													</div>														
													<div class="form-group">
														<label for="id_unidad" class="col-sm-3 control-label">Unidad del producto:</label>
														<div class="col-sm-6">
															<select id="id_unidad" name="id_unidad" style="width:100% !important" class="selectSerch">
																<option value="0">Seleccione unidad</option>
																'.$optUnidades.'
															</select>
														</div>
													</div> 
												</div>
											</fieldset>
											'.$ubicacion.'
											<fieldset title="Paso 3">
												<legend>Precios</legend>
												<div class="form-horizontal">
													<div class="form-group">
														<label for="precioGeneral" class="col-sm-3 control-label">Precio general:</label>
														<div class="col-sm-6">
															<input type="text" class="form-control" id="precioGeneral" name="precioGeneral" maxlength="12"/>
														</div>
													</div>
													<div class="form-group">
														<label for="costoProducto" class="col-sm-3 control-label">Costo:</label>
														<div class="col-sm-6">
															<input type="text" class="form-control" id="costoProducto" name="costoProducto" maxlength="12"/>
														</div>
													</div>
												</div>
											</fieldset>	
										</div>
								   </div>    
								</div>
								<div class="modal-footer">									
									<i class="btn-danger btn" onclick="" data-dismiss="modal">Cancelar</i>
									<i class="btn-success btn" onclick="nuevo_producto()" data-dismiss="modal">Guardar</i>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Nueva categor&iacute;a</h4>
								</div>
								<div class="modal-body">
									<div class="form-horizontal">
										<div class="form-group">
											<label for="nombreCta" class="col-sm-3 control-label">Nombre:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="nombreCta" name="nombreCta" maxlength="100"/>
											</div>
										</div>												                         
										<div class="form-group">
											<label for="descCta" class="col-sm-3 control-label">Descripci&oacute;n:</label>
											<div class="col-sm-6">
												<textarea class="form-control" id="descCta" name="descCta"></textarea>
											</div>
										</div>                              
									</div>		
								</div>
								<div class="modal-footer">									
									<i class="btn-danger btn" onclick="" data-dismiss="modal">Cancelar</i>
									<i class="btn-success btn" onclick="">Guardar</i>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Nueva subcategor&iacute;a</h4>
								</div>
								<div class="modal-body">
									<div class="form-horizontal">
										                          
									</div>		
								</div>
								<div class="modal-footer">									
									<i class="btn-danger btn" onclick="" data-dismiss="modal">Cancelar</i>
									<i class="btn-success btn" onclick="">Guardar</i>
								</div>
							</div>
						</div>
					</div>
					<!--<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Descuento de pedido</h4>
								</div>
								<div class="modal-body">
									<div class="form-horizontal">
										<div class="form-group" style="display:none;">
											<label class="col-sm-3 control-label">Check Tipo</label>
											<div class="col-sm-6">
												<label class="checkbox-inline">
												  <input readonly="readonly" type="checkbox" id="chkTipo" name="chkTipo" value="1" checked="checked">
												</label>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Tipo:</label>
											<div class="col-sm-6">
												<button type="button" class="btn btn-default btnDefaultActivo" id="btnMonto">Monto</button>
												<button type="button" class="btn btn-default" id="btnPorcentaje">Porcentaje</button>
											</div>
										</div>
										<div class="form-group" id="divMonto">
											<label class="col-sm-3 control-label">Descuento:</label>
											<div class="col-sm-6">
												<div class="input-group">
													<span class="input-group-addon">$</span>
													<input type="text" class="form-control" id="txtDescMonto"  onkeyup="aplicar_descuento_monto()">						
												</div>
											</div>
											<div class="col-md-3" style="display:none;" id="divValidaMonto">
												<p class="help-block">
													<i class="fa fa-times-circle"></i>
													Por favor, escribe un valor menor o igual a <span id="spnDivDesc"></span>.
												</p>
											</div>								
										</div>										
										<div class="form-group" id="divPorcentaje" style="display:none;">
											<label class="col-sm-3 control-label">Descuento:</label>
											<div class="col-sm-6">
												<div class="input-group">
													<input type="text" class="form-control" id="txtDescPorc"  onkeyup="aplicar_descuento_porc()">
													<span class="input-group-addon">%</span>
												</div>												
											</div>
											<div class="col-md-3" style="display:none;" id="divValidaPor">
												<p class="help-block">
													<i class="fa fa-times-circle"></i>
													Por favor, escribe un valor entre <strong>0</strong> y <strong>100</strong>.
												</p>
											</div>
										</div>										
										<div class="form-group">
											<label  class="col-sm-3 control-label">Total actual:</label>
											<div class="col-sm-6">
												<input disabled="disabled" type="text" class="form-control" id="txtSubOriginal" name="txtSubOriginal" maxlength="100"/>
											</div>
										</div> 
										<div class="form-group">
											<label  class="col-sm-3 control-label">Descuento:  <strong><span id="spnPorcentaje">0.00</span></strong>%</label>
											<div class="col-sm-6">
												<input type="text" disabled="disabled" class="form-control" id="txtDescNuevo" name="txtDescNuevo" maxlength="100" value="0.00"/>												
											</div>
										</div>
										<div class="form-group">
											<label  class="col-sm-3 control-label">Nuevo total:</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="txtNuevoSubtotal" name="txtNuevoSubtotal" maxlength="100"/>
											</div>
										</div>
									</div>								 
								</div>
								<div class="modal-footer">
									<i class="btn-primary btn" id="btnAgregaDesc" onclick="agregar_descuento()" data-dismiss="modal">Agregar descuento</i>
									<i class="btn-default btn" onclick="" data-dismiss="modal">Cancelar</i>
								</div>
							</div>
						</div>
					</div>	-->				
					<div class="modal fade" id="myModal6" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div id="divFormPago">
									<div class="modal-header">
										<h4 class="modal-title">Pago</h4>
									</div>
									<div class="modal-body">
										<div class="form-horizontal">
											<div class="form-group">
												<label  class="col-sm-3 control-label"><strong>Total:</strong></label>
												<div class="col-sm-6">
													<div class="input-group">
														<span class="input-group-addon">$</span>
														<input type="text" disabled="disabled" class="form-control" id="txtSaldoTotal2" name="txtSaldoTotal2" maxlength="100" value="0.00"/>												
													</div>
												</div>
											</div>
											<div class="form-group">
												<label  class="col-sm-3 control-label"><strong>Cliente:</strong></label>
												<div class="col-sm-6">
													<div class="input-group">
														<input type="text" disabled="disabled" class="form-control" id="txtNameCliente" name="txtNameCliente" maxlength="255"/>												
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"><strong>Crear nota:</strong></label>
												<div class="col-sm-6">
													<label class="checkbox-inline">
													  <input type="checkbox" id="creaCuenta" name="creaCuenta" value="1">
													</label>
												</div>
											</div>
										</div>										
									</div>
									<div class="modal-footer">										
										<!--<i class="btn btn-default hidden-xs" onclick="foma_pago(1)" title="Cheque">
											<span class="fa-stack fa-lg">
												<i class="fa fa-list-alt fa-stack-4x"></i>
											</span>
										</i>-->										
										<i class="btn btn-default hidden-xs" onclick="foma_pago(3)" id="btnEfectivoFin">
											<span class="fa-stack fa-lg">
												<i class="fa fa-money fa-4x"></i>
											</span>											
										</i>
										<i class="btn btn-default hidden-xs" onclick="foma_pago(2)" id="btnCreDebFin">
											<span class="fa-stack fa-lg">
												<i class="fa fa-credit-card fa-4x"></i>
											</span>
										</i>
										<i class="btn btn-danger hidden-xs" data-dismiss="modal" id="btnCancelarFin"> 
											<span class="fa-stack fa-lg">
												<i class="fa fa-times fa-4x"></i>
											</span>											
										</i>
									</div>
								</div>
								<div id="divFormPagoTotal" style="display:none">
									<div class="modal-header">
										<h4 class="modal-title">
											Pago <span id="spnPagoCheque">por cheque</span>
												 <span id="spnPagoTarjeta">con tarjeta de cr&eacute;dito/d&eacute;bito</span>
												 <span id="spnPagoEfectivo">de efectivo</span>
											
										</h4>
									</div>
									<div class="modal-body">
										<div class="form-horizontal">
											<div class="form-group">
												<label  class="col-sm-3 control-label"><strong>Total a pagar:</strong></label>
												<label  class="col-sm-3 control-label"><strong>$ <span id="spnTotalPagar"></span></strong></label>
												<input readonly="readonly" type="hidden" class="form-control" id="txtSaldoTotal" name="txtSaldoTotal" maxlength="100"/>	
											</div>
											<div class="form-group" id="divMonto">
												<label class="col-sm-3 control-label">Monto de descuento:</label>
												<div class="col-sm-6">
													<div class="input-group">
														<span class="input-group-addon">$</span>
														<input type="text" class="form-control" id="txtDescMonto" name="txtDescMonto" onkeyup="aplicar_descuento_monto()">						
													</div>
												</div>
												<div class="col-md-3" style="display:none;" id="divValidaMonto">
													<p class="help-block">
														<i class="fa fa-times-circle"></i>
														Por favor, escribe un valor menor o igual a <span id="spnDivDesc"></span>.
													</p>
												</div>								
											</div>										
											<div class="form-group" id="divPorcentaje">
												<label class="col-sm-3 control-label">Porcentaje de descuento:</label>
												<div class="col-sm-6">
													<div class="input-group">
														<input type="text" class="form-control" id="txtDescPorc" name="txtDescPorc" onkeyup="aplicar_descuento_porc()">
														<span class="input-group-addon">%</span>
													</div>												
												</div>
												<div class="col-md-3" style="display:none;" id="divValidaPor">
													<p class="help-block">
														<i class="fa fa-times-circle"></i>
														Por favor, escribe un valor entre <strong>0</strong> y <strong>100</strong>.
													</p>
												</div>
											</div>										
											<div class="form-group">
												<label  class="col-sm-3 control-label">Total actual:</label>
												<div class="col-sm-6">
													<input readonly="readonly" type="text" class="form-control" id="txtNewSaldoTotal" name="txtNewSaldoTotal" maxlength="100"/>
												</div>
											</div> 
											<!--<div class="form-group">
												<label  class="col-sm-3 control-label">Descuento:  <strong><span id="spnPorcentaje">0.00</span></strong>%</label>
												<div class="col-sm-6">
													<input type="text" disabled="disabled" class="form-control" id="txtDescNuevo" name="txtDescNuevo" maxlength="100" value="0.00"/>												
												</div>
											</div>
											<div class="form-group">
												<label  class="col-sm-3 control-label">Nuevo total:</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="txtNuevoSubtotal" name="txtNuevoSubtotal" maxlength="100" readonly="readonly"/>
												</div>
											</div>-->
											<div class="form-group">
												<label  class="col-sm-3 control-label">Su pago:</label>
												<div class="col-sm-6">
													<div class="input-group">
														<span class="input-group-addon">$</span>
														<input type="text" class="form-control" id="txtRecibido" name="txtRecibido" maxlength="100" onkeyup="revisar_cambio()"/>
													</div>
												</div>
											</div> 
											<div class="form-group">
												<label class="col-sm-3 control-label">Nota:</label>
												<div class="col-sm-6">
													<textarea class="form-control" id="descNota" name="descNota"></textarea>
												</div>
											</div> 
											<div class="form-group">
												<label  class="col-sm-3 control-label"><span id="spnTxtCambio" class="spnTxtCambio">Cambio:</span></label>
												<div class="col-sm-6">
													<div class="input-group">
														<span class="input-group-addon">$</span>
														<input type="text" disabled="disabled" class="form-control" id="txtCambio" name="txtCambio" maxlength="100" value="0"/>
														<input type="hidden" disabled="disabled" class="form-control" id="txtCambioSinComas" name="txtCambioSinComas" maxlength="100" value="0"/>												
													</div>
												</div>
											</div>
										</div>	
									</div>
									<div class="modal-footer">
										<i class="btn btn-primary hidden-xs" onclick="regresa_forma_pago()">
											<i class="fa fa-angle-left"></i>
										</i>
										<i id="btnCredito" class="btn-default btn btnCredito" data-dismiss="modal" data-dismiss="modal" onclick="cerrar_venta_saldo()" disabled="disabled">Cr&eacute;dito</i>	
										<i id="btnCobroSimple" class="btn-default btn btnCobroSimple" data-dismiss="modal" data-dismiss="modal" onclick="cerrar_venta_simple()">Cobrar sin ticket</i>										
										<i id="btnCobroTicket" class="btn-success btn btnCobroTicket" data-dismiss="modal" data-dismiss="modal" onclick="cerrar_venta_ticket()">Cobrar e imprimir</i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="myModal7" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div id="divFormCotizar">
									<div class="modal-header">
										<h4 class="modal-title">Cotizar</h4>
									</div>
									<div class="modal-body">
										<div class="form-horizontal">
											<div class="form-group">
												<label  class="col-sm-3 control-label"><strong>Total a pagar:</strong></label>
												<label  class="col-sm-3 control-label"><strong>$ <span id="spnTotalPagarCotizar"></span></strong></label>
												<input readonly="readonly" type="hidden" class="form-control" id="txtSaldoTotalCotiza" name="txtSaldoTotalCotiza" maxlength="100"/>	
											</div>
											<div class="form-group" id="divMonto">
												<label class="col-sm-3 control-label">Monto de descuento:</label>
												<div class="col-sm-6">
													<div class="input-group">
														<span class="input-group-addon">$</span>
														<input type="text" class="form-control" id="txtDescMontoCotiza" name="txtDescMontoCotiza" onkeyup="aplicar_descuento_monto_cotiza()">						
													</div>
												</div>
												<div class="col-md-3" style="display:none;" id="divValidaMontoCotiza">
													<p class="help-block">
														<i class="fa fa-times-circle"></i>
														Por favor, escribe un valor menor o igual a <span id="spnDivDescCotiza"></span>.
													</p>
												</div>								
											</div>										
											<div class="form-group" id="divPorcentajeCotiza">
												<label class="col-sm-3 control-label">Porcentaje de descuento:</label>
												<div class="col-sm-6">
													<div class="input-group">
														<input type="text" class="form-control" id="txtDescPorcCotiza" name="txtDescPorcCotiza" onkeyup="aplicar_descuento_porc_cotiza()">
														<span class="input-group-addon">%</span>
													</div>												
												</div>
												<div class="col-md-3" style="display:none;" id="divValidaPorCotiza">
													<p class="help-block">
														<i class="fa fa-times-circle"></i>
														Por favor, escribe un valor entre <strong>0</strong> y <strong>100</strong>.
													</p>
												</div>
											</div>										
											<div class="form-group">
												<label  class="col-sm-3 control-label">Total actual:</label>
												<div class="col-sm-6">
													<input readonly="readonly" type="text" class="form-control" id="txtNewSaldoTotalCotiza" name="txtNewSaldoTotalCotiza" maxlength="100"/>
												</div>
											</div> 
											<div class="form-group">
												<label for="notaCotiza" class="col-sm-3 control-label">Nota:</label>
												<div class="col-sm-6">
													<textarea class="form-control" id="notaCotiza" name="notaCotiza"></textarea>
												</div>
											</div> 											
										</div>	
									</div>
									<div class="modal-footer">										
										<i class="btn btn-default hidden-xs btnGuardarCotiza" data-dismiss="modal" data-dismiss="modal" onclick="guardar_cotizacion();" title="Guardar">
											<span class="fa-stack fa-lg">
												<i class="fa fa-save fa-4x"></i>
											</span>
										</i>
										<i class="btn btn-default hidden-xs btnGuardarCotiza" onclick="foma_cotizar()">
											<span class="fa-stack fa-lg">
												<i class="fa fa-envelope-o fa-4x"></i>
											</span>
										</i>
									</div>
								</div>
								<div id="divFormCotizarTotal" style="display:none">
									<div class="modal-header">
										<h4 class="modal-title">
										</h4>
									</div>
									<div class="modal-body">
										<div class="form-horizontal">
											<div class="form-group">
												<label class="col-sm-3 control-label">Correo:</label>
												<div class="col-sm-6">
													<input type="text" name="correoCli" id="correoCli" class="form-control frmCorreo" value=""/>
												</div>
											</div> 											
										</div>	
									</div>
									<div class="modal-footer">
										<i class="btn btn-primary hidden-xs" onclick="regresa_forma_cotiza()">
											<i class="fa fa-angle-left"></i>
										</i>
										<i id="btnCobroSimple" class="btn-default btn" data-dismiss="modal" data-dismiss="modal" onclick="guardar_cotizacion_enviar()">Enviar</i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="myModalMisc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div id="divFormPago">
									<div class="modal-header">
										<h4 class="modal-title">Producto miscelaneo</h4>
									</div>
									<div class="modal-body">
										<div class="form-horizontal">
											<div class="form-group">
												<label  class="col-sm-3 control-label">Cantidad:</label>
												<div class="col-sm-6">
													<div class="input-group">
														<input type="text" class="form-control" id="txtCantMisc" name="txtCantMisc" maxlength="100"/>												
													</div>
												</div>
											</div>
											<div class="form-group">
												<label for="nombreProducto" class="col-sm-3 control-label">Nombre:</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="nombreMisc" name="nombreMisc" maxlength="100"/>
												</div>
											</div>
											<div class="form-group">
												<label for="id_linea" class="col-sm-3 control-label">Unidad del producto:</label>
												<div class="col-sm-6" id="divListaUni">
													<select id="idUniMisc" name="idUniMisc" style="width:100% !important" class="selectSerch">
														<option value="0">Seleccione unidad</option>
														'.$optUnidades.'
													</select>
												</div>
											</div>
											<div class="form-group">
												<label for="precioGeneral" class="col-sm-3 control-label">Precio general:</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="precioMisc" name="precioMisc" maxlength="12"/>
												</div>
											</div>
										</div>										
									</div>
									<div class="modal-footer">									
										<i class="btn-danger btn" onclick="" data-dismiss="modal">Cancelar</i>
										<i class="btn-success btn" data-dismiss="modal" data-dismiss="modal" onclick="agregar_productoMisc()">Guardar</i>
									</div>
								</div>								
							</div>
						</div>
					</div>
					<div class="modal fade" id="myModalMisc2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div id="divFormPago">
									<div class="modal-header">
										<h4 class="modal-title">Abono de clientes</h4>
									</div>
									<div class="modal-body">
										<div class="panel panel-primary">
											<div class="panel-heading panelPagoCliente">
												<ul class="nav nav-tabs">													
													<li class="active">
														<a href="#pCuenta" data-toggle="tab">Pago a cuenta</a>
													</li>
													<li>
														<a href="#pSaldo" data-toggle="tab">Pago a saldo</a>
													</li>
												</ul>
											</div>
											<div class="panel-body">
												<div class="tab-content">
													<div class="tab-pane active" id="pCuenta">
														<div class="form-horizontal">
															<div class="form-group">
																<label for="idCliPagoCta" class="col-sm-3 control-label">Cliente:</label>
																<div class="col-sm-6" id="divListaUni">
																	<select id="idCliPagoCta" name="idCliPagoCta" style="width:100% !important" class="selectSerch">
																		'.$optClientes.'
																	</select>
																</div>
															</div>
														</div>
														<div class="form-horizontal" id="divCtaCliente" style="display:none;">
															<div class="form-group">
																<label for="idCtaCliente" class="col-sm-3 control-label">Cuenta del cliente:</label>
																<div class="col-sm-6">
																	<select id="idCtaCliente" name="idCtaCliente" style="width:100% !important" class="selectSerch">																		
																	</select>																	
																</div>
															</div>
														</div>
														<div class="form-horizontal">
															<div class="form-group">
																<label class="col-sm-3 control-label">Tipo de pago</label>
																<div class="col-sm-6">
																	<div class="radio">
																	  <label>
																		<input type="radio" name="optTipoPagoCta" id="optTipoPagoCta" value="3" checked>
																		Efectivo
																	  </label>
																	</div>
																	<div class="radio">
																	  <label>
																		<input type="radio" name="optTipoPagoCta" id="optTipoPagoCta" value="2">
																		Tarjeta de cr&eacute;dito/d&eacute;bito
																	  </label>
																	</div>
																	<div class="radio">
																	  <label>
																		<input type="radio" name="optTipoPagoCta" id="optTipoPagoCta" value="1">
																		Cheque
																	  </label>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Limite de cr&eacute;dito:</label>
																<div class="col-sm-6">
																	<div class="input-group">
																		<input readonly="readonly" type="text" class="form-control" id="txtLimiteActualCtaCli" name="txtLimiteActualCtaCli" maxlength="100"/>												
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Saldo actual:</label>
																<div class="col-sm-6">
																	<div class="input-group">
																		<input readonly="readonly" type="text" class="form-control" id="txtSaldoActualCtaCli" name="txtSaldoActualCtaCli" maxlength="100"/>	
																		<input readonly="readonly" type="hidden" class="form-control" id="saldoActualCtaCli" name="saldoActualCtaCli" maxlength="100"/>												
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Abono:</label>
																<div class="col-sm-6">
																	<input type="text" class="form-control" id="txtAbonoCtaCli" name="txtAbonoCtaCli" maxlength="22" onkeyup="cargar_new_saldo_cta_cliente();"/>
																	<input type="hidden" class="form-control" id="abonoCtaCli" name="abonoCtaCli" maxlength="22"/>
																</div>
															</div>											
															<div class="form-group">
																<label class="col-sm-3 control-label">Nuevo Saldo:</label>
																<div class="col-sm-6">
																	<input readonly="readonly" type="text" class="form-control" id="txtNewSaldoCtaCli" name="txtNewSaldoCtaCli" maxlength="22" value="0.00"/>
																	<input readonly="readonly" type="hidden" class="form-control" id="newSaldoCtaCli" name="newSaldoCtaCli" maxlength="22" value="0.00"/>
																</div>
															</div>
															<div class="form-group">
																<label  class="col-sm-3 control-label">Cambio:</label>
																<div class="col-sm-6">
																	<div class="input-group">
																		<span class="input-group-addon">$</span>
																		<input type="text" readonly="readonly" class="form-control" id="txtCambioCtaCliente" name="txtCambioCtaCliente" maxlength="100" value="0.00"/>
																		<input type="hidden" readonly="readonly" class="form-control" id="cambioCtaCliente" name="cambioCtaCliente" maxlength="100" value="0.00"/>												
																	</div>
																</div>
															</div>
														</div>
														<div class="modal-footer">									
															<i class="btn-danger btn" onclick="" data-dismiss="modal">Cancelar</i>
															<i class="btn-success btn" disabled="disabled" id="btnAgregarPagoCta" data-dismiss="modal" data-dismiss="modal" onclick="agregar_pago_cta_cliente()">Guardar</i>
														</div>
													</div>
													<div class="tab-pane" id="pSaldo">
														<div class="form-horizontal">
															<div class="form-group">
																<label for="idCliPago" class="col-sm-3 control-label">Cliente:</label>
																<div class="col-sm-6" id="divListaUni">
																	<select id="idCliPago" name="idCliPago" style="width:100% !important" class="selectSerch">
																		'.$optClientes.'
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Tipo de pago</label>
																<div class="col-sm-6">
																	<div class="radio">
																	  <label>
																		<input type="radio" name="optTipoPago" id="optTipoPago" value="3" checked>
																		Efectivo
																	  </label>
																	</div>
																	<div class="radio">
																	  <label>
																		<input type="radio" name="optTipoPago" id="optTipoPago" value="2">
																		Tarjeta de cr&eacute;dito/d&eacute;bito
																	  </label>
																	</div>
																	<div class="radio">
																	  <label>
																		<input type="radio" name="optTipoPago" id="optTipoPago" value="1">
																		Cheque
																	  </label>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Limite de cr&eacute;dito:</label>
																<div class="col-sm-6">
																	<div class="input-group">
																		<input readonly="readonly" type="text" class="form-control" id="txtLimiteActualCli" name="txtLimiteActualCli" maxlength="100"/>												
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Saldo actual:</label>
																<div class="col-sm-6">
																	<div class="input-group">
																		<input readonly="readonly" type="text" class="form-control" id="txtSaldoActualCli" name="txtSaldoActualCli" maxlength="100"/>	
																		<input readonly="readonly" type="hidden" class="form-control" id="saldoActualCli" name="saldoActualCli" maxlength="100"/>												
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Abono:</label>
																<div class="col-sm-6">
																	<input type="text" class="form-control" id="txtAbonoCli" name="txtAbonoCli" maxlength="22" onkeyup="cargar_new_saldo_cliente();"/>
																	<input type="hidden" class="form-control" id="abonoCli" name="abonoCli" maxlength="22"/>
																</div>
															</div>											
															<div class="form-group">
																<label class="col-sm-3 control-label">Nuevo Saldo:</label>
																<div class="col-sm-6">
																	<input readonly="readonly" type="text" class="form-control" id="txtNewSaldoCli" name="txtNewSaldoCli" maxlength="22" value="0.00"/>
																	<input readonly="readonly" type="hidden" class="form-control" id="newSaldoCli" name="newSaldoCli" maxlength="22" value="0.00"/>
																</div>
															</div>
															<div class="form-group">
																<label  class="col-sm-3 control-label">Cambio:</label>
																<div class="col-sm-6">
																	<div class="input-group">
																		<span class="input-group-addon">$</span>
																		<input type="text" readonly="readonly" class="form-control" id="txtCambioCliente" name="txtCambioCliente" maxlength="100" value="0.00"/>
																		<input type="hidden" readonly="readonly" class="form-control" id="cambioCliente" name="cambioCliente" maxlength="100" value="0.00"/>												
																	</div>
																</div>
															</div>
														</div>
														<div class="modal-footer">									
															<i class="btn-danger btn" onclick="" data-dismiss="modal">Cancelar</i>
															<i class="btn-success btn" disabled="disabled" id="btnAgregarPago" data-dismiss="modal" data-dismiss="modal" onclick="agregar_pago_cliente()">Guardar</i>
														</div>
													</div>													
												</div>
											</div>
										</div>								
									</div>									
								</div>								
							</div>
						</div>
					</div>
					<div class="modal fade" id="myModalMisc3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div id="divFormasCotizaciones">
									<div class="modal-header">
										<h4 class="modal-title">Cotizaciones</h4>
									</div>
									<div class="modal-body">
										<div class="panel panel-primary">
											<div class="panel-body">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="idCliCotiza" class="col-sm-3 control-label">Cliente:</label>
														<div class="col-sm-6">
															<select id="idCliCotiza" name="idCliCotiza" style="width:100% !important" class="selectSerch">
																'.$optClientes.'
															</select>
														</div>
													</div>
												</div>
												<div class="form-horizontal">
													<div class="scrollthis">
														<table class="table table-striped table-advance table-hover mailbox"">
															<thead>
																<tr>
																	<th width="5%" colspan="3"><span><input type="checkbox" id="toggle-all"/></span></th>
																</tr>
																<tr>
																	<th width="5%"></th>
																	<th>FOLIO</th>
																	<th>FECHA</th>
																	<th>IMPORTE</th>
																</tr>
															</thead>
															<tbody id="tablaCotizaciones">	
															</tbody>
														</table>
													</div>	
												</div>
												<div class="form-horizontal" id="divDatosCotizacion" style="display:none;">
													<div class="form-group">
														<label class="col-sm-3 control-label">Crear nota:</label>
														<div class="col-sm-6">
															<label class="checkbox-inline">
															  <input type="checkbox" id="creaNota" name="creaNota" value="1">
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Forma de pago:</label>
														<div class="col-sm-6">
															<div class="radio">
															  <label>
																<input type="radio" name="optFormaPago" id="optFormaPago1" value="3" checked>
																Efectivo
															  </label>
															</div>
															<div class="radio">
															  <label>
																<input type="radio" name="optFormaPago" id="optFormaPago2" value="2">
																Cr&eacute;dito/D&eacute;bito
															  </label>
															</div>
															<div class="radio">
															  <label>
																<input type="radio" name="optFormaPago" id="optFormaPago2" value="1">
																Cheque
															  </label>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">									
													<i class="btn-danger btn" onclick="" data-dismiss="modal">Cancelar</i>
													<i class="btn-success btn" disabled="disabled" id="btnGuardarCotizacion" onclick="guardarVentaCotizaciones()">Siguiente></i>
												</div>
											</div>
										</div>								
									</div>									
								</div>
								<div id="divFormasPagoCotizaciones" style="display:none">
									<div class="modal-header">
										<h4 class="modal-title">											
										</h4>
									</div>
									<div class="modal-body">
										<div class="form-horizontal">
											<div class="form-group">
												<label  class="col-sm-3 control-label"><strong>Total a pagar:</strong></label>
												<label  class="col-sm-3 control-label"><strong>$ <span id="spnTotalPagarCotiza"></span></strong></label>
												<input readonly="readonly" type="hidden" class="form-control" id="txtSaldoTotalCotiza" name="txtSaldoTotalCotiza" maxlength="100"/>	
											</div>																				
											<div class="form-group">
												<label  class="col-sm-3 control-label">Su pago:</label>
												<div class="col-sm-6">
													<div class="input-group">
														<span class="input-group-addon">$</span>
														<input type="text" class="form-control" id="txtRecibidoCotiza" name="txtRecibidoCotiza" maxlength="100" onkeyup="revisar_cambio_venta_cotizacion()"/>
													</div>
												</div>
											</div> 
											<div class="form-group">
												<label  class="col-sm-3 control-label"><span id="spnTxtCambioCotiza" class="spnTxtCambioCotiza">Cambio:</span></label>
												<div class="col-sm-6">
													<div class="input-group">
														<span class="input-group-addon">$</span>
														<input type="text" disabled="disabled" class="form-control" id="txtCambioCotiza" name="txtCambioCotiza" maxlength="100" value="0"/>
														<input type="hidden" disabled="disabled" class="form-control" id="txtCambioSinComasCotiza" name="txtCambioSinComasCotiza" maxlength="100" value="0"/>												
													</div>
												</div>
											</div>
										</div>	
									</div>
									<div class="modal-footer">
										<i class="btn btn-primary hidden-xs" onclick="regresa_cotizaciones_venta()">
											<i class="fa fa-angle-left"></i>
										</i>
										<i disabled="disabled" id="btnCobroCotizaSimple" class="btn-default btn btnCobroCotizaSimple" data-dismiss="modal" data-dismiss="modal" onclick="cerrar_venta_cotiza_simple()">Cobrar sin ticket</i>										
										<i disabled="disabled" id="btnCobroCotizaTicket" class="btn-success btn btnCobroCotizaTicket" data-dismiss="modal" data-dismiss="modal" onclick="cerrar_venta_cotiza_ticket()">Cobrar e imprimir</i>
									</div>
								</div>								
							</div>
						</div>
					</div>
					<div id="div_articulos"></div>';
							
		return $pagina;
	}
	
?>