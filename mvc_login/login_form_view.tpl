<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CafThé</title>
</head>
<body>
<h1>CafThé</h1>

{if isset($erreur)}
    <div class="mb-4" style="color:red;">Erreur : {$erreur}</div>
{/if}

{* *********************************

/!\ action du form sera à modifier /!\

*****************************************}

<form method="post" action="index.php">

    <label for="mail">Email : </label>
    <input type="email" id="mail" name="mail" required>

    <label for="mdp">Mot de passe : </label>
    <input type="password" id="mdp" name="mdp" required>

    <button type="submit">Se connecter</button>

</form>

</body>
</html>