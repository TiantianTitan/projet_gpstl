function edt(choix) {
    //alert("choix :"+choix);
    listeUE = getCalendrier();
    choix_poids = []; //Tableau des poids locaux (nombre de cases horaires cours compris) de chaque ue
    EDT = []; //remise a zero : important
    l = []; //remise a zero : important
    listeEDTs = []; //liste des classes d'edts
    var NB_LISTEDTs=2; //Variable en dur qui doit etre synchrone avec le nombre de classes de edt_utils/poids

    for(var nbl=0;nbl<NB_LISTEDTs;nbl++)
        listeEDTs[nbl]=[]; //Creation dynamique d'une liste(classe) d'edts dans la liste des classes dedts

    for (var i = 0; i < choix.length; i++) //Calcul des poids locaux (lentgh,nom_ue)
        choix_poids[i] = [listeUE[choix[i]].length, choix[i]];//paires (lentgh(nombre de cases horaires de l'ue),nom_ue)

    sort_choixpoids(choix_poids); //tri des choix en fonction de leurs poids locaux
    //alert(choix_poids + " & cplength=" + choix_poids.length); //cplength = choixpoids length

    //Ajout des cours et Traitement des chevauchements associes
	var nbCours = 0;
    for (var i = 0; i < choix_poids.length; i++)

    	for (var j = 0; j < listeUE[choix_poids[i][1]][0].length; j++) {
    		nbCours = nbCours + 1;
        	if (EDT.indexOf(listeUE[choix_poids[i][1]][0][j]) == -1) //Si l'horaire du cours de l'UE est libre(absent) dans EDT on l'ajoute.
            	EDT.push(listeUE[choix_poids[i][1]][0][j]); //On ajoute ici les cours des autres UEs
    //alert("EDT Cours=" + EDT);
    	}

    if (EDT.length == nbCours) //Si la longueur de EDT differente de 6 on stope la generation:Chevauchement detecte(EDT.length<6 => certains horaires n'ont pas pu etre ajoutes (collision)))
        h_edt(0); //On peut continuer la generation d'edt //Pas de Deadlock entre cours detecte

    /*for (var k = 0; k < listeEDTs.length; k++) //Verification de la construction de n lists et de leur contenu //Debug
        console.log(JSON.stringify(listeEDTs[k]));*/

    for (var eln = 0; eln < listeEDTs.length; eln++) //eln->Edt List Number
     print_edts(listeEDTs[eln], eln+1); //Appel de la fonction d'ecriture des edts sur le document html !
}


function h_edt(ue) { //ue represente le numero de l'ue courante
    var nextue = ue + 1;
    if (ue < choix_poids.length) {
        //alert("ue=" + ue + "& cpl=" + choix_poids.length);

        for (var h = 1; h < choix_poids[ue][0]; h++) { //h represente l'horaire d un groupe td/tme

       		if (SEMNUM == 1 || SPE != 'personne'){  //'CCA') {
            while (h < choix_poids[ue][0] && (listeUE[choix_poids[ue][1]][h].length == 0 || EDT.indexOf(listeUE[choix_poids[ue][1]][h][0]) != - 1 || EDT.indexOf(listeUE[choix_poids[ue][1]][h][1]) != - 1))
                h++; //EDT gere les collisions d'horaires : on saute les horaires a collision
            }
            if (h == choix_poids[ue][0]){
                break;
            }

            //On ajoute les seances h de TD/TME de l'ue
            EDT.push(listeUE[choix_poids[ue][1]][h][0]);
            EDT.push(listeUE[choix_poids[ue][1]][h][1]);
            //alert("ue" + ue + "=" + choix_poids[ue][1] + "\n EDT=" + EDT);

            //On ajoute aussi l'horaire h de l'ue i a l'edt courant
            l.push(bind([choix_poids[ue][1], h]));
            //alert("l=" + l); //verification du dynamisme de la construction des edts

            if (ue == (choix_poids.length - 1))//On last ue add to suitable liste
                listeEDTs[poids(l)[0]-1].push([poids(l)[1], unbindAll(l)]);

            h_edt(nextue);

            //On supprime les seances h de TD/TME de l'ue
            EDT.splice(EDT.indexOf(listeUE[choix_poids[ue][1]][h][0]), 1);
            EDT.splice(EDT.indexOf(listeUE[choix_poids[ue][1]][h][1]), 1);
            //alert("ue" + ue + "=" + choix_poids[ue][1] + "\n EDT=" + EDT);
            l.splice(l.indexOf(bind([choix_poids[ue][1], h])), 1);
        }
    }
}
