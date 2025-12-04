<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../img/icon.png" />

    <title>CafThé - Panier</title>

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

            {if isset($erreur)}
                <div class="mb-4" style="color:red;">Erreur : {$erreur}</div>
            {/if}

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="text-align: center">
                    <span style="font-weight: bold; font-size: 18px; margin-right: 20px">
                        Total panier TTC : <span id="montant-total-ttc">{$totalTTC|number_format:2:'.':''} €</span>
                    </span>
                    <a href="index.php?action=view_panier"
                       class="btn btn-success btn-icon-split"
                       id="btn-access-panier">
                       <span class="text" style="font-weight: bold">Accéder au panier</span>
                       <span class="icon text-white-50">
                           <i class="fa-solid fa-arrow-right"></i>
                       </span>
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
                                <th>Stock</th>
                                <th>Prix HT</th>
                                <th>Prix TTC</th>
                                <th>Quantité</th>
                                <th>Total TTC</th>
                                <th>Ajouter au panier</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>ID produits</th>
                                <th>Catégorie</th>
                                <th>Nom</th>
                                <th>Stock</th>
                                <th>Prix HT</th>
                                <th>Prix TTC</th>
                                <th>Quantité</th>
                                <th>Total TTC</th>
                                <th>Ajouter au panier</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            {foreach from=$produit item=produit}
                            <form method="post" action="index.php?action=ajouterPanier">
                                <tr>
                                    <td>{$produit.Id_produit|escape}</td>
                                    <td>{$produit.Nom_categorie|escape}</td>
                                    <td>{$produit.Nom_produit|escape}</td>
                                    <td>{$produit.Stock|escape}</td>
                                    <td>{$produit.Prix_HT|escape}</td>
                                    <td>{$produit.Prix_TTC|escape}</td>
                                    <td>
                                        <input
                                            type="number"
                                            name="quantite"
                                            min="0"
                                            placeholder="0"
                                            {*pour récupérer la quantité saisie par l'utilisateur lors des calculs en js*}
                                            class="quantite"
                                            {*pour stocker prix unitaire pour l'exploiter lors des calculs en js*}
                                            data-prixttc="{$produit.Prix_TTC}"
                                        >
                                    </td>
                                    <td class="prix-total">0.00 €</td>
                                    <td>
                                        <!-- Champs cachés pour transmettre les infos du produit -->
                                        <input type="hidden" name="id" value="{$produit.Id_produit}">
                                        <input type="hidden" name="nom" value="{$produit.Nom_produit}">
                                        <input type="hidden" name="prixht" value="{$produit.Prix_HT}">
                                        <input type="hidden" name="prixttc" value="{$produit.Prix_TTC}">

                                        <button type="button"
                                                {*pour cibler le bouton pour faire les calculs en js*}
                                                class="add-panier">
                                            <i class="fa-solid fa-cart-plus fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            </form>
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

        <!-- Calculs, mise en session et fenêtre alert -->
        <script src="mvc_panier/js/panier-liste.js"></script>

</body>

</html>