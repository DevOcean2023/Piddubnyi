import {tinySlider} from "../../base/defaults/scripts/libs/tiny-slider";

tinySlider("[data-slider=\"banner-slider\"]", {
	items: 1,
	gutter: 0,
	autoplay: true,
	autoplayTimeout: 4000,
	autoplayButtonOutput: false,
	speed: 1500,
	controls: true,
	controlsPosition: "bottom",
	controlsText: ["<span>&#10229;</span>", "<span>&#10230;</span>"],
	navAsThumbnails: true,
	navPosition: "bottom",
	swipeAngle: 15,
	displayCounter: true,
});

tinySlider(
	"[data-slider=\"slider-reviews\"]",
	{
		items: 1,
		gutter: 0,
		autoplay: true,
		autoplayTimeout: 3000,
		autoplayButtonOutput: false,
		speed: 1500,
		controls: true,
		controlsText: ["<span>&#10229;</span>", "<span>&#10230;</span>"],
		navPosition: "bottom",
		swipeAngle: false,
		responsive: {
			350: {
				items: 1,
			},
			568: {
				items: 2,
			},

			1321: {
				items: 3,
			},
		},
		filter({item, slider}) {
			return item.dataset.filter?.includes(slider.getData());
		},
	},
	(slider) => {
		slider
			.getElement()
			.closest("section")
			.querySelectorAll("button[data-filter]")
			?.forEach(function (button) {
				button.addEventListener("click", function () {
					slider.setData(this.dataset.filter).filter();
				});
			});
	}
);

tinySlider("[data-slider=\"slider-products\"]", {
	items: 2,
	gutter: 10,
	autoplay: true,
	autoplayTimeout: 3000,
	autoplayButtonOutput: false,
	speed: 1500,
	controls: true,
	controlsText: ["<span>&#10229;</span>", "<span>&#10230;</span>"],
	navAsThumbnails: true,
	navPosition: "bottom",
	swipeAngle: 15,
	displayCounter: true,
	responsive: {
		375: {
			items: 2,
		},

		568: {
			items: 3,
		},
		768: {
			items: 4,
		},
		1024: {
			items: 3,
		},
		1360: {
			items: 4,
		},
	},
});

tinySlider("[data-slider=\"demo-simple\"]", {
	items: 1,
	gutter: 30,
	autoplay: true,
	autoplayTimeout: 3000,
	autoplayButtonOutput: false,
	speed: 1500,
	controls: true,
	controlsText: ["<span>&#10229;</span>", "<span>&#10230;</span>"],
	navAsThumbnails: true,
	navPosition: "bottom",
	swipeAngle: false,
	displayCounter: true,
	responsive: {
		350: {
			items: 2,
		},
		568: {
			items: 3,
		},
	},
});

tinySlider(
	"[data-slider=\"demo-filters\"]",
	{
		items: 1,
		gutter: 30,
		autoplay: true,
		autoplayTimeout: 3000,
		autoplayButtonOutput: false,
		speed: 1500,
		controls: true,
		controlsText: ["<span>&#10229;</span>", "<span>&#10230;</span>"],
		navPosition: "bottom",
		swipeAngle: false,
		responsive: {
			350: {
				items: 2,
			},
			568: {
				items: 3,
			},
		},
		filter({item, slider}) {
			return item.dataset.filter?.includes(slider.getData());
		},
	},
	(slider) => {
		slider
			.getElement()
			.closest("section")
			.querySelectorAll("button[data-filter]")
			?.forEach(function (button) {
				button.addEventListener("click", function () {
					slider.setData(this.dataset.filter).filter();
				});
			});
	}
);

tinySlider("[data-slider=\"our-team__slider\"]", {
	items: 3,
	gutter: 24,
	autoplay: false,
	autoplayTimeout: 3000,
	autoplayButtonOutput: false,
	speed: 1500,
	controls: true,
	nav: false,
	swipeAngle: 15,
	responsive: {
		350: {
			items: 1,
		},
		568: {
			items: 2,
		},
		768: {
			items: 3,
		},
	},
});

const productSliderEl = document.querySelector("[data-slider=\"product-slider\"]");
if (productSliderEl) {
	tinySlider("[data-slider=\"product-slider\"]", {
		loop: false,
		navContainer: ".thumb-slider",
		items: 1,
		navAsThumbnails: true,
		mouseDrag: true,
		center: true,
		controls: false
	});

	tinySlider("[data-slider=\"thumb-slider\"]", {
		axis: "vertical",
		loop: false,
		items: 4,
		mouseDrag: true,
		nav: false,
		controls: false,
		preventScrollOnTouch: true,
	});
}

const productCardsSliderEl = document.querySelector("[data-slider=\"product-cards-slider\"]");
if (productCardsSliderEl) {
	tinySlider("[data-slider=\"product-cards-slider\"]", {
		autoplay: true,
		autoplayTimeout: 3000,
		autoplayButtonOutput: false,
		speed: 1500,
		controls: true,
		controlsText: ["<span>&#10229;</span>", "<span>&#10230;</span>"],
		navPosition: "bottom",
		swipeAngle: 15,
		displayCounter: true,
		loop: false,
		autoHeight: false,
		responsive: {
			375: {
				items: 2,
				gutter: 16,
			},
			568: {
				items: 3,
			},
			1360: {
				gutter: 24,
				items: 4,
			},
		},
	});
}
