function Dialog() {
	this._init.apply(this, arguments);
}

Dialog.prototype = {

		_init: function(websocketServerUri, uploadUrl, user, appid) {
			this.websocket_server = websocketServerUri;
			this.uploadUrl = uploadUrl;
			this.username = user;
			this.appid = appid;
		},

		findLinkFromButton: function(btn) {
			let list_item = btn.closest('.list-group-item');

			if ((list_item.tagName === 'a' || list_item.tagName === 'A') && list_item.href && list_item.href.length > 1) {
				return list_item.href;
			}

			let a = list_item.querySelector('a[href]:not([href="#"])');

			if (a) {
				return a.href;
			} else {
				return null;
			}
		},

		createLinkButton: function (text, link, type, icon) {
			let icon_tag = icon ?  "<i>class=" + icon + "></i>" : "";
				//<i class="far fa-file-excel"></i>
			let type_class = type ? 'btn-'+type : '';
			return widgets.renderElement({
				tag: 'a',
				attrs: { href: link, className: 'btn my-0 '+type_class },
				children: [
					{
						tag: 'i',
						attrs: {
							className: icon ? icon + " mr-2": "" ,
						},
					},
					String(text),
				],
			});
		},

		createDateInputGroup: function(label, date, date_fmt) {
			date_fmt = date_fmt || 'YYYY-MM-DD';
			let container = widgets.renderElement({
				attrs: { className: 'form-group' },
				children: [
					label && {
						tag: 'label',
						children: String(label),
					},
					{
						attrs: { className: 'input-group datepicker' },
						children: [
							{
								attrs: { className: 'input-group-prepend' },
								children: [
									{
										tag: 'span',
										attrs: { className: 'input-group-text' },
										children: [
											{ tag: 'i', attrs: { className: 'now-ui-icons ui-1_calendar-60' } },
										]
									},
								]
							},
							{
								tag: 'input',
								attrs: {
									className: 'form-control',
									autocomplete: 'off',
									required: true,
									placeholder: date_fmt,
									value: moment(date, 'YYYY-MM-DD').format(date_fmt),
								}
							}
						]
					},
				]
			});

			$('input', container).on("focus", function() {
				$(this).parent(".input-group").addClass("input-group-focus")
			}).on("blur", function() {
				$(this).parent(".input-group").removeClass("input-group-focus")
			}).datetimepicker({
				format: date_fmt,
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
				}
			});
			return container;
		},

		renderCheckBox: function(name, text) {
			return widgets.renderElement({
				attrs: { className: 'form-check form-check-inline' },
				children: {
					tag: 'label',
					attrs: { className: 'form-check-label' },
					children: [
						{ tag: 'input', attrs: { type: 'checkbox', className: 'form-check-input', name: name } },
						{ tag: 'span', attrs: { className: 'form-check-sign' } },
						String(text),
					]
				}
			});
		},

		createFileInput: function(accept_input) {
			let finput = new widgets.DNDFileInput({
				multiple: true,
				accept: accept_input,
			}, { max_files: Globals.MAX_FILE_UPLOADS });
			finput.element().style.maxHeight = '48vh';
			return finput;
		},

		createProgressBar: function() {
			return new widgets.ProgressBar({ animated: true });
		},

 		initTableUploadDialog: function(info) {
			let button = document.getElementById(info.key+'-btn');
			if (!button) {
				return;
			}

			button.addEventListener('click', function (event) {
				event.preventDefault();
				let dialog = new widgets.WebSocketDialog({
					websocket: this.websocket_server,
					open: true,
					title: info.title,
					submitButtonText: info.submitButtonText || "Subir",
				});

				let view_table_btn;
				if (info.table_url) {
					view_table_btn = this.createLinkButton("Ver tabla", info.table_url, 'info');
					view_table_btn.hidden = true;
				}

				let download_template_btn;
				if (info.template) {
					download_template_btn = this.createLinkButton("Plantilla XLSX", info.template, 'info','far fa-file-excel');
					download_template_btn.setAttribute('download', '');
				}

				let fileInput = this.createFileInput(info.accept_input);
				let uploadProgressBar = this.createProgressBar();
				let dialog_body = widgets.renderElement({
					children: [
						fileInput.element(),
						uploadProgressBar.element(),
						{
							attrs: { className: 'd-flex' },
							children: [
								{
									attrs: { className: 'flex-grow-1' }
								},
								{
									attrs: { className: 'clear-table-container' },
									children: this.renderCheckBox('clear-table', "Vaciar tabla"),

								}
							]
						}
					]
				});
				if (info.delete_table === false) {
					dialog_body.querySelector('.clear-table-container').hidden = true;
				}

				uploadProgressBar.hide();
				fileInput.on('change', function () {
					dialog.enableSubmitbuttons();
				});
				dialog.on('dialog-created', function () {
					this.enableSubmitbuttons(false);
					this.addElementToBody(dialog_body);
					if (view_table_btn) {
						this.addButton(view_table_btn);
					}
					if (download_template_btn) {
						this.addButton(download_template_btn,'left');
					}
				});

				dialog.on('submit', function () {
					if (download_template_btn) download_template_btn.hidden = true;
					dialog.enableClosing(false);
					dialog.enableInputs(false);
					fileInput.hideDropZone();

					dialog.writeToScreen("Subiendo archivos...");
					fileInput.uploadFiles(
						'upload',
						this.uploadUrl,
						function onUploadComplete(event) {
							let xhr = event.target;
							let path = JSON.parse(xhr.response);

							uploadProgressBar.setProgress(1);
							uploadProgressBar.animate(false);
							uploadProgressBar.setStyle('success');
							uploadProgressBar.hide();

							dialog.writeToScreen("Archivos subidos exitosamente");
							dialog.sendMessage({
								appid: this.appid,
								type: info.type,
								user: this.username,
								timestamp: Date.now(),
								action: info.key,
								delete: dialog_body.querySelector('[name=clear-table]').checked,
								input_paths: path,
							});
						}.bind(this),
						function onUploadProgress (event) {
							uploadProgressBar.setProgress(event.loaded / event.total);
						},
						function onUploadError (event) {
							dialog.writeToScreen("err: Error al subir los archivos seleccionados.");
							dialog.enableClosing();
							uploadProgressBar.animate(false);
							uploadProgressBar.setStyle('danger');

							let xhr = event.target;
							let errorMessage;
							try {
								let jsonError = JSON.parse(xhr.responseText);
								if (typeof jsonError.error === 'string')
									errorMessage = jsonError.error;
							} catch (e) { /* Ignore */}
							switch (xhr.status) {
								case 401: window.location.reload(); return; // Session expired
								case 413:
									dialog.writeToScreen("warn: Archivo demasiado grande.");
									break;
								case 415:
									dialog.writeToScreen("err: Formato de archivo incorrecto");
									break;
								default:
									dialog.writeToScreen('err:' + xhr.status + ' - ' + (errorMessage || xhr.statusText));
									dialog.writeToScreen("err: Se ha producido un error, por favor contacte con un administrador.");
							}
						}
					); // End upload call
				}.bind(this)); // End on submit event

				dialog.on('ws-close', function (error) {
					if (!error) {
						if (view_table_btn) view_table_btn.hidden = false;
						// Refresh counters and stuff on dialog close by reloading the page
						dialog.on('closed', function () { window.location.reload() });
					}
				});
			}.bind(this)); // End button click
		},

		pdPdfImportUploadDialog: function(info) {
			let button = document.getElementById(info.key+'-btn');
			if (!button) {
				return;
			}

			button.addEventListener('click', function (event) {
				let template_select = document.getElementById(info.template_select);
				event.preventDefault();
				let dialog = new widgets.WebSocketDialog({
					websocket: this.websocket_server,
					open: true,
					title: info.title + template_select[template_select.selectedIndex].text,
					submitButtonText: info.submitButtonText || "Subir",
				});

				let view_table_btn;
				if (info.table_url) {
					view_table_btn = this.createLinkButton("Ver tabla", info.table_url, 'info');
					view_table_btn.hidden = true;
				}

				let download_template_btn;
				if (info.template) {
					let new_template = info.template + template_select[template_select.selectedIndex].text + '.xlsx';
					download_template_btn = this.createLinkButton("Plantilla XLSX", new_template, 'info','far fa-file-excel');
					download_template_btn.setAttribute('download', '');
				}

				let fileInput = this.createFileInput(info.accept_input);
				let uploadProgressBar = this.createProgressBar();
				let dialog_body = widgets.renderElement({
					children: [
						fileInput.element(),
						uploadProgressBar.element(),
						{
							attrs: { className: 'd-flex' },
							children: [
								{
									attrs: { className: 'flex-grow-1' }
								},
								{
									attrs: { className: 'clear-table-container' },
									children: this.renderCheckBox('clear-table', "Vaciar tabla"),

								}
							]
						}
					]
				});
				if (info.delete_table === false) {
					dialog_body.querySelector('.clear-table-container').hidden = true;
				}

				uploadProgressBar.hide();
				fileInput.on('change', function () {
					dialog.enableSubmitbuttons();
				});
				dialog.on('dialog-created', function () {
					this.enableSubmitbuttons(false);
					this.addElementToBody(dialog_body);
					if (view_table_btn) {
						this.addButton(view_table_btn);
					}
					if (download_template_btn) {
						this.addButton(download_template_btn,'left');
					}
				});

				dialog.on('submit', function () {
					if (download_template_btn) download_template_btn.hidden = true;
					dialog.enableClosing(false);
					dialog.enableInputs(false);
					fileInput.hideDropZone();

					dialog.writeToScreen("Subiendo archivos...");
					fileInput.uploadFiles(
						'upload',
						this.uploadUrl,
						function onUploadComplete(event) {
							let xhr = event.target;
							let path = JSON.parse(xhr.response);

							uploadProgressBar.setProgress(1);
							uploadProgressBar.animate(false);
							uploadProgressBar.setStyle('success');
							uploadProgressBar.hide();

							dialog.writeToScreen("Archivos subidos exitosamente");
							dialog.sendMessage({
								appid: this.appid,
								type: info.type,
								user: this.username,
								timestamp: Date.now(),
								action: info.key,
								pdf_template: template_select.value,
								delete: dialog_body.querySelector('[name=clear-table]').checked,
								input_paths: path,
							});
						}.bind(this),
						function onUploadProgress (event) {
							uploadProgressBar.setProgress(event.loaded / event.total);
						},
						function onUploadError (event) {
							dialog.writeToScreen("err: Error al subir los archivos seleccionados.");
							dialog.enableClosing();
							uploadProgressBar.animate(false);
							uploadProgressBar.setStyle('danger');

							let xhr = event.target;
							let errorMessage;
							try {
								let jsonError = JSON.parse(xhr.responseText);
								if (typeof jsonError.error === 'string')
									errorMessage = jsonError.error;
							} catch (e) { /* Ignore */}
							switch (xhr.status) {
								case 401: window.location.reload(); return; // Session expired
								case 413:
									dialog.writeToScreen("warn: Archivo demasiado grande.");
									break;
								case 415:
									dialog.writeToScreen("err: Formato de archivo incorrecto");
									break;
								default:
									dialog.writeToScreen('err:' + xhr.status + ' - ' + (errorMessage || xhr.statusText));
									dialog.writeToScreen("err: Se ha producido un error, por favor contacte con un administrador.");
							}
						}
					); // End upload call
				}.bind(this)); // End on submit event

				dialog.on('ws-close', function (error) {
					if (!error) {
						if (view_table_btn) view_table_btn.hidden = false;
						// Refresh counters and stuff on dialog close by reloading the page
						dialog.on('closed', function () { window.location.reload() });
					}
				});
			}.bind(this)); // End button click
		},

		initConversionesPosiblesActivosDialog : function(key, petitionDate) {
			let info = {
				'conversiones': {
					title: "Filtro de conversiones",
					info: "Se obtendrán los casos de conversiones de contratos temporales en indefinidos (contratos acabados en 9) comparando la remesa de vidas laborales de la ronda actual con la de la ronda anterior.",
				},
				'posibles-activos': {
					title: "Filtro de posibles activos",
					info: "Se obtendrán los casos de posibles activos (nuevas altas en los últimos 30 días) comparando la remesa de vidas laborales de la ronda actual con la de la ronda anterior.",
				},
				'filtrado-bajas': {
                    title: "Filtro de bajas",
                    info: "Se obtendrán las bajas para realizar peticiones.",
                },
                'incremento-neto': {
                    title: "Filtro de incremento neto",
                    info: "Se obtendrá el incremento neto de los posibles activos, conversiones y bajas de los últimos 30 días.",
                },
			};

			if (!(key in info)) {
				console.error("Key not supported: " + key);
				return;
			}

			let button = document.getElementById(key+'-btn');
			if (!button) {
				return;
			}

			button.addEventListener('click', function (event) {
				event.preventDefault();
				let dialog = new widgets.WebSocketDialog({
					websocket: this.websocket_server,
					open: true,
					title: info[key].title,
					submitButtonText: "Filtrar casos",
				});

				let dialog_body_content = widgets.renderElement({
					attrs: { className: 'row' },
					children: [
						// {
						// 	attrs: { className: 'col-sm-6' },
						// 	children: this.createDateInputGroup("Fecha de petición", petitionDate, 'YYYY-MM-DD'),
						// },
						{
							attrs: {className: 'col-12' },
							children: [
								info[key].info && {
									attrs: { className: 'blockquote-info mb-3' },
									children: info[key].info
								},
								{
									attrs: { className: 'text-secondary mb-2' },
									children: [
										widgets.renderIcon('fas fa-info-circle mr-2'),
										"Sólo se tendrán en cuenta los casos cuyo contrato sea subvencionable en la provincia a la que pertenece su CCC.",
									]
								}
							]
						},
					]
				});
				let view_table_btn = this.createLinkButton("Ver tabla", this.findLinkFromButton(button), 'info');
				view_table_btn.hidden = true;
				dialog.on('dialog-created', function () {
					this.enableSubmitbuttons(false);
					this.addElementToBody(dialog_body_content);
					this.addButton(view_table_btn);
				});

				dialog.on('ws-open', function () {
					dialog.enableSubmitbuttons();
				});

				dialog.on('submit', function () {
					dialog.sendMessage({
						appid: this.appid,
						type: 'subvenciones',
						user: this.username,
						timestamp: Date.now(),
						action: key,
						// date: dialog_body_content.querySelector('input').value,
					});
					dialog.enableClosing(false);
					dialog.enableInputs(false);
					dialog_body_content.hidden = true;
				}.bind(this));

				dialog.on('ws-close', function (error) {
					if (!error) {
						view_table_btn.hidden = false;
						// Refresh counters and stuff on dialog close by reloading the page
						this.on('closed', function () { window.location.reload() });
					}
				});
			}.bind(this));
		},
}
