<?php include 'views/layout/head.php'; ?>
<?php include 'views/layout/header.php'; ?>

<main>
    <h1>Gestion des films</h1>

    <h2>Ajouter ou modifier un film</h2>
    <form method="post">
        <input type="hidden" name="id" id="film_id">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" id="titre" required>

        <label for="description">Description :</label>
        <textarea name="description" id="description" required></textarea>

        <label for="age_minimum">Âge minimum :</label>
        <input type="number" name="age_minimum" id="age_minimum" min="0" required>

        <label for="affiche">Nom du fichier image :</label>
        <input type="text" name="affiche" id="affiche" required>

        <label><input type="checkbox" name="coup_de_coeur" id="coup_de_coeur"> Coup de cœur</label><br>

        <button type="submit" name="ajouter">Ajouter</button>
        <button type="submit" name="modifier">Modifier</button>
    </form>

    <h2>Liste des films</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th><th>Titre</th><th>Âge</th><th>Affiche</th><th>Coup de cœur</th><th>Actions</th>
        </tr>
        <?php foreach ($films as $film): ?>
            <tr>
                <td><?= $film['id'] ?></td>
                <td><?= htmlspecialchars($film['titre']) ?></td>
                <td><?= $film['age_minimum'] ?></td>
                <td><?= htmlspecialchars($film['affiche']) ?></td>
                <td><?= $film['coup_de_coeur'] ? 'Oui' : 'Non' ?></td>
                <td>
                    <button onclick="remplirForm(<?= htmlspecialchars(json_encode($film)) ?>)">✏️</button>
                    <form method="post" style="display:inline;">
                        <button type="submit" name="supprimer" value="<?= $film['id'] ?>">❌</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
    function remplirForm(film) {
        document.getElementById('film_id').value = film.id;
        document.getElementById('titre').value = film.titre;
        document.getElementById('description').value = film.description;
        document.getElementById('age_minimum').value = film.age_minimum;
        document.getElementById('affiche').value = film.affiche;
        document.getElementById('coup_de_coeur').checked = film.coup_de_coeur == 1;
    }
    </script>
</main>

<?php include 'views/layout/footer.php'; ?>
