
<!--
	voilà l'appel habituel pour confirmation

affichage_edt.php?UE1=LRC&GR1=1&UE2=MOGPL&GR2=2
	
-->


<?php

	$choix = []; //choix de type complet (ex:[["Conferences","1"],["ca","1"],["sup1x","1"],["cpa","2"],["cps","2"],["pc2r","3"]])

	$nb = 0;
	for ($i=1; $i<11; $i++)
	{
		if(isset($_GET['UE'.$i]))
		{
			$nb = $nb + 1;
		array_push($choix, [strtolower($_GET['UE'.$i]), $_GET['GR'.$i]]);
	    }  
	} 
	$sup = 0;
	for ($i=$nb; $i<6; $i++)
	{
		$sup = $sup + 1;
		array_push($choix, ["sup" . $sup . "x", "1"]);
	}  

	$SEMESTRE = 1;

	$month = date("m"); //1->jan ... 12->dec
	if ($month >=3 && $month <= 10) {
	    $SEMESTRE = 1;
	}
	else
	{
	    $SEMESTRE = 2;
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head> 
        <meta name="description" content="Inscriptions des etudiants au master informatique de Sorbonne Universit&eacute;">
            <meta name="keywords" content="EDT,Sorbonne Universit&eacute;,MASTER,INFO,CHOIX,UE">
        <title>Sorbonne Universit&eacute;, Master Informatique : Affichage de l'emploi du temps</title>
        <link rel="stylesheet" href="css/confirmation.css" type="text/css" />
        <link rel="stylesheet" href="css/ue.css" type="text/css" />
        <link rel="stylesheet" href="css/maincss.css" type="text/css" />
        <!-- <script src="js/jquery-latest.js"></script>  -->
		 <!-- --------------------------------------------- -->

		 <script src="js/jquery-3.7.1.min.js"></script> <!-- copie locale de jquery(realisee en 2024) -->
                <!-- Inclure jQuery Migrate pour la compatibilité -->
		<script src="https://code.jquery.com/jquery-migrate-3.4.1.min.js"></script>

		 <!-- ------------------------------------------------------ -->
        <script type="text/javascript" src="js/utils.js"></script>
        <script type="text/javascript">
            SEMNUM =<?php echo $SEMESTRE; ?>;
//            alert("SEMNUM : "+JSON.stringify(SEMNUM));
        </script>
        <script type="text/javascript" src="js/calendrier.js"></script>  <!--// là où est stocké l'EDT-->
        <script type="text/javascript" src="js/edt_print.js"></script> <!--//composant d'affichage des edt (reutilisable)-->
        <script type ="text/javascript">
            function show_edt() {

                var ue = <?php echo json_encode($choix); ?>;
        //        alert(ue);
                var listeUE = getCalendrier(), asso = {};
      //          for (const property in listeUE) {
//					 alert(property); //listeUE[property]);
	//			}
               

                for (var r = 0; r < ue.length; r++) 
                {
                	for (var j = 0; j < listeUE[ ue[r][0] ][0].length; j++){
                		if (listeUE[ ue[r][0] ][0][j] in asso == false) {
                			asso[listeUE[ ue[r][0] ][0][j]] = '';
                		}
                		else {	
		                	asso[listeUE[ ue[r][0] ][0][j]] += '    /    ';
		                }
		              //  alert(ue[r][0]);
		                if(ue[r][0] != 'conferences') {
		                   asso[listeUE[ ue[r][0] ][0][j]] += ue[r][0].toUpperCase();
		                }
		                else {
							asso[listeUE[ ue[r][0] ][0][j]] += 'CONF';
						}
            		}
            		
            		if (listeUE[ ue[r][0] ][ue[r][1]] [0] in asso == false) {
            			asso[listeUE[ ue[r][0] ][ue[r][1]] [0]] = '';
            		}
            		else {
        				asso[listeUE[ ue[r][0] ][ue[r][1]] [0]] += '    /    ';
        			}
                    asso[listeUE[ ue[r][0] ][ue[r][1]] [0]] += ue[r][0].toUpperCase() + "-" + ue[r][1];

            		if (listeUE[ ue[r][0] ][ue[r][1]] [1] in asso == false) {
            			asso[listeUE[ ue[r][0] ][ue[r][1]] [1]] = '';
            		}
            		else {
        				asso[listeUE[ ue[r][0] ][ue[r][1]] [1]] += '    /    ';
        			}                    
                    asso[listeUE[ ue[r][0] ][ue[r][1]] [1]] += ue[r][0].toUpperCase() + "-" + ue[r][1];    	
    			}          
//                alert(JSON.stringify(asso));
                print_edt(asso, "", ""); //Precondition : Une balise d'id "edtbox__" doit exister dans le document html
               (document.getElementById('planning')).value = JSON.stringify(asso);//ajout de l'edt dans un hidden pour la suite(edtideal & pdf)
            }
        </script>
    
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>             
    </head>

    <body style="background-color:lightgrey;" onload="show_edt()">
        <?php include("navbar_1.php"); ?>

            <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">        
        <h2><b>Voil&agrave; votre emploi du temps.</b></h2>
            <br/>

        
        <div class="edtbox" id="edtbox__"></div>      


          </div>    
        </form>
        <br/>
       
      </div>
    </div>           
    </body>
</html>
