 <!--Page d'accueil : index.php
 
 Fichier à conserver
 
 -->
<?php
require_once ('semestre_cache.php');
//echo " & cur_sem : ".$_SESSION['SEMESTRE']."<br/>";
//print_r($_SESSION['ALLUES']);
//echo "<br/><br/>";
//print_r($_SESSION['MASTER']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta charset="UTF-8">
            <meta name="description" content="Inscriptions des etudiants au master informatique de Sorbonne Universit&eacute;">
            <meta name="keywords" content="EDT,Sorbonne Universit&eacute;,MASTER,INFO,CHOIX,UE,ANAGBLA,NOUIRA">
            <meta name="author" content="ANAGBLA Joan & NOUIRA Chafik">
            <title>Sorbonne Universit&eacute;, Master Informatique : Saisie des voeux d'UE</title>
            <link rel="stylesheet" href="css/maincss.css" type="text/css" />
            <link rel="stylesheet" href="css/index.css" type="text/css" />
            <!-- Decommenter sur le seveur si connexion disponible
            <script src="http://code.jquery.com/jquery-latest.js"></script>
            Contenu duplique en local dans js/jquery-latest.js  -->
            <!-- <script src="js/jquery-latest.js"></script> copie locale de jquery(realisee en 2014) -->

           <!-- --------------------------------------------- -->

		 <script src="js/jquery-3.7.1.min.js"></script> <!-- copie locale de jquery(realisee en 2024) -->
                <!-- Inclure jQuery Migrate pour la compatibilité -->
		<script src="https://code.jquery.com/jquery-migrate-3.4.1.min.js"></script>

		 <!-- ------------------------------------------------------ -->

            <script type="text/javascript" src="js/index_cache.js"></script>
            <script type="text/javascript" src="js/utils.js"></script>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css"/>

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    </head>


    <body style="background-color:lightgrey;">
        <?php include("navbar_1.php"); ?>


            <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h2><b>Voeux d'inscription aux UE du Master Informatique de Sorbonne Universit&eacute;.</b></h2>
        <p>Ce site permet de proposer ses voeux de choix d'UE.
        L'inscription effective aura lieu en septembre, lors de l'inscription p&eacute;dagogique des &eacute;tudiants du M1 Informatique de Sorbonne Universit&eacute;.</p>
        <br/><br/>

        <div id="div_formI" class="form-inline" role="form" style="width:100%;">
            <!--formI : formulaire Informations personnelles-->
            <form id="formI" name="formInfos" method="get" onsubmit="javascript:connect(this)" action="javascript:(function(){return;})()">
                <!-- action="javascript:(function(){return;})()" permet :
                Gestion par aiguillage centralisee avec javascript : js/file.js qui decidera en fonction des parametres transmis
                ou aller ensuite / ou bien rester sur la meme page active( parametres incorrects par exemple )
                sans recharger la page (conservation de l etat du formulaire)  -->



                <legend><b>Informations sur l'&eacute;tudiant</b></legend>


            <div style="display:inline-block;float:left;width:50%;">
                    <div class="form-group">

                    <label>Mot de passe :</label>
                    <?php
                    if (isset($_GET['code']))
                        echo "<input value='" . $_GET['code'] . "' class='field' id='code' type='text' name='code'/>";
                    else
                        echo "<input class='field' id='code' type='password' name='code'/>";
                    ?>

                    <br/> <br/>

                    <label>Num&eacute;ro de dossier :</label>
                    <?php
                    if (isset($_GET['num']))
                        echo "<input value='" . $_GET['num'] . "' pattern='{7,}' class='field' id='num' type='text' name='numetu'/>";
                    else
                        echo "<input pattern='{7,}' class='field' id='num' type='text' name='numetu'/>";
                    ?>
                    <div class="con_error" id="con_error_num"></div> <!--Sert a l'affichage des messages d'erreur-->
                    <span class="note" id="noteN">Si le num&eacute;ro n'est pas connu, les donn&eacute;es ne seront pas enregistr&eacute;es.</span>


                    <br/> <br/>

                    <label>Confirmation du num&eacute;ro de dossier :</label>
                    <?php
                    if (isset($_GET['num']))
                        echo "<input value='" . $_GET['num'] . "' pattern='(2|3)\d{6}' class='field' id='num2' type='text' name='numetu2'/>";
                    else
                        echo "<input pattern='{7,}' class='field' id='num2' type='text' name='numetu2'/>";
                    ?>
                    <div class="con_error" id="con_error_num2"></div>

                    </div>

                    <br/>




                    <label>Nom :</label>
                    <?php
                    if (isset($_GET['nom']))
                        echo "<input value='" . $_GET['nom'] . "' class='field' id='nom' type='text' name='nometu'/>";
                    else
                        echo "<input class='field' id='nom' type='text' name='nometu'/>";
                    ?>
                    <div class="con_error" id="con_error_nom"></div>
                    <br/>


                    <label>Pr&eacute;nom :</label>
                    <?php
                    if (isset($_GET['prenom']))
                        echo "<input value='" . $_GET['prenom'] . "' class='field' id='prenom' type='text' name='prenometu'/>";
                    else
                        echo "<input class='field' id='prenom' type='text' name='prenometu'/>";
                    ?>
                    <div class="con_error" id="con_error_prenom"></div>
                    <br/>


                    <label>Adresse email :</label>
                    <?php
                    if (isset($_GET['mail']))
                        echo "<input value='" . $_GET['mail'] . "' class='field' type='email' id='email' name='email'/>";
                    else
                        echo "<input class='field' type='email' id='email' name='email'/>";
                    ?>
                    <div class="con_error" id="con_error_email"></div>
                    <br/>




            </div>


            <div style="display:inline-block;float:right;width: 50%;">


            		<label>Parcours :</label>
                    <select class="selectop" id="spe" name="spe">
                        <option value='AI2D' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'AI2D'){echo "selected='selected'";} ?> >AI2D</option>
                        <option value='BIM' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'BIM') {echo "selected='selected'";} ?> >BIM</option>
                        <option value='CCA'<?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'CCA'){echo "selected='selected'";} ?> >CCA</option>
                        <option value='IMA' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'IMA') {echo "selected='selected'";} ?> >IMA</option>
                        <option value='MIND' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'MIND') {echo "selected='selected'";} ?> >MIND</option>
                        <option value='RES' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'RES') {echo "selected='selected'";} ?> >RES</option>
                        <option value='SAR' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'SAR') {echo "selected='selected'";} ?> >SAR</option>
                        <option value='SESI'<?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'SESI'){echo "selected='selected'";} ?> >SESI</option>
                        <option value='STL' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'STL') {echo "selected='selected'";} ?> >STL</option>
                        <option value='QI' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'QI') {echo "selected='selected'";} ?> >QI</option>
                    </select>

                    <br/><br/><br/>


                    <b>&Ecirc;tes-vous redoublant du master Informatique de Sorbonne Universit&eacute; ?</b><br/>
                    <?php
                    if (isset( $_GET['redouble'] ) and $_GET['redouble'] == 'true')
                        echo "<input class='radio' id='r1' type='radio' name='redoublant' value='non'/>".
                        "<span class='opt' id='optR1'> <b> Non</b></span>".
                        "<br>".
                        "<input class='radio' id='r2' type='radio' name='redoublant' value='oui' checked='checked' />".
                        "<span class='opt' id='optR2'><b> Oui</b></span>";
                    else
                        echo "<input class='radio' id='r1' type='radio' name='redoublant' value='non'  checked='checked'/>".
                        "<span class='opt' id='optR1'> <b> Non</b> </span>".
                        "<br>".
                        "<input class='radio' id='r2' type='radio' name='redoublant' value='oui' />".
                        "<span class='opt' id='optR2'> <b> Oui</b></span>";
                    ?>
                    <br/><br/><br/>

                 <!--ajout sytematique d'une ue au nombre d'ues choisies ou par defaut(5))-->
                 <div>  <!-- <div style='visibility: hidden'> -->
                   <div class='con_error' id='con_error_magistere'></div>
                    <b>Appartenez-vous au parcours d'excellence du master Informatique de Sorbonne Universit&eacute; ?</b><br/>
                     <br/>

                     <?php
                    if (isset( $_GET['magister'] ) and $_GET['magister'] == 'true') {
                        echo "<input class='radio' id='m1' type='radio' name='magistere' value='non' />" .
                        "<span class='opt' id='optM1'> <b> Non</b> </span>" .
                        "<br>" .
                        "<input class='radio' id='m2' type='radio' name='magistere' value='oui' checked='checked' />" .
                        "<span class='opt' id='optM2'> <b> Oui</b> </span>";
                    }
                    else {
                        echo "<input class='radio' id='m1' type='radio' name='magistere' value='non' checked='checked' />".
                        "<span class='opt' id='optM1'> <b> Non</b> </span>".
                        "<br>".
                        "<input class='radio' id='m2' type='radio' name='magistere' value='oui' />".
                        "<span class='opt' id='optM2'> <b> Oui</b> </span>";
                    }
                    ?>
                    <br/><br/><br/>
                  </div>

                 <!--   <br/><br/><br/>  -->

                <div id="div_buttons">
                    <input class="btn btn-lg btn-primary" id="b0" type="submit" name="submit" value="Connexion"/>
                    <span class="note" id="noteF"><font color="red"><b><i>Tous les champs sont obligatoires.</i></b></font></span>
                </div>
            </div>

            </form>
        </div>
      </div>
      </div>
    </body>
</html>
