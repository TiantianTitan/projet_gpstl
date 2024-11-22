function print_edts(listeEDT, classe) {
   //console.log(JSON.stringify(listeEDT));//Debug : Voir si la liste est triee
    var listeUE = getCalendrier(); //recuperation du calendrier du semestre
    printSupHTML("#edts", "<span class=\"nbedt\" id=\"nbedt_" + classe + "\"><font size='4'><b>Il y a " + listeEDT.length + " emplois du temps de classe " + classe + " </b></font></span><br clear='left'/><br/>");
    tri(listeEDT, 0, listeEDT.length - 1); //Tri decroissant des listes des EDT en se basant sur la valeur s
    var asso = {}; //tableau associatif de type horaire=>ue (exemple : ma14 => algav-1)

    for (var i = 0; i < listeEDT.length; i++) {
        asso = {}; //Reinitialiser asso //remise a zero de l'edt
        //affichage du bouton radio correspondant a un edt (value=liste des cases horaires(cours + td/tme) de l'edt)
       
        printSupHTML("#edts", "<span class=\"edtbox\" id=\"edtbox_" + classe + "_" + i + "\">\n\
        <input onclick=\"showValidator(this)\" id=\"edt_" + classe + "_" + i + "\" class=\"edt\" type=\"radio\" name=\"edt\" value=\"" + listeEDT[i][1] + "\" />\n\
        </span>");
       
        //alert(JSON.stringify(listeEDT[i][1]));
        //Pour chaque horaire on cree une entree dans le tableau asso de type horaire=>ue (exemple : ma14 => algav-1)
        for (var horaire = 0; horaire < (listeEDT[i][1]).length; horaire++) {
        	for (var j = 0; j < listeUE[ listeEDT[i][1] [horaire][0] ][0].length; j++){
        		if (listeUE[ listeEDT[i][1] [horaire][0] ][0][j] in asso == false) {
        			asso[listeUE[ listeEDT[i][1] [horaire][0] ][0][j]] = '';
        		}
        		else {
        			asso[listeUE[ listeEDT[i][1] [horaire][0] ][0][j]] += '    /    ';
        		}
            	asso[listeUE[ listeEDT[i][1] [horaire][0] ][0][j]] += listeEDT[i][1] [horaire][0].toUpperCase();
            }
            
            if (listeUE[ listeEDT[i][1] [horaire][0] ][listeEDT[i][1] [horaire][1]] [0] in asso == false) {
            	asso[listeUE[ listeEDT[i][1] [horaire][0] ][listeEDT[i][1] [horaire][1]] [0]] = '';
            }
            else {
        		asso[listeUE[ listeEDT[i][1] [horaire][0] ][listeEDT[i][1] [horaire][1]] [0]] += '    /    ';
        	}
            asso[listeUE[ listeEDT[i][1] [horaire][0] ][listeEDT[i][1] [horaire][1]] [0]] += listeEDT[i][1] [horaire][0].toUpperCase() + "-" + listeEDT[i][1] [horaire][1];
            
            if (listeUE[ listeEDT[i][1] [horaire][0] ][listeEDT[i][1] [horaire][1]] [1] in asso == false) {
            	asso[listeUE[ listeEDT[i][1] [horaire][0] ][listeEDT[i][1] [horaire][1]] [1]] = '';
            }
            else {
        		asso[listeUE[ listeEDT[i][1] [horaire][0] ][listeEDT[i][1] [horaire][1]] [1]] += '    /    ';
        	}
            asso[listeUE[ listeEDT[i][1] [horaire][0] ][listeEDT[i][1] [horaire][1]] [1]] += listeEDT[i][1] [horaire][0].toUpperCase() + "-" + listeEDT[i][1] [horaire][1];
        }
        
        print_edt(asso, classe, i);
        printSupHTML("#edts","<div/><br/>");
    }
    
    printSupHTML("#edts","<div/><br/><br/>");
}

function print_edt(asso, classe, num) {
    //alert(JSON.stringify(asso));
    var time = {"jours": ["lundi", "mardi", "mercredi", "jeudi", "vendredi"],
        "heures": ["08:30 - 10:30", "10:45 - 12:45", "13:45 - 15:45", "16:00 - 18:00", "18:15 - 20:15"]};
    
    printSupHTML("#edtbox_" + classe + "_" + num, "<table id=\"edttab_" + classe + "_" + num + "\" class=\"edttab_"+classe+"\"></table>");
    printSupHTML("#edttab_" + classe + "_" + num, "<tr id=\"tr_" + classe + "_" + num + "\"> <th id=\"th_" + classe + "_" + num + "\" class=\"classe_" + classe + "\"></th></tr>");

    for (var j = 0; j < time["jours"].length; j++)
        printSupHTML("#tr_" + classe + "_" + num, "<th class=\"jour\"><center>" + time["jours"][j] + "</center></th>");

    for (var h = 0; h < time["heures"].length; h++)
        printSupHTML("#edttab_" + classe + "_" + num, "<tr id=\"tr_" + classe + "_" + num + "_" + h + "\"> <th class=\"heure\">" + time["heures"][h] + "</th></tr>");

    for (var j = 0; j < time["jours"].length; j++)
        for (var h = 0; h < time["heures"].length; h++) {
            var jh = (time["jours"][j]).substr(0, 2) + (time["heures"][h]).substr(0, 2);
            if (asso[jh] != undefined){
            	//alert(jh + "->" + asso[jh] + "     " + unbind(asso[jh])[0]);
            	if (asso[jh].length >12)
            		printSupHTML("#tr_" + classe + "_" + num + "_" + h, "<td class='CHEVAUCHE' id =\"" + asso[jh] + "\" align=center>" + asso[jh] + "</td>");
            	else 
	                printSupHTML("#tr_" + classe + "_" + num + "_" + h, "<td class=\"" + unbind(asso[jh])[0] + "\" id =\"" + asso[jh] + "\" align=center>" + asso[jh] + "</td>");
            }
            else
                printSupHTML("#tr_" + classe + "_" + num + "_" + h, "<td class=\"jhue\" id =\"" + jh + "\" align=center></td>");
        }
}

function printRecap(uetab, gpetab) {
    //alert("uetab=" + JSON.stringify(uetab) + " gpetab=" + JSON.stringify(gpetab));
    printSupHTML("#recapbox", "<table id=\"recaptab\"></table>");
    //printSupHTML("#recaptab", "<tr id=\"descrecaptr\"><th class=\"descrecapth\"><center>Recapitulatif de votre inscription</center></th></tr>");
    printSupHTML("#recaptab", "<tr class=\"recaptr\"><th class=\"recapth\"> <center>UE</center> </th><th class=\"recapth\"> <center>Groupe de TD/TME</center> </th></tr>");
    for (var i = 0; i < uetab.length; i++)
        printSupHTML("#recaptab",
                "<tr class =\""+(uetab[i]).toUpperCase()+"\" id=\"recaptr\"><td class=\"recapth\" align=\"center\"> " +
                (uetab[i]).toUpperCase() + "</td><td align=\"center\">" + gpetab[i] + "</td></tr>");
}

function showValidator(input) {
    var cn = input.getAttribute("id").split("_");//classeNum (cn)   
    printHTML("#th_" + cn[1] + "_" + cn[2], "<input class=\"boutton\" id=\"bedt\" type=\"submit\" name=\"submit\" value=\"Valider\"/>");
    for (var e = 1; e <= 2; e++) {
        var edts = document.getElementsByClassName("classe_" + e);
        for (var i = 0; i < edts.length; i++)
            if (edts[i].getAttribute("id") != "th_" + cn[1] + "_" + cn[2])
                printHTML(edts[i], "");
    }
}
