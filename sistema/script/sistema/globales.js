
/* ------------------------- OBJETO AJAX ------------------------------ */

function crea_objetoAjax(controlador, parametros)
{
	var xmlhttp;
	
	if(window.XMLHttpRequest){
	  xmlhttp = new XMLHttpRequest();
	}else{
	  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.open("POST",controlador,true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(parametros);

	return xmlhttp;
}

/* ------------------------- VALIDAR TEXTO MALINTENCIONADO ------------------------------ */
function validar(campo){
	if(campo.type == "text" || campo.type == "password" || campo.tagName == "TEXTAREA"){
		campo.value = unescape(campo.value);
		
		campo.value = campo.value.replace(/'/gi,"&lsquo;",campo.value);
		campo.value = campo.value.replace(/"/gi,"&ldquo;",campo.value);
		campo.value = campo.value.replace(/</gi,"&lt;",campo.value);
		campo.value = campo.value.replace(/>/gi,"&gt;",campo.value);
		
		if(campo.value != "")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}



function validar_contenido(campo,tipo){
	switch(tipo){
		case "numero":
			setTimeout(
			function(){
				var regexp = /^\d*\.?\d*$/;
				var nstr='';
				for(var i=0;i<campo.value.length;i++)
				{
					if(regexp.test(campo.value.charAt(i)))
					{
						nstr+=campo.value.charAt(i);
					}
				}
				campo.value=nstr;},50
			);
			return true;
		break;
		
		default:
		
		setTimeout(
			function(){
				var regexp = /^\d*\.?\d*$/;
				var nstr='';
				for(var i=0;i<campo.value.length;i++){
					if(regexp.test(campo.value.charAt(i))){
						nstr+=campo.value.charAt(i);
					}
				}
				campo.value=nstr;},50
			);
			return true;
		break;
	}
}

function validaEntero(numero)
{
	if (!/^([0-9])*$/.test(numero))
		return false;
	else
		return true;
}

function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}