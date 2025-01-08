<?php

require_once ('uerl.php');

/** GESTION UES **/
session_start(); //recuperation de la session
$master=$_SESSION['MASTER'];

//print_r($_SESSION);//Debug

$spe = $_SESSION['spe'];  //recuperation de la specialite de l'etudiant
$semestre = $_SESSION['SEMESTRE'];
//$max_ues = ($_SESSION['magister'] == 'true' ? 6 : 5);  //nombre max d'ues suivies (redoublant ou pas)
$max_ues = ($_SESSION['SEMESTRE'] == 2 ? 7 : 5); // MODIF ICI Antoine
if ($_SESSION['magister'] == 'true'){
	$max_ues++;
}

$nb_suivi = $max_ues; //nombre d'ues suivies ce semestre( default =max_ues)
$ue_valides = []; //tableau vide : necessaire car tout est base sur la presence de ce tableau
if ($_SESSION['redouble'] == 'true') { //recuperation des ues validees par un redoublant
	$max_valides = ($_SESSION['SEMESTRE'] == 2 ? 6 : 4); // MODIF ICI Antoine
    for ($i = 1; $i <= $max_valides; $i++)               //4 en dur : immuable on ne peut pas valider 5 ue et redoubler mm si on a fait un magistere
        if (isset($_GET["uev$i"]))
            $ue_valides[] = $_GET["uev$i"];

    $_SESSION['uevtab'] = $ue_valides; //Mise a jour de l'environement de session pour la suite du processus (ici nous utiliserons $ue_valides)
    $nb_suivi = $max_ues - sizeof($ue_valides); //nombre d'ues suivies = max_ues-nombre d'ues validees
    //echo "validees=[".implode(",", $_SESSION['uevtab'])."] ->nb_suivi=$nb_suivi";//debug
}
$_SESSION['nb_suivi'] = $nb_suivi; //ajout du nombre d' ues suivies a l'environnement de session

// /* * GESTION EDT* */
// require_once('config.php'); //On aura besoin de config.php afin de se connecter a la base
// $reponse = mysql_query("SELECT * FROM UEGroupes");
// $groupes = []; //Tableau qui contiendra les paires (groupe_ue => effectif) Exemple ( groupe : algav1, effectif : 30 )
// while ($donnees = mysql_fetch_array($reponse))
//     $groupes[$donnees['groupe']] = $donnees['effectif'];

require_once('config.php');
// $reponse = $pdo->query("SELECT * FROM UEGroupes");
// $groupes = [];
// while ($donnees = $reponse->fetch(PDO::FETCH_ASSOC)) {
// 	$groupes[$donnees['groupe']] = $donnees['effectif']; // Ajout du groupe et de l'effectif
// }

?>

<!--Page de saisie des ues suivies ce semestre par un etudiant: choix_ues.php--> <!--FUSIONNE THIS TO CHOIXEDT-->
<!--Page script gerant le calcul de l'emploie du temps de l'etudiant en fonction de ses ues choisies: edt.php-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta charset="UTF-8">
            <meta name="description" content="Inscriptions des etudiants au master informatique de Sorbonne Universit&eacute;">
            <meta name="keywords" content="EDT,Sorbonne Universit&eacute;,MASTER,INFO,CHOIX,UE">
            <title>SU, Master Informatique : Saisie des voeux d'UE</title>

            <?php if ($_SESSION['SEMESTRE'] == 1) {
            		echo '<link rel="stylesheet" href="css/choix_ues.css" type="text/css" />';
            	}
            	else {
            		echo '<link rel="stylesheet" href="css/edt_ideal.css" type="text/css" />';
            	}
            ?>

            <link rel="stylesheet" href="css/maincss.css" type="text/css" />


            <!-- Decommenter sur le seveur si connexion disponible
            <script src="http://code.jquery.com/jquery-latest.js"></script>
            Contenu duplique en local dans js/jquery-latest.js  -->
            <!-- <script src="js/jquery-latest.js"></script> copie locale de jquery(realisee en 2014) -->
			
			  <!-- --------------------------------------------- -->

			  <script src="js/jquery-3.7.1.min.js"></script> <!-- copie locale de jquery(realisee en 2024) -->
                <!-- Inclure jQuery Migrate pour la compatibilité -->
        <script src="https://code.jquery.com/jquery-migrate-3.4.1.min.js">

		 <!-- ------------------------------------------------------ -->


            <script type="text/javascript" src="js/utils.js"></script>
            <script type="text/javascript" src="js/choix_ues.js"></script>
            <script type="text/javascript">
                GRPES  =<?php echo json_encode($groupes); ?> ;
                //Modifs du aux besoin du s2 :
                SPERECOM =<?php echo json_encode($master[$spe]["recom"]); ?> ;
                SEMNUM =<?php echo json_encode($_SESSION['SEMESTRE']); ?> ;
                UEVALIDES =<?php echo json_encode($ue_valides); ?>;
                NBSUIVI = <?php echo json_encode($nb_suivi); ?>;
                SPE =<?php echo json_encode($_SESSION['spe']); ?>;
                CODE =<?php echo json_encode($_SESSION['code']); ?>; 											// a corriger
                //alert("SPERECOM : "+JSON.stringify(SPERECOM));
                //alert("SEMNUM : "+JSON.stringify(SEMNUM));
                //alert("UEVALIDES : "+JSON.stringify(UEVALIDES));
            </script>
            <script type="text/javascript" src="js/descriptions.js"></script>
            <script type="text/javascript" src="js/calendrier.js"></script>
            <script type="text/javascript" src="js/edt_utils.js"></script>
            <script type="text/javascript" src="js/edt_print.js"></script>
            <script type="text/javascript" src="js/edt.js"></script>
            <script type="text/javascript" src="js/effectifs.js"></script>
            <script type="text/javascript" src="js/sem2constraints.js"></script>



            <script type="text/javascript">
                function rollback() { //fonction de retour arriere en dur (l'url de retour est fixe et statique)
					var semestre = <?php echo json_encode($_SESSION['SEMESTRE']); ?> ;
                    var num = <?php echo(json_encode($_SESSION['num'])); ?>;
                    var nom = <?php echo(json_encode($_SESSION['nom'])); ?>;
                    var spe = <?php echo(json_encode($_SESSION['spe'])); ?>;
                    var mail = <?php echo(json_encode($_SESSION['mail'])); ?>;
                    var prenom = <?php echo(json_encode($_SESSION['prenom'])); ?>;
                    var magister = <?php echo(json_encode($_SESSION['magister'])); ?>;
                    var redouble = <?php echo(json_encode($_SESSION['redouble'])); ?>;
                    if (redouble == 'true')
                        window.location.href = "saisie_ues_valides.php";
                    else
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

    <body style="background-color:lightgrey;s" onload="add_oblig(<?php echo(json_encode($nb_suivi)); ?>)">

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
        <h2><b data-translate-key="titre">Choix des UE</b></h2>
        <!-- <h2><i><span style="color:#0000FF">Lectures' wishes</span></i></h2> -->

        <span class="note" id="description_master"> </span>

        <br/><br/>

            <form id="formUes" name="formChoixUes" method="GET" action="genererMailPDF.php">
                <!-- action="javascript:(function(){return;})()" permet :
                Gestion par aiguillage centralisee avec javascript : js/file.js qui decidera en fonction des parametres transmis
                ou aller ensuite / ou bien rester sur la meme page active( parametres incorrects par exemple )
                sans recharger la page (conservation de l etat du formulaire)  -->
                <!--<h3><b>Choix des unit&eacute;s d'enseignement</b></h3>-->


                    <?php
                    $choix_ues = $master["$spe"];
                    $cpt = 1;
                  	$nbOblig = 0;
                  	$nbRecom = 0;
                  	$nbLibre = 0;

                    foreach ($choix_ues as $key => $value)
                    {
                        sort($value); //ordre alphabetique sur la liste d'ues
												if($spe != 'IMA' or $semestre==1)
												{
	                        switch($cpt)
	                        {	
	                            case 1 : echo "<b data-translate-key='obl'>UE obligatoires : </b>"; break;
	                            case 2 : echo "<b data-translate-key='recom'>UE recommand&eacutes par le parcours : </b>"; break;
	                            case 3 : echo "<b data-translate-key='autre'>autres UE propos&eacutees par le master : </b>"; break;
	                            default : echo "<b>Should not occur !</b>"; break;
	                        }
												}
												else  // spécifique pour IMA au S2
												{
													switch($cpt)
	                        {
	                            case 1 : echo "<b>UE obligatoires : </b>"; break;
	                            case 2 : echo "<b>Choisir 1 UE, &agrave; 3ECTS, obligatoire parmi : </b>"; break;
	                            case 3 : echo "<b>Choisir 2 UE, &agrave; 6ECTS parmi :</b>"; break;
	                            default : echo "<b>Should not occur !</b>"; break;
	                        }
												}
                        echo "<br/>";

                        if ($cpt == 1) {
							foreach ($value as $ue)
							{
								if (!in_array($ue, $ue_valides))
								{//retirer les ues deja valides pour un redoublant
									if ($key == 'oblig')
									{
										$nbOblig++;

										echo "<div style=\"display:inline-block;float:left;\">";
										if ($ue !=' ')
										{
										  echo '<span class="box_ue_' . $key . '" id="span_' . $ue . '">' .
										  '<input disabled="true" checked="checked" class="check_ue" type="checkbox" name="' . $ue . '" id="ue_' . $ue . '"/>' .
										  '<a target="_blank" href="'.$uerl[$ue].'" id="label_' . $ue . '" for="' . $ue . '">' . strtoupper($ue) . '</a>' .
										  '</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . "\n";
										}
										else
										{
											echo '<span class="box_ue_' . $key . '" id="span_' . $ue . '">' .
										  '<input type=hidden class="check_ue"  name="' . $ue . '" id="ue_' . $ue . '"/>' .
								    	  '</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . "\n";
										}
										echo "</div>";
									}
								}
							}
						}
						if ($cpt == 2) {
							foreach ($value as $ue)
							{
								if (!in_array($ue, $ue_valides))
								{//retirer les ues deja valides pour un redoublant
									if ($key == 'recom')
									{
										$nbRecom++;
										echo "<div style=\"display:inline-block;float:left;\">";
										echo '<span class="box_ue_recom" id="span_' . $ue . '">' .
										'<a target="_blank" href="'.$uerl[$ue].'" id="label_' . $ue . '" for="' . $ue . '">' . strtoupper($ue) . '</a>' .
										'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . "\n";
										echo "</div>";
									}
								}
							}
						}
						if ($cpt==3) {
							foreach ($value as $ue)
							{
								if (!in_array($ue, $ue_valides))
								{//retirer les ues deja valides pour un redoublant
									if ($key == 'libre')
									{
										$nbLibre++;
										if ($spe!='IMA' or $semestre==1)
										{
											echo "<div style=\"display:inline-block;float:left;\">";
											echo '<span class="box_ue_recom" id="span_' . $ue . '">' .
											'<a target="_blank" href="'.$uerl[$ue].'" id="label_' . $ue . '" for="' . $ue . '">' . strtoupper($ue) . '</a>' .
											'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . "\n";
											echo "</div>";
										}
										else // pour IMA au S2
										{
											if ($ue == 'dj')
											{
												echo "UE conseill&eacute;es par le parcours :<br>";
											}
											elseif ($ue == 'rital')
											{
												echo "<br>UE d'ouvertures :<br>";
											}
											echo "<div style=\"display:inline-block;float:left;\">";
											echo '<span class="box_ue_recom" id="span_' . $ue . '">' .
											'<a target="_blank" href="'.$uerl[$ue].'" id="label_' . $ue . '" for="' . $ue . '">' . strtoupper($ue) . '</a>' .
											'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . "\n";
											echo "</div>";
										}
									}
								}
							}

								//echo $nbOblig;  nb d'UE oblig prise
								if ($semestre == 1)
								{
								   $nb_ajout =  $nb_suivi - $nbOblig + 3;   // pour le S1 +3 : choix en supp
								}
								else
								{ // pour le S2
									$nb_par_parcours = array(
										"ANDROIDE" => 7, "BIM" => 10, "CCA" =>  7, "DAC" => 7, "IMA" => 7, "IQ" => 4, "RES" => 10, "SAR" => 9, "SESI" => 6, "STL" => 7 );

								   $nb_ajout = $nbRecom + $nbLibre;
								   if ($nb_ajout > $nb_par_parcours[$spe])
								   {
									   $nb_ajout = $nb_par_parcours[$spe];
								    }
								}

								$listeUEoblig = $choix_ues['oblig'];
								sort($listeUEoblig);
								$i = 0;
								foreach ($listeUEoblig as $ue)
								{
									if (!in_array($ue, $ue_valides))
									{
										$i = $i+1;
										echo '<input type="hidden" name="UEoblig'.$i.'" value="'. strtoupper($ue) .'">'."\n";
									}
								}


								$listeUE = array_merge($choix_ues['recom'],$choix_ues['libre']) ;
								sort($listeUE);

								if ($semestre == 2 and ($spe == 'IMA' or $spe=='IQ'))
								{
									$list1UE = $choix_ues['recom'];
									sort($list1UE);
									$list2UE = $choix_ues['libre'];
									if ($spe == 'IQ')
									{
										sort($list2UE);
									}
								}

								$nbUE = count($listeUE);
								foreach ($listeUE as $ue)
								{
									if (in_array($ue, $ue_valides))
									{
										$nbUE--;
									}
								}
								// $nbUE contient le nombre d'éléments de la 1ere liste de choix

								echo "</br></br>";

								if ($nb_ajout>0)
								{
																		//    $nb_ajout : nb de voeux; $nb_suivi = 7 si semestre2   et $nbOblig : nb UE obligatoire qui seront prise
									echo "</br></br>";


									for ($i=0; $i<$nb_ajout; $i++)
									{
										$ii = $i+1;
										echo '<div style="display:inline-block;float:left;width:10%;"> ';
										echo	"<th>UE voeu ".$ii." :</th>";
										echo '</div>';
									}
									echo '</br>';
									for ($i=0; $i<$nb_ajout; $i++)
									{
										$ii = $i+1;
										echo '<div style="display:inline-block;float:left;width:10%;"> ';
										if ($semestre == 1)
										{
											echo	'<select  name="UEsup'.$ii.'" onchange="ChoixS'.$semestre.'_'.$ii.'(\''.$spe.'\', this.form,'.$nb_ajout.','.$nb_suivi.')">'."\n";
										}
										else
										{
											echo	'<select  name="UEsup'.$ii.'" onchange="ChoixS'.$semestre.'(\''.$spe.'\', this.form, '.$i.')">'."\n";
										}
										if ($ii == 1)
										{
											echo '<option selected="selected">--- Choisissez une UE---</option>'."\n";
											$nblignes = 0;
											if ($semestre == 1 or ($spe != 'IMA' and $spe != 'IQ'))
											{
												foreach ($listeUE as $ue)
												{
													if (!in_array($ue, $ue_valides))
													{
														echo '<option>'. strtoupper($ue) .'</option>'."\n";
														$nblignes++;
													}
												}
											}
											else
											{
												foreach ($list1UE as $ue) // modifier pour affichage pour IMA
												{
													if (!in_array($ue, $ue_valides))
													{
														echo '<option>'. strtoupper($ue) .'</option>'."\n";
														$nblignes++;
													}
												}
											}
										}
										else
										{
											if ($semestre == 1 or (($spe != 'IMA' or $ii !=3) and ($spe != 'IQ' or $ii !=2)))
											{
												$nblignes--;
												echo '<option selected="selected">--- Choisissez une UE---</option>'."\n";
												for ($k=0; $k<$nblignes; $k++)
												{
													echo '<option></option>'."\n";
												}
											}
											else
											{
												echo '<option selected="selected">--- Choisissez une UE---</option>'."\n";
												$nblignes = 0;
												foreach ($list2UE as $ue)
												{
													if (!in_array($ue, $ue_valides))
													{
														echo '<option>'. strtoupper($ue) .'</option>'."\n";
														$nblignes++;
													}
												}
											}
										}
										echo '</select> '."\n";
										echo '</div>';
									}

								}

						}
                        echo "<br/><br/><br/>";

                        $cpt++;
                    }

                    ?>

      	        <center>
                <br/>

			<input id="Valider" type="hidden" value="Valider">

                </center>

            </form>

	         <br><br>


      </div>
    </div>
    </body>
</html>
