async function getData() {
    try {
        const response = await fetch(''); // Appel à getData.php
        if (!response.ok) {
            throw new Error(`Erreur réseau : ${response.status}`);
        }

        const data = await response.json();

        const messageElement = document.getElementById('message');

        if (data.status === 'success') {
            messageElement.textContent = `Message: ${data.message}`;

            // Afficher les données du semestre 1
            if (data.data.S1) {
                displaySemestreData('Semestre 1', data.data.S1);
            }

            // // Afficher les données du semestre 2 (optionnel)
            // if (data.data.S2) {
            //     displaySemestreData('Semestre 2', data.data.S2);
            // }

        } else {
            messageElement.textContent = `Erreur: ${data.message}`;
        }
    } catch (error) {
        console.error("Erreur :", error);
        document.getElementById('message').textContent = "Erreur lors du chargement des données.";
    }
}

function displaySemestreData(semestreTitle, semestreData) {
    // Crée une section dédiée au semestre
    const section = document.createElement('section');
    const title = document.createElement('h2');
    title.textContent = semestreTitle;
    section.appendChild(title);

    // Ajoute les données des UE
    const pre = document.createElement('pre');
    pre.textContent = JSON.stringify(semestreData, null, 2);
    section.appendChild(pre);

    document.body.appendChild(section);
}

// Charger les données au chargement de la page
getData();
