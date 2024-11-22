<?php

/*
 * Semestre.php
 * A appeler en debut de session (index.php) pour initialiser la session avec les donnees du semestre en cours
 */

session_start(); //recuperation de la session
$month = date("m"); //1->jan ... 12->dec
//echo "month : $month";

//$month=5;//pour tests


if ($month >=3 && $month <= 10) {// pour l'instant : Mars->Octobre (S1) //ask genitrini les periodes
    $_SESSION['SEMESTRE'] = 1; //Definition du numero de semestre
    //Definition de la liste complete des ues du semestre
//    "il" en moins
    $_SESSION['ALLUES'] = array("aagb", "algav", "archi", "archi_en", "ares", "bima", "bima_en", "comnet_en", "complex", "dlp", "esa", "lrc", "mapsi", "maths4m062", "mlbda", "mobj", "model", "model_en", "mogpl", "noyau", "ouv_ang", "ppar_en", "progres", "pscr", "qclg_qk4cs_en", "qph4cs_en", "rtel", "sc", "sigcom_en", "signal", "vlsi");

    //Definition des contraintes sur  les ues du semestre pour chaque parcours
    $_SESSION['MASTER'] = array(
  "ANDROIDE" => array(
		"oblig" => array("lrc", "mogpl"),
		"recom" => array("mapsi", "complex"),
		"libre" => array("mlbda", "bima_en", "bima", "aagb", "model","model_en", "pscr", "signal", "algav", "dlp", "qclg_qk4cs_en") //"il",
	),
	"BIM" => array(
		"oblig" => array("mapsi", "aagb", "maths4m062"),
		"recom" => array("bima", "bima_en", "lrc", "mogpl", "complex", "model_en", " model"), //"il",
		"libre" => array("algav", "mlbda")
	),
    "CCA" => array(
		"oblig" => array("model", "complex"),
		"recom" => array("ares", "archi", "noyau", "ppar_en", "qclg_qk4cs_en"),
		"libre" => array("mlbda", "mogpl", "mapsi", "pscr")
	),
	"DAC" => array(
		"oblig" => array("mlbda", "lrc", "mapsi"),
		"recom" => array(),
		"libre" => array("bima_en", "bima", "complex", "model", "model_en", "mogpl", "aagb", "signal") //"il",
	),
	"IMA" => array(
		"oblig" => array("bima", "mapsi"),
		"recom" => array("model", "model_en", "mogpl"),
		"libre" => array("aagb", "algav", "mlbda", "archi", "mobj", "ares", "complex", "noyau", "dlp", "pscr", "rtel", "signal", "lrc", "vlsi", "ppar_en") //"il",
	),
	"IQ" => array(
		"oblig" => array("qclg_qk4cs_en", "qph4cs_en"),
		"recom" => array(),
		"libre" => array("model", "model_en", "algav", "complex", "mogpl", "sigcom_en", "ppar_en", "archi_en", "comnet_en", "bima_en")
	),
	"RES" => array(
		"oblig" => array("ares", "rtel", "progres"),
		"recom" => array("archi", "archi_en", "complex", "signal", "mogpl", "noyau", "pscr"),
		"libre" => array("mapsi", "algav", "mlbda") //"il",
	),
	"SAR" => array(
		"oblig" => array("noyau", "pscr"),
		"recom" => array("archi", "ares", "dlp", "algav"), //"il",
		"libre" => array("aagb", "mapsi", "mlbda", "mobj", "model","model_en", "bima", "mogpl", "complex", "rtel", "signal", "lrc", "vlsi", "sc")
	),
	"SESI" => array(
		"oblig" => array("archi", "vlsi"),
		"recom" => array("esa", "mobj", "signal", "ares", "rtel", "pscr", "noyau"),
		"libre" => array("aagb", "mapsi", "algav", "mlbda","model","model_en", "bima", "bima_en", "mogpl", "complex", "dlp", "lrc", "sc") //"il",
	),
	"STL" => array(
		"oblig" => array("algav", "dlp", "ouv_ang"),
		"recom" => array("lrc", "noyau", "pscr"), //"il",
		"libre" => array("mlbda", "archi_en", "ares", "model","model_en", "mogpl", "complex", "mapsi")
	)
    );
}
else if ($month >=11 && $month <= 12 || $month >=1 && $month <= 2 ) {
    $_SESSION['SEMESTRE'] = 2;

    $_SESSION['ALLUES'] = array('Anglais','Anglais ', 'Anglais/FLE','Conferences','Projet(3)','Projet(6)','Projet(9)_anum','anum','aps','ar','arob','bium','bmc','ca','cpa','comnum',
      'cge','cps','dalas','dj','ecfa','flag','fosyma','fpga','iamsi','ihm','ig3d','ioc','isec','ml','mll','multi', 'multi_en','paf','pc2r','pnl','qiintro','pqig',
      'rital','rp','sam','sas','sbas','sdm','srcs','sftr');

    $_SESSION['MASTER'] = array(
        'ANDROIDE' => array(													//  OK
            'oblig' => array('Conferences', 'Projet(3)', 'Anglais'),
            'recom' => array('arob', 'dj', 'ihm', 'rp', 'fosyma'),
            'libre' => array('ml', 'iamsi')
            ),
        'BIM' => array(                                                             // non fait
            'oblig' => array('Conferences', 'Projet(6)', 'Anglais'),
            'recom' => array(),
            'libre' => array()
            ),
        'CCA' => array(											//  non fait
            'oblig' => array('Conferences', 'Projet(6)', 'Anglais ', 'anum'),
            'recom' => array('flag', 'isec'),
            'libre' => array('ml', 'ar', 'fpga', 'multi_en') // 'ar' remplace 'pnl'
            ),
        'DAC' => array(                     // OK
            'oblig' => array('Conferences', 'Projet(3)', 'Anglais'),
            'recom' => array('dalas', 'sam', 'iamsi', 'ml', 'rital'),
            'libre' => array('dj','arob','rp')
            ),
        'IMA' => array(															// OK
            'oblig' => array('Conferences', 'Projet(6)', 'Anglais', 'ig3d'),
            'recom' => array('cge', 'mll', 'anum'),
            'libre' => array('dj', 'flag', 'iamsi', 'ml', 'rp', 'fosyma', 'ihm', 'rital') // attention ordre important
            ),
        'IQ' => array(															//  OK
            'oblig' => array('Anglais/FLE', 'qiintro', 'pqig'), //'Anglais'
            'recom' => array('Projet(6)', 'Projet(9)_anum'),
            'libre' => array('flag', 'ig3d', 'sdm')
            ),
        'RES' => array(															      // non fait
            'oblig' => array('Conferences', 'Projet', 'Anglais '),
            'recom' => array(),
            'libre' => array()
            ),
        'SAR' => array(													//non fait
            'oblig' => array('Conferences', 'Projet(3)', 'Anglais '),
            'recom' => array('ar', 'pnl', 'sftr', 'srcs', 'sas'),
            'libre' => array('ioc','multi','fpga', 'sam','aps','ca','ml','isec')
            ),
        'SESI' => array(												// OK
            'oblig' => array('Conferences', 'Anglais ', 'cge'),
            'recom' => array('ecfa', 'fpga', 'multi', 'ioc', 'Projet(6)', 'multi_en'),
            'libre' => array('comnum', 'pnl', 'ar')
            ),
        'STL' => array(												// OK
            'oblig' => array('Conferences', 'Projet(6)'),
            'recom' => array('aps', 'ca', 'cpa', 'cps', 'paf', 'pc2r'),
            'libre' => array()
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
