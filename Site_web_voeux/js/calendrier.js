/**
 * Retourne une liste d'horaires des ues representant le calendrier du semestre
 * @returns {getCalendrier.listeUE}
 */

function getCalendrier() {
//Liste de chaque UE avec son horaire de cours, et les horaires de ses groupes de TD/TME
//Ajout de sup5x dans le cadre du passage a 6 ues

    var listeUE;
    if (SEMNUM==1)  //S1   2024 A JOUR LE 03.09 !!!!
        listeUE =  {'sup1x': [['lu00'], ['lu02', 'lu04']], 'sup2x': [['ma00'], ['ma02', 'ma04']], 'sup3x': [['me00'], ['me02', 'me04']], 'sup4x': [['je00'], ['je02', 'je04']], 'sup5x': [['ve00'], ['ve02', 've04']],
            'maths4m062': [['lu16'], ['me10', 've04']],  
            'aagb': [['lu10'], ['me16', 've16']],  
            'algav': [['ma10'], ['lu13', 'lu16'], ['je08', 'je10']], 
            'archi' :[['je13'], ['ma08','ma10'], ['ma13','ma16']], 
            'archi_en' :[['je10'], [], [], ['me13','me16']], 
            'ares': [['lu08'], [], [], ['me08', 'me10'], ['je13', 'je16']], 
            'bima': [['ma13'], ['je13', 'je16'], [], ['je13', 'je16']], 
            'bima_en': [['lu13'], [], ['ma08', 'ma10']], 
            'comnet_en': [['ve10'], ['ve13','ve16']], 
            'complex': [['je10'], ['lu08', 'lu10'], ['me08', 'me10'], ['je13', 'je16']], 
            'dlp': [['ma08'], ['je13', 'je16'], ['je13', 'je16']], 
            'esa' :[['lu10', 've16'], ['lu13','lu16']], 
            'lrc': [['me13'], ['lu13', 'lu16'], ['lu13', 'lu16'], ['je08', 'je10'], ['je08', 'je10']], 
            'mapsi': [['ma16'], ['lu08', 'lu10'], ['lu13', 'lu16'], ['ma08', 'ma10'],['ma08', 'ma13'], ['ma08', 'ma10']], 
            'mlbda': [['me16'], ['lu08', 'lu10'], ['me08', 'me10'], ['je08', 'je10']], 
            'mobj': [['lu08'], ['me13', 'me16']], 
            'model': [['ve08'], ['me13', 'me16']], 
            'model_en': [['ve08'], [], ['lu08', 'lu10']], 
            'mogpl':[['ve08'], ['lu13','lu16'], ['je13','je16'], ['ve10','ve13'], ['ve10','ve13']],  
            'noyau': [['lu16'], ['ma13', 'ma16'], ['ma13', 'ma16'], ['me13', 'me16']], 
            'ouv_ang' : [['ma13', 've13'], ['ma16','ve16'], ['ma16','ve16']], 
            'ppar_en': [['me08'], ['ve13', 've16'], ['ma08', 'ma10']], 
            'progres' : [['ve13'], ['ve16','ve17']], 
            'pscr': [['lu13'], ['me08', 'me10'], ['me13', 'me16'], ['ve10', 've13']], 
            'qclg_qk4cs_en': [['lu16', 'je13'], ['je08', 'je04']], 
            'qph4cs_en': [['ma13'], ['ma16', 'je16']], 
            'rtel': [['lu10'], [], ['ma13', 'ma16'], ['me08', 'me10']], 
            'sc': [['lu10'], ['ma13', 'ma16'], []], 
            'sigcom_en': [['lu10'], ['me08','me10']], 
            'signal': [['ve10'], ['ma08','ma10'], ['je08','je10']], 
            'vlsi': [['je16'], ['me08','me10']] 
            };
    else if (SEMNUM==2 ) //S2  2023
        listeUE = {
              'anglais': [['lu08'], ['lu10', 'lu11']],
              'anglais_': [['me13'], ['me16', 'me17']],
              'conferences' : [['ma13'], ['ma14', 'ma15']],
          //    'projet(3)' : [['lu14'], ['lu15', 'lu17']],
          //    'projet(6)' : [['lu14'], ['lu15', 'lu17']],
              'arob': [['ve16'], ['je13', 'je16'], ['je13', 'je16']],
              'dj': [['lu13'], ['ve08', 've10']],
              'fosyma': [['lu16'], ['ma08', 'ma10']],
              'ihm': [['ma16'], ['me08', 'me10']],
              'rp': [['ve13'], ['je08', 'je10']],
              'iamsi': [['me13'], ['ma08', 'ma10'], ['je08', 'je10']],
              'dalas': [['lu13'], ['je13', 'je16']],
              'ml': [['me16'], ['ma08', 'ma10'], ['je08', 'je10']],
              'mll': [['me16'], ['me08', 'me10']],
              'rital': [['lu16'], ['me08', 'me10']],
              'sam': [['ma16'], ['ve08', 've10']],
              'ig3d': [['ma16'], ['je13', 'je16']],
              'qiintro': [['me13'], ['ve13', 've16']],
              'sdm' : [['ma08'], ['ma10', 'ma13']],
              'comnum': [['ma10'], ['ve10', 've13']],
              'ar': [['ma10'], ['lu13', 'lu16']],
              'pnl': [['ma08'], ['je13', 'je16']],
              'sas': [['me18'], ['lu08', 'lu10']],
              'srcs': [['me10'], ['ve13', 've16']],
              'sftr': [['me08'], ['je08', 'je10']],
              'cge': [['me08'], ['me17', 'lu17']],
              'ecfa': [['me08'], ['lu08', 'lu10', 'je10']],
              'fpga': [['ma16'], ['lu08', 'lu10'], ['ma08', 'ma10'], ['ma08', 'ma10']],
              'ioc': [['je08'], ['ve08', 've10']],
              'multi': [['me10'], ['ve13', 've16']],
              'multi_en': [['je10'], ['ve13', 've16']],
              'anum': [['me08'], ['me10', 'me19']],
              'flag': [['ve08'], ['lu08', 'lu10']],
              'isec': [['je08'], ['je13', 'je16']],
              'aps': [['me16'], ['me08', 'me10']],
              'ca': [['ma08'], ['lu08', 'lu10']],
              'cpa': [['ve10'], ['ve13', 've16'], ['ve13', 've16']],
              'cps': [['ma16'], ['lu13', 'lu16'], ['lu13', 'lu16']],
              'paf': [['ma10'], ['je13', 'je16']],
              'pc2r': [['me13'], ['je08', 'je10'], ['je08', 'je10']],
            'sup1x': [['lu00'], ['lu02', 'lu04']], 'sup2x': [['ma00'], ['ma02', 'ma04']],
            'sup3x': [['me00'], ['me02', 'me04']], 'sup4x': [['je00'], ['je02', 'je04']], 'sup5x': [['ve00'], ['ve02', 've04']]
          	};
    return listeUE;
}
