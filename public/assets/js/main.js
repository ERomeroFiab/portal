const Main = (function () {
	let module = {};

	module.notify_session_expired = function () {
		setTimeout(function () {
			$.notify({
				icon: "now-ui-icons ui-2_time-alarm",
				message: "<b>La sesión ha caducado</b> <br>Recarga la página y vuelve a iniciar sesión.",
				url: window.location.href.split('#')[0], // This is to relaod the page and redirect to login
				target: '_self'
			}, {
				type: 'warning',
				timer: -1
			});
		}, Globals.SESSION_EXPIRE_TIME_MINUTES * 60 * 1000);
	};

	module.ws_connection_lost_dialog = function (cancel_url) {
		swal({
			title: "Servidor desconectado",
			html: "Se ha perdido la conexion con el servidor.<br>Contacte con el administrador.",
			buttonsStyling: false,
			confirmButtonClass: "btn btn-danger",
			type: "error",
			confirmButtonText: "Reintentar",
		})
		.then(function () { window.location.reload(); })
		.catch(function () { window.location = cancel_url; });
	};

	module.error_dialog = function (error, title, text) {
		if (typeof title !== 'string')
			title = '';
		if (typeof text !== 'string')
			title = '';

		if (!title && !text) {
			switch (error) {
				case 413:
					title = "Archivo demasiado grande";
					text = "Es necesario aplicar <b class='text-primary'>filtros más estrictos</b> <br> que reduzcan el número de entradas.";
					// I used "text-primary" because <b> doesn't work in this template
					break;
				case 400:
					title = "Datos inválidos";
					text = "Los datos introducidos no son válidos.";
					break;
				case 1:
					title = "Descarga inválida";
					text = "Contacta con el administrador.";
					break;
				default:
					title = "Error del servidor (" + error + ")";
					text = "Contacta con el administrador.";
			}
		}
		swal({
			title: title,
			html: text,
			buttonsStyling: false,
			confirmButtonClass: "btn btn-primary",
			type: "error"
		}).catch(swal.noop);
	};

	module.setFormValidation = function (id) {
		$(id).validate({
			highlight: function(element) {
				$(element).closest('.input-group').removeClass('has-success').addClass('has-danger');
				$(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
			},
			success: function(element) {
				$(element).closest('.input-group').removeClass('has-danger').addClass('has-success');
				$(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
			},
			errorPlacement: function(error, element) {
				$(element).append(error);
			},
		});
	};

	/* Remove query parameters from the current url in the browser */
	module.removeURLParams = function () {
		let url = window.location.toString().split('?')[0];
		window.history.replaceState(null, null, url);
	};

	module.bootstrapDefaultDatePickers = function(container, options) {
		$('.datepicker', container).datetimepicker($.extend(true,  {
			// DEFAULT OPTIONS - THEY CAN BE OVERRIDEN IF DEFINED
			format: 'YYYY-MM-DD',
			icons: {
				time: "now-ui-icons tech_watch-time",
				date: "now-ui-icons ui-1_calendar-60",
				up: "fa fa-chevron-up",
				down: "fa fa-chevron-down",
				previous: 'now-ui-icons arrows-1_minimal-left',
				next: 'now-ui-icons arrows-1_minimal-right',
				today: 'fa fa-screenshot',
				clear: 'fa fa-trash',
				close: 'fa fa-remove'
			},
		}, options || {}));

		$('.form-control', container).on("focus", function() {
			$(this).parent('.input-group').addClass("input-group-focus");
		}).on("blur", function() {
			$(this).parent(".input-group").removeClass("input-group-focus");
		});
	};

	module.send_form = function (form) {
		let data = new FormData(form);

		return fetch(form.action, {
			method: form.method || 'POST',
			'Content-Type': 'application/x-www-form-urlencoded',
			body: data,
		}).then(function (response) {
			if (!response.ok)
				throw response;
			return response.json().catch(function () { throw response }).then(function (json) {
				if (json.error)
					throw json;
				else
					return json;
			});
		});
	};


	function isFirefox() {
		return navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
	}

	function downloadFileFirefox(url) {
		let a = document.createElement('a');
		a.hidden = true;
		a.href = url;
		a.download = true;
		document.body.appendChild(a);
		a.click();
		setTimeout(function () { a.remove() }, 500);
	}


	let downloadsQueue = []; // Queue of files (the first one is always already downloaded)
	let intervalId;

	function launchFileDownload(url) {
		if (isFirefox()) {
			// Firefox closes the websockets connection when using window.location
			downloadFileFirefox(url);
		} else {
			window.location = url;
		}
	}

	// Download file, wait 1 second between downloads to prevent browser blocking them.
	module.downloadFile = function downloadFile(url) {
		downloadsQueue.push(url);
		if (downloadsQueue.length === 1) {
			launchFileDownload(url);
			intervalId = setInterval(function () {
				downloadsQueue.shift();
				if (downloadsQueue.length === 0) {
					clearInterval(intervalId);
				} else {
					launchFileDownload(downloadsQueue[0]);
				}
			}, 1000);
		}
	}



	module.seconds_to_human = function (time_seconds) {
		let m, s, ms;
		s = time_seconds;
		if (s > 60) {
			m = Math.floor(s / 60);
			s = s % 60;
		}
		ms = Math.floor((s % 1) * 1000);
		s = Math.floor(s);

		let parts = [];
		if (m > 0) parts.push(m+'min');
		// When the time is > 10 minutes, the num of seconds is irrelevant
		if (time_seconds < 600 && s > 0) parts.push(s+'s');
		// When the time is > 10 seconds, the num of milliseconds is irrelevant
		if (time_seconds < 10 && ms > 0) parts.push(ms+'ms');

		return parts.length ? parts.join(' ') : '0ms';
	};

	module.emptyBlankElements =	function (selector) {
		$(selector || '.empty-placeholder').prev().each(function (i, el) {
			if (!el.innerHTML.trim())
				el.innerHTML = '';
		});
	};
	module.getUserLanguage = function () {
        return navigator.language.split('-')[0].split('_')[0];
    }

	return module;
})();

// Add form validation require to be distinct to another field
$.validator.addMethod("notEqualTo", function(value, element, param) {
	return this.optional(element) || value != $(param).val();
}, "Please specify a different (non-default) value");

// Add support for pattern attribute
$.validator.addMethod("pattern", function(value, element, regexp) {
	// Ensure whole patern match
	if (!regexp.startsWith('^'))
		regexp = '^' + regexp;
	if (!regexp.endsWith('$'))
		regexp = regexp + '$';

	let re = new RegExp(regexp);
	return this.optional(element) || re.test(value);
}, "Please check your input.");



Main.notify_session_expired();
