addEventListener("message",(async t=>{const[e,a]=t.data;await fetch(a,{method:"POST",headers:{"Content-Type":"application/json;charset=utf-8"},body:JSON.stringify(e)})}));