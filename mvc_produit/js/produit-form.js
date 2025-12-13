// //////////////////////////////////////////////////////////////////
// Affichage ou mofification de la tva quand la catégorie est changée
// //////////////////////////////////////////////////////////////////
function change(select) {

    // Récupération de la catégorie via le "select" et son option selectedIndex
    const categorie = select.options[select.selectedIndex];

    // Récupération du taux de tva (stocké dans data-tauxtva)
    const tva = parseFloat(categorie.dataset.tauxtva);

    // Ciblage du champ tva et envoi du taux récupéré
    document.getElementById('tva').value = tva;

    // Appel de la fonction pour calculer TTC si prix HT a été saisi avant de choisir la catégorie
    calculTTC();
}

// //////////////////////////////////////////////////////////////////
// Calcul du TTC en fonction du HT
// //////////////////////////////////////////////////////////////////

// Ciblage du champ portant l'id "prix_ht" puis écoute et éxécution de la fonction "calculTTC"
document.getElementById("prix_ht").addEventListener('input', calculTTC);

function calculTTC() {

    // Récupération du montant saisi
    const ht = parseFloat(document.getElementById("prix_ht").value);

    // Récupération du taux de tva (stocké dans data-tauxtva)
    const tva = parseFloat(document.getElementById("tva").value);

    // Calcul du TTC
    const ttc = ht * (1 + tva/100);

    // Envoi du TTC dans le champ correspondant
    document.getElementById('prix_ttc').value = ttc.toFixed(2);
}


