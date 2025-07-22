<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Ajouter produit</title>
    </head>
    <body>

        {if isset($erreur)}
            <div style="color:red;">Erreur : {$erreur}</div>
        {/if}


        <h2>Ajouter un produit</h2>
        <form action="../../cafthe_dashboard/index.php?action=add" method="post">

            <label for="name">Nom :</label>
            <input type="text" id="name" name="nom" required> <br>

            <label for="prix">Prix :</label>
            <input type="number" id="prix" name="prix" required> <br>

            <label for="stock">Stock :</label>
            <input type="number" id="stock" name="stock" required> <br>


            <button type="submit">Ajouter</button>
        </form>
    </body>
</html>