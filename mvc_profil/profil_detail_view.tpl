<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>CafThé - Profil</title>

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

                <!-- Card principale -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h4 class="m-0 font-weight-bold text-primary">Votre profil</h4>
                    </div>
                    <div class="card-body">

                        <!-- Conteneur des formulaires -->
                        <div class="d-flex flex-wrap justify-content-between gap-4">

                            <!-- Formulaire informations -->
                            <div class="flex-fill" style="min-width: 300px; max-width: 48%;">
                                <h5>Vos informations</h5>
                                <form action="index.php?action=profil&Id_vendeur={$vendeur.Id_vendeur}" method="post">

                                    <div class="form-group">
                                        <label for="nom">Nom :</label>
                                        <input class="form-control" type="text" id="nom" name="nom" disabled value="{$vendeur.Nom_vendeur|escape}">
                                    </div>

                                    <div class="form-group">
                                        <label for="prenom">Prénom :</label>
                                        <input class="form-control" type="text" id="prenom" name="prenom" disabled value="{$vendeur.Prenom_vendeur|escape}">
                                    </div>

                                    <div class="form-group">
                                        <label for="mail">Mail :</label>
                                        <input class="form-control" type="email" id="mail" name="mail" disabled value="{$vendeur.Mail_vendeur|escape}">
                                    </div>

                                    <a href="index.php?action=update_profil&Id_vendeur={$vendeur.Id_vendeur}" class="btn btn-outline-info mt-3 mb-5">Modifier les informations</a>
                                </form>
                            </div>

                            <!-- Formulaire mot de passe -->
                            <div class="flex-fill" style="min-width: 300px; max-width: 48%;">
                                <h5>Modifier votre mot de passe</h5>
                                <form action="index.php?action=update_password" method="post">
                                    <div class="form-group mb-3">
                                        <label for="mdp">Mot de passe actuel :</label>
                                        <input class="form-control" type="password" id="mdp" name="mdp" placeholder="Saisissez votre mot de passe actuel">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="new_mdp">Nouveau mot de passe :</label>
                                        <input class="form-control" type="password" id="new_mdp" name="new_mdp" placeholder="Saisissez votre nouveau mot de passe">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="confirm_mdp">Confirmation du nouveau mot de passe :</label>
                                        <input class="form-control" type="password" id="confirm_mdp" name="confirm_mdp" placeholder="Confirmer votre nouveau mot de passe">
                                    </div>
                                    <button type="submit" class="btn btn-outline-info mt-3 mb-5">Modifier le mot de passe</button>
                                </form>
                            </div>
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