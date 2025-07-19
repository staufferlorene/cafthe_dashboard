<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Gestion des produits</title>
    </head>
    <body>
        <h1>Liste des produits</h1>
        <button><a href="index.php?action=add">Ajouter un nouveau produit</a></button>
        <table>
            <thead>
                <tr>
                    <th>ID produits</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$produits item=produit}
                <tr>
                    <td>{$produit.id_produits|escape}</td>
                    <td>{$produit.nom|escape}</td>
                    <td>{$produit.prix|escape}</td>
                    <td>{$produit.stock|escape}</td>
                    <td><button><a href="index.php?action=update&id_produits={$produit.id_produits}">Modifier</a></button></td>
                    <td><button><a href="index.php?action=delete_produits&id_produits={$produit.id_produits}">Supprimer</a></button></td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </body>
</html>