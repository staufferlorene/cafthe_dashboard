<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{if $action == 'add'}Ajouter un produit{else}Modifier un produit{/if}</title>

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

    <h4 class="m-0 mb-3 font-weight-bold text-primary">{if $action == 'add'}Ajouter un produit{else}Modifier un produit{/if}</h4>

    {if $action == 'add'}
        <form action="index.php?action=add_produit" method="post">
    {else}
        <form action="index.php?action=update_produit&Id_produit={$produit.Id_produit}" method="post">
    {/if}
            <div class="form-group">
                <label for="categorie">Catégorie :</label>
                <select class="form-control" id="categorie" name="categorie" required>
                {if $action == 'add'}<option value="">-- Choisissez une catégorie --</option>{/if}
                    {foreach from=$categories item=categorie}
                        <option value="{$categorie.Id_categorie}"
                                {if $action == 'update_produit' && $produit.Id_categorie == $categorie.Id_categorie}selected{/if}>
                            {$categorie.Nom_categorie|escape}
                        </option>
                    {/foreach}
                </select>
            </div>

            <div class="form-group">
                <label for="nom">Nom :</label>
                <input class="form-control" type="text" id="nom" name="nom" required  value="{if $action == 'update_produit'}{$produit.Nom_produit|escape}{/if}">
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <input class="form-control" type="text" id="description" name="description" required  value="{if $action == 'update_produit'}{$produit.Description|escape}{/if}">
            </div>

            <div class="form-group">
                <label for="prix_ttc">Prix TTC :</label>
                <input class="form-control" type="number" id="prix_ttc" name="prix_ttc" required value="{if $action == 'update_produit'}{$produit.Prix_TTC|escape}{/if}">
            </div>

            <div class="form-group">
                <label for="prix_ht">Prix HT :</label>
                <input class="form-control" type="number" id="prix_ht" name="prix_ht" required value="{if $action == 'update_produit'}{$produit.Prix_HT|escape}{/if}">
            </div>

            <div class="form-group">
                <label for="stock">Stock :</label>
                <input class="form-control" type="number" id="stock" name="stock" required value="{if $action == 'update_produit'}{$produit.Stock|escape}{/if}">
            </div>

            <div class="form-group">
               <label for="conditionnement">Conditionnement :</label>
                <select class="form-control" id="conditionnement" name="conditionnement" required>

                    {*En modification reprendre le conditionnement et afficher dans la liste déroulante uniquement l'autre option*}
                    {if $action == 'update_produit'}
                        <option value="">{$produit.Type_conditionnement|escape}</option>
                            <option>{if $produit.Type_conditionnement == 'vrac'}unitaire{else}vrac{/if}</option>
                    {else}
                        {*Pour l'ajout*}
                        <option value="">-- Choisissez un conditionnement --</option>
                            <option>unitaire</option>
                            <option>vrac</option>
                    {/if}
                </select>
            </div>

            <a href="index.php" class="btn btn-secondary mr-2 mt-3 mb-5">Retour</a>
            <button type="submit" class="btn btn-success mt-3 mb-5">Valider</button>
        </form>
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