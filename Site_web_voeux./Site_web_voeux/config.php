<?php
$DB_serveur = 'localhost'; // Nom du serveur
$DB_utilisateur = 'XXXX'; // Nom de l utilisateur de la base
$DB_motdepasse = 'YYYY'; // Mot de passe pour acceder a la base
$DB_base = 'ZZZZ'; // Nom de la base

$connection = mysql_connect($DB_serveur, $DB_utilisateur, $DB_motdepasse) // On se connecte au serveur
or die (mysql_error().' sur la ligne '.__LINE__);

mysql_select_db($DB_base, $connection) // On se connecte a la BDD
or die (mysql_error().' sur la ligne '.__LINE__);
?>
