<?php

session_start();
//echo 'geturl['.$_GET['num'].','.$_GET['nom'].','.$_GET['prenom'].','.$_GET['mail'].
//','.$_GET['spe'] .','.$_GET['redouble'] .','. $_GET['magister'].']'.'<br/>';

if ($_GET['num'] and $_GET['nom'] and $_GET['prenom'] and $_GET['mail']
        and $_GET['spe'] and $_GET['redouble'] and $_GET['magister']) {

    //toutes les infos utiles sont stockees pour la session en cours 
    $_SESSION['num'] = $_GET['num'];
    $_SESSION['nom'] = $_GET['nom'];
    $_SESSION['prenom'] = $_GET['prenom'];
    $_SESSION['mail'] = $_GET['mail'];
    $_SESSION['spe'] = $_GET['spe'];
    $_SESSION['redouble'] = $_GET['redouble'];
    $_SESSION['magister'] = $_GET['magister'];
    $_SESSION['nbue'] = $_GET['nbue'];
    $_SESSION['code'] = 0;


    if (!filter_var($_SESSION['mail'], FILTER_VALIDATE_EMAIL)) {
        $msg = "<b>" . $_SESSION['mail'] . " </b> est une adresse mail est invalide.";
        $url = "index.php?num=" . $_SESSION['num'] . "&num2=" . $_SESSION['num'] .
                "&nom=" . $_SESSION['nom'] . "&prenom=" . $_SESSION['prenom'] .
                "&mail=" . "&spe=" . $_SESSION['spe'] . "&redouble=" . $_SESSION['redouble'] . "&magister=" . $_SESSION['magister'];
        echo '<html><head><title>Redirection ..</title></head>' .
        '<body onload="timer = setTimeout(function(){ window.location =\'' . $url . '\';},5000)">' .
        '<p>' . $msg . ' <br/> Vous allez etre redirig&eacute; vers la page precedente dans 5 secondes</p>' .
        '</body></html>';
    } else {
        require_once('config.php'); // config.php est requis //connexion implicite a la base de donnees
        //Recuperation de l'etudiant par son numero s'il a deja termine son inscription
        // $sql = "SELECT * FROM ListeEtudiants WHERE numero = '" . $_SESSION['num'] ."'";

        // //On verifie que les voeux n'aient pas deja ete faits
        // $requete = mysql_query($sql) or die(mysql_error());
        // if (mysql_num_rows($requete) > 0) {
            $sql = "SELECT * FROM ListeEtudiants WHERE numero = :numero";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':numero', $_SESSION['num'], PDO::PARAM_INT);
        
            $stmt->execute();
        
            // Vérifier si des résultats existent
            if ($stmt->rowCount() > 0) {
            //echo 'Vous avez d&eacute;j&agrave; enregistr&eacute; vos voeux.<br>Si vous recommencez, seuls les derniers voeux valid&eacute;s seront sauvegard&eacute;s.<br>';
            echo '<html><head><title>Redirection ..</title></head>' .
        '<body onload="timer = setTimeout(function(){ window.location =\'send_id.php\';},10000)">' .
        '<p><br/> Vous avez d&eacute;j&agrave; enregistr&eacute; vos voeux.<br>Si vous recommencez, seuls les derniers voeux valid&eacute;s seront sauvegard&eacute;s.<br><br/> Vous allez &ecirc;tre redirig&eacute; vers la page suivante dans 10 secondes.</p>' .
        '</body></html>';
            exit();
        }
        header("Location: send_id.php");  //Delegation envoi du mail a send_id
    }
} else {//Ne devrait jamais se produire (mais un bon programme prevoit des controles d'erreur multicouches)
    $msg = "le formulaire saisi est incomplet ou incorrect!";
    echo '<html><head><title>Redirection ..</title></head>'
    . '<body onload="timer = setTimeout(function(){ window.location = \'index.php\';},5000)">'
    . '<p>' . $msg . ' <br/> Vous allez etre redirig&acute; vers la page pr&acute;c&acute;dente dans 5 secondes</p>'
    . '</body></html>';
}
?>
