<?php


$old = $_GET['num_old'];
$num = $_GET['num'];


require_once('config.php'); // Acces Base de donnees
//On verifie que les voeux n'aient pas deja ete faits
$sql = "SELECT * FROM NumTraduction WHERE numini=" . $old;
$requete = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($requete) > 0) {
    echo "<div id ='enddiv'>"
        . "<p id='endp'>"
                . "<span id='endspan'>Vous avez d&eacute;j&agrave; enregistr&eacute; votre num&eacute;ro.</span> <br></p></div>";
    exit();
}

//On ecrit la requete sql dans NumTraduction
$sql = "INSERT INTO NumTraduction(numini, numvrai) VALUES(" . $old . ", '" . $num . "')";
mysql_query($sql) or die(mysql_error());

//Fermeture connexion base de donnees
mysql_close();

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
        <script src="js/jquery-latest.js"></script> <!-- copie locale de jquery(realisee en 2014) -->
       
        
        
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
