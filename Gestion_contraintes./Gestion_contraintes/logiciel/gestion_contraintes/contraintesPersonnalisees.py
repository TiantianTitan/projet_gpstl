#!/usr/bin/env python3
# -*- coding: utf-8 -*-

from parametres import Parametres
from erreurs import *
from outils import *
from etudiant import Etudiant
from ue import UE
from traitement import *
import gurobipy as gp
from gurobipy import GRB
import solveur as solveur

def ajouter_contraintes(modele, vars_x, vars_y, vars_x_par_ue,
                        vars_creneaux_incompatibles, vars_cours_par_ue, vars_td_tme_par_ue):
    """Fonction appelée par le solveur de contrainte au moment de l'ajout des
    contraintes au modèle"""
    ajouter_containtes_M1_S2(modele, vars_x, vars_y, vars_x_par_ue,
                        vars_creneaux_incompatibles, vars_cours_par_ue, vars_td_tme_par_ue)
    return

#########################

def ajouter_containtes_M1_S1(modele, vars_x, vars_y, vars_x_par_ue,
                        vars_creneaux_incompatibles, vars_cours_par_ue, vars_td_tme_par_ue):
    """Contraintes particulières au premier semestre de M1"""
    return

def ajouter_containtes_M1_S2(modele, vars_x, vars_y, vars_x_par_ue,
                        vars_creneaux_incompatibles, vars_cours_par_ue, vars_td_tme_par_ue):
    """Contraintes particulières au second semestre de M1"""
    ajouter_contrainte_speciale_ml_mll(modele, vars_y)
    ajouter_contrainte_speciale_multi_multi_en(modele, vars_y)
    ajouter_contrainte_speciale_AND_ml_iamsi(modele, vars_y)
    #ajouter_contrainte_speciale_IMA_3ECTS(modele, vars_y)	# erreur déclenché avec
    ajouter_contrainte_speciale_SFPN_isec_flag(modele, vars_y)
    return

#########################


def ajouter_contrainte_speciale_ml_mll(modele, vars_y):
    """Un étudiant ne peut pas etre inscrit en ml et en mll"""
    for etu, variables in vars_y.items():
        y_ml = solveur.chercher_variable_par_ue(variables, "ml")
        y_mll = solveur.chercher_variable_par_ue(variables, "mll")
        if (y_ml and y_mll):
            modele.addConstr(gp.quicksum([y_ml, y_mll]) <= 1)

def ajouter_contrainte_speciale_multi_multi_en(modele, vars_y):
    """Un étudiant ne peut pas etre inscrit en ml et en mll"""
    for etu, variables in vars_y.items():
        y_multi = solveur.chercher_variable_par_ue(variables, "multi")
        y_multi_en = solveur.chercher_variable_par_ue(variables, "multi_en")
        if (y_multi and y_multi_en):
            modele.addConstr(gp.quicksum([y_multi, y_multi_en]) <= 1)

def ajouter_contrainte_speciale_AND_ml_iamsi(modele, vars_y):
    """Un étudiant Androide ne peut pas etre inscrit en ml et en iamsi"""
    for etu, variables in vars_y.items():
        if (etu.parcours == "ANDROIDE"):
            y_ml = solveur.chercher_variable_par_ue(variables, "ml")
            y_iamsi = solveur.chercher_variable_par_ue(variables, "iamsi")
            if (y_ml and y_iamsi):
                modele.addConstr(gp.quicksum([y_ml, y_iamsi]) <= 1)

def ajouter_contrainte_speciale_SFPN_isec_flag(modele, vars_y):
    """Un étudiant SFPN doit être inscrit en isec et en flag"""
    for etu, variables in vars_y.items():
        if (etu.parcours == "SFPN"):
            y_isec = solveur.chercher_variable_par_ue(variables, "isec")
            if (y_isec):
                modele.addConstr(gp.quicksum([y_isec]) == 1)
            y_flag = solveur.chercher_variable_par_ue(variables, "flag")
            if (y_flag):
                modele.addConstr(gp.quicksum([y_flag]) == 1)

def ajouter_contrainte_speciale_IMA_3ECTS(modele, vars_y):
    """Un étudiant IMA doit être inscrit à 1 UE à 3ECTS"""
    for etu, variables in vars_y.items():
        if (etu.parcours == "IMA"):
            ### je ne pense pas que ce soit fonctionnel : A VERIFIER
            ### j'ai vérifié, ce n'est pas fonctionnel
            y_cge = solveur.chercher_variable_par_ue(variables, "cge")
            y_ra = solveur.chercher_variable_par_ue(variables, "ra")
            y_mll = solveur.chercher_variable_par_ue(variables, "mll")
            y_anum = solveur.chercher_variable_par_ue(variables, "anum")
            S = []
            for y in [y_cge, y_ra, y_mll, y_anum]:
                if y:
                    S.append(y)

            modele.addConstr(gp.quicksum(S) == 1)
