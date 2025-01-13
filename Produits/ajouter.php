


<h1>Ajout d'un Produit</h1>
    <form method="POST">
        <label for="nomProduit">Nom du Produit :</label>
        <input type="text" name="nomProduit" required><br>

        <label for="description">Description :</label>
        <textarea name="description" required></textarea><br>

        <label for="prix">Prix :</label>
        <input type="number" step="0.01" name="prix" required><br>

        <label for="stock">Stock :</label>
        <input type="number" name="stock" required><br><br>

        <button type="submit" name="add_product">Ajouter le produit</button>
        <button type="button" onclick="ajouterProduit()">Ajouter un produit</button><br><br>

    </form>

    <script>
        let produitCount = 1;
        function ajouterProduit() {
            const container = document.getElementById('produits');
            const div = document.createElement('div');
            div.classList.add('produit');
            div.innerHTML = `
                <label>ID Produit :</label>
                <input type="number" name="produits[${produitCount}][idProduit]" required>
                <label>Quantité :</label>
                <input type="number" name="produits[${produitCount}][quantite]" required>
                <label>Prix :</label>
                <input type="number" step="0.01" name="produits[${produitCount}][prix]" required>
            `;
            container.appendChild(div);
            produitCount++;
        }
    </script>