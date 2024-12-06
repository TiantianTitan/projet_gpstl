<?php

session_start(); //recuperation de la session php en cours
//Note sur les conditions requises :
//For the Mail functions to be available, 
//PHP must have access to the sendmail binary on your system during compile time
//If you use another mail program, such as qmail or postfix, be sure to use the appropriate sendmail wrappers that come with them. 
//PHP will first look for sendmail in your PATH, and then in the following: /usr/bin:/usr/sbin:/usr/etc:/etc:/usr/ucblib:/usr/lib.
//It's highly recommended to have sendmail available from your PATH.
//Also, the user that compiled PHP must have permission to access the sendmail binary

function generate_id() {
    //generation id avec une fonction de hash( sans insertion dans la base ) basee sur le timestanp    
    $algos = hash_algos(); //recuperation du tableau d'algos de hachage
    //Ajout de l'id a la session : evite un acces base inutile
    // $_SESSION['ident'] = hash($algos[2], time()); //hash md5;
    $_SESSION['ident'] = 11111; //hash md5;

}

function send_mail_etu() {
    //generation de l' id
    generate_id();

    require_once('MSN.php');
    //Parametres du mail
    $subject = "[Master Sorbonne Universite voeux inscription M1] Verification d'email";
    $message = "Bonjour ". $_SESSION['prenom'].".<br/> Votre identifiant de session est : " . $_SESSION['ident'];
    //Pour envoyer un mail HTML, l'en-tête Content-type doit être defini
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    //En-têtes additionnels
    $headers .= 'From: ' . $msn[$_SESSION['spe']] . "\r\n";
    //envoi du mail
    if (mail($_SESSION['mail'], $subject, $message, $headers, "-f antoine.genitrini@lip6.fr"))
        header("Location: saisie_identifiant.php");
    else {
        echo "Une erreur s'est produite!";
        exit(); //sortie 
    }
    
}

send_mail_etu();
?>
