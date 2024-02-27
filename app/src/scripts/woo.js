
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
