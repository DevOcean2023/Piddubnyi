export default function e(e=!1,n){const s=document.createElement("div"),a=n.getInfo().container.closest(".tns-outer");s.setAttribute("aria-live","polite"),s.setAttribute("aria-atomic","true"),s.classList.add("screen-reader-text"),s.innerHTML+='slide <span class="current">1</span> of <span class="total"></span>',a.prepend(s),a.querySelector(".tns-liveregion").style.display="none",t(s,n),function(e,n,s){if(e){const e=document.createElement("div");e.setAttribute("aria-hidden","true"),e.classList.add("slide-counter"),e.innerHTML+='<span class="current">1</span><span class="separator">/</span><span class="total"></span>',n.after(e),t(e,s)}}(e,a,n)}function t(e,t){const n=e.querySelector(".current");e.querySelector(".total").textContent=t.getInfo().slideCount,t.events.on("indexChanged",(e=>n.textContent=e.displayIndex))}