<?php include 'views/layout/head.php'; ?>
<?php include 'views/layout/header.php'; ?>

<main>
    <h1>Gestion des séances</h1>

    <form method="post">
        <input type="hidden" name="id" id="seance_id">
        <label>Film :</label>
        <select name="film_id" required>
            <?php foreach ($films as $film): ?>
                <option value="<?= $film['id'] ?>"><?= htmlspecialchars($film['titre']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Salle :</label>
        <select name="salle_id" required>
            <?php foreach ($salles as $salle): ?>
                <option value="<?= $salle['id'] ?>">Salle <?= $salle['numero'] ?> - <?= htmlspecialchars($salle['qualite_projection']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Date :</label>
        <input type="date" name="date" required>

        <label>Heure début :</label>
        <input type="time" name="heure_debut" required>

        <label>Heure fin :</label>
        <input type="time" name="heure_fin" required>

        <label>Qualité :</label>
        <input type="text" name="qualite" required>

        <label>Prix (€) :</label>
        <input type="number" step="0.01" name="prix" required>

        <button type="submit" name="ajouter">Ajouter</button>
        <button type="submit" name="modifier">Modifier</button>
    </form>

    <h2>Liste des séances</h2>
    <table border="1" cellpadding="5">
        <tr><th>Film</th><th>Cinéma</th><th>Salle</th><th>Date</th><th>Début</th><th>Fin</th><th>Qualité</th><th>Prix</th><th>Actions</th></tr>
        <?php foreach ($seances as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['film']) ?></td>
                <td><?= htmlspecialchars($s['cinema']) ?></td>
                <td><?= $s['salle'] ?></td>
                <td><?= $s['date'] ?></td>
                <td><?= $s['heure_debut'] ?></td>
                <td><?= $s['heure_fin'] ?></td>
                <td><?= htmlspecialchars($s['qualite']) ?></td>
                <td><?= $s['prix'] ?> €</td>
                <td>
                    <form method="post" style="display:inline;">
                        <button type="submit" name="supprimer" value="<?= $s['id'] ?>">❌</button>
                                            </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php include 'views/layout/footer.php'; ?>
