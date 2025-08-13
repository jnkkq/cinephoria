<?php 
$pageTitle = 'Connexion - Cinephoria';
include 'views/layout/head.php'; 
?>

<style>
    .login-hero {
        position: relative;
        min-height: calc(100vh - 120px);
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #333;
        padding: 2rem 1rem;
    }

    .login-container {
        background: #fff;
        padding: 2.5rem;
        border-radius: 8px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid #eee;
        position: relative;
        z-index: 1;
    }

    .login-header {
        text-align: center;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid #eee;
    }

    .login-header h1 {
        margin: 0;
        font-size: 2rem;
        color: #000;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
    }
    
    .login-form .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-size: 0.9rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        color: #333;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: 'Montserrat', sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: #e50914;
        box-shadow: 0 0 0 2px rgba(229, 9, 20, 0.2);
    }

    .btn-login {
        width: 100%;
        padding: 0.8rem;
        background: #e50914;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.5rem;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-login:hover {
        background: #c11119;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(229, 9, 20, 0.3);
    }

    .login-footer {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eee;
        font-size: 0.9rem;
        color: #666;
    }

    .login-footer a {
        color: #e50914;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .login-footer a:hover {
        color: #c11119;
        text-decoration: underline;
    }

    .error-message {
        background: rgba(244, 67, 54, 0.2);
        color: #ff5252;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        text-align: center;
        border-left: 4px solid #ff5252;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .film-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        opacity: 0.1;
        background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
    }
</style>

<?php include 'views/layout/header.php'; ?>

<div class="login-hero">
    <div class="login-container">
        <div class="film-overlay"></div>
        <div class="login-header">
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <h1>Bienvenue</h1>
                <p>Connecté en tant que <?= htmlspecialchars($_SESSION['utilisateur']['prenom'] ?? 'Utilisateur') ?></p>
                <div class="user-actions" style="margin-top: 20px;">
                    <a href="index.php?page=mon_espace" class="btn-login" style="display: inline-block; width: auto; margin: 5px;">MON ESPACE</a>
                    <a href="index.php?page=logout" class="btn-login" style="display: inline-block; width: auto; margin: 5px; background: #333;">DÉCONNEXION</a>
                </div>
            <?php else: ?>
                <h1>Bienvenue</h1>
                <p>Connectez-vous pour accéder à votre espace personnel</p>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($erreur)): ?>
            <div class="error-message">
                <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="index.php?page=login" class="login-form">
            <div class="form-group">
                <label for="identifiant">Email ou nom d'utilisateur</label>
                <input type="text" name="identifiant" id="identifiant" required 
                       placeholder="Entrez votre email ou nom d'utilisateur">
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" required 
                       placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn-login">Se connecter</button>
            
            <div class="login-footer">
                Pas encore de compte ? <a href="index.php?page=register">S'inscrire</a>
            </div>
        </form>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>

<script>
    // Add focus effects to form inputs
    document.querySelectorAll('.form-group input').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
</script>
