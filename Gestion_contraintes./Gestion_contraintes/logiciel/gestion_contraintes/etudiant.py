#!/usr/bin/env python3
# -*- coding: utf-8 -*-

from parametres import Parametres
from erreurs import ColonneInexistante
from outils import *


"""Classe representant les données d'un etudiant"""
class Etudiant:
    def __init__(self, ligne_voeux):
        """
        Constructeur d'Etudiant. 
        Parametres:
        	ligne_voeux : ligne d'un fichier csv representant un etudiant.
        Erreur
        	Peut lever une erreur de type ColonneInexistante
        """
        try:
            # Numero devant etre unique au sein de chaque parcours
            self.numero = ligne_voeux[Parametres.colonne_num] 
        except:
            # Colonne pour le numero inexistante
            raise ColonneInexistante(Parametres.colonne_num, Parametres.chemin_voeux)
            
        try:
            # Parcours de l'etudiant
            self.parcours = ligne_voeux[Parametres.colonne_parcours]
        except:
            # Colonne pour le parcours inexistante
            raise ColonneInexistante(Parametres.colonne_parcours, Parametres.chemin_voeux)
            
        self.ue_obligatoires = lire_texte_colonnes_multiples (Parametres.colonne_obligatoire, ligne_voeux)
        self.ue_conseillees = lire_texte_colonnes_multiples (Parametres.colonne_conseillee, ligne_voeux)
        self.ue_equivalentes = lire_texte_colonnes_multiples (Parametres.colonne_equiv, ligne_voeux)
        self.nom_variable = "x{}{}".format(Parametres.separateur_var + self.parcours, Parametres.separateur_var + str(self.numero))
        self.nom_variable_ue = "y{}{}".format(Parametres.separateur_var + self.parcours, Parametres.separateur_var + str(self.numero))
        self.nb_ue_a_suivre = len(self.ue_obligatoires) + len(self.ue_conseillees)
        self.nb_ue_obligatoires = len(self.ue_obligatoires)
        self.nb_ue_a_total = self.nb_ue_a_suivre + len(self.ue_equivalentes)
        self.edt_incompatible = False #Attribut marquant les etudiants dont les voeux sont incoherents

    def __str__(self):
    	"""
    	Représentation en String de l'étudiant
    	"""
    	res = "Etudiant n°" + str(self.numero) + " (Parcours " + str(self.parcours) \
            	+ ")\n\t-Liste des voeux : " + str(self.ue_obligatoires +
                                            	self.ue_conseillees +
                                            	self.ue_equivalentes) + \
            	"\n\t-Nombre d'UE a suivre : " +str(self.nb_ue_a_suivre)
    	if self.edt_incompatible:
            res += "\n\t-ATTENTION LES VOEUX FOURNIS SONT INCOMPATIBLES !"
    	return res

    def noms_vars_ue(self, liste_ue):
    	"""
    	Nomme les variables y pour la resolution de l'etudiant pour chaque ue de la liste
    	Parametres :
    		liste_ue : liste d'UE
    	Retour :
    		liste de variables yij
    	"""
    	return ["y{}{}{}".format(Parametres.separateur_var + self.parcours, Parametres.separateur_var + str(self.numero), Parametres.separateur_var + nom_ue) \
                for nom_ue in liste_ue]

    def noms_vars_ue_obligatoires(self):
    	"""
    	Nomme les variables y pour les UE obligatoires de l'étudiant
    	Retour :
    		liste de variables yij
    	"""
    	return self.noms_vars_ue(self.ue_obligatoires)

    def noms_vars_ue_conseillees(self):
    	"""
    	Nomme les variables y pour les UE conseillés de l'etudiant
    	Retour :
    		liste de variables yij
    	"""
    	return self.noms_vars_ue(self.ue_conseillees)

    def noms_vars_ue_equivalentes(self):
    	"""
    	Nomme les variables y pour les UE equivalentes de l'étudiant
    	Retour :
    		liste de variables yij
    	"""
    	return self.noms_vars_ue(self.ue_equivalentes)

    def listes_ue(self):
    	"""
    	Renvoie la listes des UE dans les voeux de l'etudiant
    	Retour : 
        	liste d'UE de l'étudiant
    	"""
    	return self.ue_obligatoires +  self.ue_conseillees + self.ue_equivalentes

    def listes_ue_desirees(self):
    	"""
    	Renvoie la listes des UE desirees par l'etudiant
    	Retour :
        	liste d'UEs desirees par l'etudiant
    	"""
    	return self.ue_obligatoires +  self.ue_conseillees
        
        
        
