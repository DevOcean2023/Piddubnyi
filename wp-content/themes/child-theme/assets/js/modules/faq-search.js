import e from"../libs/live-dom.js";import{scrollToElement as t}from"../libs/smooth-scroll.js";e(".faq").init((function(){const e=this,r=e.querySelector(".faq__search-form");if(r){const o=e.querySelector('[type="search"]'),i=()=>{const t=e.querySelectorAll(".accordion");if(o.value){const e=new RegExp(o.value,"i");t.forEach((t=>{e.test(t.innerText)?t.style.removeProperty("display"):t.style.display="none"}))}else t.forEach((e=>{e.style.removeProperty("display")}))};o.addEventListener("input",i),r.addEventListener("reset",(()=>setTimeout(i,100))),r.addEventListener("submit",(r=>{r.preventDefault(),t(e.querySelector('[role="tablist"]'),30).then()}))}e.querySelector(".faq__tabs").addEventListener("theme-tabs-ready",(()=>{const t=e.querySelector(".accordion"),r=t.querySelector(".accordion__trigger"),o=t.querySelector(".accordion__panel");t.classList.add("accordion_active"),r.setAttribute("aria-expanded","true"),o.removeAttribute("hidden")}))}));