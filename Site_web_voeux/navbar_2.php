       <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a target = "_blank" class="navbar-brand" href="https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique">Sorbonne Universit&eacute; Master Informatique</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a target="_blank" href="contact.php">Contact</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Parcours<span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a target="_blank" href="https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique/parcours-AI2D">ANDROIDE</a></li>
                  <li><a target="_blank" href="https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique/parcours-bim">BIM</a></li>
                  <li><a target="_blank" href="https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique/parcours-cca">CCA</a></li>
                  <li><a target="_blank" href="https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique/parcours-MIND">MIND</a></li>
                  <li><a target="_blank" href="https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique/parcours-ima">IMA</a></li>
                  <li><a target="_blank" href="https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique/parcours-IQ">IQ</a></li>
                  <li><a target="_blank" href="https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique/parcours-res">RES</a></li>
                  <li><a target="_blank" href="https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique/parcours-sar">SAR</a></li>
                  <li><a target="_blank" href="https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique/parcours-sesi">SESI</a></li>
                  <li><a target="_blank" href="https://sciences.sorbonne-universite.fr/formation-sciences/masters/master-informatique/parcours-stl">STL</a></li>
                </ul>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">

              <li class="active">
                  <a>
                    <?php if(isset($_SESSION['prenom'])) echo "Bonjour ".$_SESSION['prenom'];
                        else echo "Bonjour";?>
                    <span class="sr-only">(current)</span>
                  </a>
              </li>
                <li><a></a></li>
                <li><a></a></li>
                <li><a></a></li>
                <li><a></a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
