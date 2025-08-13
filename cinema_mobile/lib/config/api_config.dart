class ApiConfig {
  // URL de base de l'API
  // Pour émulateur Android : 10.0.2.2 pointe vers localhost de la machine hôte
  // Pour émulateur iOS : localhost
  // Pour web : localhost
  // Adapter l'URL ci-dessous selon votre configuration XAMPP/serveur
  // Pour Android émulateur : 10.0.2.2 correspond à localhost
  // Exemple pour projet racine :
  static const String baseUrl = 'http://10.0.2.2/cinephoria-master/api';
  
  // Endpoints
  static const String login = '/auth/login.php';
  static const String userSessions = '/seances/utilisateur.php';
}
