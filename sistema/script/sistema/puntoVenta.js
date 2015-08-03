$(document).ready(function()
{	
	cargar_categorias();
	cargar_clientes();		
	$("#codigoProd").focus();
	$("#codigoProd").keypress(function(event){ 
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == "13")
		{
			carga_producto();
		}	
	});												
});

$("#tableBodyScroll").niceScroll();
$("#tabArticulos").niceScroll();
$("#tabClientes").niceScroll();
$("#tabRecibos").niceScroll();
$("#id_estado").change(function () 
{
	$("#formCiudad").css("display","none");
	$("#id_estado option:selected").each(function () 
	{
		elegido=$(this).val();
		$.post("ciudadesAjax.php", { elegido: elegido }, function(data){
		$("#id_ciudad").html(data);
		$("#formCiudad").css("display","block");								  
		});			
	});
});

function carga_producto()
{
	$("#carga_descripcion").html("");
	$("#carga_precio").html("");
	var cantidadProd = $("#cantidadProd").val();
	var codigoProd = $("#codigoProd").val();
	if (codigoProd!=""  && cantidadProd!="")
	{
		var dataString = "codigoProd="+ codigoProd;
		$.ajax
		({
			type: "POST",
			url: "carga_precio.php",
			data: dataString,
			cache: false,
			dataType: "json",
			success: function(data)
			{	
				$("#precioPrdo").val(data.precio);
				$("#carga_precio").html(data.precio);	
				$("#carga_descripcion").html(data.nombre);
				agrega_venta();
				agregar_totales();
			}			
		});																			
	}								
}

function agrega_venta()
{
	var var_codigo = $("#codigoProd").val();
	var var_cantidad = $("#cantidadProd").val();
	var var_precio = $("#precioPrdo").val();
	var var_iva = $("#factorIvaVenta").val();

	if (var_codigo!="" && var_cantidad!="" && var_precio !="")
	{	
		$("#div_articulos").load("guarda_salida.php",{codigo:var_codigo,cantidad:var_cantidad,precio:var_precio,iva:var_iva});
		$("#ticket").load("detalles_salida.php");
		$("#btnEliminar").prop("disabled", false);
		$("#btnDesuento").prop("disabled", false);
		$("#btnPagar").prop("disabled", false);
	}									
}

function agregar_totales()
{
	var codigoProd = $("#codigoProd").val();
	var dataString = "codigoProd="+ codigoProd;
	$.ajax
		({
			type: "POST",
			url: "totales_salida.php",
			data: dataString,
			cache: false,
			dataType: "json",
			success: function(data)
			{	
				var importe_totales = data.importe;																								
				var total_totales = data.total;
				var iva_totales =  total_totales - importe_totales;
				importe_totales = parseFloat(importe_totales).toFixed(2);
				total_totales = parseFloat(total_totales).toFixed(2);
				iva_totales = parseFloat(iva_totales).toFixed(2); 
				
				importe_totales = addCommas(importe_totales);
				total_totales = addCommas(total_totales);
				iva_totales = addCommas(iva_totales);
				$("#subTxt").html(importe_totales);	
				$("#ivaTxt").html(iva_totales);
				$("#totalTxt").html(total_totales);
			}			
		});	
										
	$("#codigoProd").val("");
	$("#cantidadProd").val("1");
	$("#precioPrdo").val("");
	$("#carga_precio").html("");	
	$("#carga_descripcion").html("");
	
	$("#codigoProd").focus();
}

function eliminar_detalle(idDetalle)
{
	var var_iva = $("#factorIvaVenta").val();
	$("#div_articulos").load("eliminar_detalle.php",{idDetalle:idDetalle,iva:var_iva});
	$.ajax
	({
		type: "POST",
		url: "totales_salida.php",
		cache: false,
		dataType: "json",
		success: function(data)
		{	
			var importe_totales = data.importe;																								
			var total_totales = data.total;
			var iva_totales =  total_totales - importe_totales;
			importe_totales = parseFloat(importe_totales).toFixed(2);
			total_totales = parseFloat(total_totales).toFixed(2);
			iva_totales = parseFloat(iva_totales).toFixed(2); 
			
			importe_totales = addCommas(importe_totales);
			total_totales = addCommas(total_totales);
			iva_totales = addCommas(iva_totales);
			$("#subTxt").html(importe_totales);	
			$("#ivaTxt").html(iva_totales);
			$("#totalTxt").html(total_totales);
		}			
	});	
	$("#ticket").load("detalles_salida.php");
	if(total_totales == 0)
	{
		$("#btnEliminar").prop("disabled", true);
		$("#btnDesuento").prop("disabled", true);
		$("#btnPagar").prop("disabled", true);
	}
	$("#codigoProd").val("");
	$("#cantidadProd").val("1");
	$("#precioPrdo").val("");
	$("#carga_precio").html("");	
	$("#carga_descripcion").html("");
	
	$("#codigoProd").focus();	
}

function eliminar_salida()
{
	$("#div_articulos").load("eliminar_salida.php");
	$("#ticket").html("");
	$("#codigoProd").val("");
	$("#cantidadProd").val("1");
	$("#precioPrdo").val("");
	$("#carga_precio").html("");	
	$("#carga_descripcion").html("");
	$("#subTxt").html("0.00");	
	$("#ivaTxt").html("0.00");
	$("#totalTxt").html("0.00");
	
	$("#codigoProd").focus();
	
}

function cargar_categorias()
{
	$("#tabArticulos").load("cargar_categorias.php");	
}

function cargar_clientes()
{
	$("#tabClientes").load("cargar_clientes.php");	
}

function cargar_subCategorias(idCat)
{
	$("#tabArticulos").load("cargar_subCategorias.php",{idCat:idCat});	
}

function nueva_categorias()
{
	var var_nivel = $("#nivelCat").val();
	var var_padre = $("#padreCat").val();
	var nombreCatMod = $("#nombreCatMod").val();
	var descCatMod = $("#descCatMod").val();
	$("#div_articulos").load("nueva_categorias.php",{nivel:var_nivel,idPadre:var_padre,nombreCatMod:nombreCatMod,descCatMod:descCatMod});
}

function nueva_subCategorias()
{
	var var_nivel = $("#nivelCat").val();
	var var_padre = $("#padreCat").val();
	var nombreCatMod = $("#nombreCatMod").val();
	var descCatMod = $("#descCatMod").val();
	$("#div_articulos").load("nueva_subCategorias.php",{nivel:var_nivel,idPadre:var_padre,nombreCatMod:nombreCatMod,descCatMod:descCatMod});
}

function cargar_cliente(idCliente)
{
	var idCliente = "idCliente="+ idCliente;
	$.ajax
	({
		type: "POST",
		url: "guardar_cliente.php",
		data: idCliente,
		cache: false,
		dataType: "json",
		success: function(data)
		{	
			$("#idCli").val(data.id);	
			$("#nombreCliente").html(data.nombre);
		}			
	});	
}

function nuevo_cliente()
{
	var nombre = $("#nombreCliente").val();
	var razon = $("#razon").val();
	var rfc = $("#rfcCliente").val();
	var calle = $("#calleCliente").val();
	var numExt = $("#numExtCliente").val();
	var numInt = $("#numIntCliente").val();
	var colonia = $("#coloniaCliente").val();
	var cp = $("#cpCliente").val();
	var ciudad = $("#id_ciudad").val();
	var contacto = $("#nombreContactoCliente").val();
	var correo = $("#correoCliente").val();
	var lada = $("#ladaCliente").val();
	var tel = $("#telCliente").val();
	
	if (nombre!="" && correo!="")
	{
		$("#div_articulos").load("nueva_categorias.php",
		{
				nombre:nombre,razon:razon,rfc:rfc,calle:calle,
				numExt:numExt,numInt:numInt,colonia:colonia,cp:cp,
				ciudad:ciudad,contacto:contacto,lada:lada,tel:tel
		});
	}
}
								