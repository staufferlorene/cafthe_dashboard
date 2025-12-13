// Récupération des données envoyées en JSON depuis le fichier .tpl
const dataMois = window.statsData.ventesParMois;
console.log(dataMois)

// Récupérer toutes les données du JSON en fonction de la clé donnée
const dataLabelsMois = dataMois.map(e => e.mois);
const dataDataMois = dataMois.map(e => e.total_mois);

// Graphique
const graphMois = document.getElementById('graphVentesParMois');

new Chart(graphMois, {
    type: 'line',
    data: {
        labels: dataLabelsMois,
        datasets: [{
            label: 'Montant vendu',
            data: dataDataMois,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Chiffre d\'affaire par mois'
            }
        }
    },
});
