// Récupération des données envoyées en JSON depuis le fichier .tpl
const dataCA = window.statsData.caParVendeur;
console.log(dataCA)

// Récupérer toutes les données du JSON en fonction de la clé donnée
const dataLabelsCA = dataCA.map(e => `${e.Nom_vendeur} ${e.Prenom_vendeur}`);
const dataDataCA = dataCA.map(e => e.ca_total);

// Graphique
const graphCA = document.getElementById('graphCAParVendeur');

new Chart(graphCA, {
    type: 'bar',
    data: {
        labels: dataLabelsCA,
        datasets: [{
            label: 'Montant total',
            data: dataDataCA,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Chiffre d\'affaire par vendeur'
            }
        }
    },
});
