// Récupération des données envoyées en JSON depuis le fichier .tpl
const data = window.statsData.ventesParProduits;

// Récupérer toutes les données du JSON en fonction de la clé donnée
const dataLabels = data.map(e => e.Nom_produit);
const dataData = data.map(e => e.qte_totale_vendu);

// Graphique
const graph = document.getElementById('graphVentesParProduits');

new Chart(graph, {
    type: 'pie',
    data: {
        labels: dataLabels,
        datasets: [{
            label: 'Quantité vendu',
            data: dataData,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Top 6 des produits les plus vendus'
            }
        }
    },
});