/**
 * *TRAITEMENT DU FORMULAIRE DE CONNEXION
 * !NB: Les 'alert' ne servent qu'au debug (alert ne doit pas intervenir dans le control d erreur)(seulement utile au developpeur)
 * Les messages d'erreur sur les entrees du formulaire sont geres par jquery (modification du dom)
 * */

//Get & Check formulaire Connexion
        function connect(formulaire) {
            var forbidenchars=/\W/g;  //The \W metacharacter is used to find a non-word character.
            //A word character is a character from a-z, A-Z, 0-9, including the _ (underscore) character.
            //see http://dev.mysql.com/doc/refman/5.5/en/identifiers.html for more details

            var code = formulaire.code.value.trim();
            var num = formulaire.numetu.value.trim();
            var vnum = formulaire.numetu2.value.trim(); //verification du numero etudiant : vnum
            var nom = (formulaire.nometu.value.trim()).replace(forbidenchars, '_'); //suppr aussi les é à è ç ù , etc : pas terrible mais pas le choix
            var prenom = (formulaire.prenometu.value.trim()).replace(forbidenchars, '_'); //suppr aussi les é à è ç ù , etc : pas terrible mais pas le choix
            var mail = formulaire.email.value.trim();
            var spe = formulaire.spe.value;
            var redouble = document.getElementById("r2").checked; //est un redoublant
            var magister = document.getElementById("m2").checked; //postule pour un magistere(6 ues)

            //Nombre d'ues (5:Pas de Magistere, 6:Magistere)(5 par defaut)
            var nbue = 5;
            if (magister)
                nbue++;

            /*alert("MsgFrom : Connect.js/connect,\n msg:{num=" + num + " vnum=" + vnum + " nbues=" + nbue +
             " nom=" + nom + " prenom=" + prenom + " mail=" + mail + "\n\
             spe=" + spe + " redouble=" + redouble + " magister=" + magister + "}");*/

            var conform = (code==141592) && verif_num(num) && verif_vnum(num, vnum)
                    && verif_text("nom", nom) && verif_text("prenom", prenom) && verif_mail(mail)
                    && verif_magistere(redouble, magister);

            if (conform) { //les entrees du formulaire sont toutes conformes
                //redirection vers start_session avec les parametres utiles du formulaire
                window.location.href = "start_session.php?num=" + num + "&nom=" + nom + "&prenom=" + prenom + "&mail=" + mail +
                        "&spe=" + spe + "&redouble=" + redouble + "&magister=" + magister + "&nbue=" + nbue + "&code=1";
            }
            //alert("conform=" + conform);
        }


//Fonctions de verification de conformite du formulaire

//verification numero d etudiant
function verif_num(num) {
    if (num.length > 6 & num.length <20) {
        printHTML("#con_error_num", ""); //remise a blanc du precedent message d'erreur eventuel
        return true;
    }
    func_erreur_connexion("#con_error_num", "<font color = \"red\">Num&eacute;ro d'&eacute;tudiant invalide.</font>");
    //alert("num rouge");
    return false;
}


//Verification unicite numero etudiant
function verif_vnum(num, num2) {
    if (num == num2) {
        printHTML("#con_error_num2", ""); //remise a blanc du precedent message d'erreur eventuel
        return true;
    }
    func_erreur_connexion("#con_error_num2", "<font color='red'>La v&eacute;rification n'est pas identique au num&eacute;ro d'&eacute;tudiant.</font>");
    //alert("vnum rouge");
    return false;
}

//Verification taille des inputs de type texte (nom, prenom, etc)
function verif_text(desc, texte) {
    if (texte.length > 0) {
        printHTML("#con_error_" + desc, ""); //remise a blanc du precedent message d'erreur eventuel
        return true;
    }
    func_erreur_connexion("#con_error_" + desc, "<font color = 'red'>Votre " + desc + " doit &ecirc;tre renseign&eacute;.</font>");
    // alert(desc + " is empty");
    return false;
}

//Vérification redoublant et magistère (un étudiant ne peut être redoublant et candidat au parcours d'excellence)
function verif_magistere(redouble, magistere) {
    if (!(redouble && magistere)) {
        printHTML("#con_error_magistere", ""); //remise a blanc du precedent message d'erreur eventuel
        return true;
    }
    func_erreur_connexion("#con_error_magistere", "<font color='red'>Vous ne pouvez pas &ecirc;tre redoublant et candidat au parcours d'exellence.</font>");
    // alert(desc + " is empty");
    return false;
}

//verification email
function verif_mail(mail) {
    //un mail contient au moins 5 caracteres (A@B.C)
    //verification par simple pattern "email" (A@B (avec A et B sans espaces)) au niveau html. le ".nomDomaine" n'est pas verifie
    //une adresse de type nom@mailserver est acceptee au lieu du pattern srtict (nom@mailserver.nomDomaine)
    //Ceci est fait expres dans un esprit de permissivite large.
    //Il ne sert a rien de verifier le nom de domaine car celui-ci peut etre imbrique(.keio.ac.jp est un nom de domaine valable et existant)
    //Mais (.fr.fr) ou (.f.f) sont tout aussi valables mais inexistants : nom@mailserver.fr.fr est accepte par le pattern mais inexistant
    //un mail de verification est envoye a chaque etudiant pour palier cette largesse.
    if (mail.length > 4) {
        printHTML("#con_error_email", ""); //remise a blanc du precedent message d'erreur eventuel.
        return true;
    }
    func_erreur_connexion("#con_error_email", "<font color='red'>Votre email doit &ecirc;tre renseign&eacute; ! Un mail de v&eacute;rification vous sera envoy&eacute;.</font>");
    //alert("mail="+mail);
    return false;
}

//affichage d'un message d'erreur personnalise pour le fieldset en cause (modifications sur le dom)
function func_erreur_connexion(dom, msg) {
    //alert("Connect.js/func_erreur_connexion, \n msg=" + msg);
    var msg = "<span class=\"con_error_msg\">" + msg + "</span>";
    printHTML(dom, msg);
}
