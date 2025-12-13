// Récupération des données envoyées en JSON depuis le fichier .tpl
const dataCategorie = window.statsData.ventesParCategories;

// Récupérer toutes les données du JSON en fonction de la clé donnée
const dataLabelsCategorie = dataCategorie.map(e => e.Nom_categorie);
const dataDataCategorie = dataCategorie.map(e => e.montant_total);

// Graphique
const graphCategorie = document.getElementById('graphVentesParCategories');

new Chart(graphCategorie, {
    type: 'pie',
    data: {
        labels: dataLabelsCategorie,
        datasets: [{
            label: 'Montant vendu',
            data: dataDataCategorie,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Ventes par catégorie'
            }
        }
    },
});
