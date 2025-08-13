<?php 
// Vérification si la session n'est pas déjà démarrée pour éviter msg d'erreur
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="cinema-header-main">
    <div class="header-container">
        <div class="header-left">
            <div class="real-time-clock">
                <i class="far fa-clock"></i>
                <span id="current-time"><?= date('H:i:s') ?></span>
            </div>
            <div class="menu-container">
                    <div class="menu-brand-container">
                        <button class="header-menu-button" aria-label="Menu">
                            <span class="header-menu-icon"></span>
                            <span class="header-menu-icon"></span>
                            <span class="header-menu-icon"></span>
                            <span class="menu-text">MENU</span>
                        </button>
                        <h1 class="cinema-brand">CINEPHORIA</h1>
                    </div>
                    <nav class="main-nav">
                        <ul>
                            <li><a href="index.php?page=accueil">Accueil</a></li>
                            <li><a href="index.php?page=films">Films</a></li>
                            <li><a href="index.php?page=reservation">Réservation</a></li>
                            <?php if (isset($_SESSION['utilisateur']) && in_array($_SESSION['utilisateur']['role'], ['admin', 'employe'])): ?>
                                <li><a href="index.php?page=dashboard">Panneau de gestion</a></li>
                                <?php if (($_GET['page'] ?? '') !== 'dashboard_advanced'): ?>
                                    <li><a href="index.php?page=dashboard_advanced">Tableau de bord avancé</a></li>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="header-right">
                <?php if (!isset($_SESSION['utilisateur'])): ?>
                    <a href="index.php?page=login" class="login-btn">SE CONNECTER</a>
                    <a href="index.php?page=register" class="login-btn">S'INSCRIRE</a>
                <?php else: ?>
                    <span class="welcome-message">Connecté en tant que <?= htmlspecialchars(ucfirst($_SESSION['utilisateur']['prenom'] ?? 'Utilisateur')) ?></span>
                    <a href="index.php?page=mon_espace" class="login-btn">MON ESPACE</a>
                    <a href="index.php?page=logout" class="login-btn">DÉCONNEXION</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

<script>
// Mise à jour de l'horloge en temps réel
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
}

// Mettre à jour l'horloge immédiatement
updateClock();

// Mettre à jour l'horloge toutes les secondes
setInterval(updateClock, 1000);
</script>
</header>
