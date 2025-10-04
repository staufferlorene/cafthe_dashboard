<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>CafThé - Détail client</title>

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

                <h4 class="m-0 mb-3 font-weight-bold text-primary">Détail du client</h4>

                    <form action="index.php?action=detail_client&Id_client={$client.Id_client}" method="post">

                        <div class="form-group">
                            <label for="nom">Nom :</label>
                            <input class="form-control" type="text" id="nom" name="nom" disabled value="{$client.Nom_client|escape}">
                        </div>

                        <div class="form-group">
                            <label for="prenom">Prénom :</label>
                            <input class="form-control" type="text" id="prenom" name="prenom" disabled value="{$client.Prenom_client|escape}">
                        </div>

                        <div class="form-group">
                            <label for="adresse">Adresse :</label>
                            <input class="form-control" type="text" id="adresse" name="adresse" disabled value="{$client.Adresse_client|escape}">
                        </div>

                        <div class="form-group">
                            <label for="tel">N° téléphone :</label>
                            <input class="form-control" type="tel" id="tel" name="tel" disabled value="{$client.Telephone_client|escape}">
                        </div>

                        <div class="form-group">
                            <label for="mail">E-mail :</label>
                            <input class="form-control" type="email" id="mail" name="mail" disabled value="{$client.Mail_client|escape}">
                        </div>

                        <a href="index.php?action=client" class="btn btn-secondary mt-3 mb-5">Retour</a>
                        <a href="index.php?action=update_client&Id_client={$client.Id_client}" class="btn btn-outline-info mt-3 mb-5">Modifier</a>
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