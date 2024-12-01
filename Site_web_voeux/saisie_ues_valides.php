<?php
session_start(); //recuperation de la session

//$liste_ues =$_SESSION['ALLUES']; //recuperation de la liste complete des ues du semestre
//print_r($liste_ues);

$liste = $_SESSION['MASTER'][$_SESSION['spe']];
$liste_ues = array_merge($liste['oblig'], $liste['recom'], $liste['libre']);
print_r($liste_ues);

//echo "Semestre: ".$_SESSION['SEMESTRE'];
?>

<!--Page de saisie des ues deja validees par un redoublant : saisie_ues_valides.php-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta charset="UTF-8"> 
            <meta name="description" content="Inscriptions des etudiants au master informatique de Sorbonne Universit&eacute;">
            <meta name="keywords" content="EDT,Sorbonne Universit&eacute;,MASTER,INFO,CHOIX,UE">
            <meta name="author" content="">  
            <title>Sorbonne Universit&eacute;, Master Informatique : Saisie des voeux d'UE</title>
            <link rel="stylesheet" href="css/maincss.css" type="text/css" />
            <link rel="stylesheet" href="css/saisie_ues_valides.css" type="text/css" />
            <!-- Decommenter sur le seveur si connexion disponible
            <script src="http://code.jquery.com/jquery-latest.js"></script>
            Contenu duplique en local dans js/jquery-latest.js  -->
            <!-- <script src="js/jquery-latest.js"></script> copie locale de jquery(realisee en 2014) -->
            <!-- --------------------------------------------- -->

		 <script src="js/jquery-3.7.1.min.js"></script> <!-- copie locale de jquery(realisee en 2024) -->
                <!-- Inclure jQuery Migrate pour la compatibilité -->
		<script src="https://code.jquery.com/jquery-migrate-3.4.1.min.js"></script>

		 <!-- ------------------------------------------------------ -->

            <script type="text/javascript" src="js/utils.js"></script>
            <script type="text/javascript" src="js/saisie_ues_valides.js"></script>

            <script type="text/javascript">
                function rollback() { //fonction de retour arriere en dur (l'url de retour est fixe et statique)       
                    var num = <?php echo(json_encode($_SESSION['num'])); ?>;
                    var nom = <?php echo(json_encode($_SESSION['nom'])); ?>;
                    var spe = <?php echo(json_encode($_SESSION['spe'])); ?>;
                    var mail = <?php echo(json_encode($_SESSION['mail'])); ?>;
                    var prenom = <?php echo(json_encode($_SESSION['prenom'])); ?>;
                    var magister = <?php echo(json_encode($_SESSION['magister'])); ?>;
                    var redouble = <?php echo(json_encode($_SESSION['redouble'])); ?>;
                    
                    window.location.href = "index.php?num=" + num + "&nom=" + nom + "&spe=" + spe +
                            "&mail=" + mail + "&prenom=" + prenom + "&magister=" + magister + "&redouble=" + redouble;
                }
            </script>
            
            
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
        <h2><b>Saisie des UEs Valid&eacute;es</b></h2>          
        <br/>
        <button class="btn btn-sm btn-primary" id="bbackindex" onclick="javascript:rollback()">Retour</button>
        <span id="span_tab_ues_valides"><!-- une span pour plus de flexibilite dans l'affichage avec css-->

            <form id="formUev" name="formUeValides" method="POST" onsubmit="javascript:transmitValides(this)" action="javascript:(function(){return;})()">
                <!-- action="javascript:(function(){return;})()" permet : 
                Gestion par aiguillage centralisee avec javascript : js/file.js qui decidera en fonction des parametres transmis 
                ou aller ensuite / ou bien rester sur la meme page active( parametres incorrects par exemple )
                sans recharger la page (conservation de l etat du formulaire)  -->
                <h3><b>Informations sur l'&eacute;tudiant (Unit&eacute;s d'enseignement valid&eacute;es)</b></h3>
                
                <fieldset>
                    <legend>Selectionnez les UEs que vous avez d&eacute;j&agrave; valid&eacute;es : </legend>
                    <?php
                    sort($liste_ues); //ordre alphabetique sur la liste d'ues
                    foreach ($liste_ues as $value) {
                    	//if ($value == 'Anglais'){
                    		//$ch = 'Anglais AND_IMA_BIM_DAC_IQ';
                    	//}
                    	//else {
                    		//if  ($value == 'Anglais '){
                    			//$ch = 'Anglais CCA_RES_SAR_SESI';
                    		//}
                    		//else {
                    			//$ch = strtoupper($value);
                    		//}
                    	//}
                    	$ch = strtoupper($value);
                        echo "<div style=\"display:inline-block;float:left;\">";
                        echo '<span class="box_ue" id="span_' . $value . '">' .
                           '<input class="check_ue" onclick="add_ue_valide(this)" type="checkbox" name="' . $value . '" id="ue_' . $value . '"/>' .
                            '<label id="label_' . $value . '" for="' . $value . '">' . $ch . '</label>' .
                            '</span>' . "\n" .
                            '<br/>';
                      
                        echo "</div>";
                    }
                    ?>
                </fieldset>
                <div id="hidens"></div>
                
                <br/> <br/>
                        
                    
                    <input class="btn btn-sm btn-primary" id="buev" type="submit" name="submit" value="Valider"/> 
                    <span class="note" id="noteUev">   Si vous n'avez valid&eacute; aucune UE, cliquez directement sur <b>Valider</b>.</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    
            </form>
            <br/>
            
            
        </span>
      </div>
    </div>        
    </body>
</html>


