<?php include 'views/layout/head.php'; ?>
<?php include 'views/layout/header.php'; ?>

<main>
    <h1>Modération des avis</h1>

    <?php if ($avisEnAttente): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>Film</th>
                <th>Utilisateur</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>Action</th>
            </tr>
            <?php foreach ($avisEnAttente as $avis): ?>
                <tr>
                    <td><?= htmlspecialchars($avis['titre']) ?></td>
                    <td><?= htmlspecialchars($avis['prenom'] . ' ' . $avis['nom']) ?></td>
                    <td><?= $avis['note'] ?>/5</td>
                    <td><?= nl2br(htmlspecialchars($avis['commentaire'])) ?></td>
                    <td>
                        <form method="post" action="index.php?page=admin_avis" style="display:inline;">
                            <button type="submit" name="valider" value="<?= $avis['id'] ?>">✅ Valider</button>
                        </form>
                        <form method="post" action="index.php?page=admin_avis" style="display:inline;">
                            <button type="submit" name="supprimer" value="<?= $avis['id'] ?>">❌ Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucun avis en attente.</p>
    <?php endif; ?>
    <?php if (!empty($avisValides)): ?>
        <h2>Liste des avis validés</h2>
        <table border="1" cellpadding="5">
            <tr>
                <th>Film</th>
                <th>Utilisateur</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>Action</th>
            </tr>
            <?php foreach ($avisValides as $avis): ?>
                <tr>
                    <td><?= htmlspecialchars($avis['titre']) ?></td>
                    <td><?= htmlspecialchars($avis['prenom'] . ' ' . $avis['nom']) ?></td>
                    <td><?= $avis['note'] ?>/5</td>
                    <td><?= nl2br(htmlspecialchars($avis['commentaire'])) ?></td>
                    <td>
                        <form method="post" action="index.php?page=admin_avis" style="display:inline;">
                            <button type="submit" name="supprimer" value="<?= $avis['id'] ?>" onclick="return confirm('Supprimer cet avis validé ?')">❌ Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</main>

<?php include 'views/layout/footer.php'; ?>
