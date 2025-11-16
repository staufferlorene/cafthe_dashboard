<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
                <div class="card-header py-3">
                    <h4 class="m-0 font-weight-bold text-primary">Produit(s) dans le panier</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">

                            <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix HT / unité</th>
                                <th>Prix TTC / unité</th>
                                <th>Quantité</th>
                                <th>Total HT</th>
                                <th>Total TTC</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$panier item=panier}
                                <tr>
                                    <td>{$panier.nom|escape}</td>
                                    <td>{$panier.prixht|escape|number_format:2:'.':''} €</td>
                                    <td>{$panier.prixttc|escape|number_format:2:'.':''} €</td>
                                    <td>
                                        <input
                                            type="number"
                                            name="quantite"
                                            min="0"
                                            value="{$panier.quantite|escape}"
                                            {*pour récupérer la quantité saisie par l'utilisateur lors des calculs en js*}
                                            class="quantite"
                                            {*pour stocker prix unitaire pour l'exploiter lors des calculs en js*}
                                            data-prixttc="{$panier.prixttc}"
                                            data-prixht="{$panier.prixht}"
                                            data-id="{$panier.id}"
                                        >
                                    </td>
                                    <td class="prix-total-ht-produit">{(($panier.prixht+0) * ($panier.quantite+0))|number_format:2:'.':''} €</td>
                                    <td class="prix-total-ttc-produit">{(($panier.prixttc+0) * ($panier.quantite+0))|number_format:2:'.':''} €</td>
                                    <td>
                                        <a href="index.php?action=delete_panier&id={$panier.id}"><i class="fa-solid fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
                    <div class="row pl-4 pr-4">
                        <div class="col-md-6">
                            <p>Montant total HT : {$totalHT|number_format:2:'.':''} €</p>
                            <p>Montant total TVA : {$totalTVA|number_format:2:'.':''} €</p>
                            <p class="font-weight-bold">Montant total TTC : {$totalTTC|number_format:2:'.':''} €</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="index.php?action=panier" class="btn btn-secondary mr-2">Retour</a>
                            <a href="index.php?action=client_panier" class="btn btn-success" id="valider-panier">Valider le panier</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calculs, mise en session et fenêtre alert -->
<script src="mvc_panier/js/panier-detail.js"></script>

</body>

</html>