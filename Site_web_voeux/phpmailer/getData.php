<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$file = 'horaires.csv';
if (!file_exists($file)) {
    echo json_encode(["status" => "error", "message" => "Fichier CSV introuvable."]);
    exit;
}

$handle = fopen($file, 'r');
if ($handle === false) {
    echo json_encode(["status" => "error", "message" => "Impossible d'ouvrir le fichier CSV."]);
    exit;
}

$data = [];
$headers = fgetcsv($handle, 0, ',', '"', '\\'); // Ajout du paramètre $escape
while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
    $ue = $row[0];
    $semestre = $row[1];
    $horaires = array_slice($row, 2);

    if (!isset($data[$semestre])) {
        $data[$semestre] = [];
    }

    $data[$semestre][$ue] = $horaires;
}

fclose($handle);

echo json_encode(["status" => "success", "message" => "Données chargées depuis le fichier CSV.", "data" => $data]);
?>
