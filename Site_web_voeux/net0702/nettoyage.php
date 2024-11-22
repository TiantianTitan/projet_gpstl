
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
        <title>Sorbonne Universit&eacute;, Master Informatique : Saisie des voeux d'UE du S1</title>

	</head>

    <body>


<?php
    
    $etu = $_GET['etu'];
    
    require('../config.php'); // On réclame le fichier
        
    $sql = "SELECT * FROM ListeEtudiants WHERE numero=".$etu;
    $requete = mysql_query($sql) or die ( mysql_error() );
    $donnees = mysql_fetch_array($requete);
    
    $gpe = ['','','','','','','',''];
    for ($i=1; $i<=count($donnees); $i++) {
        $gpe[$i-1] = $gpe[$i-1] . $donnees['ue'.$i] . $donnees['ue'.$i.'gpe'];
        }
    for ($i=0; $i<count($donnees); $i++) {
        $sql = "UPDATE UEGroupes SET effectif = effectif-1 WHERE groupe = '".$gpe[$i]."'";
        mysql_query($sql) or die( mysql_error() );
        }
    
    $sql = "DELETE FROM ListeEtudiants WHERE numero=".$etu;
	$requete = mysql_query($sql) or die ( mysql_error() );
	
    echo 'C est fait. <br>';

    mysql_close();
    
        
?>



    </body>


</html>

