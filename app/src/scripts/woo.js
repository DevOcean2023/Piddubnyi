import select2 from "../../base/defaults/scripts/jquery/libs/select2";

document.addEventListener("DOMContentLoaded", function () {
	initFilterControls();
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


$(document).ready(function () {
	initCustomSelects();
});

function initCustomSelects() {
	$(".custom-select").select2({
		minimumResultsForSearch: -1,
	});
}

////filer-button-expand
document.addEventListener("DOMContentLoaded", function () {
	const liElements = document.querySelectorAll(".wpfFilterVerScroll li");

	liElements.forEach(function (li) {
		const ulElement = li.querySelector("ul");

		if (ulElement) {
			const divElement = document.createElement("div");
			divElement.className = "filer-button-expand";
			li.insertBefore(divElement, li.querySelector('.wpfLiLabel'));
		}
	});

	const expandButtons = document.querySelectorAll(".filer-button-expand");

	expandButtons.forEach(function (button) {
		button.addEventListener("click", function () {
			const liElement = button.closest('li');
			const ulElement = liElement.querySelector('ul');

			if (ulElement) {
				ulElement.classList.toggle("filter-display-on");
				button.classList.toggle("filer-button-expand_active-expand");
				const siblingUls = liElement.querySelectorAll('ul');
				siblingUls.forEach(function (ul) {
					if (ul !== ulElement) {
						ul.classList.remove("filter-display-on");
					}
				});
			}
		});
	});

});
