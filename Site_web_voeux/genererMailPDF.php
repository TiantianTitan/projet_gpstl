<?php

session_start();

$choix = $_SESSION['choix'];
$num = $_SESSION['num'];
$mailetu = $_SESSION['mail'];
$spe = $_SESSION['spe'];
    $nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$voeux = 1;


$listeUE = [];
$nboblig = 0;
for ($i=1; $i<6; $i++)
{
	if(isset($_GET['UEoblig'.$i]))
	{
		$nboblig = $nboblig +1;
        array_push($listeUE, $_GET['UEoblig'.$i]);
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



   /***** Ecriture en BDD *****/


require_once('config.php'); // Acces Base de donnees

// ------------------------------------------------------------ Nouveau Code ------------------------------------

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


// Commencer une transaction (pour assurer la cohérence des opérations)
try {
    $pdo->beginTransaction();
    if ($result) {
        $rang = $result['rang'];
        $_SESSION['rang'] = $rang; // Enregistrer le rang dans la session
    } else {
        throw new Exception("Le rang n'a pas pu être récupéré.");
    }

    // Check si l'étudiant est déjà inscrit
    $sql = "SELECT * FROM ListeEtudiants WHERE numero = :num";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // si l'étudiant est déjà inscrit, on met à jour ses voeux
    if ($result) {
        $sql = "UPDATE ListeEtudiants SET $ue WHERE numero = :num";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':num', $num, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo "Les voeux de l'étudiant ont été mis à jour avec succès.";
        } else {
            throw new Exception("Erreur lors de la mise à jour des voeux de l'étudiant.");
        }
    } else {
        // sinon, on l'insère dans la table ListeEtudiants
        $sql = "INSERT INTO ListeEtudiants (numero, nom, prenom, mail, spe, voeux) 
                VALUES (:numero, :nom, :prenom, :mail, :spe, :voeux)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':numero', $num, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':mail', $mailetu, PDO::PARAM_STR);
        $stmt->bindParam(':spe', $spe, PDO::PARAM_STR);
        $stmt->bindParam(':voeux', $voeux, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo "L'étudiant a été enregistré avec succès.";
        } else {
            throw new Exception("Erreur lors de l'enregistrement de l'étudiant.");
        }
    }

    // Construire et exécuter la requête UPDATE pour les UE
    $sql = "UPDATE ListeEtudiants SET ";
    for ($i = 1; $i <= 15; $i++) {
        $sql .= "ue$i = :ue$i";
        if ($i < 15) {
            $sql .= ", ";
        }
    }
    $sql .= " WHERE numero = :num";

    $stmt = $pdo->prepare($sql);

    // Lier les valeurs des UE
    for ($i = 1; $i <= count($listeUE); $i++) {
        $stmt->bindValue(":ue$i", strtolower($listeUE[$i - 1]), PDO::PARAM_STR);
    }
    for ($i = count($listeUE) + 1; $i <= 15; $i++) {
        $stmt->bindValue(":ue$i", "", PDO::PARAM_STR);
    }

    $stmt->bindParam(':num', $num, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Les UE ont été mises à jour avec succès.";
    } else {
        throw new Exception("Erreur lors de la mise à jour des UE.");
    }

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
}


$pdo = null;
//print_r($effectif); //Debug
//Fermeture connexion base de donnees


/***** Envoi de mail *****/
// ASSEZ LENT...
// Inclusion du fichier PHP contenant les adresses mail des secrétariats
$smtp_configs = require 'MSN.php';

// Inclusion de la bibliothèque PHPMailer et FPDF
require 'vendor/autoload.php';
require 'fpdf186/fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Image('SU_logo.jpg', 10, 6, 30);
$pdf->Ln(20);
$pdf->Cell(0, 10, "MASTER $spe - Ann\xe9e " . date('Y') . '/' . (date('Y') + 1), 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10);
$pdf->Cell(0, 10, "Voeux M1-S". $_SESSION['SEMESTRE']." " , 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "N\xba Etudiant : $num", 0, 1);
$pdf->Cell(0, 10, "Nom : $nom", 0, 1);
$pdf->Cell(0, 10, "Prenom : $prenom", 0, 1);
$pdf->Cell(0, 10, "Parcours : $spe", 0, 1);


// Ajouter les UE obligatoires
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, "UE obligatoires :", 0, 1);
$pdf->SetFont('Arial', '', 12);
foreach (array_slice($listeUE, 0, $nboblig) as $ue) {
    $pdf->Cell(0, 10, "- " . $ue, 0, 1);
}

// Ajouter les UE supplémentaires
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, "Voeux d'UE suppl\xe9mentaires", 0, 1);
$pdf->SetFont('Arial', '', 12);
foreach (array_slice($listeUE, $nboblig) as $ue) {
    $pdf->Cell(0, 10, "- " . $ue, 0, 1);
}


// Chemin temporaire pour le PDF
$pdf_file = sys_get_temp_dir() . "/voeux_$num.pdf";
$pdf->Output('F', $pdf_file);

// Vérification que la spécialité existe dans les configurations
if (!isset($smtp_configs[$spe])) {
    die("Aucune configuration SMTP trouvée pour la spécialité : $spe");
}
$config = $smtp_configs[$spe];

$mailspe = $config['smtp_username'];
if (!filter_var($mailspe, FILTER_VALIDATE_EMAIL)) {
    die("L'adresse email du secrétariat est invalide.");
}

$mailadmin = 'yokyann@outlook.fr';

// Création du mail avec PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    // Configuration SMTP
    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_username'];
    $mail->Password = $config['smtp_password'];
    $mail->SMTPSecure = $config['smtp_secure'];
    $mail->Port = $config['smtp_port'];
    

    // Codage des caractères
    $mail->CharSet = 'UTF-8';

    // Adresse de l'expéditeur (secrétariat)
    $mail->setFrom($mailspe, "Sorbonne Université - Master $spe");

    // Destinataires
    $mail->addAddress($mailetu); // Email de l'étudiant
    $mail->addAddress($mailadmin); // Email de l'administrateur
    $mail->addAddress($mailspe); // Email du secrétariat

    // Sujet du mail
    $mail->Subject = "Sorbonne Université - Master Informatique - Parcours $spe - Voeux M1-S" . $_SESSION['SEMESTRE'] . " de $num";

    // Contenu du mail
    $txt = "Bonjour $prenom $nom,<br><br>";
    $txt .= "Vous avez déposé vos voeux sous le numéro $num.<br>";
    $txt .= "Veuillez trouver ci-dessous la liste ordonnée de vos voeux d'UE.<br><br>";

    $txt .= "<strong>UE obligatoires :</strong><br>";
    for ($i = 0; $i < $nboblig; $i++) {
        $txt .= "- " . htmlspecialchars($listeUE[$i]) . "<br>";
    }

    $txt .= "<br><strong>Voeux d'UE supplémentaires :</strong><br>";
    for ($i = $nboblig; $i < count($listeUE); $i++) {
        $txt .= "- " . htmlspecialchars($listeUE[$i]) . "<br>";
    }

    $txt .= "<br>L'emploi du temps vous sera communiqué ultérieurement par mail.<br><br>";
    $txt .= "Cordialement,<br>Master Informatique de Sorbonne Université - Parcours $spe";

    $mail->Body = $txt;
    $mail->isHTML(true);

    $mail->addAttachment($pdf_file);

    // Envoi du mail
    $mail->send();
    echo "E-mail envoyé avec succès.";

    // Suppression du fichier temporaire
    unlink($pdf_file);
} catch (Exception $e) {
    echo "Erreur lors de l'envoi de l'e-mail : " . $mail->ErrorInfo;
}

// ?>


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
