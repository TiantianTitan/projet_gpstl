//Tri des UEs selon leur poids local (tri croissant) 
function sort_choixpoids(choix_poids) {
    var tmp = [], rep = true;
    while (rep) {
        rep = false;
        for (i = 0; i < choix_poids.length - 1; i++)
            if (choix_poids[i][0] > choix_poids[i + 1][0]) {
                tmp = choix_poids[i];
                choix_poids[i] = choix_poids[i + 1];
                choix_poids[i + 1] = tmp;
                rep = true;
            }
    }
}

//Reçoit une liste l contenant des paires (s,l) : s calculée par la fct poids
function rearrange(l, d, f) {
    var piv = l[f][0]; //C'est la variable s calculée dans la fonction poids
    var k = d;
    var tmp = [];
    for (var i = d; i < f; i++) {
        //On teste la variable s calculée dans la fonction poids
        if (l[i][0] > piv) {
            tmp = l[i];
            l[i] = l[k];
            l[k] = tmp;
            k++;
        }
    }
    tmp = l[k];
    l[k] = l[f];
    l[f] = tmp;
    return k;
}

//Tri en fonction du poids (QUICKSORT)
function tri(l, d, f) {
    if (d < f) {
        var q = rearrange(l, d, f);
        tri(l, d, q - 1);
        tri(l, q + 1, f);
    }
}

//Fonction de calcul du poids d'un edt
function poids(l) {
    //alert("edt_utils/l : "+l); //Affichage format initial //debug
    var groupes = GRPES; //issu de la recup depuis la bd dans choix_ues.php et de forme : [algav1 =>5, Il2=>4] ou 5 est l'effectif du groupe algav1    
    l = bondAll(unbindAll(l)); //Maj format de l : dlp-1 -> dlp1 // pour directement "demaper"(recuperer l'effectif depuis) groupes
    console.log("l : " + l + "\nGRPES : " + JSON.stringify(GRPES));//Debug

    var capa;
    var capa2;
    if (SEMNUM == 1) {
    	capa = 24; //Max capacite groupe
    	capa2 = 55;
    }
    else {
    	capa = 27;  //2e semestre
    }
    var pf = 1, geff = []; //pf: poids fin de l, geff:Tableau contenant les effectifs des groupes 
    for (var i = 0, sup = 0; i < l.length; i++)
        if (l[i].substring(0, 3) == "sup" || l[i]==" " || l[i]=="ouv_ang1" || l[i]=='progres1' || l[i]=='Conferences1' || l[i]=='Anglais1' || l[i]=='Anglais 1' || l[i]=='Projet1' || l[i]==' 1') //Ne pas tenir compte des effectifs des conferences (vu comme fictive(une supX ue))
            sup++;  // les effectifs ne sont pas considérés ici
        else {
			
			if (l[i]=='il1' || l[i]=='mapsi3' || l[i]=='noyau2' || l[i]=='lrc1' || l[i]=='dlp1' || l[i]=='algav2' || l[i]=='mogpl3'){
				capa = capa2;
				if (l[i]=='mapsi3') {
					capa = capa-10;
				}
			}
            var eff = parseInt(groupes[l[i]]) || 0; // ||0  will convert "falsey" values to 0. The "falsey" values are:false,null,undefined,0,"",NaN
            //Imporant ||0: si un groupe n'existe pas en bd(parcequ'il n'a jamais ete choisi) : effectif=0 (evite des valeur Nan ou null lors du calcul de p)
            geff[i - sup] = eff;
            pf *= (capa - eff); //pf(poids fin)=moyenne geometrique du nombre de places restantes des groupes  //POIDS STANDARDISE POUR TOUTES LES CLASSES D'EDT ET REPRESENTANT LE PRODUIT DU NOMBRE DE PLACES RESTANTES DE CHAQUE GROUPE
        }

    var maxeff = Math.max.apply(Math, geff);
    //console.log("geff : [" + geff + "] & maxeff=" + maxeff + " poidsfin=" + pf + "\n\n"); //Debug

    var res = []; //tableau resultat de la forme : [classe ,pf] ou pf est le poids fin de l'edt l & classe sa classe(type d'edt parmi (vert, orange, rouge))

    if (maxeff >= capa) //Si au moins un groupe est plein : edt rouge (classe 2)    
        res = [2, pf];
    else //edt vert (classe 1)    
        res = [1, pf];

    return res;
}

