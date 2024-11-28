/**
 * Récupère les horaires des UE depuis un fichier CSV via un script PHP
 * @returns {Promise<Object>} Retourne une structure de données des UE
 */
async function getCalendrier() {
    try {
        // Chemin vers le script PHP qui retourne les données JSON
        const response = await fetch('Site_web_voeux/horaires.csv');
        
        // Vérifie si la requête a réussi
        if (!response.ok) {
            throw new Error(`Erreur réseau : ${response.status}`);
        }

        // Parse les données JSON reçues
        const data = await response.json();
        
       // Vérifier quel semestre est sélectionné
        const semestre = SEMNUM === 1 ? 'S1' : 'S2';

  // Retourner les horaires correspondant au semestre
        const listeUE = data.data.semestre;
        return listeUE;
    } catch (error) {
        console.error("Erreur lors du chargement des données :", error);
        return {};
    }
}

