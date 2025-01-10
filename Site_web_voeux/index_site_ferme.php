 <!--Page d'accueil : index.php-->
<?php
require_once ('semestre.php');
//echo " & cur_sem : ".$_SESSION['SEMESTRE']."<br/>";
//print_r($_SESSION['ALLUES']);
//echo "<br/><br/>";
//print_r($_SESSION['MASTER']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta charset="UTF-8">
            <meta name="description" content="Inscriptions des etudiants au master informatique de SU">
            <meta name="keywords" content="EDT,SU,MASTER,INFO,CHOIX,UE">
            <title>SU, Master Informatique : Saisie des voeux d'UE</title>
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

            <script type="text/javascript" src="js/index.js"></script>
            <script type="text/javascript" src="js/utils.js"></script>
            
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css"/>

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
    </head>

   
    <body style="background-color:lightgrey;">
        <?php include("navbar_1.php"); ?>
        
        
            <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h2><b>Voeux d'inscription aux UE du Master Informatique de Sorbonne Universit&eacute;.</b></h2>
        
        <p>La saisie des voeux est close.</p>

		
      </div>
      </div>
    </body>
</html>
