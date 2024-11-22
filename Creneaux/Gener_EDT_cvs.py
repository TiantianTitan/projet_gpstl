## basé sur la semaine du 26 septembre




edt  ={'sup1x': [['lu00'], ['lu02', 'lu04']], 'sup2x': [['ma00'], ['ma02', 'ma04']], 'sup3x': [['me00'], ['me02', 'me04']], 'sup4x': [['je00'], ['je02', 'je04']], 'sup5x': [['ve00'], ['ve02', 've04']],
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



def gener():

    global edt

    jour = ['lu', 'ma', 'me', 'je', 've']
    aff = {'08':[[],[],[],[],[]], '10':[[],[],[],[],[]], '13':[[],[],[],[],[]], '16':[[],[],[],[],[]], '18':[[],[],[],[],[]]}

    for ue in edt:
        horaires = edt[ue]

        for cours in horaires[0]:
            if cours[2:] in aff and cours[:2] in jour:
                aff[cours[2:]][jour.index(cours[:2])].append(ue.upper())

    for ue in edt:
        horaires = edt[ue]

        for i in range(1,len(horaires)):
            if len(horaires[i]) > 0:
                for td in horaires[i]:
                    if td[2:] in aff and td[:2] in jour:
                        aff[td[2:]][jour.index(td[:2])].append(ue + ' ' + str(i))

    return aff


def gener_fic():

    aff = gener()
    fic = open('/home/tan/Bureau/EDT_M.csv','w')

    heure = ['08', '10', '13', '16', '18']

    fic.write(' ; Lundi; Mardi; Mercredi; Jeudi; Vendredi; \n')

    for h in heure:
        lg = max([len(elt) for elt in aff[h]])

        for i in range(lg):
            ligne = ';'
            if i == 0:
                ligne = h + ligne

            for jo in range(5):
                if len(aff[h][jo])> i:
                    ligne += aff[h][jo][i]
                ligne += ';'
            ligne += '\n'
            fic.write(ligne)
        fic.write('\n')
    fic.close()
    return "fait"


gener_fic()
