<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>CafThé - Détail commande</title>

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

                <h4 class="m-0 mb-3 font-weight-bold text-primary">Détail de la commande</h4>

                <h5 class="m-0 mb-3 mt-3 font-weight-bold text-primary">Informations client</h5>

                    <form action="index.php?action=detail_commande&Id_commande={$commande.Id_commande}" method="post">

                        <div class="form-group">
                            <label for="nom">Nom :</label>
                            <input class="form-control" type="text" id="nom" name="nom" disabled value="{$commande.Nom_client|escape}">
                        </div>

                        <div class="form-group">
                            <label for="prenom">Prénom :</label>
                            <input class="form-control" type="text" id="prenom" name="prenom" disabled value="{$commande.Prenom_client|escape}">
                        </div>

                        <div class="form-group">
                            <label for="adresse">Adresse de livraison :</label>
                            <input class="form-control" type="text" id="adresse" name="adresse" disabled value="{$commande.Adresse_livraison|escape}">
                        </div>

                        <div class="form-group">
                            <label for="date">Date de la commande :</label>
                            <input class="form-control" type="date" id="date" name="date" disabled value="{$commande.Date_commande|escape}">
                        </div>

                        <div class="form-group">
                            <label for="statut">Statut :</label>
                            <input class="form-control" type="text" id="statut" name="statut" disabled value="{$commande.Statut_commande|escape}">
                        </div>

                        <div class="form-group">
                            <label for="total">Montant TTC :</label>
                            <input class="form-control" type="email" id="mail" name="mail" disabled value="{$commande.Montant_commande_TTC|escape}">
                        </div>

                        <h5 class="m-0 mb-3 mt-3 font-weight-bold text-primary">Détail des produits achetéss</h5>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">

                                    <thead>
                                        <tr>
                                            <th>Nom produit</th>
                                            <th>Quantité</th>
                                            <th>Prix HT</th>
                                            <th>TVA</th>
                                            <th>Prix TTC</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{$commande.Nom_produit|escape}</td>
                                            <td>{$commande.Quantite_produit_ligne_commande|escape}</td>
                                            <td>{$commande.Montant_commande_HT|escape}</td>
                                            <td>{$commande.Montant_TVA|escape}</td>
                                            <td>{$commande.Montant_commande_TTC|escape}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <a href="index.php?action=commande" class="btn btn-secondary mt-3 mb-5">Retour</a>
                        <a href="index.php?action=update_commande&Id_commande={$commande.Id_commande}" class="btn btn-outline-info mt-3 mb-5">Modifier</a>
                    </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
{*<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>*}

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