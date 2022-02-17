function CompanySearch(input_id, companies_id, tables_id, subvencion) {
	this._init(input_id, companies_id, tables_id, subvencion);
}


CompanySearch.prototype = {
	LAST_SEARCH_STORAGE_KEY: 'clients.last-search',

	_init: function (items, main_container, meta_container, tables_container) {
		this.items = items;
		this.subvencion = null;
		this._selected_item = null;

		this._main_container = main_container;
		this._meta_el = meta_container;
		this._tables_el = tables_container;

		this._create_inner_dom();
		this._rebuild_list();

		let delete_btn = this._meta_el.querySelector('button[name="delete"][data-url]');
		if (delete_btn) {
			delete_btn.addEventListener('click', function () {
				swal({
					title: "¿Eliminar cliente?",
					text: "Se dará de baja el cliente '" + this._selected_item.client_name + "'",
					buttonsStyling: false,
					confirmButtonClass: "btn btn-danger mr-2",
					cancelButtonClass: "btn",
					type: 'warning',
					confirmButtonText: "Eliminar",
					showCancelButton: true,
					cancelButtonText: "Cancelar"
				}).then(function () {
					let client_id = this._selected_item.client_id;
					let url = delete_btn.getAttribute('data-url');
					$.post({
						url: url,
						data: { id: client_id },
					}).done(function () { window.location.reload() });
				}.bind(this)).catch(swal.noop);
			}.bind(this));
		}
	},

	_create_inner_dom: function () {
		this._create_input_el();

		// Div holding items
		this._list_container = document.createElement('div');
		this._list_container.className = 'table-responsive companies-list table p-0';
		this._main_container.appendChild(this._list_container);
		this._setup_scroll(this._list_container);
	},

	_create_input_el: function () {
		let div = document.createElement('div');
		div.className = 'input-group';
		{
			let prepend = document.createElement('div');
			prepend.className = 'input-group-prepend';
			prepend.innerHTML = '<div class="input-group-text"><i class="now-ui-icons ui-1_zoom-bold"></i></div>';
			div.appendChild(prepend);

			let input = document.createElement('input');
			input.className = 'form-control';
			input.setAttribute('tabindex', '1');
			this.input_el = div.appendChild(input);
			this.input_el.addEventListener('input', this._on_input_changed.bind(this));
			this._animateSearchPlaceholder(input);
		}
		this._main_container.appendChild(div);

		// Set last searched value when the page is reloaded or the user comes back
		if (this._was_page_reloaded()) {
			// Fetch last search
			try {
				this.input_el.value = sessionStorage.getItem(this.LAST_SEARCH_STORAGE_KEY);
			} catch (e) { /* Some browsers may throw errors in private mode, etc.*/ }
		} else {
			// Clear last search so that next time we come back we dn't find the previous search (if there was any)
			try {
				 sessionStorage.setItem(this.LAST_SEARCH_STORAGE_KEY, this.input_el.value);
			} catch (e) { /* Some browsers may throw errors in private mode, etc.*/ }
		}

		let loaded = false;
		if (this.input_el.value) {
			this._on_input_changed();
			loaded = true;
		}

		setTimeout(function () {
			this.input_el.focus();

			if (!loaded && this.input_el.value) {
				this._on_input_changed();
			}
		}.bind(this), 500);
	},

	_animateSearchPlaceholder: function (input) {
		let basePlaceholder = input.placeholder = "Buscar";
		let placeholders = [
			"Buscar por autorizado",
			"Buscar por nombre",
			"Buscar por titular certificado",
			"Buscar nuevos clientes",
		];
		let strIdx = 0;
		let DURATION = 2600;
		let typeIntervalId;
		function updatePlaceholder() {
			let placeholder = placeholders[strIdx];
			let charIdx = basePlaceholder.length;
			if (typeIntervalId) clearInterval(typeIntervalId);
			typeIntervalId = setInterval(function () {
				input.placeholder = placeholder.substring(0, charIdx);
				if (charIdx >= placeholder.length) {
					clearInterval(typeIntervalId);
				}
				charIdx++;
			}, DURATION/4/placeholder.length);
			strIdx = (strIdx + 1) % placeholders.length;
		}
		setTimeout(function () {
			updatePlaceholder();
			setInterval(updatePlaceholder, DURATION);
		}, 1000);
	},

	_rebuild_list: function () {
		let container = this._list_container;
		container.innerHTML = '';
		for (let i = 0; i < this.items.length; i++) {
			let item = this.items[i];
			if (item.__hide)
				continue;

			let row = document.createElement('div');
			row.className = 'row align-items-center';
			row.setAttribute('tabindex', String(i+2));
			{
				let title = document.createElement('div');
				title.className = 'col';
				title.textContent = item.client_name;
				row.appendChild(title);

				if (item.is_new) {
					let new_badge = document.createElement('small');
					new_badge.className = 'col-auto px-0 text-success text-monospace font-weight-bold';
					new_badge.textContent = "NEW";
					row.appendChild(new_badge);
				}

				let autorizado = document.createElement('div');
				autorizado.className = 'col-auto text-muted text-monospace';
				autorizado.textContent = item.autorizado || '--------';
				row.appendChild(autorizado);

				let arrow = document.createElement('div');
				arrow.className = 'col-auto text-center';
				arrow.innerHTML = '<i class="now-ui-icons arrows-1_minimal-right"></i>';
				row.appendChild(arrow);
			}
			item.element = container.appendChild(row);

			let clickEvent = this._on_table_clicked.bind(this, item);
			row.addEventListener('focus', clickEvent);
			row.addEventListener('keydown', function (event) {
				let ok = true;
				switch (event.key) {
					case 'Enter': clickEvent(event); break;
					case 'ArrowDown': row.nextSibling.focus(); break;
					case 'ArrowUp': row.previousSibling.focus(); break;
					default: ok = false;
				}
				if (ok)
					event.preventDefault();
			});
		}

		// No items added, add placeholder
		if (!container.firstChild) {
			let empty_placeholder = document.createElement('div');
			empty_placeholder.className = 'text-center text-secondary py-3';
			empty_placeholder.textContent = "Sin coincidencias";
			container.appendChild(empty_placeholder);
		}
	},

	_get_rows: function () {
		let rows = this.companies_el.getElementsByClassName('row');
		rows.forEach = Array.prototype.forEach; // Polyfill
		return rows;
	},

	_on_table_clicked: function (item, event) {
		if (this._selected_item)
			this._selected_item.element.classList.remove('active');
		item.element.classList.add('active');
		this._selected_item = item;

		this.showSelectedItem();
	},

	showSelectedItem: function () {
		let item = this._selected_item;
		this._get_company_metadata(item.client_id, function (data) {
			if (data) {
				item.company = data;
			}
			this._show_meta(item);
			this._show_tables(item);
		}.bind(this));
	},

	_on_input_changed: function () {
		let str = this.input_el.value;
		if (this.input_elTimeout)
			clearTimeout(this.input_elTimeout);
		this.input_elTimeout = setTimeout(this._do_search.bind(this, str), 200);
		try {
			sessionStorage.setItem(this.LAST_SEARCH_STORAGE_KEY, str);
		} catch (e) { /* Some browsers may throw errors in private mode, etc.*/ }
	},

	_do_search: function (str) {
		str = this._norm_string(str);
		for (let i = this.items.length - 1; i >= 0; i--) {
			let item = this.items[i];

			// If it passes this filter continue testing
			if (this.subvencion !== null && item.subv !== this.subvencion) {
				item.__hide = true;
				continue;
			}

			let matches = false;
			for (let k in item) {
				if (typeof item[k] === 'string' && this._norm_string(item[k]).includes(str)) {
					matches = true;
					break;
				}
			}
			matches || (matches = item.is_new && (str === 'new' || 'nuevos'.startsWith(str)));

			// It has passed all filters, so unhide
			item.__hide = !matches;
		}
		this._rebuild_list();
	},

	_norm_string: function (str) {
		if (String.prototype.hasOwnProperty('normalize')) {
			// https://stackoverflow.com/a/51874002
			return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
		} else {
			// This fixes most cases for old browsers (IE11)
			return str.toUpperCase()
				.replace('Á', 'A')
				.replace('É', 'E')
				.replace('Í', 'I')
				.replace('Ó', 'O')
				.replace('Ú', 'U');
		}
	},

	_get_company_metadata: function (id, callback) {
		if (this._request) {
			this._request.abort();
		}
		this._request = $.post({
			url: "./dashboard/script/get_company_tables.php",
			data: { company: id, subvencion: this.subvencion }
		}).done(function (data) {
			let json = null;
			if (typeof data === 'object') {
				json = data;
			} else if (typeof data === 'string') {
				try {
					json = JSON.parse(data);
					json.company = id;
				} catch (e) {}
			}
			callback(json);
		}).fail(function (jqXHR) {
			if (jqXHR.status === 401) {
				window.location.reload();  // Session has expired
			} else if (jqXHR.status) {
				callback(null);
			} else if (jqXHR.readyState !== 0) { // Is error
				throw jqXHR;
			}
		});
	},

	_show_tables: function (item) {
		if (!this._tables_el)
			return;

		let el = this._tables_el.querySelector('.tables-info');
		if (!el) return;

		el.innerHTML = '';
		this._tables_el.hidden = false;

		let data = item.company;

		if (data == null || data.error) {
			el.innerHTML = '<span class="text-danger">Ha ocurrido un error.</span>';
			return;
		} else if (!Array.isArray(data.tables) || data.tables.length === 0) {
			let createStructure = !!this._tables_el.getAttribute('data-create-url');
			let content = widgets.renderElement({
				children: [
					{ tag: 'div', children: "Esta empresa no tiene tablas." },
					createStructure && {
						tag: 'button',
						attrs: { type: 'button', className: 'btn btn-success btn-sm' },
						children: [
							widgets.renderIcon('mr-2 fas fa-hammer'),
							"Crear estructura de tablas",
						],
					},
				],
			});
			if (createStructure)
				content.querySelector('button').addEventListener('click', this.callCreateTables.bind(this, data.id))
			el.innerHTML = '';
			el.appendChild(content);
			return;
		}

		let tableEl = widgets.renderElement({
			tag: 'table',
			attrs: { className: 'table compact' },
			children: data.tables.map(function (table) {
				if(table.table.startsWith('check')) return ''
				return {
					tag: 'tr',
					children: [
						{
							tag: 'td',
							children: {
								tag: 'a',
								attrs: { href: './table.php?'+$.param({ db: table.schema, table: table.table }) },
								children: String(table.title),
							}
						},
						{
							tag: 'td',
							attrs: { className: 'text-secondary font-italic text-right' },
							children: "~ %s filas".replace('%s', this._count_to_string(parseInt(table.rows))),
						},
					]
				};
			}.bind(this)),
		});
		el.appendChild(tableEl);

	},

	_show_meta: function (item) {
		if (!this._meta_el)
			return;

		let show = false;
		let el = this._meta_el.querySelector('.autorizados-info');
		if (el) {
			let html = '';
			let autorizados = item.company ? item.company.autorizados : null;
			if (Array.isArray(autorizados)) {
				html = autorizados.map(function (autorizado) {
					autorizado = String(parseInt(autorizado));
					let pad = '00000000'.substring(autorizado.length)
					return '<span class="text-muted">'+pad+'</span>'+autorizado;
				}).join(', ') || '<span class="text-danger">Sin autorizaciones</span>';
			} else {
				html = '<span class="text-danger">Sin información</span>';
			}
			el.innerHTML = html;
			show = true;
		}
		el = this._meta_el.querySelector('.ccode-info');
		if (el) {
			let id = item.company ? item.company.id : item.client_id;
			if (id) {
				el.textContent = id;
			} else {
				el.innerHTML = '<span class="text-danger">Sin información</span>';
			}
			show = true;
		}
		el = this._meta_el.querySelector('.name-info');
		if (el) {
			let name = item.company ? item.company.name : item.client_name;
			if (name)
				el.textContent = name;
			else
				el.innerHTML = '<span class="text-danger">Sin información</span>'
			show = true;
		}
		el = this._meta_el.querySelector('.is-new-info');
		if (el) {
			el.hidden = !item.is_new;
		}
		el = this._meta_el.querySelector('.certificate-info');
		if (el) {
			if (item.username)
				el.textContent = item.username;
			else
				el.innerHTML = '<span class="text-secondary">Sin <i>SILCON</i></span>'
			show = true;
		}
		el = this._meta_el.querySelector('.subvencion-info');
		if (el) {
			if (item.company && item.company.subvencion != null) {
				let text, icon;
				if (item.company.subvencion) {
					text = "Sí";
					icon = '<i class="fas fa-check fa-sm text-success"></i>';
				} else {
					text = "No";
					icon = '<i class="fas fa-times align-middle text-danger"></i>';
				}
				el.innerHTML = text + ' ' + icon;
			} else {
				el.innerHTML = '<span class="text-danger">Sin información</span>'
			}
			show = true;
		}

		el = this._meta_el.querySelector('[data-edit-link]');
		if (el) {
			if (!item.company || item.company.automatic) {
				el.href = '#';
				el.hidden = true;
			} else {
				el.href = el.getAttribute('data-edit-link') + '?id='+item.company.id;
				el.hidden = false;
			}
		}

		el = this._meta_el.querySelector('[data-view-link]');
		if (el) {
			if (!item.company) {
				el.href = '#';
				el.hidden = true;
			} else {
				el.href = el.getAttribute('data-view-link') + '?id='+item.company.id;
				el.hidden = false;
			}
		}

		el = this._meta_el.querySelector('button[name=delete]');
		if (el) {
			if (!item.company || item.company.automatic) {
				el.hidden = true;
			} else {
				el.hidden = false;
			}
		}

		this._meta_el.hidden = !show;
	},

	callCreateTables: function (client_id) {
		let url = this._tables_el.getAttribute('data-create-url');
		if (!url) return;
		fetch(url+'?'+$.param({ client: client_id }), {
			method: 'POST',
		}).then(function (response) {
			if (!response.ok)
				throw response;
			return response.json().catch(function () { throw response }).then(function (json) {
				if (json.error)
					throw json;
				else
					return json;
			});
		}).then(this.showSelectedItem.bind(this));
	},

	_count_to_string: function (count) {
		let n, u;
		if (count >= 1000000) {
			n = Math.round(count / 100000) / 10;
			u = 'M';
		} else if (count >= 10000) {
			n = Math.round(count / 1000);
			u = 'K';
		} else if (count >= 1000) {
			n = Math.round(count / 100) / 10;
			u = 'K';
		} else {
			n = count;
			u = '';
		}

		return n+u;
	},

	_scroll_top: function () {
		$(this.companies_el).scrollTop(0).perfectScrollbar('update');
	},

	_setup_scroll: function (where) {
		// Add perfect-scrollbar
		$(where).perfectScrollbar({ minScrollbarLength: 48 });

		function resize_container () {
			// Set a height so that a scrollbar appears
			let rect = where.getBoundingClientRect();
			let y = rect.top + window.scrollY;
			where.style.height = Math.min(window.innerHeight - y - 64, 600) + 'px';
		}

		resize_container();
		window.addEventListener('resize', resize_container);
	},

	_was_page_reloaded: function () {
		if (!performance.getEntriesByType)
			return false; // Not supported!

		let entries = performance.getEntriesByType('navigation');
		for (let i = 0; i < entries.length; i++) {
			let type = entries[i].type;
			if (type === 'reload' || type === 'back_forward')
				return true;
		}
		return false;
	},

	subvenciones: function (subvencion) {
		this.subvencion = subvencion;
		this._do_search(this.input_el.value);
	},
}
