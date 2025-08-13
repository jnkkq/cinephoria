<?php include 'views/layout/head.php'; ?>
<?php include 'views/layout/header.php'; ?>

<main>
    <h1>🪑 Gestion des salles</h1>

    <form method="post">
        <input type="hidden" name="id" id="salle_id">
        <label>Numéro :</label>
        <input type="number" name="numero" id="numero" required>
        <label>Capacité :</label>
        <input type="number" name="capacite" id="capacite" required>
        <label>Qualité projection :</label>
        <input type="text" name="qualite_projection" id="qualite_projection" required>
        <label>Cinéma :</label>
        <select name="cinema_id" id="cinema_id" required>
            <?php foreach ($cinemas as $cinema): ?>
                <option value="<?= $cinema['id'] ?>"><?= htmlspecialchars($cinema['nom']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="ajouter_salle">Ajouter</button>
        <button type="submit" name="modifier_salle">Modifier</button>
    </form>

    <h2>Liste des salles</h2>
    <table border="1">
        <tr><th>ID</th><th>Numéro</th><th>Capacité</th><th>Qualité</th><th>Cinéma</th><th>Actions</th></tr>
        <?php foreach ($salles as $salle): ?>
        <tr>
            <td><?= $salle['id'] ?></td>
            <td><?= $salle['numero'] ?></td>
            <td><?= $salle['capacite'] ?></td>
            <td><?= $salle['qualite_projection'] ?></td>
            <td><?= htmlspecialchars($salle['cinema_nom']) ?></td>
            <td>
                <button onclick="remplirFormulaire(<?= htmlspecialchars(json_encode($salle)) ?>)">✏️</button>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $salle['id'] ?>">
                    <button type="submit" name="supprimer_salle" onclick="return confirm('Supprimer cette salle ?')">❌</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>

<script>
function remplirFormulaire(salle) {
    document.getElementById('salle_id').value = salle.id;
    document.getElementById('numero').value = salle.numero;
    document.getElementById('capacite').value = salle.capacite;
    document.getElementById('qualite_projection').value = salle.qualite_projection;
    document.getElementById('cinema_id').value = salle.cinema_id;
}
</script>

<?php include 'views/layout/footer.php'; ?>
