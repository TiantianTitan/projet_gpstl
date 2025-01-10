/**
 * *TRAITEMENT DU FORMULAIRE DE CONNEXION
 * !NB: Les 'alert' ne servent qu'au debug (alert ne doit pas intervenir dans le control d erreur)(seulement utile au developpeur)
 * Les messages d'erreur sur les entrees du formulaire sont geres par jquery (modification du dom)
 * */

//Get & Check formulaire Connexion
function connect(formulaire) {
  var forbidenchars = /\W/g; //The \W metacharacter is used to find a non-word character.
  //A word character is a character from a-z, A-Z, 0-9, including the _ (underscore) character.
  //see http://dev.mysql.com/doc/refman/5.5/en/identifiers.html for more details

  var num = formulaire.numetu.value.trim().replace(forbidenchars, "_");
  var vnum = formulaire.numetu2.value.trim().replace(forbidenchars, "_"); //verification du numero etudiant : vnum
  var nom = formulaire.nometu.value.trim().replace(forbidenchars, "_"); //suppr aussi les é à è ç ù , etc : pas terrible mais pas le choix
  var prenom = formulaire.prenometu.value.trim().replace(forbidenchars, "_"); //suppr aussi les é à è ç ù , etc : pas terrible mais pas le choix
  var mail = formulaire.email.value.trim();
  var spe = formulaire.spe.value;
  var redouble = document.getElementById("r2").checked; //est un redoublant
  var magister = document.getElementById("m2").checked; //postule pour un magistere(6 ues)

  //Nombre d'ues (5:Pas de Magistere, 6:Magistere)(5 par defaut)
  var nbue = 5;
  if (magister) nbue++;

  /*alert("MsgFrom : Connect.js/connect,\n msg:{num=" + num + " vnum=" + vnum + " nbues=" + nbue +
             " nom=" + nom + " prenom=" + prenom + " mail=" + mail + "\n\
             spe=" + spe + " redouble=" + redouble + " magister=" + magister + "}");*/

  var conform =
    verif_num(num) &&
    verif_vnum(num, vnum) &&
    verif_text("nom", nom) &&
    verif_text("prenom", prenom) &&
    verif_mail(mail) &&
    verif_magistere(redouble, magister) &&
    verif_spe(spe);


  if (conform) {
    //les entrees du formulaire sont toutes conformes
    //redirection vers start_session avec les parametres utiles du formulaire
    window.location.href =
      "start_session.php?num=" +
      num +
      "&nom=" +
      nom +
      "&prenom=" +
      prenom +
      "&mail=" +
      mail +
      "&spe=" +
      spe +
      "&redouble=" +
      redouble +
      "&magister=" +
      magister +
      "&nbue=" +
      nbue;
  }
  //alert("conform=" + conform);
}

//Fonctions de verification de conformite du formulaire

//verification numero d etudiant

/*
function verif_num(num) {
    if (isNumber(num) && num.length == 7) { //un nombre de 7 chiffres (verifications supplementaires sur la couche html)
        //!attention cette 2eme verification est importante car elle protege des champs vides (non filtres par le pattern html)
        printHTML("#con_error_num", ""); //remise a blanc du precedent message d'erreur eventuel
        return true;
    }
    func_erreur_connexion("#con_error_num", "<font color = \"red\">Num&eacute;ro d'&eacute;tudiant invalide.</font>");
    //alert("num rouge");
    return false;
}
*/

function verif_num(num) {
  if ((num.length > 6) & (num.length < 20)) {
    printHTML("#con_error_num", ""); //remise a blanc du precedent message d'erreur eventuel
    return true;
  }
  func_erreur_connexion(
    "#con_error_num",
    '<font color = "red">Num&eacute;ro d\'&eacute;tudiant invalide.</font>'
  );
  //alert("num rouge");
  return false;
}

//Verification unicite numero etudiant
function verif_vnum(num, num2) {
  if (num == num2) {
    printHTML("#con_error_num2", ""); //remise a blanc du precedent message d'erreur eventuel
    return true;
  }
  func_erreur_connexion(
    "#con_error_num2",
    "<font color='red'>La v&eacute;rification n'est pas identique au num&eacute;ro d'&eacute;tudiant.</font>"
  );
  //alert("vnum rouge");
  return false;
}

//Verification taille des inputs de type texte (nom, prenom, etc)
function verif_text(desc, texte) {
  if (texte.length > 0) {
    printHTML("#con_error_" + desc, ""); //remise a blanc du precedent message d'erreur eventuel
    return true;
  }
  func_erreur_connexion(
    "#con_error_" + desc,
    "<font color = 'red'>Votre " +
      desc +
      " doit &ecirc;tre renseign&eacute;.</font>"
  );
  // alert(desc + " is empty");
  return false;
}

//Vérification redoublant et magistère (un étudiant ne peut être redoublant et candidat au parcours d'excellence)
function verif_magistere(redouble, magistere) {
  if (!(redouble && magistere)) {
    printHTML("#con_error_magistere", ""); //remise a blanc du precedent message d'erreur eventuel
    return true;
  }
  func_erreur_connexion(
    "#con_error_magistere",
    "<font color='red'>Vous ne pouvez pas &ecirc;tre redoublant et candidat au parcours d'exellence.</font>"
  );
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
  func_erreur_connexion(
    "#con_error_email",
    "<font color='red'>Votre email doit &ecirc;tre renseign&eacute; ! Un mail de v&eacute;rification vous sera envoy&eacute;.</font>"
  );
  //alert("mail="+mail);
  return false;
}

//verification spe
function verif_spe(spe) {
  console.log("Vérification de spe :", spe); // Ajout de debug
  if (spe !== '---') {
    console.log("spe valide :", spe); // Ajout de debug
    printHTML("#con_error_spe", ""); // Supprime les anciens messages d'erreur
    return true;
  }
  console.log("spe invalide :", spe); // Ajout de debug
  func_erreur_connexion(
    "#con_error_spe",
    "<font color='red'>Veuillez choisir votre parcours.</font>"
  );
  return false;
}


//affichage d'un message d'erreur personnalise pour le fieldset en cause (modifications sur le dom)
function func_erreur_connexion(dom, msg) {
  //alert("Connect.js/func_erreur_connexion, \n msg=" + msg);
  var msg = '<span class="con_error_msg">' + msg + "</span>";
  printHTML(dom, msg);
}

// Stocke les traductions
const translations = {
  fr: {
    voeux:
        "Voeux d'inscription aux UE du Master Informatique de Sorbonne Université.",
    description:
        "Ce site permet de proposer ses voeux de choix d'UE pour le S2. L'inscription effective aura lieu en janvier après convocation par le secrétariat du M1 Informatique.",
    info:
        "Informations sur l'étudiant",
    numetu: "Numéro de dossier : ",
    numetu_info: "Si le numéro n'est pas connu, les données ne seront pas enregistrées.",
    numetu2: "Confirmation du numéro de dossier : ",
    nom : "Nom : ",
    prenom: "Prénom : ",
    email: "Adresse mail : ",
    parcours: "Parcours : ",
    redouble: "Êtes-vous redoublant du master Informatique de Sorbonne Université ?",
    oui : "Oui",
    non: "Non",
    connex: "Connexion",
    champs: "Tous les champs sont obligatoires."

  },
  en: {
    voeux: "Wishes for the courses",
    description: "Though this website you can express wishes for your lectures for the second semester. But the registration will be effective only after a meeting with the administrative staff of the Master.",
    info: "Student's informations",
    numetu: "Student's number : ",
    numetu_info: "If the number is not known by the administration, the wishes will not be stored.",
    numetu2: "Confirmation of the student's number : ",
    nom : "Name : ",
    prenom: "First name : ",
    email: "Email address : ",
    parcours: "Speciality : ",
    redouble: "Are you repeating the first year of the computer science master ?",
    oui : "Yes",
    non: "No",
    connex: "Connection",
    champs: "All fields must be filled."
  },
};

// Change la langue et met à jour les textes
function changeLanguage() {
  const selectedLanguage = document.getElementById("language").value;

  // Parcours les éléments traduisibles
  document.querySelectorAll("[data-translate-key]").forEach((element) => {
    const key = element.getAttribute("data-translate-key");
    const translation = translations[selectedLanguage][key];

    if (translation) {
      element.textContent = translation;
    }

    // Applique une classe pour styliser le texte
    element.classList.remove("fr", "en");
    element.classList.add(selectedLanguage);
  });
}

// Initialisation par défaut (Français)
window.onload = () => {
  changeLanguage();
};
