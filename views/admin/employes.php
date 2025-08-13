<?php include 'views/layout/head.php'; ?>
<?php include 'views/layout/header.php'; ?>
<main>
    <h1>👤 Gestion des employés</h1>
    <form method="post">
        <h2>Ajouter un employé</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="text" name="nom" placeholder="Nom" required>
        <button type="submit" name="ajouter">Ajouter</button>
    </form>
    <h2>Liste des employés</h2>
    <table border="1">
        <tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Nom d'utilisateur</th><th>Actions</th></tr>
        <?php foreach ($employes as $emp): ?>
        <tr>
            <td><?= htmlspecialchars($emp['nom']) ?></td>
            <td><?= htmlspecialchars($emp['prenom']) ?></td>
            <td><?= htmlspecialchars($emp['email']) ?></td>
            <td><?= htmlspecialchars($emp['username']) ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="reinitialiser" value="<?= $emp['id'] ?>">
                    <input type="text" name="nouveau_mot_de_passe" placeholder="Nouveau mot de passe" required>
                    <button type="submit">🔁</button>
                </form>
                <form method="post" style="display:inline;">
                    <button type="submit" name="supprimer" value="<?= $emp['id'] ?>" onclick="return confirm('Supprimer cet employé ?')">❌</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>
<?php include 'views/layout/footer.php'; ?>
