<?php

/*
 * Smestre.php
 * A appeler en debut de session (index.php) pour initialiser la session avec les donnees du semestre en cours
 * 
 * Fichier plus à jour, mais à conserver
 * 
 */

session_start(); //recuperation de la session
$month = date("m"); //1->jan ... 12->dec
//echo "month : $month";

//$month=5;//pour tests

$ALLSPE = array('AI2D', 'BIM', 'CCA', 'MIND', 'IMA', 'RES', 'SAR', 'SESI', 'STL');  //useless

if ($month >=3 && $month <= 10) {// pour l'instant : Avril->Septembre (S1) //ask genitrini les periodes
    $_SESSION['SEMESTRE'] = 1; //Definition du numero de semestre
    //Definition de la liste complete des ues du semestre
    $_SESSION['ALLUES'] = array("maths4m062", "aagb", "mapsi", "algav", "mlbda", "archi", "mobj", "ares", "model", "bima", "mogpl", "complex", "noyau", "dlp", "pscr", "esa", "rtel", "il", "signal", "lrc", "vlsi", "prog_res", "ouv_anglais_stl");
    //Definition des contraintes sur  les ues du semestre pour chaque specialite
    $_SESSION['MASTER'] = array(
        "AI2D" => array(
            "oblig" => array("lrc", "mogpl"),
            "recom" => array("mapsi", "complex"), //ajout de il recommande (sur le site du master)
            "libre" => array("mlbda", "bima", "aagb", "il", "model", "pscr", "signal", "algav", "dlp")
        ),
        "BIM" => array(
            "oblig" => array("mapsi", "aagb", "maths4m062"), //mm062 ou 4m062?
            "recom" => array("bima", "lrc", "mogpl", "complex", "il"), //model est recommande ajout ou non ?
            "libre" => array("algav", "mlbda")
        ),
        "CCA" => array(
            "oblig" => array("model"),
            "recom" => array("complex", "ares", "archi", "noyau", "mapsi", "pscr"),
            "libre" => array("mlbda", "dlp")
        ),
        "MIND" => array(
            "oblig" => array("mlbda", "lrc"),
            "recom" => array("il", "mapsi"),
            "libre" => array("bima", "complex", "model", "mogpl")
        ),
        "IMA" => array(
            "oblig" => array("bima", "mapsi"),
            "recom" => array("model", "mogpl"),
            "libre" => array("aagb", "algav", "mlbda", "archi", "mobj", "ares", "complex", "noyau", "dlp", "pscr", "esa", "rtel", "il", "signal", "lrc", "vlsi")
        ),
        "RES" => array(
            "oblig" => array("ares", "rtel", "prog_res"),
            "recom" => array("archi", "complex", "signal", "mogpl", "noyau", "pscr"),
            "libre" => array("aagb", "mapsi", "algav", "mlbda", "mobj", "model", "bima", "dlp", "esa", "il", "lrc", "vlsi")
        ),
        "SAR" => array(
            "oblig" => array("noyau", "pscr"),
            "recom" => array("archi", "ares", "dlp", "il", "algav",),
            "libre" => array("aagb", "mapsi", "mlbda", "mobj", "model", "bima", "mogpl", "complex", "esa", "rtel", "signal", "lrc", "vlsi")
        ),
        "SESI" => array(
            "oblig" => array("archi", "vlsi"),
            "recom" => array(), //ajout des "ue au choix" du site de sesi en recom ou pas ?
            "libre" => array("aagb", "mapsi", "algav", "mlbda", "mobj", "ares", "model", "bima", "mogpl", "complex", "noyau", "dlp", "pscr", "esa", "rtel", "il", "signal", "lrc")
        ),
        "STL" => array(
            "oblig" => array("algav", "dlp", "ouv_anglais_stl"),
            "recom" => array("il", "mlbda", "lrc", "noyau", "pscr"),
            "libre" => array("aagb", "mapsi", "archi", "mobj", "ares", "model", "bima", "mogpl", "complex", "esa", "rtel", "signal", "vlsi")
        )
    );
} else if ($month >=11 && $month <= 12 || $month >=1 && $month <= 2 ) { // pour l'instant : Novembre ->Fevrier(S1) //ask genitrini les periodes
    $_SESSION['SEMESTRE'] = 2;

    $_SESSION['ALLUES'] = array('Anglais', 'Anglais ', 'Conferences', 'Projet', 'dj', 'ihm', 'rp', 'fosyma', 'sbas', 'mmcn', 'mv418', 'bi','tal',
        'bdr', 'arf', 'iamsi', 'ig3d', 'rout', 'mob', 'algores', 'progres','sev', 'comnum','ar', 'acii', 'pnl', 'specif', 'srcs', 'sas', 'fpga1',
        'anumdsp','archi2', 'peri', 'elecana2', 'hpc', 'isec', 'flag', 'rna', 'aps', 'ca', 'cpa', 'cps', 'pc2r');


    $_SESSION['MASTER'] = array(
        'AI2D' => array(
            'oblig' => array(),
            'recom' => array(),
            'libre' => $_SESSION['ALLUES']
            ),
        'BIM' => array(
            'oblig' => array(),
            'recom' => array(),
            'libre' => $_SESSION['ALLUES']
            ),
        'CCA' => array(
            'oblig' => array(),
            'recom' => array(),
            'libre' => $_SESSION['ALLUES']
            ),
        'MIND' => array(
            'oblig' => array(),
            'recom' => array(),
            'libre' => $_SESSION['ALLUES']
            ),
        'IMA' => array(
            'oblig' => array(),
            'recom' => array(),
            'libre' => $_SESSION['ALLUES']
            ),
        'RES' => array(
            'oblig' => array(),
            'recom' => array(),
            'libre' => $_SESSION['ALLUES']
            ),
        'SAR' => array(
            'oblig' => array(),
            'recom' => array(),
            'libre' => $_SESSION['ALLUES']
            ),
        'SESI' => array(
            'oblig' => array(),
            'recom' => array(),
            'libre' => $_SESSION['ALLUES']
            ),
        'STL' => array(
            'oblig' => array(),
            'recom' => array(),
            'libre' => $_SESSION['ALLUES']
            )
    );


} else {
    $msg="Le site n'est pas encore ouvert.";
    $url="http://www-master.ufr-info-p6.jussieu.fr/lmd/";
    echo '<html><head><title>Redirection ..</title></head>' .
        '<body onload="timer = setTimeout(function(){ window.location =\'' . $url . '\';},5000)">' .
        '<p><b><font color=red>' . $msg . '</font></b> <br/> Vous allez etre redirige vers la page du master dans 5 secondes</p>' .
        '</body></html>';
}
?>
