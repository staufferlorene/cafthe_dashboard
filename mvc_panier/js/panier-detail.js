//////////////////////////////////////////
// CALCUL DES PRIX TOTAL TTC ET HT
// POUR CHAQUE PRODUIT
//////////////////////////////////////////


// Ciblage des inputs portant la classe "quantite"
const saisie = document.querySelectorAll(".quantite");

// Pour chaque input écoute de l’événement, puis exécution du code de la fonction
saisie.forEach(e => {
    e.addEventListener("input", function () {
        // Récupération de la quantité saisie
        const quantite = parseFloat(this.value);

        // Récupération des prix unitaire (stocké dans data-prixttc + data-prixht)
        const prixUnitaireTTC = parseFloat(this.dataset.prixttc);
        const prixUnitaireHT = parseFloat(this.dataset.prixht);

        // Calcul des prix TTC + HT pour chaque produit
        const totalTTC = quantite * prixUnitaireTTC;
        const totalHT = quantite * prixUnitaireHT;

        // On trouve les champs ".prix-total-ttc-produit" + "prix-total-ht-produit" dans la même ligne <tr>
        const champTotalTTC = this.closest("tr").querySelector(".prix-total-ttc-produit");
        const champTotalHT = this.closest("tr").querySelector(".prix-total-ht-produit");

        // Insertion des prix TTC + HT pour chaque produit dans leur champ respectif
        champTotalTTC.textContent = totalTTC.toFixed(2) + " €";
        champTotalHT.textContent = totalHT.toFixed(2) + " €";


        /////////////////////////////////////////////////////////
        // MODIFICATION QUANTITE DANS VARIABLE DE SESSION (pour
        // recalcul des montants totaux HT, TVA et TTC du panier)
        /////////////////////////////////////////////////////////


        // Récupération de l'id
        const id = this.dataset.id;

        // Construction du FormData avec ses données
        const formData = new FormData();
        formData.append('id', id);
        formData.append('quantite', quantite);

        // Envoi AJAX vers le contrôleur
        fetch('index.php?action=update_panier', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) throw new Error('Erreur');
                return response.text();
            })
            .catch(error => {
                console.error('Erreur lors de la modification :', error);
            })

        // rafraîchis la page pour mettre à jour le montant TTC avec le montant calculé côté back
        location.reload();
    });
});

//////////////////////////////////////////
//////////////////////////////////////////

// ALERTE SI QUANTITE = 0

//////////////////////////////////////////
//////////////////////////////////////////

const btnValidePanier = document.getElementById('valider-panier');

if (btnValidePanier) {
    btnValidePanier.addEventListener('click', function (event) {

        // Récupération de la quantité dans les inputs
        const quantite = document.querySelectorAll('.quantite');

        // Blocage si panier vide
        if(quantite.length === 0) {
            // empêche la redirection
            event.preventDefault();
            alert('Le panier est vide');
            return
        }

        for (const input of quantite) {
            // Récupération de la quantité saisie
            const valeur = parseFloat(input.value);

            // Blocage si quantité = 0
            if(valeur === 0) {
                // empêche la redirection
                event.preventDefault();
                alert('Indiquer une quantité valide');
                return;
            }
        }
    });
}