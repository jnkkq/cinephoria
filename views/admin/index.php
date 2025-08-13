<?php include 'views/layout/head.php'; ?>
<?php include 'views/layout/header.php'; ?>

<main>
    <h1>Espace Intranet</h1>

    <ul>
        <li><a href="index.php?page=admin_films">🎬 Gestion des films</a></li>
        <li><a href="index.php?page=admin_seances">📅 Gestion des séances</a></li>
        <li><a href="index.php?page=admin_avis">💬 Modération des avis</a></li>

        <?php if ($_SESSION['utilisateur']['role'] === 'admin'): ?>
            <li><a href="index.php?page=admin_employes">💬 Modération des employés</a></li>
            <li><a href="index.php?page=admin_salles">💬 Modération des salles</a></li>
            <li><a href="index.php?page=admin_statistiques">📊 Statistiques des réservations</a></li>
        <?php endif; ?>
    </ul>
</main>

<?php include 'views/layout/footer.php'; ?>
