#!/usr/bin/env python3
# -*- coding: utf-8 -*-

#---------------------------------------------------------
#		      IMPORT
#---------------------------------------------------------

from tkinter import *
from tkinter.messagebox import *
from tkinter import messagebox
from tkinter import filedialog
import tkinter.ttk as ttk
from parametres import Parametres
from outils import *
import traceback
import csv

# griser une des deux lignes
from solveur import *
#from solveur_glpk import *

class MenuGestionnaireVoeux:


#---------------------------------------------------------
#		INITIALISATION
#---------------------------------------------------------

    def __init__(self):
        """
        Fonction de lancement de l'application
        Creation de la fenetre avec ses onglets
        """

        # marqueur
        self.mode_testeur = False    # Mode permettant de charger les fichiers du dossier testeur
        self.succes_calcul = False   # Permettant de savoir si le calcul effectué est réussi
        self.err_voeux = ""	     # vide tant qu'il n'y a pas d'erreurs detecté sur le fichier voeux
        self.err_edt = ""	     # vide tant qu'il n'y a pas d'erreurs detectés sur le fichier edt
        self.err_coh = ""	     # vide tant qu'il n'y a pas d'incoherences entre fichier voeux et edt

	# Dimension de la fenetre avec titrage
        self.fenetre = Tk()
        largeur_ecran = self.fenetre.winfo_screenwidth()
        hauteur_ecran = self.fenetre.winfo_screenheight()
        self.dimension_largeur = largeur_ecran - 150
        self.dimension_hauteur = hauteur_ecran - 150
        self.fenetre.geometry("{}x{}+0+0".format(self.dimension_largeur, self.dimension_hauteur))
        self.fenetre.title(Parametres.texte[18][Parametres.langue])



        # ajout d'une barre de menu
        self.menubar = Menu(self.fenetre)
        self.menu1 = Menu(self.menubar, tearoff=0) # Operations relatives aux fichiers
        self.menu2 = Menu(self.menubar, tearoff=0) # Operations relatives au calcul / affichage sur la gui
        self.menu3 = Menu(self.menubar, tearoff=0) # Autre (mode testeur, aide)


	# Creation d un notebook pour les onglets (donc 2 Frame)
        self.notebook = ttk.Notebook(self.fenetre)
        self.notebook.pack()
        self.onglet_informations = Frame(self.notebook)
        self.onglet_affectations = Frame(self.notebook)
        self.onglet_remplissage_groupes = Frame(self.notebook)

        # methode pour ajouter les onglets et leur scrollbar
        self.notebook.add(self.onglet_informations, text=Parametres.texte[171][Parametres.langue])
        self.notebook.add(self.onglet_affectations, text=Parametres.texte[16][Parametres.langue])
        self.notebook.add(self.onglet_remplissage_groupes, text=Parametres.texte[17][Parametres.langue])
        self.definir_scrollbar()

	# methode pour le chargement des menu
        self.chargerMenuFichier()
        self.chargerMenuCalculer()
        self.chargerMenuAide()

	# ajoute une cascade pour chaque menu MenuFichier, MenuCalculer,MenuAide
        self.menubar.add_cascade(label=Parametres.texte[0][Parametres.langue], menu=self.menu1)
        self.menubar.add_cascade(label=Parametres.texte[1][Parametres.langue], menu=self.menu2)
        self.menubar.add_cascade(label=Parametres.texte[2][Parametres.langue], menu=self.menu3)

        self.affectation_realisee = False
        self.remplissage_realise = False

        self.fenetre.config(menu=self.menubar)

        # remplit l'onglet pour le log
        self.afficher_onglet_log()

	# Style de l'affichage
        self.style = ttk.Style()
        self.style.theme_use('clam')
        self.style.configure("Treeview",
                        background="#D3D3D3",
                        foreground="black",
                        rowheight=25,
                        fieldbackground="#D3D3D3")
        self.style.configure("Treeview.Heading",
                        background="orange",
                        foreground="black",
                        rowheight=25,
                        fieldbackground="orange")
        self.style.map('Treeview', background=[("selected", "orange")])

        self.fenetre.lift()
        self.rafraichir()
        self.fenetre.mainloop()


    def definir_scrollbar(self):
        """
        Fonction servant a construire les scrollbars
        """
        self.scrollbarx_affectations = Scrollbar(self.onglet_affectations, orient=HORIZONTAL)
        self.scrollbary_affectations = Scrollbar(self.onglet_affectations, orient=VERTICAL)
        self.scrollbarx_remplissage = Scrollbar(self.onglet_remplissage_groupes, orient=HORIZONTAL)
        self.scrollbary_remplissage = Scrollbar(self.onglet_remplissage_groupes, orient=VERTICAL)


#---------------------------------------------------------
#		CREATION MENUS
#---------------------------------------------------------


    def chargerMenuFichier(self, recharger=False, resetHard=False):
        """
        Charge les boutons du menu Fichier. On peut indiquer si cette fonction est appelee au lancement de la gui
        ou suite à un changement de mode. Un hard reset permet de refaire tous les boutons du menu (du a un
        changement de langue)
        Parametres :
        	recharger : True -> fonction appelé au lancement de la gui ou suite à un changement de mode
        	resetHard : True -> refait tous les boutons du menu
        """
        if (resetHard):
            a_supprimer = 7
            if self.mode_testeur:
                a_supprimer = 6
            for i in range(a_supprimer):
                self.menu1.delete(0)
            self.menubar.entryconfig(1,label=Parametres.texte[0][Parametres.langue])

        if self.mode_testeur:
            if (recharger):
                for i in range (7):
                    self.menu1.delete(0)
            self.menu1.add_command(label=Parametres.texte[3][Parametres.langue],
            			command=lambda:self.commande_charger_fichiers())
        else:
            if (recharger):
                for i in range(6):
                    self.menu1.delete(0)
            self.menu1.add_command(label=Parametres.texte[4][Parametres.langue],
            			command=lambda:self.commande_charger_edt())
            self.menu1.add_command(label=Parametres.texte[6][Parametres.langue],
            			command=lambda:self.commande_charger_voeux())

        self.menu1.add_command(label=Parametres.texte[7][Parametres.langue],
        			command=lambda:self.commande_exporter_resultats(),
        			state=DISABLED)
        self.menu1.add_command(label=Parametres.texte[8][Parametres.langue],
        			command=lambda:self.commande_exporter_statistiques(),
        			state=DISABLED)
        self.menu1.add_separator()
        self.menu1.add_command(label=Parametres.texte[9][Parametres.langue],
        			command=lambda:self.commande_exit())



    def chargerMenuCalculer(self, resetHard=False):
        if (resetHard):
            for i in range(3):
                self.menu2.delete(0)
            self.menubar.entryconfig(2,label=Parametres.texte[1][Parametres.langue])
        self.menu2.add_command(label=Parametres.texte[10][Parametres.langue],
                               command=lambda:self.commande_calcul(),
                               state=DISABLED)
        self.menu2.add_command(label=Parametres.texte[11][Parametres.langue],
                               command=lambda:self.commande_afficher_remplissage(),
                               state=DISABLED)
        self.menu2.add_command(label=Parametres.texte[101][Parametres.langue],
                               command=lambda:self.commande_effacer_affichage())

    def chargerMenuAide (self, recharger=False, resetHard=False):
        """
        Charge les boutons du menu Aide. On peut indiquer si cette fonction est appelee au lancement de la gui
        ou suite à un changement de mode. On peut également faire un hard reset
        Parametres :
                recharger : True -> fonction appelé au lancement de la gui ou suite à un changement de mode
        	resetHard : True -> refait tous les boutons du menu
        """
        if (resetHard):
            for i in range(3):
                self.menu3.delete(0)
            self.menubar.entryconfig(3,label=Parametres.texte[2][Parametres.langue])
        if recharger:
            self.menu3.delete(2)
        else:
            self.menu3.add_command(label=Parametres.texte[12][Parametres.langue],
            			command=lambda:self.commande_aide())
            self.menu3.add_command(label=Parametres.texte[13][Parametres.langue],
            			command=lambda:self.commande_langue())

        if (self.mode_testeur):
            self.menu3.add_command(label=Parametres.texte[15][Parametres.langue],
            			command=lambda:self.commande_testeur())
        else:
            self.menu3.add_command(label=Parametres.texte[14][Parametres.langue],
            			command=lambda:self.commande_testeur())


#---------------------------------------------------------
#		COMMANDES DES MENUS
#---------------------------------------------------------

    def effacer_affichage(self):
    	"""
    	efface l'affichage dans les deux onglets
    	"""
    	self.efface_remplissage_groupes()
    	self.efface_remplissage_affectations()
    	self.definir_scrollbar()
    	self.rafraichir()


    def aller_vers_onglet(self,onglet):
    	"""
    	force l'observateur a aller sur l'onglet
    	attention : onglet doit se trouver dans le notebook
    	"""
    	self.notebook.select(onglet)


    def commande_calcul(self):
    	"""
    	commandes pour le lancement du calcul
    	"""
    	self.insertion_texte_log(Parametres.texte[68][Parametres.langue])
    	self.calculer()
    	if self.succes_calcul :
    		self.insertion_texte_log(Parametres.texte[70][Parametres.langue])
    		self.aller_vers_onglet(self.onglet_affectations)
    	else :
    		self.insertion_texte_log(Parametres.texte[69][Parametres.langue])
    		if self.err_edt != "" :
    			self.afficher_erreurs_log(self.err_edt,Parametres.chemin_edt)
    			self.afficher_liste_erreur_newWindows(self.err_edt,
    						[Parametres.chemin_edt])
    			self.err_edt = ""
    		if self.err_voeux != "" :
            		self.afficher_erreurs_log(self.err_voeux,Parametres.chemin_voeux)
            		self.afficher_liste_erreur_newWindows(self.err_voeux,
            					[Parametres.chemin_voeux])
            		self.err_voeux = ""
    		if self.err_coh != "" :
            		self.afficher_erreurs_log(self.err_coh,Parametres.chemin_voeux,"etudiant")
            		self.afficher_liste_erreur_newWindows(self.err_coh,
            					[Parametres.chemin_voeux,Parametres.chemin_edt],
            					"etudiant")
            		self.err_coh = ""
    		self.aller_vers_onglet(self.onglet_informations)


    def commande_afficher_remplissage(self):
    	"""
    	commandes pour afficher le remplissage
    	"""
    	self.insertion_texte_log(Parametres.texte[72][Parametres.langue])
    	self.afficher_remplissage_groupes()
    	self.aller_vers_onglet(self.onglet_remplissage_groupes)


    def commande_effacer_affichage(self):
    	"""
    	commandes pour effacer l'affichage des resultats
    	"""
    	self.effacer_affichage()
    	self.aller_vers_onglet(self.onglet_informations)


    def commande_exit(self):
    	"""
    	commande pour quitter
    	"""
    	self.fenetre.quit()


    def commande_exporter_resultats(self):
    	"""
    	commandes pour l'exportation des résultats
    	"""
    	self.exporter_res()
    	self.insertion_texte_log(Parametres.texte[73][Parametres.langue])


    def commande_exporter_statistiques(self):
    	"""
    	commandes pour l'exportation des statistiques
    	"""
    	self.exporter_stats()
    	self.insertion_texte_log(Parametres.texte[74][Parametres.langue])


    def commande_charger_edt(self):
    	"""
    	commandes pour le chargement de l'emploi du temps
    	"""
    	self.insertion_texte_log(Parametres.texte[67][Parametres.langue])
    	self.charger_edt()
    	self.insertion_texte_fichier()


    def commande_charger_voeux(self):
    	"""
    	commandes pour le chargement des voeux
    	"""
    	self.insertion_texte_log(Parametres.texte[66][Parametres.langue])
    	self.charger_voeux()
    	self.insertion_texte_fichier()


    def commande_charger_fichiers(self):
    	"""
    	commandes pour le chargement des fichiers
    	"""
    	self.charger_fichiers()


    def commande_testeur(self):
    	"""
    	commande pour le testeur
    	"""
    	self.testeur()


    def commande_langue(self):
    	"""
    	commande pour le changement de langue
    	"""
    	self.langue()


    def commande_aide(self):
    	"""
    	commande pour l'aide
    	"""
    	self.aide()

#---------------------------------------------------------
#		MENU AIDE
#---------------------------------------------------------

    def testeur(self):
        """
        Fonction qui active/désactive le mode testeur selon le choix de l'utilisateur
        """
        if not self.mode_testeur:
            choix = messagebox.askquestion (Parametres.texte[14][Parametres.langue],Parametres.texte[19][Parametres.langue],icon = 'warning')
            if choix == "yes":
                self.mode_testeur = True
                self.rafraichir(True)

        else:
           choix = messagebox.askquestion (Parametres.texte[14][Parametres.langue],Parametres.texte[20][Parametres.langue],icon = 'warning')
           if choix=="yes":
               self.mode_testeur = False
               self.rafraichir(True)



    def aide(self):
        """
        Fonction qui affiche un readMe sur la gui
        """
        showinfo(Parametres.texte[41][Parametres.langue], Parametres.texte[42][Parametres.langue])
        afficher_log(Parametres.chemin_log, Parametres.texte[42][Parametres.langue], niveau=logging.INFO, affichage=False)


    def langue(self):
        """Change la langage de l'application (français ou anglais)"""
        if Parametres.langue == 0:
            choix = messagebox.askquestion ("Changer de langue","Souhaitez-vous passer en anglais ?",icon = 'warning')
        else:
            choix = messagebox.askquestion ("Switching language","Would you like to switch to French ?",icon = 'warning')

        if choix == "yes":
            Parametres.langue = (Parametres.langue + 1) % 2
            #changement des menu (et de leurs boutons)
            self.chargerMenuFichier(resetHard=True)
            self.chargerMenuCalculer(resetHard=True)
            self.chargerMenuAide(resetHard=True)
            #changement des onglets
            self.notebook.tab(0, text = Parametres.texte[171][Parametres.langue])
            self.notebook.tab(1, text = Parametres.texte[16][Parametres.langue])
            self.notebook.tab(2, text = Parametres.texte[17][Parametres.langue])
            self.fenetre.title(Parametres.texte[18][Parametres.langue])
            #reset de l'onglet Log
            self.efface_onglet_log()
            self.afficher_onglet_log()
            #showinfo(Parametres.texte[41][Parametres.langue], Parametres.texte[43][Parametres.langue])
        self.rafraichir()


#---------------------------------------------------------
#		MENU FICHIER ET FONCTIONS ASSOCIES
#---------------------------------------------------------

    def charger_fichiers(self):
        """
        Fonction du monde testeur. Permet de charger tous les csv contenus dans le dossier testeur.
        """
        Parametres.chemin_edt = "./testeur/EDT22_M1S2.csv"
        Parametres.chemin_voeux = "./testeur/voeux.csv"
        showinfo(Parametres.texte[21][Parametres.langue], Parametres.texte[22][Parametres.langue])
        afficher_log(Parametres.chemin_log, Parametres.texte[22][Parametres.langue], niveau=logging.INFO, affichage=False)
        self.rafraichir()

    def charger_csv(self, message):
        """
        Fonction de chargement d'un fichier csv. Retourne le chemin du fichier
        sélectionné (la chaine vide si aucun n'a ete selectionne) et affiche le
        message
        Parametres :
        	message : message affiché
        Sortie :
        	chemin : chemin du fichier
        """

        chemin = filedialog.askopenfilename(title=Parametres.texte[23][Parametres.langue],filetypes=[('CSV', '.csv')])
        if chemin != "":
            showinfo(Parametres.texte[24][Parametres.langue], message)
            afficher_log(Parametres.chemin_log, Parametres.texte[24][Parametres.langue], niveau=logging.INFO, affichage=False)
        else:
             showinfo(Parametres.texte[25][Parametres.langue], Parametres.texte[26][Parametres.langue])
             afficher_log(Parametres.chemin_log, Parametres.texte[26][Parametres.langue], niveau=logging.INFO, affichage=False)
        self.rafraichir()
        return chemin

    def charger_edt(self):
        """
        Fonction de chargement de l'edt
        """
        Parametres.chemin_edt = self.charger_csv(Parametres.texte[27][Parametres.langue])
        self.rafraichir()

    def charger_voeux(self):
         """
         Fonction de chargement des voeux
         """
         Parametres.chemin_voeux = self.charger_csv(Parametres.texte[29][Parametres.langue])
         self.rafraichir()

    def exporter_res(self):
        """
        Fonction d'ecriture des resultats
        """
        fichier = filedialog.asksaveasfile (title = Parametres.texte[33][Parametres.langue], defaultextension = ".csv")
        if (fichier == None):
            showinfo(Parametres.texte[34][Parametres.langue], Parametres.texte[35][Parametres.langue])
            afficher_log(Parametres.chemin_log, Parametres.texte[35][Parametres.langue], niveau=logging.INFO, affichage=False)
            return
        for ligne in self.donnees:
            fichier.write(ligne + "\n")
        fichier.close()
        showinfo(Parametres.texte[36][Parametres.langue], Parametres.texte[37][Parametres.langue])
        afficher_log(Parametres.chemin_log, Parametres.texte[37][Parametres.langue], niveau=logging.INFO, affichage=False)
        self.rafraichir()

    def exporter_stats(self):
        """
        Fonction d'ecriture des statistiques
        """
        fichier = filedialog.asksaveasfile (title = Parametres.texte[33][Parametres.langue], defaultextension = ".csv")
        if (fichier == None):
            showinfo(Parametres.texte[34][Parametres.langue], Parametres.texte[35][Parametres.langue])
            afficher_log(Parametres.chemin_log, Parametres.texte[35][Parametres.langue], niveau=logging.INFO, affichage=False)
            return


        for ligne in self.remplissage:
            fichier.write(ligne + "\n")
        fichier.close()
        showinfo(Parametres.texte[36][Parametres.langue], Parametres.texte[38][Parametres.langue])
        afficher_log(Parametres.chemin_log, Parametres.texte[38][Parametres.langue], niveau=logging.INFO, affichage=False)
        self.rafraichir()


#---------------------------------------------------------
#		MENU CALCULER
#---------------------------------------------------------


    def calculer(self):
        """
        Fonction qui calcule les affectations et les affiche sur l'application
        En cas d s sur un fichier, ouvre une fenetre listant les erreurs
        Retourne False si le calcul n'a pas pu etre effectué, True sinon
        """
        try:
            self.succes_calcul = True
            self.err_edt = ""
            self.err_voeux = ""
            self.err_coh = ""

            # creation du ditionnaire ue. si erreurs_ue est different de "" alors
            # il y a des erreurs dans le fichier
            self.dictionnaire_ue,erreurs_ue = recuperer_ue(Parametres.chemin_edt)
            if len(erreurs_ue) > 0 :
            	self.err_edt = erreurs_ue
            	self.succes_calcul = False

            # creation de la liste etudiante. si erreurs_voeux est different de ""
            # alors il y a des erreurs dans le fichier
            self.liste_etudiants,erreurs_voeux = recuperer_etudiants(Parametres.chemin_voeux)
            if len(erreurs_voeux) > 0 :
            	self.err_voeux = erreurs_voeux
            	self.succes_calcul = False

            # s'il y a des erreurs dans au moins un des deux fichiers, arret de la fonction
            if not self.succes_calcul :
            	return

            # regarde la cohérence entre les etudiants et ue
            erreurs_coherence = verification_coherence(self.dictionnaire_ue,self.liste_etudiants)
            if len(erreurs_coherence) > 0 :
            	self.err_coh = erreurs_coherence
            	self.succes_calcul = False
            	return


            # marque les etudiants dont les voeux sont incoherents
            #etudiants_edt_incompatible = marquer_etudiants(self.dictionnaire_ue, self.liste_etudiants)

            # resolution
            chaine = ""
            try:
                chaine += Parametres.texte[44][Parametres.langue]
                res, nb_etu_affectation_incomplete = resoudre(self.dictionnaire_ue, self.liste_etudiants)
            except Exception as e:
                res, nb_etu_affectation_incomplete = resoudre(self.dictionnaire_ue, self.liste_etudiants, contrainte_relachee=True)

            # regarde les affectations incompletes et fait un signalement
            self.insertion_texte_log(Parametres.texte[70][Parametres.langue])
            if nb_etu_affectation_incomplete > 0:
                msg = Parametres.texte[31][Parametres.langue] + str(nb_etu_affectation_incomplete) + \
                    Parametres.texte[32][Parametres.langue]
                self.insertion_texte_log(msg)
                afficher_log(Parametres.chemin_log, msg, niveau=logging.WARNING, affichage=False)
                showwarning(title=Parametres.texte[30][Parametres.langue], message=msg)

			# affichage des resultats
            chaine += res
            self.donnees = chaine.splitlines()
            self.effacer_affichage()
            self.afficher_resultats2(self.donnees)

            # mise à jour des marqueurs
            self.affectation_realisee = True
            self.succes_calcul = True

        except Exception as e:
            showerror(title=Parametres.texte[5][Parametres.langue], message=e)
            self.insertion_texte_log(Parametres.texte[71][Parametres.langue])
            afficher_log(Parametres.chemin_log, traceback.format_exc(), niveau=logging.ERROR, affichage=False)

        self.rafraichir()


    def afficher_remplissage_groupes(self):
        """
        Affiche le remplissage de chaque groupe dans un onglet dedie
        """
        # initialisation
        nb_colonnes = 0
        lignes_a_afficher = []
        stockagePourExport = []
        tmp = ""

        # separation en lignes pour chaque ue en utilisant le dictionnaire des ues
        for nom_ue, ue in self.dictionnaire_ue.items():
            nb_groupes = len(ue.capacites_groupes)
            nb_colonnes = max(nb_colonnes, nb_groupes)
            ligne = [nom_ue]
            tmp = nom_ue + ';'
            for i in range(nb_groupes):
                ligne.append(str(ue.nb_inscrits[i]) + " : " + str(ue.capacites_groupes[i]))
                tmp += str(ue.nb_inscrits[i]) + Parametres.texte[51][Parametres.langue] + str(ue.capacites_groupes[i]) + ";"
            stockagePourExport.append(tmp)
            lignes_a_afficher.append(ligne)

		# nom de chaque colonne, col pour l'export
        nb_colonnes += 1
        colonnes = [Parametres.texte[45][Parametres.langue]]
        col = Parametres.texte[45][Parametres.langue] + ";"
        for i in range(1, nb_colonnes):
            colonnes.append(Parametres.texte[46][Parametres.langue].format(i))
            col += (Parametres.texte[46][Parametres.langue] + ";").format(i)

        stockagePourExport.insert(0, col)
        self.remplissage = stockagePourExport # Utile pour l'export sur un csv

		# initialisation de l'arbre
        self.arbre_remplissage = ttk.Treeview(self.onglet_remplissage_groupes, columns=(colonnes), height=400,
                    yscrollcommand=self.scrollbary_remplissage.set, xscrollcommand=self.scrollbarx_remplissage.set,
                    show="headings", selectmode="extended")

		# creation des scrollbars
        self.scrollbary_remplissage.config(command = self.arbre_remplissage.yview)
        self.scrollbarx_remplissage.config(command = self.arbre_remplissage.xview)
        self.scrollbary_remplissage.pack(side=RIGHT, fill=Y)
        self.scrollbarx_remplissage.pack(side=BOTTOM, fill=X)

		# creation des colonnes dabs l'arbre
        for i in range(nb_colonnes):
            self.arbre_remplissage.heading('#'+str(i+1), text=colonnes[i], anchor=W)
            self.arbre_remplissage.column('#'+str(i+1), stretch=NO, minwidth=0)

        self.arbre_remplissage.pack()

		# remplissage des lignes de l'abre
        i = 0
        for ligne in lignes_a_afficher:
            self.arbre_remplissage.insert("", index=i, values=(ligne))
            i+=1

        self.remplissage_realise = True
        showinfo(Parametres.texte[39][Parametres.langue], Parametres.texte[40][Parametres.langue])
        afficher_log(Parametres.chemin_log, Parametres.texte[40][Parametres.langue], niveau=logging.INFO, affichage=False)
        self.rafraichir()


    def afficher_resultats(self, nom_fichier):
        """
        Affiche les affectations (contenues dans un fichier) dans un onglet dedie
        """

        # recupere le nom des colonnes
        nb_colonnes = nb_colonnes_csv(nom_fichier)
        colonnes = [ Parametres.texte[47][Parametres.langue], Parametres.texte[48][Parametres.langue], Parametres.texte[49][Parametres.langue] ]
        for i in range(1, nb_colonnes+1 - len(colonnes)):
            colonnes.append(Parametres.texte[50][Parametres.langue] + str(i))

	    # initialisation de l'arbre
        self.arbre_affectations = ttk.Treeview(self.onglet_affectations, columns=(colonnes), height=400,
                    yscrollcommand=self.scrollbary_affectations.set, xscrollcommand=self.scrollbarx_affectations.set,
                    show="headings", selectmode="extended")

        # creation des scrollbars
        self.scrollbary_affectations.config(command = self.arbre_affectations.yview)
        self.scrollbarx_affectations.config(command = self.arbre_affectations.xview)
        self.scrollbary_affectations.pack(side=RIGHT, fill=Y)
        self.scrollbarx_affectations.pack(side=BOTTOM, fill=X)

        # noms des colonnes dans l'arbre
        for i in range(nb_colonnes):
            self.arbre_affectations.heading('#'+str(i+1), text=colonnes[i], anchor=W)
            self.arbre_affectations.column('#'+str(i+1), stretch=NO, minwidth=0)

        self.arbre_affectations.pack()
        i = -1

	# remplissage des lignes de l'arbre
        with open(nom_fichier, 'r') as f:
            obj = csv.reader(f, delimiter=';')
            for ligne in obj:
                if i == -1:
                    i = 0
                    continue

                if Parametres.texte[52][Parametres.langue][1:] in ligne:
                    self.arbre_affectations.insert("", index=i, values=(ligne), tag="TAG_INCOMPLET")
                elif Parametres.texte[53][Parametres.langue][1:] in ligne:
                    self.arbre_affectations.insert("", index=i, values=(ligne), tag="TAG_CHEVAUCHEMENT")

                else:
                    self.arbre_affectations.insert("", index=i, values=(ligne))
                i+=1

        # tag visuel dans l'arbre pour l'affichage
        self.arbre_affectations.tag_configure ("TAG_INCOMPLET" , foreground = "white" , background = "red")
        self.arbre_affectations.tag_configure ("TAG_CHEVAUCHEMENT" , foreground = "white" , background = "purple")

        self.rafraichir()


    def afficher_resultats2(self, donnees):
        """
        Affiche les affectations (recues sous forme de chaine de caractères) dans un onglet dedie
        """
        # recupere le nom des colonnes
        nb_colonnes = nb_colonnes_csv(donnees, True)
        colonnes = [ Parametres.texte[47][Parametres.langue], Parametres.texte[48][Parametres.langue], Parametres.texte[49][Parametres.langue] ]
        for i in range(1, nb_colonnes+1 - len(colonnes)):
            colonnes.append(Parametres.texte[50][Parametres.langue] + str(i))

	# initialisation de l'arbre
        self.arbre_affectations = ttk.Treeview(self.onglet_affectations, columns=(colonnes), height=400,
                    yscrollcommand=self.scrollbary_affectations.set, xscrollcommand=self.scrollbarx_affectations.set,
                    show="headings", selectmode="extended")

	# creation des scrollbars
        self.scrollbary_affectations.config(command = self.arbre_affectations.yview)
        self.scrollbarx_affectations.config(command = self.arbre_affectations.xview)
        self.scrollbary_affectations.pack(side=RIGHT, fill=Y)
        self.scrollbarx_affectations.pack(side=BOTTOM, fill=X)

	# noms des colonnes dans l'arbre
        for i in range(nb_colonnes):
            self.arbre_affectations.heading('#'+str(i+1), text=colonnes[i], anchor=W)
            self.arbre_affectations.column('#'+str(i+1), stretch=NO, minwidth=0)

        self.arbre_affectations.pack()
        i = -1

	# remplissage des lignes de l'arbre
        obj = csv.reader(donnees, delimiter=';')
        for ligne in obj:
            if i == -1:
                i = 0
                continue
            if Parametres.texte[52][Parametres.langue][1:] in ligne:
                self.arbre_affectations.insert("", index=i, values=(ligne), tag="TAG_INCOMPLET")
            elif Parametres.texte[53][Parametres.langue][1:] in ligne:
                self.arbre_affectations.insert("", index=i, values=(ligne), tag="TAG_CHEVAUCHEMENT")
            else:
                self.arbre_affectations.insert("", index=i, values=(ligne))
            i+=1

        # tag visuel dans l'arbre pour l'affichage
        self.arbre_affectations.tag_configure ("TAG_INCOMPLET" , foreground = "white" , background = "red")
        self.arbre_affectations.tag_configure ("TAG_CHEVAUCHEMENT" , foreground = "white" , background = "purple")

        self.rafraichir()


#---------------------------------------------------------
#		RAFRAICHISSEMENT DE LA FENETRE
#---------------------------------------------------------

    def griser_menu(self, numero, etat=True, menu=1):
        """
        Griser le menu dont le numero est passe en argument
        Parametres :
        	etat   : True -> grise , False -> degrise
        	numero : numero de l'entrée à griser dans le menu
        	menu   : 1 pour menu1, 2 pour menu2
        """
        if etat:
            if (menu == 1):
                self.menu1.entryconfigure(numero, state=DISABLED)
            else:
                self.menu2.entryconfigure(numero, state=DISABLED) # pas besoin de tester menu3, aucun bouton grisable
        else:
            if (menu == 1):
                self.menu1.entryconfigure(numero, state=NORMAL)
            else:
                self.menu2.entryconfigure(numero, state=NORMAL) # pareil


    def griser_calculer(self, etat=True):
        """
        Grise le menu export
        Parametres :
        	etat : True -> grise , False -> degrise
        """
        bouton = 0
        self.griser_menu(bouton, etat, 2)


    def griser_exporter_resultats(self, etat=True):
        """
        Grise le menu d'export des résultats
        Parametres :
        	etat : True -> grise , False -> degrise
        """
        bouton = 2
        if self.mode_testeur:
            bouton = 1
        self.griser_menu(bouton, etat)

    def griser_exporter_statistiques(self, etat=True):
        """
        Grise le menu d'export des résultats
        Parametres :
        	etat : True -> grise , False -> degrise
        """
        bouton = 3
        if self.mode_testeur:
            bouton = 2
        self.griser_menu(bouton, etat)


    def griser_afficher_remplissage(self, etat=True):
        """
        Grise le menu export d'affichage du remplissage des groupes
        Parametres :
        	etat : True -> grise , False -> degrise
        """
        bouton = 1
        self.griser_menu(bouton, etat, 2)


    def rafraichir(self, changementMode=False):
        """
        Actualise l'etat de la fenetre. Si on indique qu'un changement de mode (testeur) a été
        effectué, il faut par conséquent remplacer des boutons
        Parametres :
        	changementMode : True si un changement de mode a été effectué
        """

        if Parametres.chemin_edt == "" or Parametres.chemin_voeux == "":
            self.griser_calculer()
        else:
            self.griser_calculer(False)

        if changementMode:
            self.chargerMenuFichier(recharger=True)
            self.chargerMenuAide(recharger=True)

        if self.affectation_realisee:
            self.griser_afficher_remplissage(False)
            self.griser_exporter_resultats(False)
        else:
            self.griser_exporter_resultats()
            self.griser_afficher_remplissage()

        if self.remplissage_realise:
            self.griser_exporter_statistiques(False)
        else :
            self.griser_exporter_statistiques()


#---------------------------------------------------------
#		AFFICHAGE DES ERREURS
#---------------------------------------------------------


    def afficher_liste_erreur_newWindows(self, messages,nom_fichier,entete="ligne"):
    	"""
    	ouvre une nouvelle fenetre affichant les erreurs du fichier
    	Parametres :
    		messages    : liste de tuples (-entete de la ligne-, message de l'erreur)
    		nom_fichier : String nom du fichier où se trouve l'erreur
    		entete	    : si "ligne" affiche le numero de la ligne, sinon on peut personnaliser
    	L'entete permet de savoir comment ont été trié les messages d'erreurs (par ligne, par etudiant ...)
    	"""
    	nouvelleFenetre = Toplevel()
    	nouvelleFenetre.title(Parametres.texte[59][Parametres.langue])
    	nouvelleFenetre.focus_set()
    	nouvelleFenetre.geometry("400x500")

    	display = Label(nouvelleFenetre).pack()

    	scrollbarErr = Scrollbar(nouvelleFenetre,orient='vertical')
    	scrollbarErr.pack(side=RIGHT,fill=Y)

    	display = Text(nouvelleFenetre, yscrollcommand=scrollbarErr.set)

    	for nom in nom_fichier :
    		display.insert(END,Parametres.texte[60][Parametres.langue]+nom+"\n\n")


    	if entete == "etudiant" :
    		for (i,message) in messages:
    			display.insert(END,"\n"+
    				Parametres.texte[611][Parametres.langue]+i+"\n"+
    				message)

    	else :
    		for (i,message) in messages:
    			display.insert(END,"\n"+
    				Parametres.texte[61][Parametres.langue]+str(i)+"\n"+
    				message)

    	scrollbarErr.config(command = display.yview)
    	display.pack()


    	boutonBack = Button(nouvelleFenetre, text= Parametres.texte[62][Parametres.langue],
    		command=lambda:nouvelleFenetre.destroy()).pack()


#---------------------------------------------------------
#		ONGLET POUR LA TRACE/LOG
#---------------------------------------------------------

    def afficher_onglet_log(self):
    	"""
    	affichage de la trace (log), c'est à dire les fichiers chargés et les actions prises
    	"""

    	# bouton pour nettoyer la seconde trace (la trace modifiable)
    	self.buttonRafraichir = Button(self.onglet_informations,
    			text="Clear",
    			command=lambda:self.effacer_texte_onglet_log())

    	# trace qui affiche les fichiers chargés, non modifiable manuellement
    	# par l'utilisateur
    	self.logFile = Text(self.onglet_informations,
    		height = 8,
    		font=("Helvetica",12))
    	self.vsb = Scrollbar(self.onglet_informations)
    	self.vsb.config(command=self.logFile.yview)
    	self.logFile.config(yscrollcommand=self.vsb.set)
    	self.logFile.grid(row=0, column=0)
    	self.vsb.grid(row=0, column=1, sticky='ns')
    	self.logFile.config(state=DISABLED)

    	# trace qui affiche les actions effectuées par l'utilisateur, peut etre
    	# modifié manuellement par l'utilisateur
    	self.logAction = Text(self.onglet_informations,
    		font=("Helvetica",12))
    	self.vsb2 = Scrollbar(self.onglet_informations)
    	self.vsb2.config(command=self.logAction.yview)
    	self.logAction.config(yscrollcommand=self.vsb2.set)
    	self.logAction.grid(row=1, column=0)
    	self.vsb2.grid(row=1, column=1, sticky='ns')

    	# placement du bouton
    	self.buttonRafraichir.grid(row=2,column=0,columnspan=2)

    	# affiche les fichiers chargés
    	self.insertion_texte_fichier()


    def insertion_texte_log(self,texte):
    	"""
    	insertion d'un texte via une fonction dans la seconde trace
    	Paametres :
    		texte : String texte à ecrire dans la trace
    	"""
    	self.logAction.insert("end",texte+"\n")


    def insertion_texte_fichier(self):
    	"""
    	ecrit les noms des fichiers chargés dans la premiere trace
    	"""
    	edt = Parametres.texte[64][Parametres.langue]+ " : "+Parametres.chemin_edt
    	voeux = Parametres.texte[65][Parametres.langue]+" : "+Parametres.chemin_voeux
    	self.logFile.config(state=NORMAL)
    	self.logFile.delete("1.0","end")
    	self.logFile.insert("end",edt+"\n")
    	self.logFile.insert("end","\n"+voeux)
    	self.logFile.config(state=DISABLED)


    def afficher_erreurs_log(self,messages,nom_fichier,entete="ligne"):
    	"""
    	affiche les erreurs se trouvant dans le fichier voeux/EDT
    	Parametres :
    		messages    : liste de tuples (-entete du message-, message de l'erreur)
    		nom_fichier : String nom du fichier où se trouve l'erreur
    		entete      : Par default affiche le numero de la ligne comme message d'entete, sinon
    				on peut personnaliser
    	L'entete permet de savoir comment ont été trié les messages d'erreurs (par ligne, par etudiant ...)
    	et modifier l'affichage en consequence
    	"""
    	self.insertion_texte_log("===============================")
    	if entete == "etudiant" :
	    	for (i,message) in messages:
	    		self.insertion_texte_log(Parametres.texte[611][Parametres.langue]+i+"\n"+message)
    	else :
    		self.insertion_texte_log(Parametres.texte[60][Parametres.langue]+nom_fichier)
    		self.insertion_texte_log("===============================")
	    	for (i,message) in messages:
	    		self.insertion_texte_log(Parametres.texte[61][Parametres.langue]+str(i)+"\n"+message)
    	self.insertion_texte_log("===============================")


    def effacer_texte_onglet_log(self):
    	"""
    	efface le texte se trouvant dans la seconde trace
    	"""
    	self.logAction.delete("1.0","end")

#---------------------------------------------------------
#		NETTOYAGE DES ONGLETS
#---------------------------------------------------------

    def efface_onglet_log(self):
    	"""
    	efface tout les elements se trouvant dans l'onglet log
    	"""
    	for widget in self.onglet_informations.winfo_children():
    		widget.destroy()


    def efface_remplissage_affectations(self):
        """
        efface l'affichage se trouvant dans l'onglet des affectations
        """
        if self.affectation_realisee :
            for widget in self.onglet_affectations.winfo_children():
                widget.destroy()
        self.affectation_realisee = False

    def efface_remplissage_groupes(self):
    	"""
    	efface l'affichage se trouvant dans l'onglet de remplissage de groupes
    	"""
    	if self.remplissage_realise :
    	    for widget in self.onglet_remplissage_groupes.winfo_children():
    	        widget.destroy()
    	self.remplissage_realise = False
