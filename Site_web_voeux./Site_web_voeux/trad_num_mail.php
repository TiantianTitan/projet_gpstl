<?php

require_once('config.php'); // Acces Base de donnees

// Inclusion du fichier PHP contenant les adresses mail des secrétariats
require_once('MSN.php');
 
// Inclusion de la bibliothèque PHPMailer nécessaire pour la suite
require('phpmailer/class.phpmailer.php');

$sql = "SELECT * FROM ListeEtudiants";
$requete = mysql_query($sql) or die(mysql_error());

$i = 0;
$j = 0;

while($ligne = mysql_fetch_assoc($requete))
{
	$i = $i+1;
	$num = $ligne['numero'];
	$pb = 1;
	if(strlen($num)<7 or strlen($num)>8)
		$pb = 0;
	if(ctype_digit($num))
		$val = intval($num);
	else
	{
		$val = 0;
		$pb = 0;
	}
	
    echo  $num . '    ' . $val . '      ' . $pb . '   ' . strlen($ligne['mail']) . '<br />';
    
    if($pb == 0 and strlen($ligne['mail'])>0)   //$num == 'FauxNum19')    // 
    {
		$j = $j+1;
		
		
						/***** Envoi de mail *****/
	
				   // Déclaration des adresses mail de l'étudiant, de l'admin et du secrétariat de lu parcours
				   $mailetu = $ligne['mail'];
				   $mailadmin = 'm1voeuxs1@gmail.com'; 
				   $spe = $ligne['spe'];
				   $mailspe = $msn[$spe];

				 
				   // Création d'une nouvelle instance de mail
				   $mail = new PHPMailer();
				   
				   // Codage des caractères
				   $mail->CharSet = "UTF-8";
				   
				   // balise HTML
				   $mail->IsHTML(true);
				   
				   // Adresse d'envoi et nom de l'émetteur
				   $mail->setFrom($mailspe, "Sorbonne Université - Master Informatique");
				   
				   // Définition du sujet
				   $mail->Subject = "Sorbonne Université - Master Informatique - Parcours ".$ligne['spe']."--".$ligne['numero'];

				   // Contenu du mail
				   $mail->Body = "<p>Bonjour ".$ligne['prenom'].",<br><br>
	   
Lors de la saisie de vos voeux d'UE de M1 Informatique,
vous avez utilisé comme identifiant votre numéro de dossier e-candidat ou Campus France :
".$ligne['numero']."<br>
Nous avions rendu cette identification possible car vous n'aviez pas encore connaissance
de votre numéro d'étudiant Sorbonne Université (SU) à ce moment-là.<br><br>

Afin que nous puissions finaliser votre inscription dans les UE et ainsi réaliser votre
inscription pédagogique, il faut <b>impérativement</b> que :<br>
<blockquote> Si vous avez déjà fait votre pré-inscription en ligne,
allez directement à l'étape 2 pour saisir votre numéro d'étudiant SU.</blockquote>
<br>
1. vous effectuiez <b>votre pré-inscription administrative en ligne</b>
afin d'obtenir votre numéro d'étudiant SU composé de 8 chiffres dont le premier est 2.
Vous trouverez toutes les informations pour réaliser votre inscription administrative sur ce lien <br>
<a href='https://sciences.sorbonne-universite.fr/inscriptions-en-licence-et-master'>
https://sciences.sorbonne-universite.fr/inscriptions-en-licence-et-master</a>
<br><br>

2. vous vous connectiez à l'adresse suivante afin d'enregistrer votre numéro d'étudiant obtenu
lors de votre pré-inscription en ligne : <br>
<a href=\"https://www-apr.lip6.fr/~genitrini/edt_master/lmd/forms/traduction_num.php?num_old='".$ligne['numero']."'\">
https://www-apr.lip6.fr/~genitrini/edt_master/lmd/forms/traduction_num.php?num_old='".$ligne['numero']."'
</a>
<br><br><br>
Sans ces démarches, nous ne pourrons pas faire vos inscriptions pédagogiques et
vous ne serez pas inscrits dans les UE.
<br><br><br>

Cordialement,<br>
Antoine GENITRINI pour<br>
Master Informatique de Sorbonne Université - Parcours ".$ligne['spe'];
				 
				   // Ajout de l'adresse mail des destinataires
				   $mail->AddAddress($mailetu);
				   $mail->AddAddress($mailadmin);
				// ne pas décommenter //  $mail->AddAddress($mailspe);    ///////ici les secretaires
				 
				   
				   // Envoi Mail  
				   $mail->send();
   	
    
	}
}

//Fermeture connexion base de donnees
mysql_close();

echo 'nb d enregistrements : '.$i.'    nb d envois mail : '.$j;

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
	
        
    </body>
</html>
