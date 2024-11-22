function description(desc){

if (SEMNUM == 1){
var descs = {

"instructchoixue1":"<p><font size='4'>Pour former l'ensemble d'UE qui composeront votre contrat p&eacute;gogique, les <font color='red'>UE obligatoires</font> sont pr&eacute;-s&eacute;lectionn&eacute;es.</br>\n\
Il vous reste alors &agrave; compl&eacute;ter tous les voeux d'UE en choisissant, <b>par ordre de priorit&eacute;</b>, dans chaque liste, une UE disponible.</br>\n\
Vous ne suivrez que "+NBSUIVI+" UE : vos UE obligatoires et certaines des UE choisies, pour lesquelles nous essaierons de satisfaire au mieux vos pr&eacute;f&eacute;rences.</br>\n\
La <a class='rlink' target='_blank' href='http://master.informatique.sorbonne-universite.fr'>description compl&egrave;te du master est consultable ICI.</font></a></br>\n\
<font color='red'>Les UE dont l'acroyme se termine par _EN sont enseign&eacute;es en anglais.</font></br>\n\
<br><font size='4', color='blue'>In order to choose your lectures for the first semester you first note there are some Mandatory lectures (in red color).</br>\n\
Then you have to give wishes for the other lectures, by piorizing them according to your preferences.</font></br>\n\
<font color='red'>The lectures whose name ends with _EN are given in english (otherwise the lectures are taught in french).</font></br>\n\</p>",

"instructchoixue2":"<p><font size='4'>Tous les emplois du temps compatibles avec les UE que vous avez choisies sont affich&eacute;s ci-dessous.</br> \n\
Ils sont r&eacute;pertori&eacute;s en <b>2 classes</b> suivant le taux de remplissage actuel des groupes de TD/TME.</br> \n\
Les emplois du temps de <font color='green'> <b>classe 1 (contour VERT)</b></font> sont ceux, dont les groupes ont encore de la place.</br> \n\
Les emplois du temps de <font color='red'><b>classe 2 (contour ROUGE)</b></font> sont pleins.</br> \n\
<b><font color='red'>Attention:</b></font> En choisissant un emploi du temps <font color='red'>Rouge</font> ,\n\
 il est fortement probable que vos voeux soient modifi&eacute;s &agrave; la rentr&eacute;e.\n\
 <br/> Nous vous rappelons qu'il s'agit de voeux d'UE et d'emploi du temps, et quels que soient vos choix, il est possible qu'ils soient modifi&eacute;s\n\
 lors de la pr&eacute;-rentr&eacute;e, pour des raisons p&eacute;dagogiques ou des contraintes de remplissage de groupes.</font> <br><br><br><h3><b>Choix de l'emploi du temps</b></h3> <h4>S&eacute;lectionnez votre voeu d'emploi du temps puis valider (coin sup&eacute;rieur gauche de l'emploi du temps choisi)</h4></p>"
};

printHTML("#description_master",descs[desc]);
}

else {
var descs = {

"instructchoixue1":"<p><font size='4'>Dans la liste ci-dessous, les <font color='red'>UE obligatoires</font> sont pr&eacute;-s&eacute;lectionn&eacute;es.</br>\n\
Il vous reste alors &agrave; compl&eacute;ter le choix d'UE afin de g&eacute;n&eacute;rer les emplois du temps disponibles.</font></br>\n\
<font size='4'>Chaque parcours indique certaines <font color='blue'>UE recommand&eacute;es</font>.\n\
La <a class='rlink' target='_blank' href='http://www-master.ufr-info-p6.jussieu.fr/lmd'>description compl&egrave;te du master est consultable ICI.</font></a></br>\n\
<font size = '4'><b>Un clic sur un nom d'UE ouvre la page de sa description dans un nouvel onglet.</b></font><br><br><br><h3><b>Choix des unit&eacute;s d'enseignement</b></h3><h4>S&eacute;lectionnez les UE que vous souhaitez suivre, les emplois du temps seront g&eacute;n&eacute;r&eacute;s en dessous : </h4></p>",

"instructchoixue2":"<p><font size='4'>Tous les emplois du temps compatibles avec les UE que vous avez choisies sont affich&eacute;s ci-dessous.</br> \n\
Ils sont r&eacute;pertori&eacute;s en <b>2 classes</b> suivant le taux de remplissage actuel des groupes de TD/TME.</br> \n\
Les emplois du temps de <b>classe 1</b> sont ceux, dont les groupes ont encore de la place.</br> \n\
Les emplois du temps de <b>classe 2</b> sont pleins.</br> \n\
Plus l'emploi du temps apparait <font color='red'> <b>en haut de la liste</b></font>, plus il y a de la place dans les groupes de TD/TME.</br> \n\
N&eacute;anmoins Vous serez prioritaires dans les UE de votre parcours, mais ne serez pas prioritaires pour les UE des autres parcours, m&ecirc;me si vous avez choisi un emploi du temps de <b>classe 1</b>.Les demandes seront trait&eacute;es par ordre d'inscription. </br> \n\
RAPPEL : ce sont des voeux, et il est possible que, lors de l'entretien avec l'&eacute;quipe p&eacute;dagogique, ces voeux soient modifi&eacute;s.\n\
</font> <br><br><br><h3><b>Choix de l'emploi du temps</b></h3> <h4>S&eacute;lectionnez votre voeu d'emploi du temps puis valider (coin sup&eacute;rieur gauche de l'emploi du temps choisi)</h4></p>"
};


printHTML("#description_master",descs[desc]);
}

}
