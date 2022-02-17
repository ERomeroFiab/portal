

let widgets = (function () {
	// Documentation: https://nodejs.org/api/events.html
	let EventEmitter = EventEmitter3;

	function PreparedObject() {
		EventEmitter.call(this);
	}

	PreparedObject.prototype = {
		__proto__: EventEmitter.prototype,

		// Proxy des not exist in IE11
		// parent: function () {
		// 	let current = this;
		// 	return new Proxy(this.__proto__.__proto__, {
		// 		/**
		// 		 * @param target   Same as obj.
		// 		 * @param receiver Left-hand side of dot operator.
		// 		 */
		// 		'get': function (target, key, receiver) {
		// 			const result = target[key];  // (*)
		// 			if (typeof result !== 'function') {
		// 				return result;
		// 			}
		// 			return result.bind(current);
		// 		},
		// 	});
		// },
	}

	/**
	 * [Dialog description]
	 * @param       {object} options
	 *         - {string} title: Dialog title or empty (optional)
	 *         - {bool} open: Open the dialog just after calling constructor (default true)
	 *         - {string} size: Dialog size: sm, md, lg. (default md)
	 *         - {string} submitButtonText: Text of the submit button. The submit button will not show if empty.
	 * @constructor
	 */
	function Dialog(options) {
		PreparedObject.call(this);

		if (!options) options = {};
		options.open = options.open !== false; // Default true unless it is false
		options.size = options.size || 'md';
		options.submitButtonText = options.submitButtonText,
		this.options = options;

		this._body_els = [];
		this._buttons = [];

		this.canClose = true;
		this._onFormSubmitBound = null;

		this.dialog = null;

		if (this.options.open) {
			setTimeout(this.open.bind(this), 1); // do it later
		}

		window.addEventListener('beforeunload', function(event) {
			if (!this.canClose) {
				return event.returnValue = true;
			}
		}.bind(this));
	}

	Dialog.prototype = {
		__proto__: PreparedObject.prototype,

		destroy: function () {
			if (this._onFormSubmitBound) {
				this.form.removeEventListener('submit', this._onFormSubmitBound);
				this._onFormSubmitBound = null;
			}
			this.form = null;

			if (this.dialog) {
				$(this.dialog).modal('dispose');
				this.dialog.parentNode.removeChild(this.dialog);
				this.dialog = null;
			}
			this.emit('closed');
		},

		open: function (open) {
			if (this.dialog) {
				$(this.dialog).modal();
			} else {
				this._renderDialog();
			}
			if (!this._onFormSubmitBound) {
				this._onFormSubmitBound = this._onFormSubmit.bind(this);
				this.form.addEventListener('submit', this._onFormSubmitBound);
			}
			this.emit('open');
		},

		close: function () {
			if (!this.dialog)
				return;
			$(this.dialog).modal('hide');
			this.emit('closed');
		},

		addElementToBody: function (element) {
			this._body_els = element;

			if (!this.dialog) // We'll add it later
				return;

			// If the dialog was already created, add the button to the html
			let body = this.dialog.querySelector('.my-dialog-body');
			body.appendChild(element);
		},

		addButton: function (button, zone) {
			this._buttons.push(button);

			if (!this.dialog) // We'll add it later
				return;

			// If the dialog was already created, add the button to the html
			let container = this.dialog.querySelector(zone == 'left' ? '.btns-zone-left' : '.btns-zone-right');
			container.appendChild(button);
		},

		enableInputs: function (enable) {
			$('.my-dialog-body :input', this.dialog).prop('disabled', enable === false);
			$('.my-dialog-body').animate({ opacity: enable ? 1 : .5 });
		},

		setTitle: function(title) {
			this.options.title = title;
			if (!this.dialog)
				return; // Yet to be created, we'll add the title later

			let ts = this.dialog.querySelectorAll('.modal-title');
			for (let i = 0; i < ts.length; i++) {
				ts[i].textContent = title;
			}
		},

		enableClosing: function (enable) {
			this.canClose = enable !== false;
			let config = $(this.dialog).data('bs.modal')._config;

			// prevents modal from closing by presing ESC
			config.keyboard = this.canClose;
			// prevents modal from closing by cliking in the background
			config.backdrop = this.canClose || 'static';

			let dismiss_btns = this.dialog.querySelectorAll('[data-dismiss=modal]');
			for (var i = 0; i < dismiss_btns.length; i++) {
				dismiss_btns[i].disabled = !this.canClose;
			}
		},

		_renderDialog: function () {
			this.dialog = this._createDialogHTML();
			this.form = this.dialog.querySelector('form');
			this.form.addEventListener('submit', function (event) { event.preventDefault() });
			document.body.appendChild(this.dialog);
			$(this.dialog).modal()
				.on('hidden.bs.modal', this.destroy.bind(this));
			this.emit('dialog-created');
		},

		_createDialogHTML: function () {
			return renderElement({
				attrs: { className: 'modal fade', tabindex: -1, role: 'dialog', },
				children: [
					{
						attrs: { className: 'modal-dialog'+(this.options.size==='lg' ? ' modal-lg' : ''), role: 'document', },
						children: [
							{
								tag: 'form',
								attrs: { className: 'modal-content modal-form', },
								children: [
									{
										attrs: { className: 'modal-header d-flex align-items-center', },
										children: [
											{
												tag: 'h5',
												attrs: { className: 'modal-title text-truncate mt-0 flex-grow-1', },
												children: this.options.title ? String(this.options.title) : '',
											},
										]
									},
									{
										attrs: { className: 'modal-body d-flex flex-column my-dialog-body', style: 'max-height: 60vh' },
										children: this._body_els, // Add content here
									},
									{
										attrs: { className: 'modal-footer', },
										children: [
											{
												attrs: { className: 'btns-zone-left flex-grow-1 text-left'},
												children: [
													{ tag: 'button', attrs: { type: 'button', className: 'btn btn-secondary mr-2', 'data-dismiss': 'modal'}, children: "Cerrar", },
												]
											},
											{ attrs: { className: 'btns-zone-right flex-grow-1 text-right'}, children: this._buttons },
											this.options.submitButtonText
												&& { tag: 'button', attrs: { type: 'submit', className: 'btn btn-primary', }, children: this.options.submitButtonText },
										]
									}
								]
							}
						]
					},
				]
			});
		},

		_onFormSubmit: function (event) {
			this.form.removeEventListener('submit', this._onFormSubmitBound);
			this._onFormSubmitBound = null;
			this._hideSubmitButtons(false);

			// Notify listeners so that they can send a message
			// using this.sendMessage(msg)
			this.emit('submit');
		},

		enableSubmitbuttons: function (enable) {
			let submit_btns = this.dialog.querySelectorAll('[type=submit], button:not([type])');
			for (var i = 0; i < submit_btns.length; i++) {
				submit_btns[i].disabled = enable === false;
			}
		},

		_hideSubmitButtons: function (enable) {
			let submit_btns = this.dialog.querySelectorAll('[type=submit], button:not([type])');
			for (var i = 0; i < submit_btns.length; i++) {
				submit_btns[i].disabled = !enable;
			}
			let padding = enable ? 'unset' : '0px';
			$(submit_btns).animate({
				opacity: enable ? 1 : 0,
				width: enable ? 'auto' : '0px',
				paddingLeft: padding,
				paddingRight: padding,
			}, function () { this.hidden = true });
		},
	};

	function WebSocketDialog(options) {
		if (!options) options = {};
		options.autoexecute = Boolean(options.autoexecute);
		Dialog.call(this, {
			open: options.open,
			title: options.title,
			size: options.size,
			submitButtonText: options.autoexecute ? '' : (options.submitButtonText || "Ejecutar"),
			autoexecute: options.autoexecute,
			websocket: options.websocket,
		});

		this._output_screen = null; // Logger output

		this.ws = null;
	}

	WebSocketDialog.prototype = {
		__proto__: Dialog.prototype,
		destroy: function () {
			if (this.ws) {
				if (this.ws.readyState < WebSocket.CLOSING) {
					this.ws.close();
				}
				this.ws = null;
			}
			this.__proto__.__proto__.destroy.call(this);
		},

		open: function (open) {
			this.__proto__.__proto__.open.call(this, open);

			if (!this.ws || this.ws.readyState >= WebSocket.CLOSING) {
				this._prepareWebSocket();
			}

			// This widget will automatically update itself
			let statusContainer = this.dialog.querySelector('.modal-header');
			let statusIndicator = new ConnectionStatusIndicator(this.ws);
			statusContainer.appendChild(statusIndicator.element());

			this._output_screen = renderElement({
				attrs: { className: 'ws-output h-100', style: 'overflow-y: auto', },
			});
			this.addElementToBody(this._output_screen);
		},

		clearScreen: function () {
			this._output_screen.innerHTML = '';
		},

		writeToScreen: function (message) {
			// FIXME: use MessageBox widget instead of this copy
			message = message || ''; // Don't use default param values (Support IE)

			if (this._loadingDots) { // Stop animations
				this._loadingDots.setAttribute('data-stop', true);
				this._loadingDots = null;
			}

			let output = this._output_screen;
			let className = '';
			if (message.startsWith('err:')) {
				message = message.replace(/^err:\ ?/, '');
				className = 'text-danger';
			} else if (message.startsWith('warn:')) {
				message = message.replace(/^warn:\ ?/, '');
				className = 'text-warning';
			}
			message = message.trim();
			let line = document.createElement('div');
			line.className = className;
			if (message.endsWith && message.endsWith('...')) { // Add some fanciness (animated dots)
				line.textContent = message.substring(0, message.length-3);
				line.appendChild(this._loadingDots = loadingDots());
			} else {
				line.textContent = message;
			}
			output.appendChild(line);
			output.scrollTop = output.scrollHeight + 100
		},


		_prepareWebSocket: function () {
			this.ws = new WebSocket(this.options.websocket, 'echo-protocol');

			this.enableClosing(false);
			this.ws.addEventListener('open', function () {
				this.enableClosing(true);
				// this.clearScreen();
				// this.writeToScreen("Conectado");

				this.ws.addEventListener('message', function (message) {
					let lines = message.data.trim().split("\n"); // Convert \n to actual html lines
					for (let i = 0; i < lines.length; i++) {
						let line = lines[i];
						if (line.startsWith('file:')) {
							let filename = line.replace(/^file:\ ?/, '');
							this.emit('file-received', filename);
						} else if (line.startsWith('progress:')) {
							return; // Ignore
						} else {
							this.writeToScreen(line);
						}
					}
				}.bind(this));

				this.emit('ws-open', this.ws);
				if (this.options.autoexecute)
					this.emit('submit');
			}.bind(this));

			this.ws.addEventListener('close', function (event) {
				let is_unexpected_close = event.code !== 1000; // Abnormal connection close
				this.emit('ws-close', is_unexpected_close);
				if (!this.dialog)
					return;

				if (is_unexpected_close) {
					this.writeToScreen("err: Se ha perdido la conexión con el servidor.");
					// Main.ws_connection_lost_dialog("<?php echo APP_ROOT ?>");
					this.enableInputs(false);
				}
				this._hideSubmitButtons(false);
				this.enableClosing(true);
			}.bind(this));
		},

		sendMessage: function (msg) {
			this.ws.send(JSON.stringify(msg));
		},
	};

	function FileListWidget(options) {
		this._init(options || {});
	}

	FileListWidget.prototype = {
		__proto__: PreparedObject.prototype,
		_init: function (options) {
			PreparedObject.call(this);
			options.deletable = 'deletable' in options ? Boolean(options.deletable) : true; // true by default
			options.clickable = 'clickable' in options ? Boolean(options.clickable) : false; // false by default
			options.fileIcons = 'fileIcons' in options ? Boolean(options.fileIcons) : true; // true by default
			this.options = options;
			this._files = []; // List of file objects
			this._container = null; // Main widget's container element
			this.fileList = null; // Container of files' HTML elements
			this._renderHTML();
		},

		element: function() {
			return this._container;
		},

		files: function () {
			return this._files;
		},

		add: function (file, idx /* optional */) {
			idx = idx > 0 ? idx : (this.fileList.childElementCount); // Default to append
			let row = this._renderFileItem(file);
			this.fileList.insertBefore(row, this.fileList.children[idx]);
			this._files.splice(idx, 0, file);
			this.refreshFileCount();
			this.emit('change');
		},

		delete: function (file) {
			let idx = this._files.indexOf(file);
			if (idx >= 0) {
				this.deleteAtIndex(idx);
			} else {
				console.warn("File cannot be deleted: not found");
			}
		},

		deleteAtIndex: function (idx) {
			this.fileList.removeChild(this.fileList.children[idx]);
			this._files.splice(idx, 1);
			this.refreshFileCount();
			this.emit('change');
		},

		reset: function () {
			this._files = [];
			this.fileList.innerHTML = '';
			this.refreshFileCount();
			this.emit('change');
		},

		refreshFileCount: function () {
			if (typeof this.options.numOfFiles === 'function') {
				let element = this._container.querySelector('.number-of-files');
				element.textContent = this.options.numOfFiles(this._files.length);
			}
		},

		_renderHTML: function () {
			this._container = renderElement({
				attrs: { className: 'd-flex flex-column overflow-hidden' },
				children: [
					typeof this.options.numOfFiles === 'function' && {
						attrs: { className: 'text-right text-secondary number-of-files' },
						children: String(this.options.numOfFiles(0))
					},
					{
						tag: 'ul',
						attrs: { className: 'my-2 file-list '+(this.options.clickable?'clickable':'') },
					}
				]
			});
			this.fileList = this._container.querySelector('.file-list');
		},

		_renderFileItem: function (file) {
			let li = renderElement({
				tag: 'li',
				attrs: { className: 'file-item' },
				children: [
					this.options.fileIcons && {
						tag: 'i',
						attrs: { className: 'text-secondary pr-2 ' + this.getFileIcon(file.name) } ,
					},
					{ tag: 'span', attrs: { className: 'file-name flex-grow-1 text-truncate' }, children: String(file.name) },
					{ tag: 'span', attrs: { className: 'text-secondary mx-1' }, children: this.humanFileSize(file.size) },
					this.options.deletable && {
						tag: 'button',
						attrs: { type: 'button', className: 'btn btn-sm btn-icon btn-just-icon btn-link' },
						children: { tag: 'i', attrs: { className: 'far fa-trash-alt' } }
					}
				]
			});
			if (this.options.deletable) {
				li.querySelector('button').addEventListener('click', function (event) {
					event.stopPropagation();

					if (event.currentTarget.classList.contains('disabled'))
						return; // It has already been clicked
					event.currentTarget.classList.add('disabled');

					let idx = this._files.indexOf(file);
					this.emit('delete-requested', idx, file);
				}.bind(this));
			}
			if (this.options.clickable) {
				li.addEventListener('click', function () {
					let idx = this._files.indexOf(file);
					this.emit('click', file, idx);
				}.bind(this));
			}
			return li;
		},

		// Convert bytes to a readable file size string
		humanFileSize: function (bytes, si) {
			let thresh = si ? 1000 : 1024;
			if (Math.abs(bytes) < thresh) {
				return bytes + ' B';
			}
			let units = si
				? ['kB','MB','GB','TB','PB','EB','ZB','YB']
				: ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
			let u = -1;
			do {
				bytes /= thresh;
				++u;
			} while (Math.abs(bytes) >= thresh && u < units.length - 1);
			return Math.round(bytes)+' '+units[u];
		},

		getFileIcon: function (path) {
			let index = [
				{re: /\.(docx?)$/, icon: 'fa-file-word'},
				{re: /\.(tsv|xlsx?)$/, icon: 'fa-file-excel'},
				{re: /\.(ppt|ppx)$/, icon: 'fa-file-powerpoint'},
				// {re: /\.(csv)$/, icon: 'fa-file-csv'},
				{re: /\.(pdf)$/, icon: 'fa-file-pdf'},
				{re: /\.(bz2?|gz|gzip|rar|tar|zip)$/, icon: 'fa-file-archive'},
				{re: /\.(avi|mpg|mp4)$/, icon: 'fa-file-video'},
				{re: /\.(bmp|jpe?g|gif|png|webm)$/, icon: 'fa-file-image'},
				{re: /\.(mp3|wave?)$/, icon: 'fa-file-audio'},
				{re: /./, icon: 'fa-file'},
			];
			let base = path.toLowerCase();
			let icon = null;
			for (let i = 0; i < index.length; i++) {
				if (index[i].re.test(base)) {
					icon = 'far' + ' ' + index[i].icon;
					break;
				}
			}
			return icon;
		},
	}

	function DNDFileInput(input_attrs, options) {
		PreparedObject.call(this);
		options = options || {};

		this.options = {
			max_files: parseInt(options.max_files) || 0,
		};

		this._files = [];

		this.fileList = new FileListWidget({
			numOfFiles: function (num) { return "%d ficheros seleccionados".replace('%d', num) },
		});
		this.fileList.on('delete-requested', function (i) {
			this.deleteFile(this._files[i]);
		}.bind(this));

		this._renderHTML(input_attrs);
	}

	DNDFileInput.prototype = {
		__proto__: PreparedObject.prototype,

		element: function () {
			return this.container;
		},

		getSelectedFiles: function () {
			return this._files;
		},

		reset: function () {
			this._files = [];
			this.fileInput.value = '';
			this.fileList.reset();
			this.emit('change');
		},

		deleteFileAtIndex: function (idx) {
			this._files.splice(idx, 1);
			this.fileList.deleteAtIndex(idx);

			if (this._files.length < this.options.max_files) {
				this.enable();
			}

			this.emit('change');
		},

		deleteFile: function (file) {
			this.deleteFileAtIndex(this._files.indexOf(file));
		},

		addFiles: function (files) {
			if (this.options.max_files && this._files.length + files.length > this.options.max_files) {
				this._showWarning("El número máximo de ficheros es %d".replace('%d', this.options.max_files));
				return;
			} else {
				this._showWarning(false); // Hide warning
			}

			for (let i = 0; i < files.length; i++) {
				this._files.push(files[i]);
				this.fileList.add(files[i]);
			}

			if (this.options.max_files && this._files.length >= this.options.max_files) {
				this.disable();
			}

			this.emit('change');
		},

		files: function () {
			return this._files;
		},

		showDropZone: function () {
			this.container.querySelector('.upload-drop-zone').hidden = false;
		},

		hideDropZone: function () {
			this.container.querySelector('.upload-drop-zone').hidden = true;
		},

		enable: function () {
			this.container.querySelector('input[type=file]').disabled = false;
		},

		disable: function () {
			this.container.querySelector('input[type=file]').disabled = true;
		},

		uploadFiles: function (name, url, complete, progress, error, abort, extra_data) {
			let formdata = new FormData();
			for (let i = 0; i < this._files.length; i++) {
				formdata.append(name+'[]', this._files[i]);
			}
			if (extra_data) {
				for (let k in extra_data) {
					formdata.append(k, String(extra_data[k]));
				}
			}
			formdata.append('filesent', true); // To check when file size is exceeded
			let xhr = new XMLHttpRequest();
			if (progress) xhr.upload.addEventListener('progress', progress);
			xhr.addEventListener('load', function (event) {
				let xhr = event.target;
				if (xhr.status !== 200 && error) {
					if (error) error(event);
				} else {
					if (complete) complete(event);
				}
			});
			if (error) xhr.addEventListener('error', error);
			if (abort) xhr.addEventListener('abort', abort);
			xhr.open('POST', url, true);
			xhr.send(formdata);
			return xhr;
		},

		_renderHTML: function (input_attrs) {
			input_attrs = input_attrs || {};
			input_attrs.id = input_attrs.id || 'dnd-finput-'+Math.round(Math.random()*1000000);
			input_attrs.hidden = true;
			input_attrs.type = 'file';

			// create input
			this.fileInput = document.createElement('input');
			for (let attr in input_attrs) {
				this.fileInput.setAttribute(attr, input_attrs[attr]);
			}
			this.container = renderElement({
				attrs: { className: 'dnd-finput-container' },
				children: [
					this.fileInput,
					{
						tag: 'label',
						attrs: { className: 'upload-drop-zone', 'for': this.fileInput.id },
						children: [
							{ tag: 'i', attrs: { className: 'fas fa-upload fa-2x m-2' } },
							{
								tag: 'span',
								children: [
									{ tag: 'b', children: "Suelta" },
									" los archivos aquí o haz ",
									{ tag: 'b', children: "clic" },
									" para seleccionarlos.",
								],
							},
							this.options.max_files > 0 && {
								tag: 'span',
								attrs: { className: 'max-files-indicator' },
								children: "Máx. %d ficheros".replace('%d', this.options.max_files),
							},
							{
								tag: 'span',
								attrs: { className: 'filetypes' },
								children: this.fileInput.accept.replace(/\./g, '')
							}
						]
					},
					{ tag: 'strong', attrs: { className: 'dnd-warning text-danger text-center my-2', hidden: true } },
					this.fileList.element(),
				],
			});

			let dropZone = this.container.querySelector('.upload-drop-zone');
			dropZone.ondrop = (function(event) {
				event.preventDefault();
				dropZone.classList.remove('dropping');
				this.addFiles(event.dataTransfer.files);
			}).bind(this);

			dropZone.ondragover = function() {
				this.classList.add('dropping');
				return false;
			};

			dropZone.ondragleave = function() {
				this.classList.remove('dropping');
				return false;
			};

			this.fileInput.addEventListener('change', function (event) {
				this.addFiles(event.target.files);

				// The file input is independent of our list. Reset the input so
				// that we are able to select the same file if we unselect it.
				event.target.value = '';
			}.bind(this));
		},

		_showWarning: function (message) {
			let container = this.container.querySelector('.dnd-warning');
			container.textContent = message || '';
			container.hidden = !message;
		},
	};

	function StatusIndicator(statuses) {
		this._init(statuses);
	}

	StatusIndicator.prototype = {
		_init: function (statuses) {
			this._parseStatuses(statuses || {});

			this._render();
			this.setDefaultStatus();
		},

		element: function () {
			return this._container;
		},

		setStatus: function (key) {
			if (key in this.statuses) {
				let icon = this.statuses[key];
				this._setText(icon);
				this._setIcon(icon);
			} else {
				throw new Exception("Unknown status ("+key+")");
			}
		},

		setDefaultStatus: function () {
			for (let key in this.statuses) {
				if (this.statuses[key].default) {
					this.setStatus(key);
					return;
				}
			}
			// If default is not found use just the first
			for (let key in this.statuses) {
				this.setStatus(key);
				return;
			}
		},

		hide: function (animate) {
			let container = this.element();
			container.style.transitionDuration = (animate >= 0 ? animate : 500) / 1000 + 's';
			container.style.opacity = '0';
			container.style.visibility = 'hidden';
		},

		show: function (animate) {
			let container = this.element();
			container.style.transitionDuration = (animate >= 0 ? animate : 500) / 1000 + 's';
			container.style.opacity = '1';
			container.style.visibility = 'visible';
		},

		_setText: function (status) {
			let element = this._container.querySelector('.connection-status-text');
			element.textContent = status.text;
		},

		_setIcon: function (status) {
			let element = this._container.getElementsByTagName('i')[0];
			element.className = status.icon;
		},


		// Do this in a function so that it can be overriden if needed
		_renderIconElement: function () {
			return document.createElement('i');
		},

		_render: function() {
			this._container = renderElement({
				tag: 'span',
				attrs: {
					className: 'd-flex align-items-center',
					style: 'opacity: 1; visibility: visible; transition: .5s all',
			 	},
				children: [
					{ tag: 'span', attrs: { className: 'connection-status-text mx-2', } },
					this._renderIconElement(), // Icon
				],
			});
			return this._container;
		},

		_parseStatuses: function (icons) {
			this.statuses = {};
			for (let key in icons) {
				let icon = icons[key];
				this.statuses[key] = {
					text: icon.text || '',
					icon: icon.icon || '',
					default: Boolean(icon.default),
				};
			}
		},
	};

	function ConnectionStatusIndicator(ws, options) {
		this._init(ws, options);
	}

	ConnectionStatusIndicator.prototype = {
		__proto__: StatusIndicator.prototype,
		_init: function (ws, options) {
			// Parse options
			options = options || {};

			this.options = {
				close_is_error: 'close_is_error' in options ? Boolean(options.close_is_error) : false,
			};


			// Icons are customizable
			let icons = options.icons || {};

			StatusIndicator.prototype._init.call(this, {
				connecting: {
					text: "Conectando...",
					icon: icons.connecting || 'now-ui-icons loader_refresh spin',
					default: true,
				},
				connected: {
					text: "Conectado",
					icon: (icons.connected || 'now-ui-icons objects_globe') + ' text-success',
				},
				disconnected: {
					text: "Desconectado",
					icon: (icons.disconnected || 'now-ui-icons objects_globe') + ' text-secondary',
				},
				error: {
					text: "Desconectado",
					icon: (icons.error || 'now-ui-icons objects_globe') + ' text-danger text-error',
				},
			});

			this._ws = ws;

			this._boundOnWsOpen = this._onWsOpen.bind(this);
			this._boundOnWsClose = this._onWsClose.bind(this);

			this._setupListeners();
		},

		_onWsOpen: function (event) {
			this.setStatus('connected');
		},

		_onWsClose: function (event) {
			let is_unexpected_close = event.code !== 1000; // Abnormal connection close
			if (this.options.close_is_error || is_unexpected_close) {
				this.setStatus('error');
			} else {
				this.setStatus('disconnected');
			}
			this._disconnectListeners();
		},

		_setupListeners: function () {
			if (!this._listenting) {
				this._ws.addEventListener('open', this._boundOnWsOpen);
				this._ws.addEventListener('close', this._boundOnWsClose);
				this._listenting = true;
			}
		},

		_disconnectListeners: function () {
			if (this._listenting) {
				this._ws.removeEventListener('open', this._boundOnWsOpen);
				this._ws.removeEventListener('close', this._boundOnWsClose);
				this._listenting = false;
			}
		},
	};


	function ProgressBar(options, attrs) {
		this._init(options, attrs);
	}

	ProgressBar.prototype = {
		_init: function (options, attrs) {
			options = options || {};
			this.progress = 0;
			this._style = options.style || null;
			this._animated = false;
			this._enabled = true;

			attrs = attrs || {};
			attrs.className = (attrs.className || '') + ' progress';

			this._element = renderElement({
				attrs: attrs,
				children: {
					attrs: { className: 'progress-bar progress-bar-striped', role: 'progressbar', style: 'width: 0%' },
				},
			});
			this._progress_bar = this._element.querySelector('.progress-bar');

			this.animate(options.animated);
		},

		element: function () {
			return this._element;
		},

		setProgress: function (progress) {
			this.progress = Math.min(Math.max(0, progress), 1);
			this._progress_bar.style.width = 100 * this.progress + '%';
		},

		enable: function () {
			this._enabled = true;
			if (this._animated)
				this._progress_bar.classList.add('progress-bar-animated');

			this._progress_bar.classList.remove('bg-secondary');
			if (this._style)
				this._progress_bar.classList.add('bg-'+this._style);
		},

		disable: function () {
			this._enabled = false;
			if (this._animated)
				this._progress_bar.classList.remove('progress-bar-animated');

			if (this._style)
				this._progress_bar.classList.remove('bg-'+this._style);
			this._progress_bar.classList.add('bg-secondary');
		},

		show: function () {
			this._element.hidden = false;
		},

		hide: function () {
			this._element.hidden = true;
		},

		setStyle: function (style) {
			if (this._enabled) {
				// These classes won't exist if the progress bar is disabled
				// They will be restored when it is enabled back again
				if (this._style) {
					this._progress_bar.classList.remove('bg-'+this._style);
				}
				if (style)
					this._progress_bar.classList.add('bg-'+style);
			}
			this._style = style;
		},

		animate: function (animate) {
			this._animated = animate !== false;
			if (this._enabled) {
				// These classes won't exist if the progress bar is disabled
				// They will be restored when it is enabled back again
				if (this._animated) {
					this._progress_bar.classList.add('progress-bar-animated');
				} else {
					this._progress_bar.classList.remove('progress-bar-animated');
				}
			}
		},
	};


	function MessageBox() {
		this._container = document.createElement("div");
		this._loadingDots = null;
	}

	MessageBox.prototype = {
		showMessage: function (message, type) {
			if (!message) return;
			// Don't use default param values (Support IE)
			message = typeof message === 'string' ? message : String(message);
			type = typeof type === 'string' ? type : null;

			let msg_div = document.createElement("div");

			if (this._loadingDots) { // Stop animations
				this._loadingDots.setAttribute('data-stop', true);
				this._loadingDots = null;
			}

			if (type) {
				if (type.startsWith('err')) {
					 msg_div.className = 'text-danger text-error';
				} else if (type.startsWith('warn')) {
					msg_div.className = 'text-warning';
				} else if (type === 'success') {
					msg_div.className = 'text-success';
				} else if (type === 'info') {
					msg_div.className = 'text-info';
				}
			}
			msg_div.classList.add('font-weight-bold');

			message = message.trim();
			if (message.endsWith && message.endsWith('...')) {
				msg_div.textContent = message.slice(0,-3);
				msg_div.appendChild(this._loadingDots = loadingDots());
			} else {
				msg_div.textContent = message;
			}

			let last_msg = this._container.lastChild;
			if (last_msg) last_msg.classList.remove('font-weight-bold');
			this._container.appendChild(msg_div);
			this._container.scrollTop = this._container.scrollHeight;
		},

		element: function () {
			return this._container;
		},

		clearMessages: function () {
			this._container.innerHTML = '';
		},
	};

	// Create 3 animated dots, that appear and disappear
	function loadingDots() {
		let container = document.createElement('span');
		let count = 3;
		let iid = setInterval(function () {
			if (container.getAttribute('data-stop') == 'true') {
				clearInterval(iid);
				container.textContent = '...';
				container = null;
				return;
			}
			count = (count + 1) % 4;
			let dots = '';
			for (let i = 0; i < count; i++)
				dots += '.';
			container.textContent = dots;
		}, 500);
		return container;
	}

	function renderIcon(className) {
		return renderElement({
			tag: 'i',
			attrs: { className: className },
		});
	}

	function Button(options) {
		EventEmitter.call(this);

		if (!options) options = {};
		this.options = {
			text: options.text,
			icon: options.icon,
			color: options.color ? String(options.color) : 'default',
			size: options.size ? String(options.size) : 'sm',
			type: options.type || 'button',
		}

		this.render();
	}

	Button.prototype = {
		__proto__: EventEmitter,

		element: function () {
			return this.button;
		},

		render: function () {
			function getButtonTypeAttr(type) {
				switch (type) {
					case 'button': return 'button';
					case 'submit': return 'submit';
					default: return undefined;
				}
			}

			return this.button = renderElement({
				tag: this.options.type === 'link' ? 'a' : 'button',
				attrs: {
					type: getButtonTypeAttr(this.options.type),
					className: 'btn btn-'+this.options.color + ' btn-'+this.options.size + ' m-0',
				},
				children: [
					{ tag: 'span', attrs: { className: 'button-icon mr-2' }, children: renderIcon(this.options.icon), },
					{ tag: 'span', attrs: { className: 'button-text' }, children: String(this.options.text) },
				]
			})
		},

		setText: function (text) {
			this.options.text = text;
			this.button.querySelector('.button-text').textContent = text;
		},

		setIcon: function (icon) {
			this.options.icon = icon;
			let iconContainer = this.button.querySelector('.button-icon');
			iconContainer.innerHTML = '';
			iconContainer.appendChild(renderIcon(icon));
		},

		setColor: function (color) {
			this.button.classList.remove('btn-'+this.options.color);
			this.button.classList.add('btn-'+color);
			this.options.color = color;
		}
	}



	/*
	 * Recursively create elements
	 * Each element has the form:
	 *     {
	 *         tag: String (optional) (default: 'div')
	 *         attrs: Object (optional)
	 *         children: Array|String (optional) Array of one or more elements like this one
	 *     }
	 */
	function renderElement(current) {
		let stack = [];
		let tree = document.createDocumentFragment(); // fake parent

		current.parent = tree;
		stack.push(current);
		while (stack.length) {
			current = stack.pop();

			if (current.element) { // If it's an element just add it
				current.parent.appendChild(current.element);
				continue;
			}

			let el = __create_el(current.tag || 'div', current.attrs);
			current.parent.appendChild(el);

			let children = current.children;
			if (!Array.isArray(children))
				children = [ children ]; // Treat an item as a single-item array
			// do it backwards to preserve order
			for (let i = children.length-1; i >= 0; i--) {
				if (children[i] instanceof Element) {
					stack.push({ parent: el, element: children[i] });
				} else if (typeof children[i] === 'object') {
					children[i].parent = el;
					stack.push(children[i]);
				} else if (typeof children[i] === 'string') {
					stack.push({ parent: el, element: document.createTextNode(children[i]) });
				}
			}
		}
		return tree.firstChild;
	}

	// Create element from a tag name and its attributes
	function __create_el(tag, attrs) {
		let el = document.createElement(tag);
		if (attrs) {
			let dest_attr;
			for (let src_attr in attrs) {
				if (src_attr === 'className')
					dest_attr = 'class';
				else
					dest_attr = src_attr;
				el.setAttribute(dest_attr, attrs[src_attr]);
			}
		}
		return el;
	}

	return {
		Dialog: Dialog,
		WebSocketDialog: WebSocketDialog,
		Button: Button,
		FileListWidget: FileListWidget,
		DNDFileInput: DNDFileInput,
		StatusIndicator: StatusIndicator,
		ConnectionStatusIndicator: ConnectionStatusIndicator,
		ProgressBar: ProgressBar,
		MessageBox: MessageBox,
		renderIcon: renderIcon,
		renderElement: renderElement,
	};
})();
