/**
 * Effectifs.js est un bonus non necessaire a la recuperation des effectifs.
 * En effet on recupere deja les effectifs dans choix_ues.php.
 * Mais ces donnees ne sont recuperees qu'une SEULE fois au debut du script de la page.
 * Pour avoir des effectifs mis a jour en temps reel(a chaque nouveau choix d'un edt(5e click))
 * on utilise refreshEffectifs() //Ajax (recuperation asynchrone des effectifs)
 * Nb: la fonction refreshEffectifs() est silencieuse en cas d'erreur :
 * Si une erreur se produit lors du rafraichissement des effectifs (recuperation en temps reel),
 * La deniere version des effectifs est affichee et on fait comme si de rien etait (aucun message d'erreur n'est affiche sur la page)
 * Cependant pour des raisons de debug facile : on fera un console.log (message d'erreur)
 */

//Ajax recuperation des effectifs de chaque groupe
function refreshEffectifs() {
    //console.log("trying to connect -> AJAX");
    $.ajax({
        type: "GET",
        url: "http://localhost/PSTL2/effectifs.php",
        //data:"gpes=all", //if sending data
        dataType: "json",
        success: updateEffectifs,
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("Impossible de joindre le serveur -> jqXHR:" + JSON.stringify(jqXHR) + "\n textStatus:" + textStatus + "\n errorThrown:" + errorThrown);
        }
    });
}


//traiteReponseConnexion
function updateEffectifs(rep) {
    GRPES = rep; //Actualisation des effectifs
    //console.log("updatedEffectifs : " + JSON.stringify(GRPES)+"\n\n\n\n");//Debug     
}