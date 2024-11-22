1. Site de voeux du M1 (S1 ou S2)

- mise à jour des bibliothèques javascript
- mise à jour du visuel
- nettoyage du code (avant covid on avait une autre manière de faire, il y a du déchet dans le code)
- nettoyage de la base de données
- attention aux réécritures (cf 2.)
- gérer un répertoire avec toutes les info qui peuvent changer : soit des fichiers séparés du reste,
    soit toutes dans un fichier puis script python va les copier aux bon endroits


2. Simplification de la base de données

- ne garder que ListeEtudiants et NumTraduction
- faire en sorte que si un étudiants clique 2 fois, il n'y ait pas 2 lignes en BDD.
- il y avait à moment donné du code empêchant un étudiant de se connecter s'il avait déjà fait ses voeux, c'est trop restrictif, 
    mais avant de réinscrire ses voeux en BDD, s'assurer que l'autre ligne est effacée, et ne changer que si les voeux changent...


3. Outil d'affichage des créneaux d'UE

dans fichier python : variable dictionnaire edt.
- réfléchir à une autre structure de données JSON ? ou pas
- script python avec fichier en entrée plutôt que directement la variable edt

- élaboration d'un site web de visualisation de l'EDT, ressemblant au calendrier du master
    - liste des UE à gauche qu'on peut sélectionner ou pas
    - affichage ressemblant plutôt à EDT_M1-S1_2024-2025.pdf (pour que ça reste lisible)
    - choix affichage jour en colonne ou en ligne (selon l'utilisateur)

- simplification refactoring du fichier (tout pourri) 


4. Outil de résolution des contraintes

- Gurobi vs GLPK : pourquoi des résultats bien différents sur les mêmes voeux
    (les contraintes ne sont pas tout à fait encodées pareil, cf mémoire  rapport_Projet_GLPK.pdf )
- écriture de EDT24_M1S1_v4.csv directement depuis fichier python cf 3.
- en sortie en plus de resultats_v4.csv, construire envoi_mails.csv et envoi_DBUFR_a_envoyer.csv