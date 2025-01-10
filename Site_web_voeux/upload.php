<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["status" => "error", "message" => "Erreur lors du téléchargement du fichier."]);
        exit;
    }

    $fileTmpPath = $_FILES['csv_file']['tmp_name'];
    $csvContent = file_get_contents($fileTmpPath);



    try {
        // Prépare l'insertion des données
        $stmt = $pdo->prepare("INSERT INTO csv_files (csv_content) VALUES (:csv_content)");
        $stmt->bindParam(':csv_content', $csvContent, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(["status" => "success", "message" => "Fichier enregistré avec succès dans la base de données."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Erreur lors de l'accès à la base de données : " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Méthode de requête non valide."]);
}
?>
