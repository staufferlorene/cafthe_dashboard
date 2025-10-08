//////////////////////////////////////////
//////////////////////////////////////////

// CALCUL DU PRIX TTC POUR CHAQUE PRODUIT

//////////////////////////////////////////
//////////////////////////////////////////


// Ciblage des inputs portant la classe "quantite"
const saisie = document.querySelectorAll(".quantite");

// Pour chaque input écoute de l’événement, puis exécution du code de la fonction
saisie.forEach(e => {
    e.addEventListener("input", function () {
        // Récupération de la quantité saisie
        const quantite = parseFloat(this.value);

        // Récupération du prix unitaire (stocké dans data-prix)
        const prixUnitaire = parseFloat(this.dataset.prix);

        // Calcul du prix TTC
        const total = quantite * prixUnitaire;

        // On trouve le champ ".prix-total" dans la même ligne <tr>
        const champTotal = this.closest("tr").querySelector(".prix-total");

        // Insertion du prix TTC dans le champ
        champTotal.textContent = total.toFixed(2) + " €";
    });
});

//////////////////////////////////////////
//////////////////////////////////////////

// CALCUL DU MONTANT TTC DU PANIER

//////////////////////////////////////////
//////////////////////////////////////////


// Ciblage des boutons portant la classe "add-panier"
const ajoutPanier = document.querySelectorAll('.add-panier');

// Execution du code à chaque clic sur "ajouter au panier"
ajoutPanier.forEach(bouton => {
    bouton.addEventListener('click', function (event) {

        let totalPanierTtc = 0;

        // Récupération des prix total de chaque produit et addition
        document.querySelectorAll('.prix-total').forEach(e => {
            totalPanierTtc += parseFloat(e.textContent);
        });

        // Ciblage du champ portant l'id "montant-total-ttc" et insertion du prix TTC du panier dedans
        document.getElementById('montant-total-ttc').textContent = totalPanierTtc.toFixed(2) + ' €';
    });
});