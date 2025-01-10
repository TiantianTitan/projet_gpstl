<!--Page de verification d'email : saisie_identifiant.php-->

<?php
session_start();   //recuperation de la session
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="description" content="Inscriptions des etudiants au master informatique de Sorbonne Universit&eacute;">
    <meta name="keywords" content="EDT,SU,MASTER,INFO,CHOIX,UE">
    <meta name="author" content="">
    <title>SU, Master Informatique : Saisie des voeux d'UE</title>
    <link rel="stylesheet" href="css/maincss.css" type="text/css" />
    <link rel="stylesheet" href="css/saisie_identifiant.css" type="text/css" />
    <!-- Decommenter sur le seveur si connexion disponible
            <script src="http://code.jquery.com/jquery-latest.js"></script>
            Contenu duplique en local dans js/jquery-latest.js  -->
    <!-- <script src="js/jquery-latest.js"></script> copie locale de jquery(realisee en 2014)  -->

    <!-- --------------------------------------------- -->

    <script src="js/jquery-3.7.1.min.js"></script> <!-- copie locale de jquery(realisee en 2024) -->
    <!-- Inclure jQuery Migrate pour la compatibilité -->
    <script src="https://code.jquery.com/jquery-migrate-3.4.1.min.js"></script>

    <!-- ------------------------------------------------------ -->

    <script type="text/javascript" src="js/utils.js"></script>
    <script type="text/javascript" src="js/saisie_identifiant.js"></script>
    <script type="text/javascript">
        function verifyId(formulaire) {
            var id_client = formulaire.idetu.value.trim();
            var id_server = <?php echo (json_encode($_SESSION['ident'])); ?>;
            var redouble = <?php echo (json_encode($_SESSION['redouble'])); ?>;
            //alert("id2=" + id_server); //Debug
            if (id_client == id_server || id_client == 'token_difficile_a_deviner') { // ajout moyen de passer outre la vérif  (Antoine)
                if (redouble == 'true')
                    window.location.href = "saisie_ues_valides.php";
                else
                    window.location.href = "choix_ues.php";
            } else {
                printHTML("#con_error_id", "<font color = \"red\">L'identifiant saisi ne correspond pas !</font>");
            }
        }
    </script>

    <script type="text/javascript">
        function rollback() { //fonction de retour arriere en dur (l'url de retour est fixe et statique)       
            var num = <?php echo (json_encode($_SESSION['num'])); ?>;
            var nom = <?php echo (json_encode($_SESSION['nom'])); ?>;
            var spe = <?php echo (json_encode($_SESSION['spe'])); ?>;
            var mail = <?php echo (json_encode($_SESSION['mail'])); ?>;
            var prenom = <?php echo (json_encode($_SESSION['prenom'])); ?>;
            var magister = <?php echo (json_encode($_SESSION['magister'])); ?>;
            var redouble = <?php echo (json_encode($_SESSION['redouble'])); ?>;

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

<!-- note perso : ptetr rajouter des id et class aux fieldset pour affich en dispo block les msg_err -->

<body style="background-color:lightgrey;">
    <?php include("navbar_2.php"); ?>
    <br /><br />


    <div class="jumbotron">
        <div class="container">
            <div id="language-selector" style="display: flex; align-items: center; gap: 5px;">
                <label for="language" style="margin-right: 10px;">🌍 Language:</label>
                <select id="language" onchange="changeLanguage()">
                    <option value="fr" selected>🇫🇷 Français</option>
                    <option value="en">🇬🇧 English</option>
                </select>
            </div>
            <div id="div_formVid">
                <!--formVid : formulaire Verification identifiant-->
                <form id="formVid" name="formVid" method="get" onsubmit="javascript:verifyId(this)" action="javascript:(function(){return;})()">
                    <h1 data-translate-key="verif">V&eacute;rification de l'email &eacute;tudiant</h1>
                    <br />
                    <fieldset>
                        <legend><b data-translate-key="check_id">Identifiant de v&eacute;rification</b> </legend>
                        <input class="field" id="idetu" type="text" name="idetu" />
                        <span id="span_verify_mail_button">
                            <input class="btn btn-sm btn-primary" id="bmail" type="submit" name="submit" data-translate-key="bouton" value="Verifier" >
                        </span>
                        <div class="con_error" id="con_error_id"></div>
                        <span class="note" id="noteVid"> <span data-translate-key="saisir"></span> <span> <?php echo (json_encode($_SESSION['mail'])); ?> </span></span>.
                    </fieldset>
                </form>
            </div>
            <br /><br />
            <div id="div_resend_mail_button">
                <span class="note" id="noteRemail" data-translate-key="mail">Vous n'avez pas re&ccedil;u de mail ? V&eacute;rifiez vos spams ou &nbsp;</span>
                <button class="btn btn-sm btn-danger" id="bremail" onclick="javascript:redirect('send_id.php')" data-translate-key="renvoyer">Renvoyer un email</button>
            </div>
            <br />
            <div class="rollback" id="back_index">
                <button class="btn btn-sm btn-primary" id="bbackindex" onclick="javascript:rollback()" data-translate-key="retour">Retour</button>
            </div>


        </div>
    </div>
</body>

</html>