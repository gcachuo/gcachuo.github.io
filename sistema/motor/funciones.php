<?php

	//ESTILOS Y SCRIPTS OPCIONALES
	function estiloNuevaEmpresa()
	{
		$styleNewCompany= '	<!--<link rel="stylesheet" href="../css/style.css">
							<link rel="stylesheet" href="../css/styles/form.css">-->
							<link rel="stylesheet" href="../css/bootstrap/datepicker.css">
							<link rel="stylesheet" href="../css/google-code-prettify/prettify.css">
							<link rel="stylesheet" href="../css/chosen/chosen.css" />
							<link rel="stylesheet" href="../css/lista/listas.css">
							<link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
							<link rel="stylesheet" href="../css/bootstrap/bootstrap-responsive.css">';
		
		return $styleNewCompany;
	}
	
	function scriptNuevaEmpresa()
	{
		$scriptNuevaEmpresa = '	<script type="text/javascript">
									contenido_textarea = "";
									num_caracteres_permitidos = 180;
									contenido_textarea_doble = "";
									num_caracteres_permitidos_doble = 600;
									
									function valida_longitud()
									{
									   num_caracteres = document.getElementById(\'descripcionEmpresa\').value.length;		
									   if (num_caracteres > num_caracteres_permitidos)
									   {
										  document.getElementById(\'descripcionEmpresa\').value = contenido_textarea;
									   }
									   else
									   {
										  contenido_textarea = document.getElementById(\'descripcionEmpresa\').value;
									   }
									}
									
									function valida_longitud_doble()
									{
									   num_caracteres_dobles = document.getElementById(\'descripcionLargaEmpresa\').value.length;		
									   if (num_caracteres_dobles > num_caracteres_permitidos_doble)
									   {
										  document.getElementById(\'descripcionLargaEmpresa\').value = contenido_textarea_doble;
									   }
									   else
									   {
										  contenido_textarea_doble = document.getElementById(\'descripcionLargaEmpresa\').value;
									   }
									}
								</script>								 
								<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
								<script>window.jQuery || document.write(\'<script src="../js/jquery.js"><\/script>\')</script>
								<script type="text/javascript" src="../js/lista/listas.js"></script>
								<script type="text/javascript" src="../js/lista/servicios.js"></script>
								<script src="../js/bootstrap/plugins.js"></script>
								<script src="../js/bootstrap/bootstrap-dropdown.js"></script>
								<script src="../js/bootstrap/bootstrap-scrollspy.js"></script>
								<script src="../js/bootstrap/bootstrap-tab.js"></script>
								<script src="../js/bootstrap/bootstrap-collapse.js"></script>
								<script src="../js/scripts.js"></script>
								<script src="../js/libs/modernizr.custom.js"></script>
								<script src="../css/chosen/chosen.jquery.js" type="text/javascript"></script>
								<script src="../js/google-code-prettify/prettify.js"></script>  
								<script src="../js/setup2.js"></script>
								<script src="../js/bootstrap/bootstrap-datepicker.js"></script>
								<script type="text/javascript">        
									$(function(){
										window.prettyPrint && prettyPrint();
										$(\'#dpd1\').datepicker({
										format: \'dd-mm-yyyy\',
										string: \'es\'
										});
										$(\'#dpd2\').datepicker({
										format: \'dd-mm-yyyy\',
										string: \'es\'
										});
										
										// disabling dates
										var nowTemp = new Date();
										var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
										var checkin = $(\'#dpd1\').datepicker({
										onRender: function(date) {
										return date.valueOf() < now.valueOf() ? \'disabled\' : \'\';
										}
										}).on(\'changeDate\', function(ev) {
										if (ev.date.valueOf() > checkout.date.valueOf()) {
										var newDate = new Date(ev.date)
										newDate.setDate(newDate.getDate() + 1);
										checkout.setValue(newDate);
										}
										checkin.hide();
										$(\'#dpd2\')[0].focus();
										}).data(\'datepicker\');
										var checkout = $(\'#dpd2\').datepicker({
										onRender: function(date) {
										return date.valueOf() <= checkin.date.valueOf() ? \'disabled\' : \'\';
										}
										}).on(\'changeDate\', function(ev) {
										checkout.hide();
										}).data(\'datepicker\');
										});
								
										var config = {
										  \'.chzn-select\'           : {},
										  \'.chzn-select-deselect\'  : {allow_single_deselect:true},
										  \'.chzn-select-no-single\' : {disable_search_threshold:10},
										  \'.chzn-select-no-results\': {no_results_text:\'No se encontro nada!\'},
										  \'.chzn-select-width\'     : {width:"95%"}
										}
										for (var selector in config) {
										  $(selector).chosen(config[selector]);
										}
								</script>  ';
		
		return $scriptNuevaEmpresa;	
	}

?>