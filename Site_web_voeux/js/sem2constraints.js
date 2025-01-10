/**
 * Gestions des contraintes specifiques au semestre 2
 * Entre autres : le fait qu'un etudiant ne peut pas choisir moins de 3 ues de sa specialite (Projet exclus)
 * pour faciliter l'insertion des contraintes avec la structure preexistante :
 *  on fait le choix pour le S2, de tagger les ues des specialites comme etant "recommandees"
 */


/*
function checkNbSpeUE(choix){




   var nbrecom=0,minrecom=3;
    //console.log("checkNbSpeUE/uec : ["+choix+"] & UEVALIDES :["+UEVALIDES+"] & SPERECOM : ["+SPERECOM+"]");

    //On cherche parmis les ues choisies
    for(var i=0;i<controle.length;i++)
        if(SPERECOM.indexOf(choix[i])!=-1)
            nbrecom++;
    //puis parmi les ues deja valides pour les redoublants
    for(var i=0;i<UEVALIDES.length;i++)
        if(SPERECOM.indexOf(UEVALIDES[i])!=-1)
            nbrecom++;

    //console.log("nbrecom : "+nbrecom);
    if(SEMNUM==2 && nbrecom < minrecom){
     var msg = "Au S2 il vous faut suivre au moins "+minrecom+" ues recommand&eacute;s";
        printHTML("#con_error_choix", msg);
        return false;
    }
    printHTML("#con_error_choix", "");
    return true;
}
*/


function checkNbSpeUE(choix){


	if (CODE == '1'){
		printHTML("#con_error_choix", "");
		return true;
	}

	var controle = ['','','','','','','','',''];
	var j=0;
	for(var i=0;i<UEVALIDES.length;i++){
    	controle[i] = UEVALIDES[i];
    	j = i;
    }

    for(i=0;i<choix.length;i++){
    	controle[i+j+1] = choix[i];
	}



	if(SEMNUM==2){

	if (SPE == 'AI2D'){
		var nb=0;
		var ue_rec = ['fosyma','dj','rp','ihm'];
		for(i=0;i<ue_rec.length;i++){
			if ( controle.indexOf(ue_rec[i]) == -1) {
				nb = nb-1;
			}
		}
		if ( nb <=-3 )  {
			var msg = "La liste de choix ne respecte pas les contraintes : <br> au moins 2 UE parmi les UE recommand&eacute;es.";
        	printHTML("#con_error_choix", msg);
        	return false;
		}
	}
	if (SPE == 'IMA'){
		var nb=0;
		var ue_rec = ['cge','ra','mll','anum'];
		for(i=0;i<ue_rec.length;i++){
			if ( controle.indexOf(ue_rec[i]) == -1) {
				nb = nb-1;
			}
		}
		if ( nb != -3 )  {
			var msg = "La liste de choix ne respecte pas les contraintes : <br> exactement 1 UE parmi les recommand&eacute;es.";
        	printHTML("#con_error_choix", msg);
        	return false;
		}
	}
	if (SPE == 'SAR'){
		var nb=0;
		var ue_rec = ['ar', 'pnl', 'sas', 'sftr', 'srcs'];
		for(i=0;i<ue_rec.length;i++){
			if ( controle.indexOf(ue_rec[i]) == -1) {
				nb = nb-1;
			}
		}
		if ( nb <=-3 )  {
			var msg = "La liste de choix ne respecte pas les contraintes : <br> au moins 2 UE parmi les UE recommand&eacute;es.";
        	printHTML("#con_error_choix", msg);
        	return false;
		}
	}
	if (SPE == 'SESI'){
		var nb=0;
		var ue_rec = ['ecfa', 'fpga', 'multi', 'ioc', 'Projet'];
		for(i=0;i<ue_rec.length;i++){
			if ( controle.indexOf(ue_rec[i]) == -1) {
				nb = nb-1;
			}
		}
		if ( nb <-3 )  {
			var msg = "La liste de choix ne respecte pas les contraintes : <br> au moins 2 UE parmi les UE recommand&eacute;es.";
        	printHTML("#con_error_choix", msg);
        	return false;
		}
	}
	if (SPE == 'CCA'){
		var nb=0;
		var ue_rec = ['flag', 'hpc', 'isec'];
		for(i=0;i<ue_rec.length;i++){
			if ( controle.indexOf(ue_rec[i]) == -1) {
				nb = nb-1;
			}
		}
		if ( nb <=-2 )  {
			var msg = "La liste de choix ne respecte pas les contraintes : <br> au moins 2 UE parmi les UE recommand&eacute;es.";
        	printHTML("#con_error_choix", msg);
        	return false;
		}
	}
	}

	printHTML("#con_error_choix", "");
    return true;
}
