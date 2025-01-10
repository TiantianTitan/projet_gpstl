<?php


$old = $_GET['num_old'];
$num = $_GET['num'];


require_once('config.php'); // Acces Base de donnees
//On verifie que les voeux n'aient pas deja ete faits

try {
$sql = "SELECT * FROM NumTraduction WHERE numini = :numini";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':numini', $old, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo "<div id='enddiv'>"
       . "<p id='endp'>"
       . "<span id='endspan'>Vous avez déjà enregistré votre numéro.</span> <br></p></div>";
    exit();
}

// Insérer dans la table NumTraduction
$sql = "INSERT INTO NumTraduction (numini, numvrai) VALUES (:numini, :numvrai)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':numini', $old, PDO::PARAM_INT);
$stmt->bindParam(':numvrai', $num, PDO::PARAM_STR);
$stmt->execute();

echo "Numéro enregistré avec succès.";

} catch (PDOException $e) {
echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <title>Sorbonne Universit&eacute;, Master Informatique : Saisie des voeux d'UE</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
        <meta name="description" content="Inscriptions des etudiants au master informatique de Sorbonne Universit&eacute;">
            <meta name="keywords" content="EDT,Sorbonne Universit&eacute;,MASTER,INFO,CHOIX,UE,ANAGBLA,NOUIRA">
            <meta name="author" content="ANAGBLA Joan & NOUIRA Chafik"> 
        <link rel="stylesheet" href="css/maincss.css" type="text/css" />
        <link rel="stylesheet" href="css/index.css" type="text/css" />
        <!-- Decommenter sur le seveur si connexion disponible
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        Contenu duplique en local dans js/jquery-latest.js  -->
        <!-- <script src="js/jquery-latest.js"></script> copie locale de jquery(realisee en 2014) -->

        <!-- --------------------------------------------- -->

		 <script src="js/jquery-3.7.1.min.js"></script> <!-- copie locale de jquery(realisee en 2024) -->
                <!-- Inclure jQuery Migrate pour la compatibilité -->
		<script src="https://code.jquery.com/jquery-migrate-3.4.1.min.js"></script>

		 <!-- ------------------------------------------------------ -->
       
        
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>          
    </head>

    <body style="background-color:lightgrey;">
        <?php include("navbar_1.php"); ?>
	
	
	<div class="jumbotron">
    <div class="container">
	<p>
		Merci d'avoir indiqu&eacute; votre num&eacute;ro d'&eacute;tudiant. 
		La proc&eacute;dure est termin&eacute;e.
	</p>
	</div>
	</div>
           
    </body>
</html>
