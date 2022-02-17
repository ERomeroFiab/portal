class ParamList {
	constructor(parent, initial_params) {
		this.values = {};
		this.keys = [];
		this.parent = parent;
		this._input_row = this.parent.firstChild;

		this.param_el = this.parent.querySelector(".json-input-param");
		this.value_el = this.parent.querySelector(".json-input-value");
		this.output = this.parent.querySelector('.json-params-output');

		if (initial_params && typeof initial_params === 'object') {
			let val;
			for (let key in initial_params) {
				val = initial_params[key];
				this.values[key] = val;
				this.keys.push(key);
				this.createRow(key, val);
			}
		}

		let btn = this.parent.getElementsByTagName("button")[0];
		btn.addEventListener("click", event => {
			event.preventDefault();
			let key = this.param_el.value;
			let val = this.value_el.value;

			if (!key || !val) {
				return;
			}

			if (key in this.values) {
				this.removeEntry(key);
			}

			this.values[key] = val;
			this.keys.push(key);

			this.param_el.value = this.value_el.value = "";
			this.createRow(key, val);

			this.updateForm();
		});

		this.updateForm();
	}

	createRow(key, value) {
		let r = document.createElement("div");
		r.className = "row";

		r.appendChild(this.createStaticInput(key));
		r.appendChild(this.createStaticInput(value));
		r.appendChild(this.createCloseButton(key));

		this.parent.insertBefore(r, this._input_row);
	}

	createStaticInput(str) {
		let el = document.createElement("p");
		el.className = "col-md-5 form-control-static";
		el.textContent = str;
		return el;
	}

	createCloseButton(key) {
		let el = document.createElement("div");
		el.className = "col-md-2";

		let icon = document.createElement("i");
		icon.className = "btn btn-danger btn-neutral btn-icon now-ui-icons ui-1_simple-remove";
		icon.style.margin = "0";
		el.appendChild(icon);

		el.addEventListener('click', this.removeEntry.bind(this, key));

		return el;
	}

	removeEntry(key) {
		let idx = this.keys.indexOf(key);
		if (idx < 0)
			return;
		this.parent.removeChild(this.parent.childNodes[idx]);
		delete this.values[key];
		this.keys.splice(idx, 1);

		this.updateForm();
	}

	updateForm() {
		this.output.value = JSON.stringify(this.values);
	}
}

let ellipsize = (str, max) => str && str.length > max ? str.substr(0, max) + '&hellip;' : str;
let toDate = d =>  moment(d).format('YYYY-MM-DD HH:mm')
let priorities = ["Alta", "Media", "Baja"];
let statuses = ["Pendiente", "Ejecutando", "Error", "Terminado"];
let columns = [
	{
		data: 'Description',
		title: 'Descripción',
		render: str => ellipsize(str, 18),
		width: '100%'
	},
	{
		data: 'Estado',
		title: 'Estado',
		render: (s, type) => {
			if (type === 'display')
				return s < statuses.length ? statuses[s] : 'Desconocido'
			else
				return s;
		}
	},
	{
		data: 'Prioridad',
		title: 'Prioridad',
		render: (prio, type) => type === 'display' ? `${priorities[prio-1]} (${prio})` : prio
	},
	{
		data: 'AgenteInicial',
		title: 'Agente'
	},
	{
		data: 'FechaCreacion',
		title: 'F. creación',
		render: toDate
	},
	{
		data: 'FechaInicio',
		title: 'F. inicio',
		// Running tasks only
		render: (d, type, row) => row && row.Estado > 0 ? (d ? toDate(d) : 'No date') : 'Pendiente'
	},
	{
		data: 'FechaFin',
		title: 'F. fin',
		// Finished or errored tasks only
		render: (d, type, row) => row && row.Estado > 1 ? (d ? toDate(d) : 'No date') : 'Pendiente'
	},
	{
		data: 'Resultado',
		title: 'Resultado',
		defaultContent: ' - '
	},
	{
		data: 'Observaciones',
		title: 'Observaciones',
		render: o => o || ' - '
	},
	{
		data: 'ID',
		visible: false,
		searchable: false
	}
];

let tableElem = $('#task-list');
if (tableElem.length) {
	let buttonsTemplate = document.getElementById('edit-buttons').innerHTML.trim();
	let buttons = buttonsTemplate ? [{
		title: 'Acciones',
		sortable: false,
		searchable: false,
		className: 'text-center table-actions',
		defaultContent: buttonsTemplate
	}] : [];
	let oTable = tableElem.DataTable({
		order: [[ 1, 'desc' ], [ 2, 'asc' ], [ 4, 'asc' ]],
		info: false,
		select: false,
		processing: true,
		language: {
			url: "../i18n/datatables.net/es.json",
		},
		ajax: {
			url: "../script/tasks/list.php",
			type: "POST",
			data: { columns },
			error: function (xhr) {
				if (xhr.status === 401) {
					window.location.reload();
				} else {
					Main.error_dialog(xhr.status);
				}
			}
		},
		columns: columns.concat(buttons),
		rowCallback: function (row, data, index) {
			// Highlight finished or error rows
			switch (data.Estado) {
				case '3': // Finished
					row.classList.add('table-success', 'text-success');
					break;
				case '2': // Error
					row.classList.add('table-danger', 'text-danger');
					break;
				case '1': // Ejecutando
					row.classList.add('table-info', 'text-info');
					break;
			}
		}
	});

	// Activate an inline edit on click of a table cell
	$('#task-list').on('click', '.remove', function (e) {
		let eRow = $(this).parents('tr')[0]; // HTML element
		let jRow = $(eRow);                   // jQuery element
		let tRow = oTable.row(eRow);         // Table row object

		let height = jRow.height();
		jRow.animate({ opacity: 0 }, 150, () => {
			// Remove content so that we can animate height
			eRow.innerHTML = '';
			eRow.style.height = height + 'px';
			jRow.animate({ height: 0 }, 200, () => tRow.remove().draw())
		});

		let data = tRow.data();
		$.get("../script/tasks/remove.php", { id: data.ID }).done(() => {
			swal({
				title: "Tarea eliminada",
				text: `${data.Description} (${data.FechaCreacion})`,
				buttonsStyling: false,
				confirmButtonClass: "btn btn-primary",
				type: "error",
				confirmButtonText: 'OK'
			});
		});
	});

	$('#task-list').on('click', '.edit', function (e) {
		var nRow = $(this).parents('tr')[0];
		let data = oTable.row(nRow).data();
		window.location = './edit-task.php?id=' + data.ID;
	});
}



// FIXME: delete
// function show_edit_dialog() {
// 	let template = document.getElementById('new-task-form');
// 	let content = document.importNode(template.content, true);
// 	let form = content.querySelector('form');
// 	console.log(form);
// 	swal({
// 		title: '<i class="fas fa-sm fa-pen"></i> Editar tarea',
// 		html: content,
// 		// text: "Taasdascibida",
// 		buttonsStyling: false,
// 		confirmButtonClass: "btn btn-primary",
// 		confirmButtonText: 'Enviar',
// 		showCancelButton: true,
// 		cancelButtonClass: "btn",
// 		cancelButtonText: 'Cancelar',
// 		width: '80%',
// 		showCloseButton: true
// 	});
// 	stepped_slider.run();
// }
