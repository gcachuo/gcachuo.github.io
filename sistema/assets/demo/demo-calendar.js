// Demo for FullCalendar with Drag/Drop internal

$(document).ready(function() 
{
	$('#calendar-drag').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			allDayDefault: false,
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: {
				url: 'php/get-events.php',
				error: function() {
					$('#script-warning').show();
				}
			},
			eventClick: function(calEvent) {
				if (confirm("Desea eliminar este evento")) 
				{
					$("#div_articulos").load("../seccion/ajax/eliminarEvento.php",
					{idEvento:calEvent.id},
					 function(){
						 renderCalendar();
						 restaura_eventos();
					  });
				}
			}
	});	
});