#!/usr/bin/env python3
# -*- coding: utf-8 -*-

from parametres import Parametres
from erreurs import *
from outils import *



def verification_ue(chemin_fichier):
	"""
	Renvoie les lignes qui ont été lu dans le fichier EDT de format csv, le fichier,
	un boolean indiquant s'il y a une ou plusieurs erreurs et une liste pour les erreurs
	où les elements de cette liste sont des tuples erreur=(int ligne, string description).
	Parametres :
		chemin_fichier : chemin du fichier à lire
	Retour :
		liste_lignes : liste des ligne du fichier
		fichier : fichier lu
		erreur : True si présence d'une erreur dans le fichier, False sinon
		liste_erreur : liste d'erreur ligne par ligne, element = (int,String)
	"""
	lignes, fichier = dictionnaire_csv(chemin_fichier)
	i = 1
	liste_erreurs = []
	liste_lignes = []
	erreur = False
	for ligne in lignes:
		liste_lignes.append(ligne)
		message = __verification_ligne_ue__(ligne)
		if message != "":
			liste_erreurs.append((i,message))
			erreur = True
		i += 1
		
	return liste_lignes,fichier,erreur,liste_erreurs


def verification_voeux(chemin_fichier):
	"""
	renvoie les lignes qui ont été lu dans le fichier voeu de format csv, le fichier,
	un boolean indiquant s'il y a au moins une erreur dans le fichier voeu et une
	liste de ces erreurs sous forme de tuples erreur=(int ligne,string description).
	Parametres :
		chemin_fichier : chemin du fichier à lire
	Retour :
		liste_lignes : liste des ligne du fichier
		fichier : fichier lu
		erreur : True si présence d'une erreur dans le fichier, False sinon
		liste_erreur : liste d'erreur ligne par ligne, element = (int,String)	
	"""
	lignes,fichier = dictionnaire_csv(chemin_fichier)
	i = 1
	liste_erreurs = []
	liste_lignes = []
	erreur = False
	for ligne in lignes:
		liste_lignes.append(ligne)
		message = __verification_ligne_voeux__(ligne)	
		if message != "":
			liste_erreurs.append((i,message))
			erreur = True
		i += 1
	return liste_lignes,fichier,erreur,liste_erreurs


def verification_coherence(dictionnaire_ue,liste_etudiants) :
    """
    Compare la liste d'etudiants au dictionnaire d'UE afin de trouver s'il y
    a des incoherences entre les deux, renvoi une liste d'incoherence par 
    etudiants
    Parametres : 
    	dictionnaire_ue : dictionnaire des UEs
    	liste_etudiants : liste des Etudiants
    Retour : 
    	liste_incoherences : liste des incoherences par Etudiants, element = (Etudiant,String)
    """
    liste_incoherences = []
    message = ""
    
    for etu in liste_etudiants :
    	ue_liste = etu.listes_ue()
    	message = ""
    	for ue in ue_liste :
    		if ue not in dictionnaire_ue :
    			message += str(ue)+" "
    	if message != "" :
    		 liste_incoherences.append((str(etu.numero),Parametres.texte[581][Parametres.langue] + message))
    
    return liste_incoherences
    
		

def __verification_ligne_ue__(ligne_ue):
	"""
	renvoi un message avec les erreurs, reste vide s'il n'y en a aucune.
	fait une verification de la ligne :
	  regarde si les cases se devant d'etre rempli sont bien rempli
	  regarde s'il n'y a pas de contradictions entre nombre de groupes et horaires+capacités
	Parametres :
		ligne_ue : une ligne du fichier
	Retour :
		log :String de description des erreurs de la ligne
	log est vide s'il n'y a pas d'erreurs
	"""
	log = ""
	intitule = Parametres.colonne_intitule
	id_ue =  Parametres.colonne_id_ue
	groupe = Parametres.colonne_nb_groupes
	
	if __caseVide__(intitule,ligne_ue) :
		log += Parametres.texte[57][Parametres.langue]+intitule+"\n"
		
	if __caseVide__(id_ue,ligne_ue) :
		log += Parametres.texte[57][Parametres.langue]+id_ue+"\n"
		
	if __caseVide__(groupe,ligne_ue) :
		log += Parametres.texte[57][Parametres.langue]+groupe+"\n"
		return log
		
	cours = Parametres.colonne_cours
	capac = Parametres.colonne_capacite
	td    = Parametres.colonne_td
	tme   = Parametres.colonne_tme
	nb_groupe = int(ligne_ue[groupe])
	
	if __caseVide__(cours+"1",ligne_ue) :
		log += Parametres.texte[57][Parametres.langue]+cours+"1"+"\n"
		
	if __coherence_groupe_ue__(nb_groupe,capac,ligne_ue):
		log += Parametres.texte[58][Parametres.langue]+groupe+"/"+capac+"\n"
	
	if __coherence_groupe_ue__(nb_groupe,td,ligne_ue):
		log += Parametres.texte[58][Parametres.langue]+groupe+"/"+td+"\n"
	
	if (nb_groupe > 1) :
		if __coherence_groupe_ue__(nb_groupe,tme,ligne_ue):
			log +=  Parametres.texte[58][Parametres.langue]+groupe+"/"+tme+"\n"
	
	return log
	
	
def __verification_ligne_voeux__(ligne_etu):
	"""
	renvoi un message avec les erreurs, reste vide s'il n'y en a aucune.
	fait une verification de la ligne : 
	  regarde si les cases devant être rempli le sont bien 
	Parametres :
		ligne_etu : une ligne du fichier
	Retour :
		log :String de description des erreurs de la ligne
	log est vide s'il n'y a pas d'erreurs
	"""
	log = ""
	parcours = Parametres.colonne_parcours
	num = Parametres.colonne_num
	
	if __caseVide__(parcours,ligne_etu) :
		log += Parametres.texte[57][Parametres.langue]+parcours+"\n"	
	if __caseVide__(num,ligne_etu) :
		log += Parametres.texte[57][Parametres.langue]+num+"\n"	
	
	return log
		


def __coherence_groupe_ue__(nb_groupe,nom_colonne,ligne_ue) :
	""" 
	Regarde s'il y a une erreur de coherence entre le nombre de groupe
	et les capacite des groupes ou les horaires de td/tme
	le nombre de colonnes pleines doivent etre celle du nombre de groupe
	Parametres : 
		nb_groupe   : nb de groupe de l'UE
		nom_colonne : nom de la colonne correspondant aux groupes (nom+i, i entier)
		ligne_ue    : ligne de l'UE
	Retour :
		True s'il y a erreur de coherence
	"""
	if nb_groupe < 1 :
		return False
	for i in range (1,(Parametres.max_nb_groupe)+1):
		if i < nb_groupe+1:
			if __caseVide__(nom_colonne+str(i),ligne_ue):
				return True
		else :
			if not __caseVide__(nom_colonne+str(i),ligne_ue):
				return True
	
	return False
	

def __caseVide__(localisation,ligne) :
	"""
	Retourne True si la case à l'endroit indiqué (localisation) est vide
	Correspond à une case dans le fichier CSV qui est vide
	Parametres :
		localisation : numero de localisation d'un element dans ligne
		ligne : ligne de l'UE/Etudiant
	Retour :
		True si vide, False sinon
	"""
	if ligne[localisation] is None or ligne[localisation] == "":
		return True
	else : 
		return False
	


	
	
    	
    	

