<?php

session_start();

$choix = $_SESSION['choix'];
$num = $_SESSION['num'];
$mailetu = $_SESSION['mail'];
$spe = $_SESSION['spe'];
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];


// $listeUE = [];
// $nboblig = 0;
// for ($i=1; $i<6; $i++)
// {
// 	if(isset($_GET['UEoblig'.$i]))
// 	{
// 		$nboblig = $nboblig +1;
//         array_push($listeUE, $_GET['UEoblig'.$i]);
//      }
// }
// for ($i=1; $i<15; $i++)
// {
// 	if(isset($_GET['UEsup'.$i]))
//         array_push($listeUE, $_GET['UEsup'.$i]);
// }
// ------------------------------------------------------------ Nouveau Code ------------------------------------

$listeUE = [];
$nboblig = 0;
$messageErreur = "";

// Récupération des UE obligatoires
for ($i = 1; $i < 6; $i++) {
    if (isset($_GET['UEoblig' . $i])) {
        $nboblig++;
        array_push($listeUE, $_GET['UEoblig' . $i]);
    }
}

// Récupération des UE supplémentaires avec vérification
for ($i = 1; $i < 15; $i++) {
    if (isset($_GET['UEsup' . $i])) {
        if ($_GET['UEsup' . $i] === '--- Choisissez une UE---') {
            $messageErreur = "Erreur : Une ou plusieurs UE supplémentaires n'ont pas été sélectionnées.";
            break;
        }
        array_push($listeUE, $_GET['UEsup' . $i]);
    }
}

// // Affichage d'un message d'erreur si nécessaire
// if ($messageErreur) {
//     echo "<p style='color: red;'>$messageErreur</p>";
// } else {
//     // Traitement des données si tout est correct
//     // Exemple : afficher les UE sélectionnées
//     echo "<p>UE sélectionnées :</p>";
//     echo "<ul>";
//     foreach ($listeUE as $ue) {
//         echo "<li>" . htmlspecialchars($ue) . "</li>";
//     }
//     echo "</ul>";
// }



   /***** Ecriture en BDD *****/


require_once('config.php'); // Acces Base de donnees
//On verifie que les voeux n'aient pas deja ete faits
//$sql = "SELECT * FROM ListeEtudiants WHERE numero='" . $_SESSION['num'] . "' AND voeux=1";
//$requete = mysql_query($sql) or die(mysql_error());
//if (mysql_num_rows($requete) > 0) {
    //echo "<div id ='enddiv'>"
        //. "<p id='endp'>"
                //. "<span id='endspan'>Vous avez d&eacute;j&agrave; enregistr&eacute; vox voeux.<br>"
                //. "</span> <br>";
        //echo "<a href='https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique'>Retour sur le site du master informatique de Sorbonne Universit&eacute;</a> "
        //. "</p>"
    //. "</div>";
    //exit();
//}


// debug print choix
print_r($choix); 

//On ecrit la requete sql dans ListEtudiants : on enregistre l'etudiant
// $sql = "INSERT INTO ListeEtudiants(numero, nom, prenom, mail, spe, voeux) VALUES('" . $num . "', '" . $nom . "', '" . $prenom . "', '" . $mailetu . "', '" . $spe . "', 0)";
// ($sql) or die(mysql_error());

// ------------------------------------------------------------ Nouveau Code ------------------------------------
// Préparation de la requête SQL pour insérer l'étudiant
$sql = "INSERT INTO ListeEtudiants (numero, nom, prenom, mail, spe, voeux) 
        VALUES (:numero, :nom, :prenom, :mail, :spe, :voeux)";
$stmt = $pdo-> prepare($sql);

// Vérification que la préparation a réussi
if ($stmt === false) {
    die("Erreur de préparation de la requête : " . $pdo->errorInfo()[2]);
}

// Bind des paramètres
$stmt->bindParam(':numero', $num, PDO::PARAM_INT);
$stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
$stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
$stmt->bindParam(':mail', $mailetu, PDO::PARAM_STR);
$stmt->bindParam(':spe', $spe, PDO::PARAM_STR);
$stmt->bindParam(':voeux', $voeux, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo "L'étudiant a été enregistré avec succès.";
} else {
    echo "Erreur lors de l'enregistrement : " . implode(", ", $stmt->errorInfo());
}

$ue = "";
for ($i = 1; $i <= count($listeUE); $i++) {
    $ue .= "ue" . $i . "='" . strtolower($listeUE[$i-1] )."'";
    if ($i < 15)
        $ue .= ", ";
}
for ($i = count($listeUE)+1; $i <=15 ; $i++) {
    $ue .= "ue" . $i . "=' '";
    if ($i < 15)
        $ue .= ", ";
}

//echo $ue; //Debug
//Ici on mets a jour les champs UEi, UEigpe et voeux de la base dans ListEtudiants
// $sql = "UPDATE ListeEtudiants SET voeux=1, " . $ue . " WHERE numero='".$num."'";
// mysql_query($sql) or die(mysql_error());

// //On ecrit la requete sql dans la SPE, ce qui donne le rang d'enregistrement des voeux
// $sql = "INSERT INTO $spe(numetu) VALUES('".$num."')";
// mysql_query($sql) or die(mysql_error());

// //On recupere rang
// $sql = "SELECT * FROM $spe WHERE numetu='".$num."'";
// $requete = mysql_query($sql) or die(mysql_error());
// $rang = mysql_fetch_array($requete)['rang'];

// $_SESSION['rang'] = $rang;

// //On ecrit la requete sql dans Master, ce qui donne le rang d'enregistrement des voeux (au sein du master)
// $sql = "INSERT INTO Master(numetu) VALUES('".$num."')";
// mysql_query($sql) or die(mysql_error());

// Commencer une transaction (pour assurer la cohérence des opérations)

try {
$pdo->beginTransaction();

$sql = "UPDATE ListeEtudiants SET voeux = 1, " . $ue . " WHERE numero = :num";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    $stmt->execute();

    // Insérer l'étudiant dans la table de la spécialisation (par exemple: $spe)
    $sql = "INSERT INTO $spe (numetu) VALUES (:num)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    $stmt->execute();

    // Récupérer le rang de l'étudiant dans la table de la spécialisation
    $sql = "SELECT rang FROM $spe WHERE numetu = :num";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $rang = $result['rang'];
        $_SESSION['rang'] = $rang; // Enregistrer le rang dans la session
    } else {
        throw new Exception("Le rang n'a pas pu être récupéré.");
    }

    // 4. Insérer l'étudiant dans la table `Master`
    $sql = "INSERT INTO Master (numetu) VALUES (:num)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    $stmt->execute();

 // Commit des transactions si toutes les étapes sont réussies
 $pdo->commit();
} catch (PDOException $e) {
    // Si une erreur survient, on annule la transaction et affiche l'erreur
    $pdo->rollBack();
    echo "Erreur : " . $e->getMessage();
} catch (Exception $e) {
    // Gestion des erreurs personnalisées
    $pdo->rollBack();
    echo "Erreur : " . $e->getMessage();
};

// A commenter et esssayer de tester pour voir si on a des bugs 
//S1
	$CodeUE = array(
	  "maths4m062" => "maths4m062",
	  "aagb" => "MU4IN700",
	  "algav" => "MU4IN500",
	  "archi" => "MU4IN100",
	  "ares" => "MU4IN001",
	  "bima" => "MU4IN600",
	  "comnet" => "MU4INX05",
	  "complex" => "MU4IN900",
	  "dlp" => "MU4IN501",
	  "esa" => "MU4EES05",
//	  "il" => "MU4IN502",
	  "irout" => "MU4INX20",
	  "lrc" => "MU4IN800",
	  "mapsi" => "MU4IN601",
	  "mlbda" => "MU4IN801",
	  "mobj" => "MU4IN103",
	  "model" => "MU4IN901",
	  "mogpl" => "MU4IN200",
	  "noyau" => "MU4IN401",
	  "ouv_ang" => "MU4IN511",
	  "progres" => "MU4IN014",
	  "pscr" => "MU4IN400",
	  "rtel" => "MU4IN002",
	  "sigcom" => "MU4INX06",
	  "signal" => "MU4IN104",
	  "sc" => "MU4IN905",
	  "vlsi" => "MU4IN101",
	  'anum' => 'MU4IN910',
	  'aps' => 'MU4IN503',
	  'ar' => 'MU4IN403',
	  'arob' => 'MU4IN207',
	  'bium' => 'MU4IN804',
	  'bmc' => 'MU4BM118',
	  'ca' => 'MU4IN504',
	  'cpa' => 'MU4IN505',
  	  'cps' => 'MU4IN506',
	  'cge' => 'MU4IN112',
	  'dalas' => 'MU4IN814',
	  'dj' => 'MU4IN204',
	  'ecfa' => 'MU4ESS18',
	  'flag' => 'MU4IN902',
	  'fosyma' => 'MU4IN202',
	  'fpga' => 'MU4IN108',
	  'iamsi' => 'MU4IN806',
	  'ihm' => 'MU4IN203',
	  'ig3d' => 'MU4IN602',
	  'ioc' => 'MU4IN109',
	  'isec' => 'MU4IN904',
	  'ml' => 'MU4IN811',
	  'mll' => 'MU4IN812',
	  'mmcn' => 'MU4IN702',
	  'multi' => 'MU4IN106',
	  'multi_en' => 'MU4IN106',
	  'paf' => 'MU4IN510',
	  'pc2r' => 'MU4IN507',
	  'pnl' => 'MU4IN402',
	  'rital' => 'MU4IN813',
	  'rp' => 'MU4IN201',
	  'sam' => 'MU4IN803',
	  'sas' => 'MU4IN405',
	  'sbas' => 'MU4IN701',
	  'srcs' => 'MU4IN404',
	  'sftr' => 'MU4IN407'
	);

$pdo = null;
//print_r($effectif); //Debug
//Fermeture connexion base de donnees




   /***** Envoi de mail *****/

   // Inclusion du fichier PHP contenant les adresses mail des secrétariats
   require_once('MSN.php');

   // Inclusion de la bibliothèque PHPMailer nécessaire pour la suite
   require('phpmailer/class.phpmailer.php');

   // Déclaration des adresses mail de l'étudiant, de l'admin et du secrétariat de lu parcours
   $mailadmin = 'XXX@mail.com';
   $mailetu = $_SESSION['mail'];
   $spe = $_SESSION['spe'];
   $mailspe = $msn[$spe];


   // Création d'une nouvelle instance de mail
   $mail = new PHPMailer();

   // Codage des caractères
   $mail->CharSet = "UTF-8";

   // Adresse d'envoi et nom de l'émetteur
   $mail->setFrom($mailspe, "Sorbonne Université - Master Informatique");

   // Définition du sujet
   $mail->Subject = "Sorbonne Université - Master Informatique - Parcours ".$_SESSION['spe']." - Voeux M1-S".
   $_SESSION['SEMESTRE']." de ".$_SESSION['num'];

   // Contenu du mail
   $txt = "Bonjour ".$_SESSION['prenom']." ".$_SESSION['nom'].",

Vous avez déposé vos voeux sous le numéro ".$_SESSION['num']."
Veuillez trouver ci-dessous la liste ordonnée de vos voeux d'UE.

UE obligatoires : " . "
	";
for ($i=0; $i<$nboblig; $i++)
{
	$txt = $txt . "- " . $listeUE[$i] . "
	";
}
$txt = $txt . "

Voeux d'UE supplémentaires : ". "
	";
for ($i=$nboblig; $i<count($listeUE); $i++)
{
	$txt = $txt . "- " . $listeUE[$i] . "
	";
}
$txt = $txt . "

L'emploi du temps vous sera communiqué ultérieurement par mail.

Cordialement,
Master Informatique de Sorbonne Université - Parcours ".$_SESSION['spe'];


   $mail->Body = $txt;

   // Ajout de l'adresse mail des destinataires
   $mail->AddAddress($mailetu);
   $mail->AddAddress($mailadmin);
   $mail->AddAddress($mailspe);    ///////ici les gestionnaires

   // Ajout de la pièce jointe
   //$mail->AddAttachment($edtfilename);
   //$mail->addStringAttachment($pdf_attach, $edtfilename);   pas de piece jointe

   // Envoi Mail
   $mail->send();

   // Suppression du fichier PDF du dossier tmp (Pour économiser de l'espace mémoire sur le serveur)
  // unlink($edtfilename);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta charset="UTF-8">
            <meta name="description" content="Inscriptions des etudiants au master informatique de Sorbonne Universit&eacute;">
            <meta name="keywords" content="EDT,Sorbonne Universit&eacute;,MASTER,INFO,CHOIX,UE">
            <title>SU, Master Informatique : Saisie des voeux d'UE</title>

            <link rel="stylesheet" href="css/choix_ues.css" type="text/css" />


            <link rel="stylesheet" href="css/maincss.css" type="text/css" />


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

    <body style="background-color:lightgrey;s" onload="add_oblig(<?php echo(json_encode($nb_suivi)); ?>)">

        <?php include("navbar_1.php"); ?>

            <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">

	Merci d'avoir effectu&eacute; vos voeux. Vous allez recevoir la liste de vos souhaits d'UE dans un mail.
	<br>
	<i><i><span style = color: #0000FF >Thank you for having expressed your wishes.</span></i></i>

      </div>
    </div>
    </body>
</html>
