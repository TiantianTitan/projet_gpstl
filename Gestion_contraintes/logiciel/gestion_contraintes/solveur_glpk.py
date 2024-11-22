#!/usr/bin/env python3
# -*- coding: utf-8 -*-
#
# Ce fichier a pour fonction de construire le modele de resolution avec pyGLPK
# Il doit par la suite pouvoir resoudre ce modele
#
# Pour l'ajout de nouvelle contrainte, definir une nouvelle fonction de maniere
# similaire qui doit renvoyer à la fin le modele modifié
#
# L'ajout de nouvelle variable dans le modèle demande aussi de garder en mémoire
# l'index de chaque variable crée
#
# On construit donc au cours de cette résolution un dictionnaire permettant de
# retrouver rapidement quel variable correspond quel index, ainsi que toutes
# les données associés à ces variables
#
# ATTENTION : les numero de groupes/cours sont listés à partir de 0, lors de l'
# affichage, il ne faut pas oublier de faire + 1
#
# Toutes les methodes qui créent, modifient, résouent le modele se trouvent dans la
# section MODELE GLPK
# En dehors de ces section, la variable appelé 'modele' est un tuple comprenant
# le modele glpk, son nombre de variables et son nombre de contraintes
#
# La section MANIPULATION DU DICO_XY permet de comprendre la structure nommé dico_xy
# plus facilement, de sorte à ce que si on modifie les informations donnés, on peut
# le faire plus facilement. Il suffira juste de verifier que les methodes dans cette
# section sont adaptés en conséquences
#
# Pour le DEBUG, privilegier une commande au lancement qui permet d'ecrire dans un
# fichier. Par exemple, la commande : python3 bouchon.py > log_bouchon.txt
# On peut controler ce qui sera affiché avec les variables DEBUG_[...]
#
#---------------------------------------------------------
#                         IMPORT
#---------------------------------------------------------


from parametres import Parametres
from erreurs import *
from outils import *
from etudiant import Etudiant
from ue import UE
from traitement import *
import glpk

#---------------------------------------------------------
#                         VARIABLES
#---------------------------------------------------------


separateur_var = "_"		# separateur pour le nommage des variable

# __debug__
# variable à activer pour un affichage de ces donnés. Si elles ne s'affiche pas
# c'est que une exception s'est déclenchée avant
DEBUG_CONSTRUCTION  = False	 # creation des nouvelles variables
DEBUG_VISUALISATION = False	 # listes de variables triés pour la construction des contraintes
DEBUG_MODELE        = False	 # ajout des lignes de contraintes dans le modele
DEBUG_MODELE_RES    = True	 # resultats des variables affichées contrainte par contrainte apres resolution
DEBUG_COHERENCE     = False	 # regarde la cohérence entre dico_xy et modele
DEBUG_ETUDIANT_RES  = False	 # comparaison entre etudiant et la liste de ses variables après resolution
DEBUG_DICO_RES      = False	 # regarde les entrées dans le dictionnaire
DEBUG_ECRITURE      = True	 # suit ce qui sera ecrit dans la chaine de caractere pour les resultats

def __deactivate_debugger__() :
	DEBUG_CONSTRUCTION  = False
	DEBUG_VISUALISATION = False
	DEBUG_MODELE_RES    = False
	DEBUG_MODELE        = False
	DEBUG_COHERENCE     = False
	DEBUG_ETUDIANT_RES  = False
	DEBUG_DICO_RES      = False
	DEBUG_ECRITURE      = False

#---------------------------------------------------------
#                         MAIN
#---------------------------------------------------------


def construction_modele(dico_ue,liste_etudiants,relache_affectation=False,relache_capacite=False) :
	"""
	Construction du modele
	Parametres :
		dico_ue             : hash map des ue
		liste_etudiant      : [etudiant] liste des etudiants
		relache_affectation : Bool indique si la contrainte affectations est relachée
		relache_capacite    : Bool indique si la contrainte capacités est relachée
	Sortie :
		modele   : modele glpk construit
		dico_xy  : hash map des variables permettant d'acceder à ses données
			{ String xijk, yij : Tuple(Int index,Tuple(etudiant,ue,String nom_ue,Int ngroupe)) }
		dico_etu : hash map des etudiants
		        { etudiant : { String intitulé : [(String nom_var, String type_var, Int num_groupe)] } }
	On considere que si la contrainte est relachée alors la valeur du booleen est True
	"""

	# creation du modele avec leur variables et les hash map utiles
	modele,dico_xy, dico_etu, yij_oblig,yij_all,xijk_cours,xijk_groupe,xijk_incomp,xijk_cap = \
		initialisation_variables_modele(dico_ue,liste_etudiants)

	# ajout des contraintes liés aux ue obligatoire
	modele = contrainte_ue_obligatoire(modele,dico_xy,yij_oblig)

	# ajout des contraintes liés aux groupes unique par ue pour chaque etudiant
	modele = contrainte_groupe_unique(modele,dico_xy,xijk_groupe)

	# ajout des contraintes lié au fait que etre inscrit dans une ue signifie qu'on
	# fait parti d'un groupe de cette ue
	modele = contrainte_association_ue_groupe(modele,dico_xy,yij_all,xijk_groupe)

	# ajout des contraintes lié au fait que l'inscription dans une ue fait qu'on suit
	# tous les cours
	modele = contrainte_association_ue_cours(modele,dico_xy,xijk_cours,yij_all)

	# ajout des contraintes sur les capacités d'accueil d'étudiants pour chaque groupe
	modele = contrainte_capacite(modele,dico_xy,xijk_cap,relache_capacite)

	# ajout des contraintes sur le nombre d'ue que doit suivre chaque etudiant
	modele = contrainte_nb_ue(modele,dico_xy,yij_all,relache_affectation)

	# ajout des contraintes sur les creneaux incompatibles
	modele = contrainte_horaires(modele,dico_xy,xijk_incomp)

	# ajout de la fonction objectif
	modele = fonction_objective(modele,dico_xy,yij_all)

	return (modele, dico_xy, dico_etu)


def resoudre(dico_ue, liste_etudiants, fichier = None, contrainte_relachee = False, DEBUG = False):
	"""
	Fonction principale du solveur creant le  modele, resolvant le probleme et inscrivant les
	resultats dans un fichier passé en argument.
	Si fichier vaut None, la fonction retourne une chaine de caractères contenant les résultats
	et n'écrit pas dans un fichier
	Parametres :
		dico_ue	    : hash map des ue
		liste_etudiant      : [etudiant] liste des etudiants
		fichier             : nom du fichier contenant le resultat
		contrainte_relachee : Bool indique si la contrainte affectations est relachée
	Sortie :
		chaine (si fichier == None)   : String resultat sous forme de chaine de caracteres
		nb_etu_affectation_incomplete : Int pour le nombre d'étudiant d'affectations incomplete
	Debugger :
		DEBUG : False pour que toutes les variables du debugger soient mise à False
	"""

	# deactive toutes les variables pour le debugger
	if not DEBUG :
		__deactivate_debugger__()

	# __debug__
	if DEBUG_ETUDIANT_RES :
		__i_etu__ = 0


	# construction du modele
	modele, dico_xy, dico_etu = construction_modele(dico_ue,liste_etudiants,contrainte_relachee)

	# resolution du modele
	modele = resoudre_modele(modele)

	# __debug__
	if DEBUG_MODELE_RES :
		print(__affichage_resultats_contraintes__(modele))
	if DEBUG_DICO_RES :
		print(__affichage_dico__(dico_xy))
		print(__affichage_dico_etu__(dico_etu))


	# trie les resultats dans un dictionnaire ne contenant que les ue obtenu par
	# l etudiant en se basant sur le dico etudiant, le dico des variables et le modele
	dico_resultat = trier_dico_etu(dico_etu,dico_xy,modele)

	# __debug__
	if DEBUG_DICO_RES :
		print(__affichage_dico_resultat__(dico_resultat))


	# transforme le dico resultat en chaine de caractere
	nb_etu_affectation_incomplete = 0
	satisfaction_totale = 0
	chaine = ""
	for etu,liste_ue in dico_resultat.items() :

		# initialise la ligne pour l'ecriture des resultats de l'etudiant
		debut_ligne = "{};{};".format(etu.parcours, etu.numero)
		ligne = ""
		cpt = 0
		ue_desirees = 0
		liste_ue_obtenues = []


		for ue_resultat in liste_ue :

			if len(liste_ue_obtenues) == etu.nb_ue_a_suivre:
				break

			# on recupere l'intitule, le numero de cours et groupe
			# (ATTENTION cours et groupe_num commence à 0)

			intitule_ue,cours_num,groupe_num = ue_resultat
			ue = dico_ue[intitule_ue]


			# __debug__
			if DEBUG_ECRITURE :
				print(__affichage_affectation_par_ligne__(etu,ue,intitule_ue,cours_num,groupe_num))

			# update du remplissage des groupes
			ue.nb_inscrits[groupe_num] += 1


			# ajout des resultat à la ligne
			ligne += ";{}{}".format(intitule_ue, groupe_num+1)   #### +1 par tan
			liste_ue_obtenues.append(intitule_ue)


		# regarde si les ue obtenus de chaque etudiant correspondent aux voeux
		voeux = ""
		voeux_obtenus = 1       # utile pour afficher les voeux obtenus
		for n_ue in etu.listes_ue():
			if n_ue in liste_ue_obtenues:
				ue_desirees += 1
				voeux += str(voeux_obtenus) + " , "
			voeux_obtenus += 1

		# __debug__
		if DEBUG_ECRITURE :
			print(__affichage_apres_affectation__(etu,liste_ue_obtenues,ue,voeux))

		# regarde si le nombre d'ue obtenus correspond au nombre d'ue devant etre suivit
		# par l'etudiant
		if len(liste_ue_obtenues) != etu.nb_ue_a_suivre:
			if etu.edt_incompatible:
				ligne += Parametres.texte[53][Parametres.langue]
			else:
				ligne += Parametres.texte[52][Parametres.langue]
				nb_etu_affectation_incomplete += 1

		# calcul de la satisfaction de l'etudiant
		satisfaction = ue_desirees / etu.nb_ue_a_suivre * 100
		voeux = voeux[:-3]  # on enleve le dernier - (avec espaces) de la chaine
		satisfaction_totale += satisfaction

		# ecrit la ligne dans la chaine de caracteres ou dans le fichier
		if (fichier != None):
	   			fichier.write(debut_ligne + "{}".format(voeux) + ligne+"\n")
		else:
	   			chaine += debut_ligne + "{}".format(voeux) + ligne+"\n"

    	#satisfaction total : somme de la satisfaction des etudiants / nb etudiants
	satisfaction_totale /= len(dico_etu)

	# renvoi le resultat du solveur dans une chaine de caractere ou un fichier
	if (fichier == None):
		chaine += "Total;;{}".format(satisfaction_totale)
		return chaine, nb_etu_affectation_incomplete
	fichier.write("Total;;{}".format(satisfaction_totale))
	return nb_etu_affectation_incomplete




#---------------------------------------------------------
#                     VARIABLES
#---------------------------------------------------------


def initialisation_variables_modele(dico_ue, liste_etudiants, nom_modele = None) :
	"""
	Creation des hash map pour les variables et du modele. Les variables sont séparés en deux
	catégories : yij pour indiqué si l'etudiant est inscrit dans l'UE et xijk s'il est inscrit
	dans le cours/groupe de l'UE

	Parametres :
		dico_ue        : hash map des ue
		liste_etudiant : [etudiant] liste des etudiants
		nom_modele     : String nom du modele (optionnel)

	Sortie :
		modele : modele GLPK où toutes les variables crées seront ajoutés à ce modele
			nouvellement créé.
		dico_xy : hash map pour xijk ou yij qui ont tous un numéro unique (>= 0).
			{ String xijk, yij : Tuple(Int index,Tuple(...)) }
		dico_etu : hash map par etudiant puis par initulé d'ue pour lister ses nom de variables
			{ etudiant : { String intitulé : [(String nom_var, String type_var, Int num_groupe)] } }
		dico_yij_obligatoire : hash map par etudiant des yij pour leur ue obligatoires
			{ etudiant : [String yij] }
		dico_yij_all : hash map par etudiant des yij pour toutes leurs ue
			{ etudiant : [String yij] }
		dico_xijk_cours : hash map par etudiant des xijk pour les cours de chaque ue
			{ etudiant : { ue : [String xijk] } }
		dico_xijk_td_tme : hash map par etudiant des xijk pour les td/tme de chaque ue
			{ etudiant : { ue : [String xijk] } }
		dico_xijk_incompatibles : hash map par etudiant des xijk pour les horaires incompatibles
			{ etudiant : { int horaire : [String xijk] } }
		dico_xijk_capacite : hash map par ue des xijk pour chaque groupe de cette ue
			{ ue : { int num_groupe : [String xijk] }

	Post condition :
		chaque index associé aux variables xijk et yij doit être unique à cette variable.
		dans le modele, les variables ajoutées sont toutes binaires
		dico_xy permet de lister toutes les informations associés: Son index et un tuple (etudiant,
		  ue,nom_ue,num_groupe/num_cours/None). le dernier element respectivement correspond aux
		  variables xijk_groupe/xijk_cours/yij

	"""

	modele = initialisation_modele(nom_modele)

	# identifiant unique pour chaque variable
	cpt_id = 0


	# __debug__
	if DEBUG_CONSTRUCTION :
		__i_etu__      = -1
		__j_ue__       = 0
		__k_ue__       = 0
		__k_ue_cours__ = 0
		__k_ue_td__    = 0
		__k_ue_tme__   = 0



	#CREATION DES HASH MAP

	# hash map listant toutes les variables et leur informations associés
	dico_xy = dict()
	# hash map listant toutes les variables associés à un etudiant
	dico_etu = dict()

	# hashmap par etudiant pour les ue obligatoires
	dico_yij_obligatoire = dict()
	# hashmap par etudiant tous les ue
	dico_yij_all = dict()

	# hashmap par etudiant pour les cours de chaque ue
	dico_xijk_cours = dict()
	# hashmap par etudiant pour les td+tme de chaque ue
	dico_xijk_td_tme = dict()
	# hashmap par etudiant pour les cours ayant des creneaux incompatibles
	dico_xijk_incompatibles = dict()

	# hashmap pour chaque groupe des capacite de ceux-ci
	dico_xijk_capacite = dict()


	for etu in liste_etudiants :

		# INITIALISATION POUR CHAQUE ETUDIANT

		# __debug__
		if DEBUG_CONSTRUCTION :
			__i_etu__     += 1
			__j_ue__       = 0
			__k_ue__       = 0
			__k_ue_cours__ = 0
			__k_ue_td__    = 0
			print("LISTING etudiant n°" + str(__i_etu__) + " : " + str(etu.numero))

		# construction du dictionnaire des variables
		dico_etu[etu] = dict()

		# contruction des yij
		dico_yij_obligatoire[etu] = []
		dico_yij_all[etu] = []

		#construction des xijk
		dico_xijk_td_tme[etu] = dict()
		dico_xijk_cours[etu] = dict()
		dico_xijk_incompatibles[etu] = dict()

		# VARIABLES Yij

		# creation d une variable yij pour les ue de l'etudiant et association à un identifiant
		# unique, les ue obligatoires sont aussi placé dans une liste suplémentaires

		for nom in etu.listes_ue() :

			# initialisation de la liste etudiante
			dico_etu[etu][nom] = []

			ue = dico_ue[nom]
			modele,dico_xy,nom_var,cpt_id, succes = \
				nouvelle_variable(modele,dico_xy,etu,ue,nom)

			if not succes :
				# __debug__
				if DEBUG_CONSTRUCTION :
					print("\t\tDOUBLON")
				# passage à la prochaine variable si aucune n'a été créée
				continue

			# enregistrement de la variable en tête de liste par l'etudiant
			# avec (-1 pour signifier qu'il s'agit d'un y)
			dico_etu[etu][nom].append((nom_var,"ue",-1))

			# ajout de la variable dans les listes concernées
			dico_yij_all[etu].append(nom_var)
			if nom in etu.ue_obligatoires :
				dico_yij_obligatoire[etu].append(nom_var)

			# __debug__
			if DEBUG_CONSTRUCTION :
				__j_ue__ += 1
				print("\tADD y ue n°"+ str(__j_ue__) +
					" : (nom) "+ str(nom) +
					" ; (nom_var) "+str(nom_var) +
					" | cpt_id = " + str(cpt_id))


		# VARIABLES Xijk


		# pour chaque ue se trouvant dans la liste de l etudiant
		for nom_ue in etu.listes_ue():
			ue = dico_ue[nom_ue]

			# INITIALISATION POUR CHAQUE UE

			# __debug__
			if DEBUG_CONSTRUCTION :
				__k_ue__       += 1
				__k_ue_cours__  = 0
				__k_ue_td__     = 0
				print("\tLISTING ue "+nom_ue)

			# construction des xijk de l etudiant pour chaque ue si pas deja fait
			if ue not in dico_xijk_td_tme[etu]:
				dico_xijk_td_tme[etu][ue] = []
			if ue not in dico_xijk_cours[etu]:
				dico_xijk_cours[etu][ue] = []

			# si l ue ne se trouve pas dans la hashmap des capacite alors on ajoute
			# une nouvelle hashmap pour cette ue
			if ue not in dico_xijk_capacite :
				dico_xijk_capacite[ue] = dict()


			# COURS

			# parcours les cours de l etudiant pour ajouter des nouvelless variables xijk
			cpt_cours = -1
			for creneau_cours in ue.creneaux_cours:

				# incrementation du compteur
				cpt_cours += 1

				# creation d une variable xijk pour les cours de chaque ue de l etudiant
				modele,dico_xy,nom_cours,cpt_id,succes = \
					nouvelle_variable(modele,dico_xy,etu,ue,nom_ue,num_cours=cpt_cours)

				if not succes :
					# __debug__
					if DEBUG_CONSTRUCTION :
						print("\t\tDOUBLON")
					# passage à la prochaine variable si aucune n'a été créée
					continue

				# enregistrement de la variable par l'étudiant
				dico_etu[etu][nom_ue].append((nom_cours,"cours",cpt_cours))

				# ajout de la variable dans la liste concernée
				dico_xijk_cours[etu][ue].append(nom_cours)

				# ajout du creneau parmi les creneau incompatibles (avec creation d une nouvelle
				# entree dans la liste si besoin)
				if creneau_cours > 0 :
					if creneau_cours not in dico_xijk_incompatibles[etu]:
						dico_xijk_incompatibles[etu][creneau_cours] = []
					dico_xijk_incompatibles[etu][creneau_cours].append(nom_cours)


				# __debug__
				if DEBUG_CONSTRUCTION :
					__k_ue_cours__ += 1
					print("\t\tADD x cours n°"+ str(__k_ue_cours__)+
						" : (nom_cours) "+ str(nom_cours)+
						" | creneau_cours = "+str(creneau_cours)+
						" | cpt_id = " + str(cpt_id))



			# TD et TME

			# parcours les td et tme de l etudiant pour ajouter des nouvelless variables xijk
			cpt_td_tme = -1
			for nbg in range(ue.nb_groupes):

				# incrementation du compteur
				cpt_td_tme +=1

				# creation d une nouvelle entree pour le groupe de l ue dans la hash map des
				# capacite si celle-ci n existe pas
				if nbg not in dico_xijk_capacite[ue] :
					dico_xijk_capacite[ue][nbg] = []

				# creation de la variable xijk pour le groupe de chaque ue de l etudiant
				modele,dico_xy,nom_groupe,cpt_id, succes = \
					nouvelle_variable(modele,dico_xy,etu,ue,nom_ue,num_groupe=nbg)

				if not succes :
					# __debug__
					if DEBUG_CONSTRUCTION :
						print("\t\tDOUBLON")
					# passage à la prochaine variable si aucune n'a été créée
					continue

				# enregistrement de la variable par l'étudiant
				dico_etu[etu][nom_ue].append((nom_groupe,"groupe",nbg))

				# ajout de la variable dans les liste associées
				dico_xijk_td_tme[etu][ue].append(nom_groupe)
				dico_xijk_capacite[ue][nbg].append(nom_groupe)

				# __debug__
				if DEBUG_CONSTRUCTION :
					__k_ue_td__ += 1
					print("\t\tADD x groupe n°"+ str(__k_ue_td__) +
						" : (nom_groupe) "+ str(nom_groupe) +
						" | cpt_id = " + str(cpt_id))

				# si le creneau existe pour le groupe de td, le rajoute parmi les creneaux
				# incompatibles et creation du creneau si non present parmi ces derniers
				if (len(ue.creneaux_td) > cpt_td_tme) :
					creneau_td = ue.creneaux_td[cpt_td_tme]
					if creneau_td > 0 :
						if creneau_td not in dico_xijk_incompatibles[etu]:
							dico_xijk_incompatibles[etu][creneau_td] = []
						dico_xijk_incompatibles[etu][creneau_td].append(nom_groupe)

				# si le creneau existe pour le groupe de tme, le rajoute parmi les creneaux
				# incompatibles et creation du creneau si non present parmi ces derniers
				if (len(ue.creneaux_tme) > cpt_td_tme) :
					creneau_tme = ue.creneaux_tme[cpt_td_tme]
					if creneau_tme > 0 :
						if creneau_tme not in dico_xijk_incompatibles[etu]:
							dico_xijk_incompatibles[etu][creneau_tme] = []
						dico_xijk_incompatibles[etu][creneau_tme].append(nom_groupe)


	# __debug__
	if DEBUG_COHERENCE :
		verif_liste_dm = __coherence_dico_modele__(modele,dico_xy)
		if len(verif_liste_dm) > 0 :
			print("CHECK LISTE ERROR variable_dico_toutes_dans_modele :")
			for elem in verif_liste_dm :
				print("\t"+str(elem))
		else  :
			print("CHECK LISTE OK variable_dico_toutes_dans_modele")

		verif_liste_md = __coherence_modele_dico__(modele,dico_xy)
		if len(verif_liste_dm) > 0 :
			print("CHECK LISTE variable_modele_toutes_dans_dico :")
			for elem in verif_liste_md :
				print("\t"+str(elem))
		else  :
			print("CHECK LISTE OK variable_modele_toutes_dans_dico")

	# __debug__
	if DEBUG_VISUALISATION :
		print(__affichage_yij__(dico_yij_obligatoire,"yij_obligatoire par etudiant"))
		print(__affichage_yij__(dico_yij_all,"yij_all par etudiant"))
		print(__affichage_xijk__(dico_xijk_cours,"xijk_cours par etudiant et par ue"))
		print(__affichage_xijk__(dico_xijk_td_tme,"xijk_td_tme par etudiant et par ue"))
		print(__affichage_xijk_horaire__(dico_xijk_incompatibles,"xijk_horaire_incompatible par etudiant"))
		print(__affichage_xijk_capacite__(dico_xijk_capacite,"xijk_capacite par ue selon capacite"))


	return (modele,
		dico_xy,
		dico_etu,
		dico_yij_obligatoire,
		dico_yij_all,
		dico_xijk_cours,
		dico_xijk_td_tme,
		dico_xijk_incompatibles,
		dico_xijk_capacite)


def nouvelle_variable(modele,dico_xy,etudiant,ue,nom_ue,num_groupe=None,num_cours=None) :
	"""
	ajoute une nouvelle variable au modele avec enregistrement dans le dico
	Parametres :
		modele       : modele GLPK
		dico_xy      : le dictionnaire des variables
		etudiant     : eudiant associé à la variable
		ue           : ue associé à la variable
		nom_ue       : String nom de l'ue
		num_groupe   : Int numero du groupe de la variable
		num_cours    : Int numero du cours de la variable
	Sortie :
		modele       : modele GLPK avec l'ajout
		dico_xy      : dictionnaire des variables avec l'ajout
		nom_variable : String nom de la nouvelle variable
		index        : index de la nouvelle variable dans le modele
		succes       : Bool indique si une nouvelle entrée a été créé
	Post condition :
		si succes == True, une nouvelle entrée a été créée dans dico et ajouté dans le modele
		 et donc on renvoi index et nom_variable non vide
		si succes == False, alors le nom crée à partir du numero etudiant, intitulé de l'ue et
		 possiblement le num de groupe/cours est deja present dans le dico, donc le dico_xy et
		 le modèle restent inchangés, index et nom_variable renvoient None
	"""

	# recupere l'identifiant de la nouvelle variable
	index = indice_derniere_variable(modele) + 1

	# nomme la nouvelle variable
	if (num_groupe != None) :
		nom_variable = nomme_variable_td_tme(etudiant,nom_ue,num_groupe)
	elif (num_groupe == None and num_cours != None) :
		nom_variable = nomme_variable_cours(etudiant,nom_ue,num_cours)
	else :
		nom_variable = nomme_variable_ue(etudiant,nom_ue)

	# verifie que la variable n'existe deja pas, si c'est le cas, on ne crée rien
	if nom_variable in dico_xy :
		return (modele,dico_xy,None,None,False)

	# ajout dans le modele
	modele = ajoute_variable_modele(modele,nom_variable)


	# nouvelle entrée dans le dico
	dico_xy = nouvelle_entree_dico_xy(dico_xy,index,nom_variable,etudiant,ue,nom_ue,num_groupe,num_cours)


	return (modele,dico_xy,nom_variable,index,True)

#---------------------------------------------------------
#                     CONTRAINTES
#---------------------------------------------------------


def contrainte_ue_obligatoire(modele,dico_xy,yij) :
	"""
	Ajoute au modele les contraintes associés aux ue obligatoire
	Parametres :
		modele  : modele glpk avant l'ajout des contraintes
		dico_xy : hash map des variables pour recuperer leur identifiant
		yij     : hash map des yij obligatoires des etudiants
	Sortie :
		modele  : nouveau modele glpk updated
	Notons qu'on a ici simplifié les contraintes yij = 1 en les additionnant
	pour n'avoir qu'une seule contrainte (somme yij) = len(nombre yij)
	"""
	liste_a_ajouter = []
	for etu, liste in yij.items() :
		if len(liste) < 1 :
			continue
		for var in liste :
			liste_a_ajouter.append(var)

	taille = len(liste_a_ajouter)
	contrainte = ("contrainte ue obligatoire "+
			" | (+) len(nombre yij) = " + str(len(liste_a_ajouter)) +
			" | (-) X" +
			" | min "+str(taille)+" ; max "+str(taille))
	modele = ajoute_contrainte_modele(modele,dico_xy,liste_a_ajouter,[],taille,taille,nom=contrainte)
	return modele


def contrainte_groupe_unique(modele,dico_xy,xijk) :
	"""
	Ajoute au modele les contraintes associés à un groupe unique par ue
	Parametres :
		modele  : modele glpk avant l'ajout des contraintes
		dico_xy : hash map des variables pour recuperer leur identifiant
		xijk    : hash map pour les xijk des groupes(/cours) des etudiant
	Sortie :
		modele  : nouveau modele glpk après ajout
	"""
	for etu, ue_xijk in xijk.items() :
		for ue, groupes in ue_xijk.items() :
			if len(groupes) < 1 :
				continue
			contrainte = ("contrainte groupe unique" +
				" | Etudiant " + str(etu.numero) +
				" | ue = " + str(ue.intitule) +
				" | (+) len(groupes) = " + str(len(groupes)) +
				" | (-) X" +
				" | min 0 ; max 1")
			modele = ajoute_contrainte_modele(modele,dico_xy,groupes,[],0,1,nom=contrainte)
	return modele


def contrainte_association_ue_groupe(modele,dico_xy,yij,xijk_groupe) :
	"""
	Ajoute au modele les contraintes associés à ue inscrit = dans un seul groupe de l'ue
	Parametres :
		modele      : modele glpk avant l'ajout des contraintes
		dico_xy     : hash map des variables pour recuperer leur identifiant
		yij         : hash map pour les yij des ues suivies par les etudiant
		xijk_groupe : hash map pour les xijk des groupes des etudiant
	Sortie :
		modele      : nouveau modele glpk après ajout
	"""
	for etu, y_liste in yij.items() :
		for y in y_liste :
			ue,intitule = recupere_ue(dico_xy,y)
			if (ue.nb_groupes >= 1) :
				groupes = xijk_groupe[etu][ue]
				inscrit = [y]
				contrainte = ("contrainte association ue groupes" +
					" | Etudiant " + str(etu.numero) +
					" | ue = " + str(ue.intitule) +
					" | (+) len(groupe) = " + str(len(groupes)) +
					" | (-) len(ue) = " + str(len(inscrit)) +
					" | min 0 ; max 0")
				modele = ajoute_contrainte_modele(modele,dico_xy,groupes,inscrit, 0,0,nom=contrainte)
	return modele


def contrainte_association_ue_cours(modele,dico_xy,xijk_cours,yij) :
	"""
	Ajoute au modele les contraintes associés à cours suivis = incrit à l'ue
	Parametres :
		modele     : modele glpk avant l'ajout des contraintes
		dico_xy    : hash map des variables pour recuperer leur identifiant
		xijk_cours : hash map pour les xijk des cours suivi par les etudiant
		yij        : hash map des inscriptions yij des etudiants aux ue
	Sortie :
		modele     : nouveau modele glpk après ajout
	"""
	for etu, yij_liste in yij.items() :
		if len(yij_liste) < 1 :
			continue
		for var_y in yij_liste :
			id_var,ue = recupere_index_et_ue(dico_xy,var_y)
			cours = xijk_cours[etu][ue]
			for var_x in cours :
				contrainte = ("contrainte association ue cours" +
						" | Etudiant " + str(etu.numero) +
						" | ue = " + str(ue.intitule) +
						" | (+) x_cours = " + str(var_x)  +
						" | (-) y = " + str(var_y)+
						" | min 0 ; max 0")
				modele = ajoute_contrainte_modele(modele,dico_xy,[var_x],[var_y],0,0,nom=contrainte)
	return modele


def contrainte_capacite(modele,dico_xy,xijk_cap, relache) :
	"""
	Ajoute au modele les contraintes associés à la capacite d'accueil d'etudiants dans
	un groupe
	Parametres :
		modele   : modele glpk avant l'ajout des contraintes
		dico_xy  : hash map des variables pour recuperer leur identifiant
		xijk_cap : hash map par groupe de chaque ue pour les inscrits dans celui-ci
		relache  : Bool considere si la contrainte est relaché ou non
	Sortie :
		modele   : nouveau modele glpk après ajout
	"""
	# si relaché alors on ignore les capacités des groupes
	if relache :
		return modele

	for ue, xjk_cap in xijk_cap.items() :
		for num_grp, xk_grp in xjk_cap.items() :
			if len(xk_grp) < 1 :
				continue
			capacite = ue.capacites_groupes[num_grp]
			contrainte = ("contrainte capacite non relache" +
				" | ue = "+ str(ue.intitule) +
				" | num_grp = "+str(num_grp) +
				" | (+) len(xk_groupe) = "+str(len(xk_grp))+
				" | (-) X"+
				" | min 0 ; max "+str(capacite))
			modele = ajoute_contrainte_modele(modele,dico_xy,xk_grp,[],0,capacite,nom=contrainte)
	return modele


def contrainte_nb_ue(modele,dico_xy,yij,relache) :
	"""
	Ajoute au modele les contraintes associés au nombre de ue que l'etudiant peut (si les
	contraintes sont relachées) ou doit suivre
	Parametres :
		modele  : modele glpk avant l'ajout des contraintes
		dico_xy : hash map des variables pour recuperer leur identifiant
		yij     : hash map des toutes les inscriptions yij des etudiants
		relache : Bool considere si la contrainte est relaché ou non
	Sortie :
		modele  : nouveau modele glpk après ajout
	"""
	if relache :
		for etu,yj_liste in yij.items() :
			if len(yj_liste) < 1 :
				continue
			contrainte = ("contrainte nombre ue relache " +
				" | Etudiant " + str(etu.numero) +
				" | (+) len(yj_liste) = " + str(len(yj_liste)) +
				" | (-) X" +
				" | min 0 ; max "+str(etu.nb_ue_a_suivre))
			modele = ajoute_contrainte_modele(modele,dico_xy,yj_liste,[],
								0,etu.nb_ue_a_suivre,nom=contrainte)

	else :
		for etu,yj_liste in yij.items() :
			if len(yj_liste) < 1 :
				continue
			nb = etu.nb_ue_a_suivre
			contrainte = ("contrainte nombre ue non relache " +
				" | Etudiant " + str(etu.numero) +
				" | (+) len(yj_liste) = " + str(len(yj_liste)) +
				" | (-) X" +
				" | min "+str(nb)+" ; max "+str(nb))
			modele = ajoute_contrainte_modele(modele,dico_xy,yj_liste,[],
								etu.nb_ue_a_suivre,etu.nb_ue_a_suivre,nom=contrainte)

	return modele


def contrainte_horaires(modele,dico_xy,xijk) :
	"""
	Ajoute au modele les contraintes associés aux creneaux horaires qui ne peuvent pas se
	superposer pour chaque etudiant
	Parametres :
		modele  : modele glpk avant l'ajout des contraintes
		dico_xy : hash map des variables pour recuperer leur identifiant
		xijk    : hash map des creneaux incompatibles par horaire de chaque etudiant
	Sortie :
		modele	: nouveau modele glpk updated
	"""
	for etu, dico_horaire in xijk.items() :
		for creneau, creneau_liste in dico_horaire.items() :
			if (len(creneau_liste) <= 1) or (creneau == Parametres.creneau_poubelle) :
				continue
			else :
				contrainte = ("contrainte horaire " +
					" | Etudiant " + str(etu.numero) +
					" | creneau = "+ str(creneau) +
					" | (+) len(creneau) = " + str(len(creneau_liste)) +
					" | (-) X" +
					" | min 0 ; max 1")
				modele = ajoute_contrainte_modele(modele,dico_xy,creneau_liste,[],0,1,nom=contrainte)
	return modele



#---------------------------------------------------------
#                       OBJECTIF
#---------------------------------------------------------


def fonction_objective(modele,dico_xy,yij) :
	"""
	Ajoute au modele la fonction objective
	Parametres :
		modele  : modele glpk avant l'ajout des contraintes
		dico_xy : hash map des variables pour recuperer leur identifiant
		yij     : hash map des inscriptions yij des etudiants aux ue
	Sortie :
		modele  : nouveau modele glpk updated
	Pre condition :
		Les listes dans yij doit etre ordonnée de sorte à ce que les ue doivent être
		de la plus demandée à la moins demandée
		Les ue obligatoires peuvent se trouver dans la liste
	"""

	modele = objectif_maximum_modele(modele)
	for etu, y_liste in yij.items() :
		cpt = 1
		coeff = 10000
		for y in y_liste :
			num = recupere_index(dico_xy,y)
			modele = definir_objectif_variable_modele(modele,num,coeff)
			coeff -= (cpt * (etu.nb_ue_a_total - cpt))
			cpt += 1

	return modele



#---------------------------------------------------------
#                      NOMMAGE
#---------------------------------------------------------

def nomme_variable_ue(etu,nom_ue) :
	"""
	Donne un nom a la variable yij pour les inscriptions à l'ue de l'etudiant
	Parametres :
		etu    : etudiant
		nom_ue : String nom de l'ue
	Sortie :
		nom    : String yij
	"""
	nom = "y{}{}".format(
	separateur_var + str(etu.numero),
	separateur_var + nom_ue
	)
	return nom


def nomme_variable_cours(etu,nom_ue,cpt) :
	"""
	Donne un nom a la variable xijk pour le cours de l'ue de l'etudiant
	Parametres :
		etu    : etudiant
		nom_ue : String nom de l'ue
		cpt    : Int numero du cours
	Sortie :
		nom    : String xijk
	"""
	nom =  "x{}{}{}".format(
	separateur_var + str(etu.numero),
	separateur_var + nom_ue,
	separateur_var + "cours" + str(cpt)
	)
	return nom


def nomme_variable_td_tme(etu,nom_ue,cpt) :
	"""
	Donne un nom a la variable xijk pour le groupe de l'ue de l'etudiant
	Parametres :
		etu    : etudiant
		nom_ue : String nom de l'ue
		cpt    : Int numero du groupe
	Sortie :
		nom    : String xijk
	"""
	nom = "x{}{}{}".format(
	separateur_var + str(etu.numero),
	separateur_var + nom_ue,
	separateur_var + "groupe" + str(cpt)
	)
	return nom


def nom_type_variable(nom_var) :
	"""
	Analyse le nom de la variable pour savoir si s'agit d'une variable y,
	d'un x pour horaire de cours ou d'un x pour horaire de groupe
	Parametres :
		nom_var : String nom de la variable
	Sortie :
		res     : "ue" si variable y, "cours" si x_cours, "groupe" si x_groupe
	"""
	if separateur_var+"groupe" in nom_var :
		return "groupe"
	elif separateur_var+"cours" in nom_var :
		return "cours"
	else :
		return "ue"


#---------------------------------------------------------
#           MANIPULATION DES DICTIONNAIRES
#---------------------------------------------------------

def nouvelle_entree_dico_xy(dico_xy,index,nom_var,etudiant,ue,nom_ue,ngroupe=None,ncours=None) :
	"""
	Ajoute une nouvelle entrée au dico_xy avec ses informations associées.
	Parametres :
		dico_xy  : le dictionnaire des variables
		index    : index de la variable dans le modele
		nom_var  : nom de la variable dans le modele GLPK
		ue       : ue associé à la variable
		etudiant : eudiant associé à la variable
		nom_ue   : String nom de l'ue
		ngroupe  : Int numero du groupe de la variable
		ncours   : Int numero du cours de la variable
	Sortie	:
		dico_xy  : dictionnaire avec la variable ajouté

	Pour les deux derniers parametres :
	- si sont egaux à None, alors la variable ajouté est yij
	- sinon la variable ajouté est xijk
	"""

	if (ngroupe != None) :
		dico_xy[nom_var] = (index,(etudiant,ue,nom_ue,ngroupe))

	elif (ngroupe == None and ncours != None) :
		dico_xy[nom_var] = (index,(etudiant,ue,nom_ue,ncours))

	else :
		dico_xy[nom_var] = (index,(etudiant,ue,nom_ue,None))

	return dico_xy


def recupere_index(dico_xy,nom_var) :
	"""
	Recupere l'index de la variable dans le dico_xy
	Parametres :
		dico_xy : dictionnaire des variables
		nom_var	: String nom de la variable
	Sortie	:
		index	: Int index de la variable dans le modele
	"""
	(index,a) = dico_xy[nom_var]
	return index


def recupere_index_et_ue(dico_xy,nom_var) :
	"""
	Recupere l'index de la variable dans le dico_xy et son ue
	Parametres :
		dico_xy : dictionnaire des variables
		nom_var : String nom de la variable
	Sortie	:
		index   : Int index de la variable dans le modele
		ue      : ue associé à la variable
	"""
	(index,(a,ue,b,c)) = dico_xy[nom_var]
	return (index,ue)


def recupere_ue(dico_xy,nom_var) :
	"""
	Recupere l'ue et l'intitule de l'ue de la variable dans le
	dico_xy
	Parametres :
		dico_xy  : dictionnaire des variables
		nom_var  : String nom de la variable
	Sortie :
		ue       : UE de la variable
		intitule : String intitule de l'ue de la variable
	"""
	(a,(b,ue,intitule,c)) = dico_xy[nom_var]
	return (ue,intitule)


def recupere_etudiant(dico_xy,nom_var) :
	"""
	Recupere l'etudiant associé à la variable
	Parametres :
		dico_xy : dictionnaire des variables
		nom_var : String nom de la variable
	Sortie :
		etu     : etudiant associé à la variable
	"""
	(index,(etu,a,b,c)) = dico_xy[nom_var]
	return etu


def recupere_ue_et_num(dico_xy,nom_var) :
	"""
	Recupere le nom de l'ue et le numéro du cours/groupe de la variable
	Parametres :
		dico_xy : dictionnaire des variables
		nom_var : String nom de la variable
	Sortie :
		ue      : ue associée
		nom_ue  : nom de l'ue associée à la variable
		num     : numero du cours ou groupe
	"""
	(index,(a,ue,nom_ue,num)) = dico_xy[nom_var]
	return (ue,nom_ue,num)


def recupere_entrée_dico(dico_xy,nom_var) :
	"""
	Recupere toutes les informations associées à la variable
	Parametres :
		dico_xy : dictionnaire des variables
		nom_var : String nom de la variable
	Sortie :
		index	: index de la variable dans le modele
		etu     : etudiant
		ue      : UE
		nom_ue  : nom de l'ue
		num     : numero du groupe/cours pour une variable x ou None si y
	"""
	(index,(etu,ue,nom_ue,num)) = dico_xy[nom_var]
	return (index,etu,ue,nom_ue,num)


def trier_dico_etu(dico_etu,dico_xy,modele) :
	"""
	Cree un dictionnaire se basant sur dico_etu dans lequel on supprime toutes les variables
	qui ne sont pas égales à 1 dans le modele et liste le numero de cours et de groupe obtenu
	Parametres :
		dico_etu      : dictionnaire etudiant des variables
		dico_xy       : dictionnaire des variables
		modele        : modele GLPK
	Sortie :
		dico_resultat : dictionnaire des resultats où on liste les numéros etudiants auquels on associe
		                l'intitulé de l'ue, le numero de cours et le numero de groupe
                        {Etu : [(String,Int,Int)]
	"""
	dico_resultat = dict()

	for etu in dico_etu :

		# initialisation
		dico_resultat[etu] = []

		# on parcourt les ue
		for nom_ue in dico_etu[etu] :

			# initialisation
			cours_obtenu = False
			groupe_obtenu = False
			num_groupe = 0
			num_cours = 0

			# on regarde le premier element qui est necessairement la variable y
			(nom_var,a,b) = dico_etu[etu][nom_ue][0]
			index_var = recupere_index(dico_xy,nom_var)
			resultat = resultat_modele(modele,index_var)

			# si elle n'a pas été obtenu alors on passe à l'ue suivante
			if resultat != 1 :
				continue

			# sinon, on recupere les numeros de groupe et de cours
			liste = dico_etu[etu][nom_ue]
			for i in range (1,len(liste)) :

				(nom_var,type_var,num) = liste[i]

				if type_var == "cours" and cours_obtenu :
					continue

				elif type_var == "cours" :
					index_var = recupere_index(dico_xy,nom_var)
					resultat = resultat_modele(modele,index_var)
					if resultat == 1 :
						num_cours = num
						cours_obtenu = True

				elif type_var == "groupe" and groupe_obtenu :
					continue

				elif type_var == "groupe" :
					index_var = recupere_index(dico_xy,nom_var)
					resultat = resultat_modele(modele,index_var)
					if resultat == 1 :
						num_groupe = num
						groupe_obtenu = True

			dico_resultat[etu].append((nom_ue,num_cours,num_groupe))

	return dico_resultat



#---------------------------------------------------------
#                     MODELE GLPK
#---------------------------------------------------------

def initialisation_modele(nom_modele = None) :
	"""
	Initialise le modele
	Parametres :
		nom_modele : donne un nom au modele
	Sortie :
		modele : le modele
	"""
	modele_glpk = glpk.LPX()
	cpt_rows = -1
	cpt_cols = -1
	if (nom_modele == None) :
		modele_glpk.name = "solveur_glpk"
	else :
		modele_glpk.name = nom_modele

	return (modele_glpk,cpt_cols,cpt_rows)


def objectif_maximum_modele(modele) :
	"""
	Utilise le maximum pour la fonction objectif
	Parametres :
		modele : modele GLPK
	Sortie :
		modele : le modele
	"""
	(modele_glpk,cpt_cols,cpt_rows) = modele
	modele_glpk.obj.maximize = True
	return (modele_glpk,cpt_cols,cpt_rows)


def definir_objectif_variable_modele(modele,index,coeff) :
	"""
	Defini le facteur dans la fonction objectif associé à la variable
	Parametres :
		modele : modele GLPK
		index  : indice de la variable dans le modele
		coeff  : facteur dans la fonction objectif associé à la variable
	Sortie :
		modele : le modele
	"""
	(modele_glpk,cpt_cols,cpt_rows) = modele
	modele_glpk.obj[index] = coeff
	return (modele_glpk,cpt_cols,cpt_rows)


def ajoute_variable_modele(modele,nom) :
	"""
	Ajoute une nouvelle variable booleenne au modele
	Parametres :
		modele : modele glpk avant l'ajout de la variable
		nom    : String nom de la variable
		cpt    : Int identifiant de la variable
	Sortie :
		modele : modele glpk updated
		cpt    : Int identifiant de la variable
	"""
	(modele_glpk,cpt_cols,cpt_rows) = modele    # recupere les donnees du modele

	cpt_cols += 1
	modele_glpk.cols.add(1)                     # nouvelle variable
	modele_glpk.cols[cpt_cols].name = nom       # nom de la variable
	modele_glpk.cols[cpt_cols].bounds = 0,None  # variable positive
	modele_glpk.cols[cpt_cols].kind = bool      # variable booleenne

	modele = (modele_glpk,cpt_cols,cpt_rows)
	return modele


def ajoute_contrainte_modele(modele,dico_xy,positif,negatif,mini,maxi, nom=None):
	"""
	Ajoute une nouvelle contrainte au modele
	Parametres :
		modele  : modele glpk avant l'ajout de la contrainte
		dico_xy : hash map des variables pour recuperer leur identifiant
		positif : [String] liste de variables sommées à la contrainte
		negatif : [String] liste de variables soustraites à la contrainte
		mini    : Int valeur minimale de la contrainte
		max     : Int valeur maximale de la contrainte
		nom     : String nom de la ligne
	Sortie :
		modele  : modele glpk updated
		cpt     : Int identifiant de la contrainte
	Pre condition :
		les variables dans positif et negatif se trouvent dans dico_xy
	Pour mini,maxi, par exemple, si la contrainte x doit etre 0<=x<=1 alors
	mini = 0, maxi = 1 ou alors si x =1 alors mini = maxi = 1
	"""
	(modele_glpk,cpt_cols,cpt_rows) = modele

	modele_glpk.rows.add(1)
	cpt_rows += 1
	modele_glpk.rows[cpt_rows].bounds = mini,maxi
	liste_a_ajouter = []
	for p in positif :
		index = recupere_index(dico_xy,p)
		liste_a_ajouter.append((index,1))
	for n in negatif :
		index = recupere_index(dico_xy,n)
		liste_a_ajouter.append((index,-1))

	modele_glpk.rows[cpt_rows].matrix = liste_a_ajouter

	if nom != None :
		modele_glpk.rows[cpt_rows].name = nom

	# __debug__
	if DEBUG_MODELE :
		print("NOM LIGNE MODELE id "+str(cpt_rows))
		print("\t"+nom)
		print("\tVALUE MODELE "+str(modele_glpk.rows[cpt_rows].matrix))

	return (modele_glpk,cpt_cols,cpt_rows)


def resoudre_modele(modele, methode = None) :
	"""
	Resolution du modele
	Parametre :
		modele  : modele avant sa resolution
		methode : methode de reoslution, par default le simplex
	Sortie :
		modele  : modele resolu
	L'utilite de cette fonction est de pouvoir rajouter plus facilement differentes
	methodes de resolution si besoin
	"""
	(modele_glpk,cpt_cols,cpt_rows) = modele

	if methode == None :
		modele_glpk.simplex()
	else :
		modele_glpk.simplex()

	return (modele_glpk,cpt_cols,cpt_rows)


def resultat_modele(modele,index) :
	"""
	Renvoi la valeur de la variable dans le modele
	Parametres :
		modele : modele GLPK
		index  : index de la variable
	Sortie :
		Int valeur de la variable
	Post condition :
		la valeur ne peut être égale qu'à 0 ou 1. GLPK peut donner une valeur qui
		à 1e-15 près environ, on arrondit dans ce cas à l'entier le plus proche
	"""
	(modele_glpk,cpt_cols,cpt_rows) = modele

	value = modele_glpk.cols[index].primal

	return int(round(value))


def indice_derniere_contrainte(modele) :
	"""
	Indique l'indice de la derniere contrainte, donc le nombre de contrainte
	de 0 à len - 1
	Parametres :
		modele : modele GLPK
	Sortie :
		Int identifiant de la derniere contrainte
	"""
	(modele_glpk,cpt_cols,cpt_rows) = modele
	return cpt_rows


def indice_derniere_variable(modele) :
	"""
	Indique l'indice de la derniere variable, donc le nombre de contrainte
	de 0 à len - 1
	Parametres :
		modele : modele GLPK
	Sortie :
		Int identifiant de la derniere variable
	"""
	(modele_glpk,cpt_cols,cpt_rows) = modele
	return cpt_cols


def recupere_resultat_contrainte(modele,index) :
	"""
	Donne la contrainte avec son résultat
	Parametres :
		modele : modele GLPK
		index  : index de la contrainte
	Sortie :
		resultat : liste [(String,Int)] contenant le nom de la variable et sa valeur
		nom       : nom de la contrainte

	Post condition :
		([],"VIDE") en sortie si l'index est erroné
		tuple sinon
	"""
	(modele_glpk,cpt_cols,cpt_rows) = modele
	if index > cpt_rows or index < 0:
		return ([],"VIDE")

	resultat = []
	nom = modele_glpk.rows[index].name
	contrainte = modele_glpk.rows[index].matrix
	for (id_var,coeff) in contrainte :
		nom_var = modele_glpk.cols[id_var].name
		value = resultat_modele(modele,id_var)
		resultat.append((nom_var,value))

	return (resultat,nom)


def est_dans_modele(modele,nom) :
	"""
	Regarde si la variable est dans le modele
	Parametres :
		modele : modele GLPK
		nom    : nom de la variable
	Sortie :
		Bool True si la variable est dans le modele
	"""
	(modele_glpk,cpt_cols,cpt_rows) = modele
	if nom not in modele_glpk.cols :
		return False
	else :
		return True

def pas_dans_le_dico(modele,dico_xy) :
	"""
	retourne une liste des variables se trouvant dans le modele mais pas
	le dictionnaire
	Parametres :
		modele  : modele GLPK
		dico_xy : liste des variables
	Sortie :
		modele
	"""
	(modele_glpk,cpt_cols,cpt_rows) = modele
	checkliste = []
	for var in modele_glpk.cols :
		if var.name not in dico_xy :
			checkliste.add(var.name)
	return checkliste




#---------------------------------------------------------
#               FONCTIONS POUR DEBUGGER
#---------------------------------------------------------



def __coherence_dico_modele__(modele,dico_xy):
	"""
	Verification que toutes les variables dans le dico se trouve dans
	le modele
	Parametres :
		modele  : modele GLPK
		dico_xy : dictionnaire des variables
	Sortie :
		checkliste : liste des variables présente dans dico mais pas modele
	"""

	checkliste = []
	for keys,elem in dico_xy.items() :
		if not est_dans_modele(modele,keys) :
			checkliste.add(keys)
	return checkliste


def __coherence_modele_dico__(modele,dico_xy):
	"""
	Verification que toutes les variables dans le modele se trouve dans
	le dico
	Parametres :
		modele  : modele GLPK
		dico_xy : dictionnaire des variables
	Sortie :
		checkliste : liste des variables présente dans dico mais pas modele
	"""

	return pas_dans_le_dico(modele,dico_xy)



def __affichage_dico__(dico_xy) :
	"""
	Affichage de toutes les variables du dictionnaires
	Parametres :
		dico_xy : dictionnaire des variables
	Sortie :
		affichage : String visualisation du dico sous forme de chaine de caracteres
	Post condition :
		la numérotation des groupes/cours dans dico_xy commence à 0. Pour l'affichage,
		on décale de 1 pour qu'elle commance à 1
	"""
	affichage = "VISUALISATION dictionnaire variables| len(dico_xy) = "+str(len(dico_xy))+"\n"
	cpt = 0

	if len(dico_xy) == 0 :
		affichage += "\t DICO VIDE\n"
		return affichage

	for var in dico_xy :
		affichage += "\t entrée dictionnaire n° "+str(cpt)+"\n"
		(index,etu,ue,nom_ue,num) = recupere_entrée_dico(dico_xy,var)
		affichage += "\t\t index = " + str(index)
		affichage +=	 " | nom = "+ var
		affichage +=	 " | type variable = "+ nom_type_variable(var)+"\n"
		affichage += "\t\t num_etu = " + str(etu.numero)
		affichage +=	 " | " + str(nom_ue)
		affichage +=	 " | num = "+ str(num)+"\n"
		cpt += 1
	return affichage


def __affichage_dico_etu__(dico_etu) :
	"""
	Affichage des données dans le dictionnaire etudiant
	Parametres :
		dico_etu : dictionnaire etudiant listant ses variables
	Sortie :
		affichage : représentation visuelle du dictionnaire
	"""
	affichage = "VISUALISATION dictionnaire etudiant | len(dico_etu) = "+str(len(dico_etu))+"\n"

	cpt = 0
	for etu in dico_etu :
		affichage += "\t Etudiant n°"+str(cpt)+"\n"

		for nom_ue in dico_etu[etu] :
			affichage += "\t\t numero Etudiant = "+str(etu.numero)+" | intitulé = "+nom_ue+"\n"
			affichage += "\t\t "+str(dico_etu[etu][nom_ue])+"\n"
		cpt += 1

	return affichage


def __affichage_dico_resultat__(dico_res) :
	"""
	Affichage des données dans le dictionnaire contenant les resultats
	Parametres :
		dico_res : dictionnaire resultat
	Sortie :
		affichage : représentation visuelle du dictionnaire
	"""
	affichage = "VISUALISATION dictionnaire resultat| len(dico_res) = "+str(len(dico_res))+"\n"

	cpt = 0
	for etu in dico_res :
		affichage += "\t Etudiant n°"+str(cpt)+"\n"

		for (nom_ue,num_cours,num_groupe) in dico_res[etu] :
			affichage += "\t\t numero Etudiant = "+str(etu.numero)+"\n"
			affichage += "\t\t\t intitule = "+str(nom_ue)
			affichage += " | num_groupe = "+str(num_groupe)+"\n"
		cpt += 1

	return affichage

def __affichage_yij__(yij,nom) :
	"""
	Cree une chaine de caractere permettant de visualiser textuellement
	la liste yij
	Parametres :
		yij : liste des variables yij { etudiant : [String yij] }
		nom : nom de la liste
	Sortie :
		affichage : String visualisation textuelle de la liste
	"""
	affichage ="VISUALISATION yij : "+nom+"\n"
	for etudiant,yj in yij.items() :
		affichage += "\tEtudiant "+ str(etudiant.numero)+"\n\t "+ str(yj)+" \n"
	return affichage


def __affichage_xijk__(xijk,nom) :
	"""
	Cree une chaine de caractere permettant de visualiser textuellement
	la liste xijk
	Parametres :
		yij : liste des variables xijk { etudiant : { ue : [String xijk] } }
		nom : nom de la liste
	Sortie :
		affichage : String visualisation textuelle de la liste
	"""
	affichage = "VISUALISATION xijk : "+nom+"\n"
	for etudiant,xjk in xijk.items() :
		affichage += "\tEtudiant "+str(etudiant.numero)+"\n"
		for ue, xk in xjk.items() :
			affichage += "\t\t UE "+str(ue.intitule)+"\n\t\t "+str(xk)+" \n"
	return affichage


def __affichage_xijk_horaire__(xijk,nom) :
	"""
	Cree une chaine de caractere permettant de visualiser textuellement
	la liste xijk
	Parametres :
		xijk : liste des variables xijk { etudiant : { int horaire : [String xijk] } }
		nom : nom de la liste
	Sortie :
		affichage : String visualisation textuelle de la liste
	"""
	affichage = "VISUALISATION xijk : "+nom+"\n"
	for etudiant,liste_horaire in xijk.items() :
		affichage += "\tEtudiant "+str(etudiant.numero)+"\n"
		for horaire, xjk in liste_horaire.items() :
			affichage += "\t\t horaire "+str(horaire)+"\n\t\t "+str(xjk)+" \n"
	return affichage



def __affichage_xijk_capacite__(xijk,nom) :
	"""
	Cree une chaine de caractere permettant de visualiser textuellement
	la liste xijk
	Parametres :
		xijk : liste des variables xijk { ue : { int num_groupe : [String xijk] }
		nom  : nom de la liste
	Sortie :
		affichage : String visualisation textuelle de la liste
	"""
	affichage = "VISUALISATION xijk : "+nom+"\n"
	for ue,liste_cap in xijk.items() :
		affichage += "\tUE "+str(ue.intitule)+"\n"
		for num_groupe, xjk in liste_cap.items() :
			affichage +="\t\t groupe num "+str(num_groupe)+" de capacite "+str(ue.capacites_groupes[num_groupe])+"\n"
			affichage +="\t\t "+str(xjk)+" \n"
	return affichage



def __affichage_resultats_contraintes__(modele,tabulation = 0) :
	"""
	Cree une chaine de caracteres permettant de visualiser textuellement
	les resultats du modele par contraintes
	Parametres :
		modele     : modele GLPK
		tabulation : Int indique le nombre de "\t" en debut de ligne pour l'affichage
	Sortie :
		affichage  : String visualisation des resultats
	"""
	tab = ""
	for i in range (0,tabulation) :
		tab +="\t"

	nb_contrainte = indice_derniere_contrainte(modele)
	affichage = tab +"VISUALISATION resultats_contrainte\n"

	for c in range (0,nb_contrainte) :
		(liste,visu_contrainte) = recupere_resultat_contrainte(modele,c)
		affichage += tab + "\tcontrainte n° "+ str(c) +"\n"
		affichage += tab + "\t"+visu_contrainte+"\n"
		affichage += tab + "\t"+str(liste)+"\n"

	return affichage


def __affichage_affectation_par_ligne__(etudiant,ue, intitule_ue,num_cours,num_groupe) :
	"""
	Cree une chaine de caracteres permettant de visualiser les resultats
	affectation par affectation (avant affectation dans l'ue)
	Parametres :
		etudiant    : etudiant concerné par l'affectation
		ue          : ue concerné par l'affectation
		intitule_ue : String intitule de l'ue
		num_cours   : Int numero du cours
		num_groupe  : Int numero du groupe
	Sortie :
		affichage   : Sring visualisant l'affectation
	"""
	affichage = "RESULTAT avant affectation\n"
	affichage += "-Etudiant-:\n"
	affichage += str(etudiant)
	affichage += "\naffectation : intitule = "+intitule_ue+" ; num_cours = "+str(num_cours)+" num_groupe = "+str(num_groupe)+"\n"
	affichage += "-UE- :\n"
	affichage += str(ue)
	affichage += "\n"
	return affichage

def __affichage_apres_affectation__(etudiant,liste_ue_obtenu,ue,voeux) :
	"""
	Cree une chaine de caracteres permettant de visualiser les resultats
	affectation par affectation (après affectaion dans l'ue)
	Parametres :
		etudiant        : etudiant concerné par l'affectation
		liste_ue_obtenu : liste des ue obtenu par l'etudiant
		ue              : ue concerné par l'affectation
		voeux           : voeux de l'etudiant
	Sortie :
		affichage   : Sring visualisant l'affectation
	"""
	affichage = "RESULTAT apres affectation des changement dans l'ue\n"
	affichage += "-Etudiant-:\n"
	affichage += str(etudiant)
	affichage += "\n"
	affichage += "voeux obtenus : "+voeux+"\n"
	affichage += "liste ue obtenu :"+str(liste_ue_obtenu)+"\n"
	affichage += "-UE- :\n"
	affichage += str(ue)
	affichage += "\n"
	return affichage
