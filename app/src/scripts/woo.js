import Swiper from "swiper";
import "swiper/css";

document.addEventListener("DOMContentLoaded", function () {
	initFilterControls();
	// initProductSlider();
});

function initFilterControls() {
	let filter = document.querySelector(".shop-page__filter-holder");
	if (!filter) return;

	let openBnt = document.querySelector(".shop-page__filter-open-btn");
	let closeBnt = document.querySelector(".shop-page__filter-close-btn");
	openBnt.addEventListener("click", e => {
		e.preventDefault();
		document.body.classList.add("filters-opened");
	});
	closeBnt.addEventListener("click", e => {
		e.preventDefault();
		document.body.classList.remove("filters-opened");
	});
}

function initProductSlider() {
	const sliderThumbs = document.querySelectorAll(".swiper-thumbs");

	if (sliderThumbs.length) {
		Array.from(sliderThumbs).forEach(sliderEl => {
			const swiper = new Swiper(sliderEl, {
				direction: "vertical",
				slidesPerView: 4,
				spaceBetween: 24,
				loop: false,
				freeMode: true,
				watchSlidesProgress: true,
				mousewheel: true,
			});
		});
	}

	const sliderProduct = document.querySelectorAll(".product-images");

	if (sliderProduct.length) {
		Array.from(sliderProduct).forEach(sliderEl => {
			let thumbs = sliderEl.nextElementSibling.querySelector(".swiper-thumbs").swiper;
			const swiper = new Swiper(sliderEl, {
				loop: false,
				thumbs: {
					swiper: thumbs,
				},
				slideClass: "product-img",
			});
		});
	}
}
