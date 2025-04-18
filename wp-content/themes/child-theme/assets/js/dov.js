(function ($) {
	"use strict";
	var dov = dov || {};
	$(document).ready(function () {
		dov.init();
		dov.countQuantityInput();
		dov.miniCart();
		dov.checkoutProductList();

		$(document.body).on("updated_checkout", function () {
			dov.countQuantityInput();
		});

		$(document.body).on("updated_cart_totals", function () {
			dov.countQuantityInput();
		});
	});

	function debounce(func, timeout = 300) {
		let timer;
		return (...args) => {
			clearTimeout(timer);
			timer = setTimeout(() => {
				func.apply(this, args);
			}, timeout);
		};
	}

	dov.init = function () {
		$(document).on("change", ".woocommerce-cart-form td.product-quantity input[type='number']", debounce(function () {
			$(".woocommerce-cart-form [name='update_cart']").removeAttr("disabled").trigger("click");
		}, 1000));
	};

	dov.miniCart = function () {
		let timer = null;
		$(document).on("click", ".mini-cart .quantity .minus, .mini-cart .quantity .plus", function (e) {
			e.preventDefault();
			restartTimer();
			let $holder = $(this).closest("li");
			$holder.addClass("for-update-el");
			let val = parseInt($holder.find(".qty").attr("value"));
			if ($(this).hasClass("minus")) {
				val--;
				$holder.find(".qty").attr("value", val);
			} else {
				val++;
				$holder.find(".qty").attr("value", val);
			}
		});

		function restartTimer() {
			clearTimeout(timer);
			timer = setTimeout(updateMiniCart, 200);
		}

		function updateMiniCart() {
			$(".mini-cart li.for-update-el").each(function () {
				let $that = $(this);
				let $inp = $that.find("input[type=\"number\"]");
				let val = parseInt($inp.val());
				let key = $inp.attr("name").replace("cart[", "").replace("][qty]", "");
				$that.block({
					message: null,
					overlayCSS: {
						background: "#fff",
						opacity: 0.6
					}
				});

				let ajax_data = {
					"action": "theme_update_mini_cart",
					"key": key,
					"qty": val,
					"nonce": theme.ajaxNonce
				};

				$.ajax({
					url: theme.ajaxUrl,
					data: ajax_data,
					type: "POST",
					success: function (res) {
						$that.unblock();
						if (!res || res.error)
							return;

						if (res) {
							$.each(res, function (key, value) {
								$(key).replaceWith(value);
							});
						}

						dov.countQuantityInput();
						jQuery("body").trigger("update_checkout");
					},
					error: function (res) {
						console.log(res);
					}
				});
			});
		}

		$(document).on("click", ".mini-cart__item-remove", function (e) {
			e.preventDefault();
			let $holder = $(this).closest("li");
			let val = 0;
			$holder.block({
				message: null,
				overlayCSS: {
					background: "#fff",
					opacity: 0.6
				}
			});
			let key = $(this).attr("data-cartkey");
			let ajax_data = {
				"action": "theme_update_mini_cart",
				"key": key,
				"qty": val,
				"nonce": theme.ajaxNonce
			};
			$.ajax({
				url: theme.ajaxUrl,
				data: ajax_data,
				type: "POST",
				success: function (res) {
					$holder.unblock();
					if (!res || res.error)
						return;

					if (res) {
						$.each(res, function (key, value) {
							$(key).replaceWith(value);
						});
					}

					dov.countQuantityInput();
					jQuery("body").trigger("update_checkout");
				},
				error: function (res) {
					console.log(res);
				}
			});
		});
	};

	dov.checkoutProductList = function () {
		let timer = null;
		$(document).on("click", ".shop_table .quantity .minus, .shop_table .quantity .plus", function (e) {
			//e.preventDefault();
			restartTimer();
			let $holder = $(this).closest("tr");
			$holder.addClass("for-update-el");
		});

		function restartTimer() {
			clearTimeout(timer);
			timer = setTimeout(updateMiniCart, 200);
		}

		function updateMiniCart() {
			$(".shop_table tr.for-update-el").each(function () {
				let $that = $(this);
				let $inp = $that.find("input[type=\"number\"]");
				let val = parseInt($inp.val());
				let key = $inp.attr("name").replace("cart[", "").replace("][qty]", "");
				$that.block({
					message: null,
					overlayCSS: {
						background: "#fff",
						opacity: 0.6
					}
				});

				let ajax_data = {
					"action": "theme_update_mini_cart",
					"key": key,
					"qty": val,
					"nonce": theme.ajaxNonce
				};

				$.ajax({
					url: theme.ajaxUrl,
					data: ajax_data,
					type: "POST",
					success: function (res) {
						$that.unblock();
						if (!res || res.error)
							return;

						dov.countQuantityInput();

						if (res) {
							$.each(res, function (key, value) {
								$(key).replaceWith(value);
							});
						}

						jQuery("body").trigger("update_checkout");
					},
					error: function (res) {
						console.log(res);
					}
				});
			});
		}
	};

	dov.countQuantityInput = function () {
		const inputWrapper = document.querySelectorAll(".quantity");

		if (inputWrapper.length) {
			inputWrapper.forEach(item => {
				if (!$(item).hasClass("activated")) {
					let input = item.querySelector(".qty");
					let minus = item.querySelector(".minus");
					let plus = item.querySelector(".plus");

					if (!input || !minus || !plus) return;

					if (parseInt(input.value) <= 1) {
						minus.classList.add("disabled");
					}

					minus.addEventListener("click", function (event) {
						event.preventDefault();
						let inputValue = parseInt(input.value);
						if (inputValue > 1) {
							inputValue--;
							input.value = inputValue;
							if (inputValue <= 1) {
								minus.classList.add("disabled");
							}
							$(input).trigger("change");
						}
					});

					plus.addEventListener("click", function (event) {
						event.preventDefault();
						let inputValue = parseInt(input.value);
						let max = input.getAttribute("max") ? parseInt(input.getAttribute("max")) : 999;

						if (inputValue < max) {
							inputValue++;
							input.value = inputValue;
							if (inputValue > 1) {
								minus.classList.remove("disabled");
							}
							$(input).trigger("change");
						}
					});

					$(item).addClass("activated");
				}
			});
		}
	};

	$(".single_variation_wrap").on("show_variation", function (event, variation) {
		let element = document.querySelector(".single_variation_wrap");
		let delElement = element.querySelector(".woocommerce-variation-price del bdi");
		let delElementAmount = element.querySelector(".woocommerce-variation-price del .amount");
		let insElement = element.querySelector(".woocommerce-variation-price ins bdi");

		if (delElement && insElement) {
			let fullPrice = parseFloat(delElement.textContent.replace(/[^\d.]/g, "").replace(",", ""));
			let discountedPrice = parseFloat(insElement.textContent.replace(/[^\d.]/g, "").replace(",", ""));

			if (!isNaN(fullPrice) && !isNaN(discountedPrice)) {
				let discountPercentage = Math.round(((fullPrice - discountedPrice) / fullPrice) * 100);
				let salesElement = document.createElement("div");
				salesElement.className = "sales";
				salesElement.innerText = "-" + discountPercentage + "%";
				delElementAmount.appendChild(salesElement);
			} else {
				console.error("Помилка при отриманні значень цін для карточки товару.");
			}
		}

		let stockStatus = $(".product_stock-status");
		if (!variation.is_in_stock) {
			stockStatus.addClass("out-of-stock");
			stockStatus.text("Немає в наявності");
		} else {
			stockStatus.removeClass("out-of-stock");
			stockStatus.text("В наявності");
		}
	});
})(jQuery);

document.addEventListener("DOMContentLoaded", function () {
	initInputMask();
	checkProductList();
});

function initInputMask() {
	let accountPhone = document.getElementById("account_phone");
	let billingPhone = document.getElementById("billing_phone");

	let im = new Inputmask("+38(999)999-99-99");
	if (accountPhone) {
		im.mask(accountPhone);
	}
	if (billingPhone) {
		im.mask(billingPhone);
	}
}

function checkProductList() {
	let products = document.querySelectorAll(".shop-page__products-holder .product");
	let sidebar = document.querySelector(".shop-page__sidebar");
	if (!products.length && sidebar) {
		sidebar.classList.add("hidden");
	}
}

////////////////////////login-section
document.addEventListener("DOMContentLoaded", function () {
	const loginOpen = document.getElementById("u-column1");
	const loginClose = document.getElementById("u-column2");
	const loginButtonOpen = document.querySelector(".form-login-registration-button .close-login");
	const registerButtonOpen = document.querySelector(".form-login-registration-button .close-registration");
	const loginShow = document.querySelector(".showlogin");

	function saveDataToLocalStorage(key, value) {
		localStorage.setItem(key, value);
	}

	function getDataFromLocalStorage(key) {
		return localStorage.getItem(key);
	}

	function hideWooCommerceErrors() {
		const errorElements = document.querySelectorAll(".woocommerce-error");
		errorElements.forEach(function (errorElement) {
			errorElement.style.display = "none";
		});
	}

	function activateButton(button) {
		button.classList.add("active");
	}

	function deactivateButton(button) {
		button.classList.remove("active");
	}

	function addHeadClass(button) {
		button.classList.add("head");
	}

	function removeHeadClass(button) {
		button.classList.remove("head");
	}

	if (loginButtonOpen) {
		loginButtonOpen.addEventListener("click", function () {
			loginClose.classList.add("show-login");
			loginOpen.classList.add("hide-login");
			saveDataToLocalStorage("loginState", "closed");
			activateButton(loginButtonOpen);
			deactivateButton(registerButtonOpen);
			removeHeadClass(registerButtonOpen); // Додали цей рядок
		});
	}

	if (registerButtonOpen) {
		registerButtonOpen.addEventListener("click", function () {
			loginOpen.classList.remove("hide-login");
			loginClose.classList.remove("show-login");
			saveDataToLocalStorage("loginState", "open");
			activateButton(registerButtonOpen);
			deactivateButton(loginButtonOpen);
			removeHeadClass(registerButtonOpen);
		});
	}

	if (loginShow) {
		loginShow.addEventListener("click", function () {
			loginOpen.classList.remove("hide-login");
			loginClose.classList.remove("show-login");
			saveDataToLocalStorage("loginState", "open");
			activateButton(registerButtonOpen);
			deactivateButton(loginButtonOpen);
			removeHeadClass(registerButtonOpen);
		});
	}

	const savedLoginState = getDataFromLocalStorage("loginState");
	if (savedLoginState === "closed") {
		loginClose.classList.add("show-login");
		loginOpen.classList.add("hide-login");
		activateButton(loginButtonOpen);
		deactivateButton(registerButtonOpen);
		removeHeadClass(registerButtonOpen);
	}
	if (savedLoginState === "open" && registerButtonOpen) {
		addHeadClass(registerButtonOpen);
	}
});

/////////////add class to login-section
document.addEventListener("DOMContentLoaded", function () {
	var customerLogin = document.getElementById("customer_login");
	if (customerLogin) {
		document.body.classList.add("login-section");
	}
});

////////////////////////add sale to shop
let instockElements = document.querySelectorAll("body.woocommerce-shop .sale, body.tax-product_cat .sale");

instockElements.forEach(function (instockElement) {
	let delElement = instockElement.querySelector("del bdi");
	let insElement = instockElement.querySelector("ins bdi");
	if (delElement || insElement) {
		let fullPrice = parseFloat(delElement.textContent.replace(/[^\d.]/g, "").replace(",", ""));
		let discountedPrice = parseFloat(insElement.textContent.replace(/[^\d.]/g, "").replace(",", ""));
		if (!isNaN(fullPrice) && !isNaN(discountedPrice)) {
			let discountPercentage = Math.round(((fullPrice - discountedPrice) / fullPrice) * 100);
			let salesElement = document.createElement("div");
			salesElement.className = "sales";
			salesElement.innerText = "-" + discountPercentage + "%";
			instockElement.appendChild(salesElement);
		} else {
			console.error("Помилка при отриманні значень цін для карточки товару.");
		}
	}
});

////////////////////////add sale to single product
let productSimple = document.querySelectorAll("body.single-product .product-type-simple");

productSimple.forEach(function (instockElement) {
	let delElement = instockElement.querySelector("del bdi");
	let insElement = instockElement.querySelector("ins bdi");
	let amountElement = instockElement.querySelector(".price del .amount");
	if (delElement || insElement) {
		let fullPrice = parseFloat(delElement.textContent.replace(/[^\d.]/g, "").replace(",", ""));
		let discountedPrice = parseFloat(insElement.textContent.replace(/[^\d.]/g, "").replace(",", ""));
		if (!isNaN(fullPrice) && !isNaN(discountedPrice)) {
			let discountPercentage = Math.round(((fullPrice - discountedPrice) / fullPrice) * 100);
			let salesElement = document.createElement("div");
			salesElement.className = "sales";
			salesElement.innerText = "-" + discountPercentage + "%";
			amountElement.appendChild(salesElement);
		} else {
			console.error("Помилка при отриманні значень цін для карточки товару.");
		}
	}
});

////////////////////////add sale to wishlist
function processElements(parentElement) {
	let saleElements = parentElement.querySelectorAll(".sale, .wishlist_item");

	saleElements.forEach(function (element) {
		let delElement = element.querySelector(".product-price del bdi");
		let insElement = element.querySelector(".product-price ins bdi");

		if (delElement && insElement) {
			let fullPrice = parseFloat(delElement.textContent.replace(/[^\d.]/g, "").replace(",", ""));
			let discountedPrice = parseFloat(insElement.textContent.replace(/[^\d.]/g, "").replace(",", ""));

			if (!isNaN(fullPrice) && !isNaN(discountedPrice)) {
				let discountPercentage = Math.round(((fullPrice - discountedPrice) / fullPrice) * 100);
				let salesElement = document.createElement("div");
				salesElement.className = "sales";
				salesElement.innerText = "-" + discountPercentage + "%";
				element.appendChild(salesElement);
			} else {
				console.error("Помилка при отриманні значень цін для карточки товару.");
			}
		}
	});
}

processElements(document);


///////////////////////add sale to home products
let productHome = document.querySelectorAll(".home-products .product");

productHome.forEach(function (instockElement) {
	let delElement = instockElement.querySelector("del bdi");
	let insElement = instockElement.querySelector("ins bdi");
	let amountElement = instockElement.querySelector(".price del .amount");
	if (delElement || insElement) {
		let fullPrice = parseFloat(delElement.textContent.replace(/[^\d.]/g, "").replace(",", ""));
		let discountedPrice = parseFloat(insElement.textContent.replace(/[^\d.]/g, "").replace(",", ""));
		if (!isNaN(fullPrice) && !isNaN(discountedPrice)) {
			let discountPercentage = Math.round(((fullPrice - discountedPrice) / fullPrice) * 100);
			let salesElement = document.createElement("div");
			salesElement.className = "sales";
			salesElement.innerText = "-" + discountPercentage + "%";
			amountElement.appendChild(salesElement);
		} else {
			console.error("Помилка при отриманні значень цін для карточки товару.");
		}
	}
});

//////////add class to contact my account
jQuery.noConflict();
jQuery(document).ready(function ($) {
	if ($(".wrapper-form-account").length > 0) {
		$(".default-page__p").addClass("deactive");
		$("button[name=\"save_account_details\"]").hide();
		$(".edit-fields-link").click(function () {
			$(this).hide();
			$("button[name=\"save_account_details\"]").show();
			$(".default-page__p").removeClass("deactive");
			$(".default-page__p input").prop("readonly", false);
			$(".password-fields").toggle();
		});
		$("button[name=\"save_account_details\"]").click(function () {
			console.log($(this));
			$(this).hide();
			$(".edit-fields-link").show();
			$(".default-page__p").addClass("deactive");
			$(".default-page__p input").prop("readonly", true);
			$(".password-fields").hide();
		});
	}
});
//////////////////confirmPassword
document.addEventListener("DOMContentLoaded", function () {
	var uColumn2Element = document.getElementById("u-column2");

	if (uColumn2Element) {
		var registerButton = uColumn2Element.querySelector(".woocommerce-form-register__submit");
		var errorMessageElement = uColumn2Element.querySelector(".text-error");
		registerButton.addEventListener("click", function (event) {
			var password = uColumn2Element.querySelector("#reg_password").value;
			var confirmPassword = uColumn2Element.querySelector("#reg_confirm_password").value;
			if (password !== confirmPassword) {
				errorMessageElement.style.display = "block";
				event.preventDefault();
			} else {
				errorMessageElement.style.display = "none";
			}
		});
	}
});


//////services-tab
document.addEventListener("DOMContentLoaded", function () {
	var homeServicesLinks = document.querySelectorAll(".home-services__permalink");

	homeServicesLinks.forEach(function (link) {
		link.addEventListener("click", function (event) {
			event.preventDefault();
			var serviceId = link.getAttribute("data-service-id");
			localStorage.setItem("selectedServiceId", serviceId);
			window.location.href = "/our-services";
		});
	});
});

document.addEventListener("DOMContentLoaded", function () {
	var selectedServiceId = localStorage.getItem("selectedServiceId");
	if (selectedServiceId) {
		var tabToActivate = document.querySelector("[data-service-id=\"" + selectedServiceId + "\"]");
		if (tabToActivate) {
			tabToActivate.click();
		}
		localStorage.removeItem("selectedServiceId");
	}
});

///////////service-accordion
document.addEventListener("DOMContentLoaded", function () {
	// Отримати айді поста з sessionStorage
	var selectedServiceId = sessionStorage.getItem("selectedServiceId");

	var accordions = document.querySelectorAll(".accordion");

	if (accordions.length > 0) {
		if (selectedServiceId) {
			openAccordion(selectedServiceId);
		} else {
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
	}
});

var homeServiceLinks = document.querySelectorAll(".home-services__permalink");
homeServiceLinks.forEach(function (link) {
	link.addEventListener("click", function (event) {
		event.preventDefault();
		var serviceId = link.getAttribute("data-service-id");
		console.log("Clicked Service ID:", serviceId);
		saveServiceIdToSessionStorage(serviceId);
		setTimeout(function () {
			window.location.href = "/our-services";
		}, 1000);
	});
});

function saveServiceIdToSessionStorage(serviceId) {
	sessionStorage.setItem("selectedServiceId", serviceId);
}

function openAccordion(serviceId) {

	var targetAccordion = document.getElementById("accordion-" + serviceId);

	if (targetAccordion) {
		var isExpanded = targetAccordion.getAttribute("aria-expanded") === "true";
		if (!isExpanded) {
			var accordions = document.querySelectorAll(".accordion");

			accordions.forEach(function (accordion) {
				accordion.setAttribute("aria-expanded", "false");
				accordion.classList.remove("accordion_active");

				var tabPanel = accordion.querySelector(".accordion__panel");
				if (tabPanel) {
					tabPanel.setAttribute("hidden", "true");
				}
				var accordionButton = accordion.querySelector(".accordion__trigger");
				if (accordionButton) {
					accordionButton.setAttribute("aria-expanded", "false");
				}
			});
			targetAccordion.setAttribute("aria-expanded", "true");
			targetAccordion.classList.add("accordion_active");

			var tabPanel = targetAccordion.querySelector(".accordion__panel");
			if (tabPanel) {
				tabPanel.removeAttribute("hidden");
			}
			var accordionButton = targetAccordion.querySelector(".accordion__trigger");
			if (accordionButton) {
				accordionButton.setAttribute("aria-expanded", "true");
			}
			targetAccordion.scrollIntoView({behavior: "smooth", block: "start"});
		}
	}
}

