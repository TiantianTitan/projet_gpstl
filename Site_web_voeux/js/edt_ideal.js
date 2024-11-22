function chooseIDEAL(form) { //le parametre form ne sera pas utilise
    var ideal = $('input[name="edt"]:checked').val(); //recuperation de l'edt ideal choisi
    ideal = ideal.replace(/,/g, "%2C");
    window.location.href = "validation_edt_ideal.php?ideal=" + ideal;
    //point de vulnerabilite ; l'url est en clair : on peut la modifier et ainsi modifier
    //les choix d'ues (perte de cohérence avec les regles de choix d'ues)
    //Peut-etre passer a post apres resolution des problemes lies au double post+redicrection  
}