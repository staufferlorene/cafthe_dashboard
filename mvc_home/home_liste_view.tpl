<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../img/icon.png" />

    <title>CafThé - Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/chart.css">

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body>

    <!-- Wrapper principal -->
    <div id="wrapper">

        {include file="template/layout_sidebar.tpl"}

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

            {include file="template/layout_topbar.tpl"}

        <!-- Begin Page Content -->
        <div class="container-fluid">

            {if isset($erreur)}
                <div class="mb-4" style="color:red;">Erreur : {$erreur}</div>
            {/if}

            <!-- Graphique Chart.js -->
            <div class="row">

                <!-- Produits les plus vendus -->
                <div class="col-md-6 mb-4">
                    <div class="chart-container p-3 bg-white shadow rounded">
                        <canvas id="graphVentesParProduits"></canvas>
                    </div>
                </div>

                <!-- Catégorie la plus vendue -->
                <div class="col-md-6 mb-4">
                    <div class="chart-container p-3 bg-white shadow rounded">
                        <canvas id="graphVentesParCategories"></canvas>
                    </div>
                </div>

                <!-- Vente par mois -->
                <div class="col-md-6 mb-4">
                    <div class="chart-container p-3 bg-white shadow rounded">
                        <canvas id="graphVentesParMois"></canvas>
                    </div>
                </div>

                <!-- CA par vendeur -->
                <div class="col-md-6 mb-4">
                    <div class="chart-container p-3 bg-white shadow rounded">
                        <canvas id="graphCAParVendeur"></canvas>
                    </div>
                </div>

            </div>

        </div>
            </div>
        </div>
        </div>

        <!-- Import Chart.js via cdn -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Pour partager données issues requête BDD -->
        <script>
            window.statsData = {
                ventesParProduits: {$ventesParProduits|json_encode},
                ventesParCategories: {$ventesParCategories|json_encode},
                ventesParMois: {$ventesParMois|json_encode},
                caParVendeur: {$caParVendeur|json_encode}
            }
        </script>

        <!-- Fichiers js pour afficher les graphiques -->
        <script src="mvc_home/js/vente-par-produit.js"></script>
        <script src="mvc_home/js/vente-par-categorie.js"></script>
        <script src="mvc_home/js/vente-par-mois.js"></script>
        <script src="mvc_home/js/ca-par-vendeur.js"></script>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/datatables-demo.js"></script>

</body>

</html>