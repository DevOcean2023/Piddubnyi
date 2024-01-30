document.addEventListener("DOMContentLoaded", () => {
	initQuantity();
});

function initQuantity() {
	const inputWrapper = document.querySelectorAll(".quantity");
	if (!inputWrapper.length) return;

	inputWrapper.forEach(item => {
		let input = item.querySelector(".qty");
		let minus = item.querySelector(".minus");
		let plus = item.querySelector(".plus");
		if (input.value < 2) {
			minus.classList.add("disabled");
		}
		minus.addEventListener("click", event => {
			event.preventDefault();
			let inputValue = input.value;
			let newValue = --inputValue;
			input.value = newValue;
			if (newValue < 2) {
				item.querySelector(".minus").classList.add("disabled");
			}
			input.dispatchEvent(new Event('input', { bubbles: true }));
		});

		plus.addEventListener("click", event => {
			event.preventDefault();
			let inputValue = input.value;
			let newValue = ++inputValue;
			input.value = newValue;
			if (newValue > 1) {
				minus.classList.remove("disabled");
			}
			input.dispatchEvent(new Event('input', { bubbles: true }));
		});
	});
}
