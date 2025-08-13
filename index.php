<?php
require_once 'config/config.php';

// Démarrer la session si pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'accueil';

switch ($page) {
    // Pages publiques
    case 'accueil':
        include 'controllers/AccueilController.php';
        break;
        
    case 'films':
        include 'controllers/FilmController.php';
        break;
        
    case 'reservation':
        include 'controllers/ReservationController.php';
        break;
        
    case 'valider_reservation':
        include 'controllers/ValiderReservationController.php';
        break;
        
    case 'login':
        include 'controllers/AuthController.php';
        break;
        
    case 'mon_espace':
        include 'controllers/MonEspaceController.php';
        break;
        
    case 'logout':
        include 'controllers/LogoutController.php';
        break;
        
    case 'register':
        include 'controllers/RegisterController.php';
        break;
        
    case 'avis':
        include 'controllers/AvisController.php';
        break;

    // Pages d'administration
    case 'admin_avis':
        include 'controllers/AdminAvisController.php';
        break;
        
    case 'admin_films':
        include 'controllers/AdminFilmController.php';
        break;
        
    case 'admin_salles':
        include 'controllers/AdminSalleController.php';
        break;
        
    case 'admin_employes':
        include 'controllers/AdminEmployeController.php';
        break;
        
    case 'admin_seances':
        include 'controllers/AdminSeanceController.php';
        break;
        
    case 'admin_statistiques':
        // Vérifier que l'utilisateur est administrateur
        if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        include 'views/admin/statistiques_reservations.php';
        break;
        
    case 'intranet':
        include 'controllers/AdminController.php';
        break;
        
    case 'dashboard':
        include 'controllers/DashboardController.php';
        break;
        
    case 'dashboard_advanced':
        include 'controllers/DashboardAdvancedController.php';
        break;

    // Page 404 par défaut
    default:
        http_response_code(404);
        include 'views/404.php';
        break;
}
?>
