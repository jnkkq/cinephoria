<?php 
$pageTitle = 'Inscription - Cinephoria';
include 'views/layout/head.php'; 
include 'views/layout/header.php'; 
?>

<div class="cinema-container">
    <main class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">Créer un compte</h1>
            
            <?php if (!empty($erreur)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="index.php?page=register" class="auth-form">
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" name="prenom" id="prenom" class="form-control" required>
                        </div>
                        <div class="form-col">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" name="nom" id="nom" class="form-control" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="mot_de_passe" class="form-label">Mot de passe</label>
                    <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
                
                <p class="auth-footer">
                    Déjà inscrit ? <a href="index.php?page=login" class="auth-link">Connectez-vous ici</a>
                </p>
            </form>
        </div>
    </main>
</div>

<?php include 'views/layout/footer.php'; ?>
