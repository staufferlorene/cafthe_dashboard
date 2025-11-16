//////////////////////////////////////////
//////////////////////////////////////////

// CALCUL DU PRIX TTC POUR CHAQUE PRODUIT

//////////////////////////////////////////
//////////////////////////////////////////


// Ciblage des inputs portant la classe "quantite"
const saisies = document.querySelectorAll(".quantite");

// Pour chaque input écoute de l’événement, puis exécution du code de la fonction
saisies.forEach(saisie => {
    saisie.addEventListener("input", function () {
        // Récupération de la quantité saisie
        const quantite = parseFloat(this.value);

        // Récupération du prix unitaire (stocké dans data-prixttc)
        const prixUnitaire = parseFloat(this.dataset.prixttc);

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

// CALCUL DU MONTANT TTC DU PANIER +
// AJOUT PANIER DANS VARIABLE DE SESSION

//////////////////////////////////////////
//////////////////////////////////////////


// Ciblage des boutons portant la classe "add-panier"
const ajoutPanier = document.querySelectorAll('.add-panier');

// Execution du code à chaque clic sur "ajouter au panier"
ajoutPanier.forEach(bouton => {
    bouton.addEventListener('click', function (event) {

        //    ///////////////////
        // ENVOI EN VARIABLE DE SESSION :
        //    ///////////////////

        // Récupération des champs <tr> pour accéder aux <input>
        const champ = this.closest('tr');

        // Récupération des valeurs des <input>
        const id = champ.querySelector('input[name="id"]').value;
        const nom = champ.querySelector('input[name="nom"]').value;
        const prixht = champ.querySelector('input[name="prixht"]').value;
        const prixttc = champ.querySelector('input[name="prixttc"]').value;
        const quantite = champ.querySelector('input[name="quantite"]').value;

        // Si quantité est vide affiche une alerte
        if (quantite === "") {
            alert('Veuillez renseigner une quantité');
        }

        // Construction du FormData avec ses données
        const formData = new FormData();
        formData.append('id', id);
        formData.append('nom', nom);
        formData.append('prixht', prixht);
        formData.append('prixttc', prixttc);
        formData.append('quantite', quantite);

        // Envoi AJAX vers le contrôleur
        fetch('index.php?action=add_panier', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) throw new Error('Erreur');
                return response.text();
            })
            .catch(error => {
                console.error('Erreur lors de l\'ajout :', error);
            })

        // rafraîchis la page pour mettre à jour le montant TTC avec le montant calculé côté back
        location.reload();

        //    ///////////////////
        // REMISE A ZERO CHAMPS QUANTITE ET PRIX TTC :
        //    ///////////////////

        champ.querySelector('input[name="quantite"]').value = '';
        champ.querySelector('.prix-total').textContent = '0.00 €';
    });
});

    //////////////////////////////////////////
    //////////////////////////////////////////

    // AFFICHAGE ALERTE SI PANIER EST VIDE
    // ET QU'ON VEUT Y ACCEDER

    //////////////////////////////////////////
    //////////////////////////////////////////

    // Ciblage bouton "Accéder au panier"
    const btnViewPanier = document.getElementById('btn-access-panier');

    if (btnViewPanier) {
        btnViewPanier.addEventListener('click', function (event) {

            // Récupère une string avec le texte du total affiché (nombre + "€")
            const totalText = document.getElementById('montant-total-ttc').textContent;

            // Convertit en nombre et remplace "€"
            const totalValue = parseFloat(totalText.replace('€', ''));

            // Vérifie si le panier est vide
            if (totalValue === 0) {
                event.preventDefault();
                alert('Votre panier est vide');
            }
        });
    }