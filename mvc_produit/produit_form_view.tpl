<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{if $action == 'add'}Ajouter un Produit{else}Modifier un Produit{/if}</title>

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
        <div style="color:red;">Erreur : {$erreur}</div>
    {/if}

    <h4 class="m-0 font-weight-bold text-primary">{if $action == 'add'}Ajouter un Produit{else}Modifier un Produit{/if}</h4>

    {if $action == 'add'}
        <form action="../../cafthe_dashboard/index.php?action=add_produit" method="post">
    {else}
        <form action="../../cafthe_dashboard/index.php?action=update_produit&Id_produit={$produit.Id_produit}" method="post">
    {/if}
            <div class="form-group">
                <label for="name">Nom :</label>
                <input class="form-control" type="text" id="name" name="nom" required  value="{if $action == 'update_produit'}{$produit.Nom_produit|escape}{/if}">
            </div>

            <div class="form-group">
                <label for="prix">Prix :</label>
                <input class="form-control" type="number" id="prix" name="prix" required value="{if $action == 'update_produit'}{$produit.Prix_TTC|escape}{/if}">
            </div>

            <div class="form-group">
                <label for="stock">Stock :</label>
                <input class="form-control" type="number" id="stock" name="stock" required value="{if $action == 'update_produit'}{$produit.Stock|escape}{/if}">
            </div>

            <button type="submit" class="btn btn-primary">Valider</button>
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