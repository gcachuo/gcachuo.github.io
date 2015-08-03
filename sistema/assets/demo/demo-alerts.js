
function show_rich() {
	$.pnotify({
		title: '<span style="color: red;">Rich Content Notice</span>',
		type: 'success',
		text: '<span style="color: blue;">Look at my beautiful <strong>strong</strong>, <em>emphasized</em>, and <span style="font-size: 1.5em;">large</span> text.</span>'
	});
}

function dyn_notice() {
	var percent = 0;
	var notice = $.pnotify({
		title: "Por favor espere",
		type: 'info',
		icon: 'icon-spin icon-refresh',
		hide: false,
		closer: false,
		sticker: false,
		opacity: 0.75,
		shadow: false,
		width: "200px"
	});

	setTimeout(function() {
		notice.pnotify({
			title: false
		});
		var interval = setInterval(function() {
			percent += 5;
			var options = {
				text: percent + "% completado."
			};
			if (percent == 80) options.title = "Falta poco";
			if (percent >= 100) {
				window.clearInterval(interval);
				options.title = "Listo!";
				options.type = "success";
				options.hide = true;
				options.closer = true;
				options.sticker = true;
				options.icon = 'icon-ok';
				options.opacity = 1;
				options.shadow = true;
				options.width = $.pnotify.defaults.width;
				validarLogIn();
			}
			notice.pnotify(options);			
		}, 120);
	}, 2000);
}

function dyn_notice2() {
	var percent = 0;
	var notice = $.pnotify({
		title: "Cambiando de sucursal",
		type: 'info',
		icon: 'icon-spin icon-refresh',
		hide: false,
		closer: false,
		sticker: false,
		opacity: 0.75,
		shadow: false,
		width: "200px"
	});

	setTimeout(function() {
		notice.pnotify({
			title: false
		});
		var interval = setInterval(function() {
			percent += 5;
			var options = {
				text: percent + "% completado."
			};
			if (percent == 80) options.title = "Falta poco";
			if (percent >= 100) {
				window.clearInterval(interval);
				options.title = "Listo!";
				options.type = "success";
				options.hide = true;
				options.closer = true;
				options.sticker = true;
				options.icon = 'icon-ok';
				options.opacity = 1;
				options.shadow = true;
				options.width = $.pnotify.defaults.width;
				validarSucursal();
			}
			notice.pnotify(options);			
		}, 120);
	}, 2000);
}
