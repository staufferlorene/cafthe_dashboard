<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="css/login.css" rel="stylesheet">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <title>CafThé</title>
</head>
<body>

{if isset($erreur)}
    <div class="mb-4" style="color:red;">Erreur : {$erreur}</div>
{/if}

<div class="wrapper fadeInDown">
    <div id="formContent">

        <!-- Icon -->
        <div>
            <img src="img/logo.png" alt="Logo de CafThé" width="90" height="90"/>
        </div>

        <!-- Login Form -->

        <form method="post" action="index.php">
            <input type="email" id="mail" name="mail" placeholder="Identifiant" required>
            <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>
            <input type="submit" value="Se connecter">
        </form>
    </div>
</div>

</body>
</html>