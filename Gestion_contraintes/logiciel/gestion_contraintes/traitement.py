#!/usr/bin/env python3
# -*- coding: utf-8 -*-

from parametres import Parametres
from erreurs import *
from outils import *
from etudiant import Etudiant
from verification import *
from ue import UE


def recuperer_etudiants(chemin_fichier):
    """
    Retourne la liste des etudiants contenus dans un fichier csv dont le
    chemin est passé en argument
    Parametres : 
    	chemin_fichier : chemin du fichier csv
    Erreurs :
    	ColonneInexistante  
    	FormatDeFichierNonReconnu
    """
    
    res = []
    lignes,fichier,erreur,liste_erreurs = verification_voeux(chemin_fichier)
    	
    try:
    	if not erreur :
    	    liste_erreurs = []
    	    for ligne in lignes:
                res.append(Etudiant(ligne))
    except (Exception) as e:
        raise e
    finally:
        fichier.close()
    return res,liste_erreurs


def recuperer_ue(chemin_fichier):
    """
    Retourne le dictionnaire (nom_ue : UE) des UE contenues dans un fichier csv dont le
    chemin est passé en argument et une liste des erreurs du fichier.
    Parametres : 
    	chemin_fichier : chemin du fichier csv
    Retour : 
    	res : dictionnaire des ues du fichier
    	liste_erreurs : liste des erreurs dans le fichier par ligne
    Erreurs :
    	ColonneInexistante  
    	FormatDeFichierNonReconnu  
    Si le fichier n'a pas d'erreurs, liste_erreurs est vide
    """
    
    res = dict()
    lignes,fichier,erreur,liste_erreurs = verification_ue(chemin_fichier)
        
    try:
    	# si aucune erreur n'est detecté alors on construit le dictionnaire
    	# et on s'assure que liste_erreurs est vide
        if not erreur :
            liste_erreurs = []
            for ligne in lignes:
                ue = UE(ligne)
                res [ue.intitule] = ue
    except (Exception) as e:
        raise e
    finally:
        fichier.close()
    return res,liste_erreurs
  
    	








    
