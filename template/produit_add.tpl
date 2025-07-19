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