<?php

require_once('config.php'); //connection a la base

if (is_ajax()) {
    //$data = $_GET["gpes"]; //if data were sent
    //echo json_encode("$data"); //just for debug

    // $requete = mysql_query("SELECT * FROM UEGroupes") or die(mysql_error());
    // $groupes = []; //Tableau qui contiendra les paires (groupe_ue => effectif) Exemple ( groupe : algav1, effectif : 30 )
    // while ($donnees = mysql_fetch_array($requete))
    //     $groupes[$donnees['groupe']] = $donnees['effectif'];

    $reponse = $pdo->query("SELECT * FROM UEGroupes");
    $groupes = [];
    while ($donnees = $reponse->fetch(PDO::FETCH_ASSOC)) {
        $groupes[$donnees['groupe']] = $donnees['effectif']; // Ajout du groupe et de l'effectif
    }
    
    echo json_encode($groupes); //reponse a envoyer au client au format json
} else
    echo 'You should not be here ! Go back Home little rogue!';

//Function to check if the request is an AJAX request 
function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>