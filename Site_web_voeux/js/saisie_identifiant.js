const translations = {
  fr: {
    verif: "Vérification de l'email étudiant",
    check_id: "Identifiant de vérification",
    saisir: "Veuillez saisir l'identifiant qui vous a été envoyé à l'adresse ",
    mail: "Vous n'avez pas reçu de mail ? Vérifiez vos spams ou ",
    bouton: "Verifier",
    renvoyer: "Renvoyer un email",
    retour: "Retour"
  },
  en: {
    verif: "Email address checking",
    check_id: "Checking Identifier",
    saisir: "Please give the identifier number you received at your address ",
    mail: "You did not receive any email containing the identifier? Check your spam folder or ",
    bouton: "Verify",
    renvoyer: "Resend a mail",
    retour: "Back"
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
      // Si c'est un bouton ou un champ avec une propriété `value`
      if (element.tagName === "INPUT" && (element.type === "submit" || element.type === "button")) {
        element.value = translation; // Met à jour la propriété `value`
      } else {
        element.textContent = translation; // Sinon, met à jour le contenu textuel
      }
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
