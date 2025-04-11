//import domReady from '../../base/defaults/scripts/libs/dom-ready';
//import liveDom from '../../base/defaults/scripts/libs/live-dom';
import "../../base/defaults/scripts/main";
// import './quantity';
import "../../base/defaults/scripts/libs/accessibility-accordion";
import defer from "../../base/defaults/scripts/jquery/libs/jquery-defer";
import "./woo";

/*menu*/

document.addEventListener("DOMContentLoaded", function () {
	var categoryMenus = document.querySelectorAll(".inner-category");
	categoryMenus.forEach(function (menu) {
		var subMenu = menu;
		if (subMenu && subMenu.children.length > 4) {
			menu.parentElement.classList.add("big");
		}
	});
});


///marquee
defer(() => {
	$(function () {
		$(".marquee").marquee({
			duration: 14000,
			startVisible: true,
			duplicated: true,
		});
	});
});


/* search */

document.addEventListener("DOMContentLoaded", function () {
	const searchLink = document.querySelector(".button__search");
	const searchForm = document.querySelector(".wrap-search");

	if (searchLink && searchForm) {
		searchLink.addEventListener("click", function (event) {
			searchForm.classList.add("active");
		});

		const closeFormButton = searchForm.querySelector(".close-button");
		if (closeFormButton) {
			closeFormButton.addEventListener("click", function (event) {
				searchForm.classList.remove("active");
			});
		}
	}
});

