!function(){function e(){return headers={"Content-Type":"application/json"},document.body.classList.contains("logged-in")&&(headers["X-WP-Nonce"]=pgps.api.nonce),headers}pgps.api.get=async function(e){let t=pgps.api.root+"skeletons";e&&(t+=`/${e}`);let n=await fetch(t);if(!n.ok)throw new Error(`HTTP Error: ${n.status}`);return n.json()},pgps.api.post=async function(t,n){let o=await fetch(pgps.api.root+"skeletons",{method:"POST",credentials:"same-origin",headers:e(),body:JSON.stringify({title:t,value:n})});if(!o.ok)throw new Error(`HTTP Error: ${o.status}`);return o.json()},pgps.api.delete=async function(t){let n=pgps.api.root+`skeletons/${t}`,o=await fetch(n,{method:"DELETE",headers:e(),credentials:"same-origin"});if(!o.ok)throw new Error(`HTTP Error: ${o.status}`);return o.json()},pgps.api.update=async function(t,n,o){let a=pgps.api.root+`skeletons/${t}`,s=await fetch(a,{method:"PATCH",headers:e(),credentials:"same-origin",body:JSON.stringify([{op:"replace",path:"/post_title",value:n},{op:"replace",path:"/post_meta/skeleton_meta",value:o}])});if(!s.ok)throw new Error(`HTTP Error: ${s.status}`);return s.json()},pgps.api.sendMessage=async function(t,n,o){let a=pgps.api.root+"messages";await fetch(a,{method:"POST",headers:e(),credentials:"same-origin",body:JSON.stringify({message:t,name:n,email:o})})}}(),window.addEventListener("load",function(){document.getElementById("frm-create-skeleton").addEventListener("submit",function(e){e.preventDefault();let t=document.getElementById("inp-title").value,n=document.getElementById("inp-value").value;pgps.api.post(t,n).then(t=>{e.target.reset(),window.location.reload(!0)})})}),window.addEventListener("load",function(){for(let e of document.querySelectorAll(".delete-skeleton-button"))e.addEventListener("click",function(t){let n=e.id.split("-").pop();pgps.api.delete(n).then(e=>{window.location.reload(!0)})})}),window.addEventListener("load",function(){document.querySelector("#pgps-email-form").addEventListener("submit",function(e){e.preventDefault();let t=document.querySelector("#pgps-message").value,n=document.querySelector("#pgps-contact-name").value,o=document.querySelector("#pgps-contact-email").value;pgps.api.sendMessage(t,n,o).then(t=>{e.target.reset(),alert("message sent!")})})}),window.addEventListener("load",function(){for(let e of document.querySelectorAll(".update-skeleton-form"))e.addEventListener("submit",function(t){t.preventDefault();let n=e.id.split("-").pop(),o=document.querySelector("#inp-title-"+n).value,a=document.querySelector("#inp-value-"+n).value;pgps.api.update(n,o,a).then(e=>{alert("success!")})})});