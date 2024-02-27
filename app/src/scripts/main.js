//import domReady from '../../base/defaults/scripts/libs/dom-ready';
//import liveDom from '../../base/defaults/scripts/libs/live-dom';
import "../../base/defaults/scripts/main";
// import './quantity';
import "../../base/defaults/scripts/libs/accessibility-accordion";
import defer from "../../base/defaults/scripts/jquery/libs/jquery-defer";
import "./woo";

/*menu*/

var categoryMenus = document.querySelectorAll('.inner-category');
categoryMenus.forEach(function (menu) {
	var subMenu = menu;
	if (subMenu && subMenu.children.length > 4) {
		menu.parentElement.classList.add('big');
	}
});

/*menu-header-category*/
function showMenu(container, button) {
	if (container && button) {
		container.style.display = 'block';
		const buttonRect = button.getBoundingClientRect();
		container.style.position = 'absolute';

		if (button.classList.contains('about-company')) {
			container.style.right = window.innerWidth - buttonRect.right - 17 + 'px';
		} else {
			container.style.left = buttonRect.left + 'px';
		}
		container.style.top = buttonRect.bottom + 'px';
		button.classList.add('active');
		container.classList.add('active');
	}
}
function hideMenu(container, button) {
	if (container) {
		container.style.display = 'none';
		button.classList.remove('active');
		container.classList.remove('active');
	}
}
function isChildOf(child, parent) {
	let node = child.parentNode;
	while (node != null) {
		if (node == parent) {
			return true;
		}
		node = node.parentNode;
	}
	return false;
}
const buttons = [document.querySelector('.face-category'), document.querySelector('.hair-category'), document.querySelector('.body-category'), document.querySelector('.about-company')];
const containers = [document.querySelector('.face-category-menu-wrapper'), document.querySelector('.hair-category-menu-wrapper'), document.querySelector('.body-category-menu-wrapper'), document.querySelector('.about-company-menu-wrapper')];
buttons.forEach((button, index) => {
	button.addEventListener('mouseenter', () => {
		containers.forEach((container, containerIndex) => {
			if (containerIndex !== index) {
				hideMenu(container, buttons[containerIndex]);
			}
		});
		showMenu(containers[index], button);
	});
	button.addEventListener('mouseleave', (event) => {
		const relatedTarget = event.relatedTarget;
		const isRelatedToContainerOrButton = containers.some((container, containerIndex) => {
			return (
				(containerIndex === index && isChildOf(relatedTarget, button)) ||
				(containerIndex !== index && isChildOf(relatedTarget, container))
			);
		});
		if (!isRelatedToContainerOrButton) {
			hideMenu(containers[index], button);
		}
	});
});

document.addEventListener('mousemove', (event) => {
	const isInsideAnyContainer = containers.some(container => container.contains(event.target));
	const isInsideAnyButton = buttons.some(button => button.contains(event.target));

	if (!isInsideAnyContainer && !isInsideAnyButton) {
		containers.forEach(container => hideMenu(container, buttons[containers.indexOf(container)]));
	}
});
containers.forEach((container, index) => {
	container.addEventListener('mouseenter', () => {
		showMenu(container, buttons[index]);
	});
	container.addEventListener('mouseleave', (event) => {
		const relatedTarget = event.relatedTarget;
		const isRelatedToButton = buttons.some((button, buttonIndex) => {
			return (buttonIndex === index && isChildOf(relatedTarget, button));
		});
		if (!isRelatedToButton) {
			hideMenu(container, buttons[index]);
		}
	});
});

///
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

	if (searchLink) {
		searchLink.addEventListener("click", function (event) {
			searchForm.classList.add('active');
		});

		const closeFormButton = searchForm.querySelector(".close-button");
		if (closeFormButton) {
			closeFormButton.addEventListener("click", function (event) {
				searchForm.classList.remove('active');
			});
		}
	}
});

//*Accordion*//
document.addEventListener("DOMContentLoaded", function() {
	var accordions = document.querySelectorAll(".accordion");
	if (accordions.length > 0) {
		var firstAccordion = accordions[0];
		if (firstAccordion) {
			firstAccordion.setAttribute("aria-expanded", "true");
			firstAccordion.classList.add("accordion_active");
			var tabPanel = firstAccordion.querySelector(".accordion__panel");
			if (tabPanel) {
				tabPanel.removeAttribute("hidden");
			}
			var firstAccordionButton = firstAccordion.querySelector(".accordion__trigger");
			if (firstAccordionButton) {
				firstAccordionButton.setAttribute("aria-expanded", "true");
			}
		}
	}
});
