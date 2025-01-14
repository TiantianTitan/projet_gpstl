<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('config.php');

try {
    // Connexion à la base de données SQLite
    // $dbFile = 'calendar_data.db'; // Nom de la base de données SQLite
    // $pdo = new PDO('sqlite:' . $dbFile);
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer le dernier fichier CSV stocké
    $query = $pdo->prepare('SELECT csv_content FROM csv_files ORDER BY uploaded_at DESC LIMIT 1');
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo json_encode(["status" => "error", "message" => "Aucun fichier CSV trouvé."]);
        exit;
    }


    $csvContent = $result['csv_content'];

    // Transformation des données CSV en tableau
    $lines = explode("\n", trim($csvContent));
    $data = [];
    $currentSemestre = null;

    foreach ($lines as $line) {
        $line = trim($line);
        $row = str_getcsv($line, ',', '"', '\\');

        // Identifier les semestres
        if ($row[0] === '-- Semestre 1 --') {
            $currentSemestre = 'S1';
            continue;
        } elseif ($row[0] === '-- Semestre 2 --') {
            $currentSemestre = 'S2';
            continue;
        }

        // Ajouter les données sous un semestre
        if ($currentSemestre && count($row) > 1) {
            $ue = $row[0]; // UE
            $horaires = array_slice($row, 1); // Les horaires pour chaque semaine

            if (!isset($data[$currentSemestre])) {
                $data[$currentSemestre] = [];
            }

            $data[$currentSemestre][$ue] = $horaires;
        }
    }

    echo json_encode(["status" => "success", "message" => "Données récupérées avec succès.", "data" => $data]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erreur lors de l'accès à la base de données : " . $e->getMessage()]);
    exit;
}
?>
