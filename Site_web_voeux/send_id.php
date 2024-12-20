<?php
session_start(); // Récupération de la session PHP

// Charger la configuration SMTP
$config = require 'config_mail.php';

// Vérifier que l'email est défini et valide
if (empty($_SESSION['mail']) || !filter_var($_SESSION['mail'], FILTER_VALIDATE_EMAIL)) {
    die("Adresse email invalide ou non définie.");
}

// Vérifier que les autres variables de session nécessaires sont définies
if (empty($_SESSION['prenom'])) {
    die("Le prénom de l'utilisateur est non défini.");
}
    
// Fonction pour générer un identifiant unique
function generate_id() {
    //generation id avec une fonction de hash( sans insertion dans la base ) basee sur le timestanp    
    $algos = hash_algos(); //recuperation du tableau d'algos de hachage
    //Ajout de l'id a la session : evite un acces base inutile
    $_SESSION['ident'] = hash($algos[2], time()); //hash md5;
}

// Fonction pour envoyer l'email
function send_mail_etu($config) {
    generate_id(); // Génération de l'identifiant

    require 'vendor/autoload.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true); // Activer les exceptions pour le débogage

    try {
        // Configuration SMTP
        $mail->isSMTP();                                    // Utiliser SMTP
        $mail->Host = $config['smtp_host'];                 // Serveur SMTP
        $mail->SMTPAuth = true;                             // Authentification SMTP
        $mail->Username = $config['smtp_username'];         // Nom d'utilisateur
        $mail->Password = $config['smtp_password'];         // Mot de passe
        $mail->SMTPSecure = $config['smtp_secure'];         // Type de chiffrement ('ssl' ou 'tls')
        $mail->Port = $config['smtp_port'];                 // Port SMTP sécurisé

        // Vérification de l'adresse email de l'expéditeur
        if (!filter_var($config['smtp_username'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Adresse email de l'expéditeur invalide.");
        }

        // Configuration de l'expéditeur et du destinataire
        $mail->setFrom($config['smtp_username'], 'Master Sorbonne Université');
        $mail->addAddress($_SESSION['mail']);               // Adresse du destinataire

        // Contenu du message
        $mail->isHTML(true);                                // Format HTML
        $mail->Subject = '[Master Sorbonne Université voeux inscription M1] Vérification d\'email';
        $mail->Body = "Bonjour " . htmlspecialchars($_SESSION['prenom']) . ",<br>"
            . "Votre identifiant de session est : <strong>" . htmlspecialchars($_SESSION['ident']) . "</strong>";


        // Envoyer l'email
        $mail->send();
        header("Location: saisie_identifiant.php");         // Redirection en cas de succès
        exit();
    } catch (Exception $e) {
        // Gestion des erreurs d'envoi
        echo "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
    }
}

// Appeler la fonction avec la configuration
send_mail_etu($config);
?>
