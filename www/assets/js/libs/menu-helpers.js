export const openTypes=new WeakMap;function e(e,n,t){let i="";return e.classList.forEach((e=>{n.test(e)&&(i=e)})),t&&(i+="_"+t),i}function n(n,t){n.classList.toggle(e(n,/__link$/,"open"),t)}function t(n,t){n.classList.toggle(e(n,/__item$/,"open"))}function i(n,t){n.classList.toggle(e(n,/__expend-button$/,"open"),t)}export function toggle(e,n){e.hidden?open(e,n):close(e,n)}export function open(e,o){openTypes.set(e,o),e.hidden=!1,e.previousElementSibling.ariaExpanded="true",i(e.previousElementSibling,!0),n(e.previousElementSibling.previousElementSibling,!0),t(e.parentElement)}export function close(e,o){openTypes.get(e)===o&&(openTypes.set(e,""),e.hidden=!0,e.previousElementSibling.ariaExpanded="false",e.querySelectorAll("button[aria-expanded]").forEach((e=>{close(e.nextElementSibling,o)})),i(e.previousElementSibling,!1),n(e.previousElementSibling.previousElementSibling,!1),t(e.parentElement))}export function setCloseEvents(e,n,t){e.addEventListener("keydown",(t=>{openTypes.get(e)===n&&"Escape"===t.key&&(t.preventDefault(),t.stopPropagation(),close(e,n),"expend"===n?e.previousElementSibling.focus():e.previousElementSibling.previousElementSibling.focus())})),t&&e.addEventListener("focusout",(t=>{openTypes.get(e)!==n||e.contains(t.relatedTarget)||e.previousElementSibling===t.relatedTarget||close(e,n)}))}