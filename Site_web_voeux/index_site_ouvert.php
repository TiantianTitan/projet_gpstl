 <!-- Page d'accueil : index.php -->
<?php
require_once ('semestre.php');
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
            <meta name="description" content="Inscriptions des etudiants au master informatique de SU">
            <meta name="keywords" content="EDT,SU,MASTER,INFO,CHOIX,UE">
            <title>SU, Master Informatique : Saisie des voeux d'UE</title>
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
            <script type="text/javascript" src="js/index.js"></script>
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
      <div id="language-selector" style="display: flex; align-items: center; gap: 5px;">        
        <label for="language" style="margin-right: 10px;">🌍 Language:</label>
        <select id="language" onchange="changeLanguage()">
            <option value="fr" selected>🇫🇷 Français</option>
            <option value="en">🇬🇧 English</option>
        </select>
      </div>
      </br>
      <h1 class="text" data-translate-key="welcome"></h1>
        <h2><b data-translate-key="voeux">Voeux d'inscription aux UE du Master Informatique de Sorbonne Universit&eacute;.</b></h2>

<!--
        <p>Ce site permet de proposer ses voeux de choix d'UE pour le S1.
        L'inscription effective aura lieu en septembre apr&egrave;s convocation par le secr&eacute;tariat du M1 Informatique.</p>
        <br/>
         <h2><i><span style="color:#0000FF">Wishes for the courses</span></i></h2>
        <p><i><span style="color:#0000FF">Though this website you can express wishes for your lectures for the first semester.
        But the registration will be effective only after a meeting with the administrative staff of the Master.</span></i></p>
        <br/><br/>
 -->
 
        <p data-translate-key="description">Ce site permet de proposer ses voeux de choix d'UE pour le S2.
        L'inscription effective aura lieu en janvier apr&egrave;s convocation par le secr&eacute;tariat du M1 Informatique.</p>
        <br/>
         <!-- <h2><i><span style="color:#0000FF">Wishes for the courses</span></i></h2>
        <p><i><span style="color:#0000FF">Though this website you can express wishes for your lectures for the second semester.
        But the registration will be effective only after a meeting with the administrative staff of the Master.</span></i></p> -->
        <br/><br/>

        <div id="div_formI" class="form-inline" role="form" style="width:100%;">
            <!--formI : formulaire Informations personnelles-->
            <form id="formI" name="formInfos" method="get" onsubmit="javascript:connect(this)" action="javascript:(function(){return;})()">
                <!-- action="javascript:(function(){return;})()" permet :
                Gestion par aiguillage centralisee avec javascript : js/file.js qui decidera en fonction des parametres transmis
                ou aller ensuite / ou bien rester sur la meme page active( parametres incorrects par exemple )
                sans recharger la page (conservation de l etat du formulaire)  -->



                <legend><b data-translate-key="info">Informations sur l'&eacute;tudiant</b> 
                        <!-- <i><span style="color:#0000FF">Student's information</span></i> -->
                </legend>


            <div style="display:inline-block;float:left;width:54%;">
                    <div class="form-group">
                    <label data-translate-key="numetu">Num&eacute;ro de dossier : 
                      <!-- <i><span style="color:#0000FF">Student's number</span></i> -->
                    </label>
                    <?php
                    if (isset($_GET['num']))

// il y a 4 lignes à commenter / décommenter ici

                        // on teste en détails
                        //echo "<input value='" . $_GET['num'] . "' pattern='(2|3)\d{6,}' class='field' id='num' type='text' name='numetu'/>";
                        // on ne vérifie juste qu'il a au moins 7 caractères
                        echo "<input value='" . $_GET['num'] . "' pattern='{7,}' class='field' id='num' type='text' name='numetu'/>";

                    else
                        //echo "<input pattern='(2|3)\d{6,}' class='field' id='num' type='text' name='numetu'/>";
                        echo "<input pattern='{7,}' class='field' id='num' type='text' name='numetu'/>";
                    ?>
                    <div class="con_error" id="con_error_num"></div> <!--Sert a l'affichage des messages d'erreur-->
                    <span class="note" id="noteN" data-translate-key="numetu_info">Si le num&eacute;ro n'est pas connu, les donn&eacute;es ne seront pas enregistr&eacute;es.
                    <!-- <i><span style="color:#0000FF">If the number is not known by the administration, the wishes will not be stored.</span></i> -->
                    </span>


                    <br/> <br/>

                    <label data-translate-key="numetu2">Confirmation du num&eacute;ro de dossier : 
                      <!-- <i><span style="color:#0000FF">Confirmation of the student's number</span></i> -->
                    </label>
                    <?php
                    if (isset($_GET['num']))
                        //echo "<input value='" . $_GET['num'] . "' pattern='(2|3)\d{6,}' class='field' id='num2' type='text' name='numetu2'/>";
                        echo "<input value='" . $_GET['num'] . "' pattern='{7,}' class='field' id='num2' type='text' name='numetu2'/>";
                    else
                        //echo "<input pattern='(2|3)\d{6,}' class='field' id='num2' type='text' name='numetu2'/>";
                        echo "<input pattern='{7,}' class='field' id='num2' type='text' name='numetu2'/>";
                    ?>
                    <div class="con_error" id="con_error_num2"></div>

                    </div>

                    <br/>




                    <label data-translate-key="nom">Nom : 
                      <!-- <i><span style="color:#0000FF">Name</span></i> -->
                    </label>
                    <?php
                    if (isset($_GET['nom']))
                        echo "<input value='" . $_GET['nom'] . "' class='field' id='nom' type='text' name='nometu'/>";
                    else
                        echo "<input class='field' id='nom' type='text' name='nometu'/>";
                    ?>
                    <div class="con_error" id="con_error_nom"></div>
                    <br/>


                    <label data-translate-key="prenom">Pr&eacute;nom : 
                      <!-- <i><span style="color:#0000FF">First name</span></i> -->
                    </label>
                    <?php
                    if (isset($_GET['prenom']))
                        echo "<input value='" . $_GET['prenom'] . "' class='field' id='prenom' type='text' name='prenometu'/>";
                    else
                        echo "<input class='field' id='prenom' type='text' name='prenometu'/>";
                    ?>
                    <div class="con_error" id="con_error_prenom"></div>
                    <br/>


                    <label data-translate-key="email">Adresse email : 
                      <!-- <i><span style="color:#0000FF">Email address</span></i> -->
                    </label>
                    <?php
                    if (isset($_GET['mail']))
                        echo "<input value='" . $_GET['mail'] . "' class='field' type='email' id='email' name='email'/>";
                    else
                        echo "<input class='field' type='email' id='email' name='email'/>";
                    ?>
                    <div class="con_error" id="con_error_email"></div>
                    <br/>




            </div>


            <div style="display:inline-block;float:right;width: 45%;">


            		<label data-translate-key="parcours">Parcours : 
                  <!-- <i><span style="color:#0000FF">Speciality</span></i> -->
                </label>
                    <select class="selectop" id="spe" name="spe">
                        <option value='ANDROIDE' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == '---'){} ?> >---</option>
                        <option value='ANDROIDE' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'ANDROIDE'){echo "selected='selected'";} ?> >ANDROIDE</option>
                        <option value='BIM' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'BIM') {echo "selected='selected'";} ?> >BIM</option>
                        <option value='CCA'<?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'CCA'){echo "selected='selected'";} ?> >CCA</option>
                        <option value='DAC' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'DAC') {echo "selected='selected'";} ?> >DAC</option>
                        <option value='IMA' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'IMA') {echo "selected='selected'";} ?> >IMA</option>
                        <option value='IQ' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'IQ') {echo "selected='selected'";} ?> >IQ</option>
                        <option value='RES' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'RES') {echo "selected='selected'";} ?> >RES</option>
                        <option value='SAR' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'SAR') {echo "selected='selected'";} ?> >SAR</option>
                        <option value='SESI'<?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'SESI'){echo "selected='selected'";} ?> >SESI</option>
                        <option value='STL' <?php if (isset( $_GET['spe'] ) and $_GET['spe'] == 'STL') {echo "selected='selected'";} ?> >STL</option>
                    </select>

                    <br/><br/><br/>


                    <b data-translate-key="redouble">&Ecirc;tes-vous redoublant du master Informatique de Sorbonne Universit&eacute; ?</b>
                    <!-- <i><span style="color:#0000FF">Are you repeating the first year of the computer science master ? </span></i> -->
                    <br/>
                    <?php
                    if (isset( $_GET['redouble'] ) and $_GET['redouble'] == 'true')
                        echo "<input class='radio' id='r1' type='radio' name='redoublant' value='non'/>".
                        "<span class='opt' id='optR1'> <b data-translate-key='non'> Non</b></span>".
                        "<br>".
                        "<input class='radio' id='r2' type='radio' name='redoublant' value='oui' checked='checked' />".
                        "<span class='opt' id='optR2'><b data-translate-key='oui'> Oui</b></span>";
                    else
                        echo "<input class='radio' id='r1' type='radio' name='redoublant' value='non'  checked='checked'/>".
                        "<span class='opt' id='optR1'> <b data-translate-key='non'> Non</b> </span>".
                        "<br>".
                        "<input class='radio' id='r2' type='radio' name='redoublant' value='oui' />".
                        "<span class='opt' id='optR2'> <b data-translate-key='oui'> Oui</b></span>";
                    ?>
                    <br/><br/><br/>

                 <!--ajout sytematique d'une ue au nombre d'ues choisies ou par defaut(5))-->

		 <div>
		  <div style='visibility: hidden'>
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



                <div id="div_buttons">
                    <input data-translate-key="connex" class="btn btn-lg btn-primary" id="b0" type="submit" name="submit" value="Connexion"/>
                    <span class="note" id="noteF"><font color="red"><b><i data-translate-key="champs" >Tous les champs sont obligatoires.</i>
                    <!-- <i><span style="color:#0000FF">Allfileds must be filled.</span></i> -->
                    </b>
                  </font></span>
                </div>
            </div>

            </form>
        </div>


      </div>
      </div>
    </body>
</html>
