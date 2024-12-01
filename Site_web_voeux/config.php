<?php
$host = '127.0.0.1'; // Adresse du serveur MySQL
$user = 'root';      // Nom d'utilisateur MySQL
$pass = 'root';      // Mot de passe pour MySQL
$dbName = 'db_gpstl'; // Nom de la base de données

// Le chemin du fichier SQL
$sqlFile = 'Site_web_voeux/MasterVoeuxS1.sql'; // Remplacez par le chemin réel de votre fichier SQL

try {
    // Connexion à MySQL sans spécifier de base de données pour créer celle-ci
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la base de données si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName");
    echo "Base de données '$dbName' créée avec succès.<br>";

    // Connexion à la base de données nouvellement créée
    $pdo->exec("USE $dbName");

    // Lire le contenu du fichier SQL
    $sqlContent = file_get_contents($sqlFile);

    // Vérifier que le fichier SQL a bien été lu
    if ($sqlContent === false) {
        die("Erreur : Impossible de lire le fichier SQL.");
    }

    // Exécuter les requêtes SQL contenues dans le fichier
    $pdo->exec($sqlContent);
    echo "Les requêtes SQL ont été exécutées avec succès.<br>";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
