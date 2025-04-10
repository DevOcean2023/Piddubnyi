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

document.addEventListener("DOMContentLoaded", function () {
	/*menu-header-category*/
	const categorySlugs = [
		"face-category",
		"set-category",
		"hair-category",
		"body-category",
		"about-company"
	];

	const buttonContainerPairs = categorySlugs
		.map(slug => {
			const button = document.querySelector(`.${slug}`);
			const container = document.querySelector(`.${slug}-menu-wrapper`);

			if (!button) {
				console.warn(`Категорія зі slug '${slug}' не знайдена, пропускаємо`);
				return null;
			}
			if (!container) {
				console.warn(`Контейнер для slug '${slug}' не знайдений, пропускаємо`);
				return null;
			}
			return { button, container, slug };
		})
		.filter(pair => pair !== null);

	if (buttonContainerPairs.length === 0) {
		console.warn("Жодна активна пара не знайдена. Меню не ініціалізовано.");
	} else {
		function showMenu(container, button) {
			container.style.display = "block";
			const buttonRect = button.getBoundingClientRect();
			if (button.classList.contains("about-company")) {
				container.style.left = `${Math.floor(buttonRect.left) + button.offsetWidth - Math.floor(container.offsetWidth)}px`;
			} else {
				container.style.left = `${buttonRect.left}px`;
			}
			container.style.top = `${buttonRect.bottom}px`;
			button.classList.add("active");
			container.classList.add("active");
		}

		function hideMenu(container, button) {
			container.style.display = "none";
			button.classList.remove("active");
			container.classList.remove("active");
		}

		function isChildOf(child, parent) {
			let node = child?.parentNode;
			while (node) {
				if (node === parent) return true;
				node = node.parentNode;
			}
			return false;
		}

		buttonContainerPairs.forEach(({ button, container }, index) => {
			button.addEventListener("mouseenter", () => {
				buttonContainerPairs.forEach(({ container: otherContainer, button: otherButton }, otherIndex) => {
					if (otherIndex !== index) {
						hideMenu(otherContainer, otherButton);
					}
				});
				showMenu(container, button);
			});

			button.addEventListener("mouseleave", (event) => {
				const relatedTarget = event.relatedTarget;
				const isRelated = isChildOf(relatedTarget, button) || isChildOf(relatedTarget, container);
				if (!isRelated) {
					hideMenu(container, button);
				}
			});

			container.addEventListener("mouseenter", () => {
				showMenu(container, button);
			});

			container.addEventListener("mouseleave", (event) => {
				const relatedTarget = event.relatedTarget;
				if (!isChildOf(relatedTarget, button)) {
					hideMenu(container, button);
				}
			});
		});

		document.addEventListener("mousemove", (event) => {
			const isInsideAny = buttonContainerPairs.some(({ button, container }) =>
				button.contains(event.target) || container.contains(event.target)
			);
			if (!isInsideAny) {
				buttonContainerPairs.forEach(({ button, container }) => {
					hideMenu(container, button);
				});
			}
		});
	}
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

