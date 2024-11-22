from tkinter import *
from tkinter.messagebox import *
from tkinter import messagebox
from tkinter import filedialog
import tkinter.ttk as ttk
from parametres import Parametres
from solveur_glpk import *
from outils import *
import traceback
import csv



chemin_edt = "./donnees/EDT23_M1S1.csv"
chemin_voeux = "./donnees/voeux2023.csv" 

dictionnaire_ue,erreurs_ue = recuperer_ue(chemin_edt)
liste_etudiants,erreurs_voeux = recuperer_etudiants(chemin_voeux)
if len(erreurs_voeux) > 0 or len(erreurs_ue) > 0 :
	print("erreur creation dico ue ou liste etudiant")
	 
else :      	
	erreurs_coherence = verification_coherence(dictionnaire_ue,liste_etudiants)
	if len(erreurs_coherence) > 0 :
		print("erreur coherence")
	else :
		chaine = ""            
		chaine += Parametres.texte[44][Parametres.langue]
		res, nb_etu_affectation_incomplete = resoudre(dictionnaire_ue, liste_etudiants, DEBUG=True)
		#res, nb_etu_affectation_incomplete = resoudre(dictionnaire_ue, liste_etudiants, contrainte_relachee=True, DEBUG=True)
		print("RESULTAT FINAL")
		print(res)
		print("nb affectation incomplete")
		print(nb_etu_affectation_incomplete)

                

