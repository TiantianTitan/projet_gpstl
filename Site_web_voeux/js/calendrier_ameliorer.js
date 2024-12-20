async function getCalendrier() {
    try {
        var listeUE= "";
        const response = await fetch('get_data.php'); // Appel à getData.php
        if (!response.ok) {
            throw new Error(`Erreur réseau : ${response.status}`);
        }

        const data = await response.json();

        console.log(data);
        if (data.status === 'success') {
            messageElement.textContent = `Message: ${data.message}`;

            listeUE = (SEMNUM== 1) ? data.data.S1 : data.data.S2;

        } else {
            messageElement.textContent = `Erreur: ${data.message}`;
        }
    } catch (error) {
        console.error("Erreur :", error);
    }
    return listeUE;
}
getCalendrier();
