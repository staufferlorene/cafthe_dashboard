<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Gestion des produits</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h4 class="m-0 font-weight-bold text-primary">Liste des produits</h4>
                </div>
                <div class="mt-3 pl-3">
                    <a href="index.php?action=add_produit" class="btn btn-secondary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fa-solid fa-plus"></i>
                                        </span>
                        <span class="text">Ajouter un produit</span>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                       <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">

                            <thead>
                            <tr>
                                <th>ID produits</th>
                                <th>Catégorie</th>
                                <th>Nom</th>
                                <th>Prix TTC</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>ID produits</th>
                                <th>Catégorie</th>
                                <th>Nom</th>
                                <th>Prix TTC</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            {foreach from=$produit item=produit}
                                <tr>
                                    <td>{$produit.Id_produit|escape}</td>
                                    <td>{$produit.Nom_categorie|escape}</td>
                                    <td>{$produit.Nom_produit|escape}</td>
                                    <td>{$produit.Prix_TTC|escape} €</td>
                                    <td>{$produit.Stock|escape}</td>
                                    <td>
                                        <a href="index.php?action=detail_produit&Id_produit={$produit.Id_produit}"><i class="fa-solid fa-eye"></i></i></a>
                                        <a href="index.php?action=update_produit&Id_produit={$produit.Id_produit}"><i class="fa-solid fa-pen-to-square text-warning mr-4 ml-4"></i></a>
                                        <a href="index.php?action=delete_produit&Id_produit={$produit.Id_produit}"><i class="fa-solid fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
        </div>

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