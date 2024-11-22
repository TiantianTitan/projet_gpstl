/**
 * UTILS
 * Ensemble de fonctions utilitaires en particulier quelques encapsulations des fonctions de bases de jquery
 */

function isNumber(s) {
    return !isNaN(s - 0);
}

function printHTML(dom, htm) {
    $(dom).html(htm);
}

function printSupHTML(dom, htm) {
    $(dom).append(htm);
}

function printAfterHTML(dom, htm) {
    $(dom).after(htm);
}
function printBeforeHTML(dom, htm) {
    $(dom).before(htm);
}

//redirige vers la page d'url 'location '
function redirect(location) { 
    window.location.href = location;
}

//attache le nom de l'ue a son groupe [Dlp,1]-> Dlp-1
function bind(tab){
    return tab.join("-");
}

//attache le nom de l'ue a son groupe [Dlp,1]-> Dlp1
function bond(tab){
    return tab.join("");
}


//attache les noms des ues a leurs groupes respectifs [[Dlp,1],[IL,2]]->(Dlp-1,IL-2)
function bindAll(list){ 
    //alert("oldlist="+JSON.stringify(list));
    var newlist=[];
    for(var i=0;i<list.length;i++)
        newlist.push(bind(list[i]));
    //alert("newlist="+JSON.stringify(newlist));
    return newlist;
}

//attache les noms des ues a leurs groupes respectifs [[Dlp,1],[IL,2]]->(Dlp1,IL2)
function bondAll(list){ 
    //alert("oldlist="+JSON.stringify(list));
    var newlist=[];
    for(var i=0;i<list.length;i++)
        newlist.push(bond(list[i]));
    //alert("newlist="+JSON.stringify(newlist));
    return newlist;
}


//detache le nom de l'ue de son groupe Dlp-1->[Dlp,1]
function unbind(str){  
    return str.split("-");
}

//detache les noms des ues de leurs groupes respectifs (Dlp-1,IL-2)->[[Dlp,1],[IL,2]]
function unbindAll(list){  
    //alert("oldlist="+JSON.stringify(list));
    var newlist=[];
    for(var i=0;i<list.length;i++)
        newlist.push(unbind(list[i]));
    //alert("newlist="+JSON.stringify(newlist));
    return newlist;
}