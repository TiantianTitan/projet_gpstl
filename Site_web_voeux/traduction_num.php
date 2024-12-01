
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta charset="UTF-8">
            <meta name="description" content="Inscriptions des etudiants au master informatique de l'Upmc">
            <meta name="keywords" content="EDT,SU,MASTER,INFO,CHOIX,UE,ANAGBLA,NOUIRA">
            <meta name="author" content="ANAGBLA Joan & NOUIRA Chafik">
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
        <h2><b>Voeux d'inscription aux UE du Master Informatique de Sorbonne Universit&eacute;.</b></h2>
       

        <p>Vous avez re&ccedil;u ce lien car vous avez r&eacute;alis&eacute; vos voeux en utilisant
        votre num&eacute;ro e-candidat (ou Campus France) :
        <br/>
        <p/>
        <?php
			if (isset($_GET['num_old']))
				echo $_GET['num_old'];
            else
                echo "Vous avez acc&eacute;d&eacute; &agrave; ce site de fa&ccedil;on non adapt&eacute;e.";
        ?> 
        <br/>
        <p>
        Mais nous avons besoin de votre num&eacute;ro d'&eacute;tudiant afin de pouvoir
        finaliser votre inscription. Ce num&eacute;ro vous a &eacute;t&eacute;
        donn&eacute; lors de votre pr&eacute;-inscription administrative
        (si vous ne l'avez pas encore faite, il faut commencer par cette &eacute;tape
        puis revenir muni de votre num&eacute;ro d'&eacute;tudiant sur ce site).</p>
        <br/><br/>
        Le num&eacute;ro que vous devez ins&eacute;rer ici se compose de soit :
        <br/> 7 chiffres dont le premier est un 3 (si vous &eacute;tiez d&eacute;j&agrave; 
			&eacute;tudiant &agrave; Sorbonne Universit&eacute;)
		<br/> 8 chiffres dont le premier est un 2 (si vous vous inscrivez pour la premi&egrave;re
			fois &agrave; Sorbonne Universit&eacute;).
        <br/><br/>
        </p>

		<form action="trad_sauve.php" method="get"> 

            <div style="display:inline-block;float:left;width:50%;">        
                    <div class="form-group">
                    <label>Num&eacute;ro d'&eacute;tudiant :</label>
                    <?php
						echo "<input pattern='(2|3)\d{6,7}' class='field' id='num' type='text' name='num'/>";
                    ?> 
                    <div class="con_error" id="con_error_num"></div> <!--Sert a l'affichage des messages d'erreur-->
                   
                    
                    <br/> <br/>
                    
            </div>

				<input type="hidden" id="num_old" name="num_old" value="<?php echo $_GET['num_old']; ?>">

                <div id="div_buttons">
                    <input class="btn btn-lg btn-primary" type="submit" value="Sauvegarde"/>
                </div>
            </div>        
                    
            </form>
        </div>
        

      </div>
      </div>
    </body>
</html>
