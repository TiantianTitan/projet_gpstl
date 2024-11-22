/**
 * *TRAITEMENT DU FORMULAIRE DE SAISIE DES UES VALIDES PAR UN REDOUBLANT
 * !NB: Les 'alert' ne servent qu'au debug (alert ne doit pas intervenir dans le control d erreur)(seulement utile au developpeur)
 * Les messages d'erreur sur les entrees du formulaire sont geres par jquery (modification du dom)
 * */

/**
 * retourne un tableau de noms d'ues valides selectionnees
 * @returns {Array|uev}
 */
function getValides() {
    uev = [];
    var valides = document.getElementsByClassName("valide"); //tableau des ues validees (input type=hiden class=valide )
    for (var i = 0; i < valides.length; i++)
        uev.push(valides[i].getAttribute("value"));
}

//Pour chaque checkbox d'ue validee on rajoute(resp. retire) du formulaire(en mode cache) l'ue selectionnee/deselectionnee
function add_ue_valide(ue) { //le parametre ue est la checkbox correspondant a l'ue qu'elle represente

    if (ue.checked == true) {
        var valide = "<input type=\"hidden\" class =\"valide\" id=\"valide_" + ue.getAttribute("name") + "\" \n\
        name=\"valide_" + ue.getAttribute("name") + "\" value=\"" + ue.getAttribute("name") + "\"/>";
        printSupHTML("#hidens", valide);

        getValides();//mise a jour du nombre d ues valides selectionnees
        //alert("true->uev=["+uev+"]");

        //prevention depassement capacite ues valides
        if (uev.length == 6) { //si 6 ue valides selectionnes alors griser les autres ues
            var ues = document.getElementsByClassName("check_ue"); //ues : tableau de toutes les ues
            for (var i = 0; i < ues.length; i++) {
                var nomUE = ues[i].getAttribute("name");
                if (uev.indexOf(nomUE) == -1)
                    ues[i].setAttribute("disabled", "true");
            }
        }
    } else {
        getValides();//mise a jour du nombre d ues valides selectionnees
        //alert("false->uev=["+uev+"]");

        //Degrisement de toutes les ues 
        if (uev.length == 6) { //si 6 ue valides selectionnes alors degriser toutes les ues
            var ues = document.getElementsByClassName("check_ue"); //ues : tableau de toutes les ues
            for (var i = 0; i < ues.length; i++)
                ues[i].removeAttribute("disabled");
        }

        var hidens = document.getElementById("hidens");
        hidens.removeChild(document.getElementById("valide_" + ue.getAttribute("name")));
    }
}

function transmitValides(form) { //le parametre form ne sera pas utilise
    getValides();
    //alert("final uev=[" + uev + "]");
    var url = "choix_ues.php";
    if (uev.length > 0) {
        url += "?uev1=" + uev[0];
        for (var i = 1; i < uev.length; i++)
            url += "&uev" + (i + 1) + "=" + uev[i];
    }
    redirect(url);
}