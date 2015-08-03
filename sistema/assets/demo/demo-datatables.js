// -------------------------------
// Initialize Data Tables
// -------------------------------

$(document).ready(function() {
    $('.datatables').dataTable({
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
		"iDisplayLength": 200,
		"oLanguage": {
		  "sLengthMenu": 'Mostrar <select>'+
			'<option value="50">50</option>'+
			'<option value="100">100</option>'+
			'<option value="150">150</option>'+
			'<option value="200">200</option>'+
			'</select> registros'
		}
       /*"oLanguage": {
            "sLengthMenu": "_MENU_ registros por página",
            "sSearch": ""
        }
*/    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Buscador...');
    $('.dataTables_length select').addClass('form-control');});