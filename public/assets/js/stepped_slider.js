const LEVELS = [ 'slider-danger', 'slider-warning', 'slider-info', 'slider-success' ];

class SteppedSlider {
	constructor(slider) {
		this.slider = slider;
		this.min = parseInt(slider.dataset.min) || 0;
		this.max = parseInt(slider.dataset.max) || 5;
		this.direction = slider.dataset.dir;
		let start = parseInt(slider.dataset.start) || 0;

		noUiSlider.create(slider, {
			start: Math.min(Math.max(this.min, start), this.max),
			step: 1,
			connect: [ this.direction !== "rtl", this.direction === "rtl" ],
			tooltips: {
				to: val => Math.floor(val)
			},
			range: {
				min: this.min,
				max: this.max
			},
			direction: this.direction,
			behaviour: "tap-drag",
			animate: true
		});
		this.createForm();

		slider.noUiSlider.on('update', this.update.bind(this));
	}

	update(val) {
		val = Math.floor(val);
		this.setLevelColor(val);
		this.updateForm(val);
	}

	setLevelColor(val) {
		let className = '';
		if (val < this.min + 2) {
			className = LEVELS[val - this.min];
		} else if (val > this.max - 2) {
			className = LEVELS[LEVELS.length - (this.max - val) - 1];
		}
		this.slider.classList.remove(...LEVELS);
		if (className)
			this.slider.classList.add(className);
	}

	createForm() {
		let name = this.slider.getAttribute("name");
		if (!name)
			return;

		this.slider.removeAttribute("name");

		this.input = document.createElement("input");
		this.input.type = "hidden";
		this.input.name = name;
		this.slider.appendChild(this.input);
	}

	updateForm(val) {
		if (this.input) {
			this.input.value = val;
		}
	}
}

let stepped_slider = {
	run: function () {
		let sliders = document.getElementsByClassName('stepped-slider');
		for (let slider of sliders) {
			if (!slider.noUiSlider)
				new SteppedSlider(slider);
		}
	}
};

stepped_slider.run();
