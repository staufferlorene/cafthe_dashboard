<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Modifier produit</title>
    </head>
    <body>
        <h1>Modifier un produit</h1>
        <form action="../../cafthe_dashboard/index.php?action=update_produit&Id_produit={$produit.Id_produit}" method="post">

            <label for="name">Nom :</label>
            <input type="text" id="name" name="nom" required value="{$produit.Nom_produit}">

            <label for="prix">Prix :</label>
            <input type="number" id="prix" name="prix" required value="{$produit.Prix_TTC}">

            <label for="stock">Stock :</label>
            <input type="number" id="stock" name="stock" required value="{$produit.Stock}">

            <button type="submit">Valider</button>
        </form>
    </body>
</html>