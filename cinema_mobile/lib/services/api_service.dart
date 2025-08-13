import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:cinema_mobile/config/api_config.dart';
import 'package:cinema_mobile/models/film.dart';
import 'package:cinema_mobile/models/seance.dart';
import 'package:cinema_mobile/models/reservation.dart';

class ApiService {
  static const String baseUrl = ApiConfig.baseUrl;

  // Headers communs pour les requêtes
  static Future<Map<String, String>> _getHeaders() async {
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      // Important: Ne pas inclure les en-têtes CORS dans la requête du client
      // Ces en-têtes doivent être définis côté serveur uniquement
    };
  }

  // Gestion des erreurs HTTP
  static dynamic _handleResponse(http.Response response) {
    switch (response.statusCode) {
      case 200:
        return jsonDecode(response.body);
      case 201:
        return jsonDecode(response.body);
      case 400:
        throw Exception('Requête incorrecte: ${response.body}');
      case 401:
      case 403:
        throw Exception('Non autorisé: ${response.body}');
      case 404:
        throw Exception('Ressource non trouvée: ${response.body}');
      case 500:
      default:
        throw Exception(
            'Erreur lors de la communication avec le serveur: ${response.statusCode}');
    }
  }

  // Récupérer les séances d'un utilisateur
  static Future<List<Reservation>> getSeancesUtilisateur(int utilisateurId) async {
    final client = http.Client();
    try {
      final url = Uri.parse('${ApiConfig.baseUrl}${ApiConfig.userSessions}?utilisateur_id=$utilisateurId');
      final headers = await _getHeaders();
      
      print('🔵 Requête API vers: $url');
      print('🔵 Headers: $headers');
      
      final response = await client.get(
        url, 
        headers: headers,
      ).timeout(const Duration(seconds: 10));
      
      print('🟢 Réponse reçue avec le statut: ${response.statusCode}');
      print('🟢 Headers de la réponse: ${response.headers}');
      print('🟢 Corps de la réponse: ${response.body}');
      
      // Vérifier le content-type de la réponse
      final contentType = response.headers['content-type'];
      if (contentType == null || !contentType.contains('application/json')) {
        print('⚠️ Attention: Le content-type de la réponse n\'est pas application/json: $contentType');
      }

      if (response.statusCode == 200) {
        // Vérifier si le corps de la réponse est vide
        if (response.body.isEmpty) {
          print('⚠️ Attention: Le corps de la réponse est vide');
          return [];
        }
        
        dynamic decodedData;
        try {
          decodedData = json.decode(response.body);
          print('✅ Données après décodage: $decodedData');
        } catch (e) {
          print('❌ Erreur lors du décodage JSON: $e');
          print('❌ Corps de la réponse (raw): ${response.body}');
          rethrow;
        }
        
        if (decodedData is! Map<String, dynamic>) {
          print('❌ Format de réponse inattendu: $decodedData');
          throw Exception('Format de réponse inattendu du serveur');
        }
        
        final Map<String, dynamic> data = decodedData;
        
        if (data['success'] == true && data['seances'] is List) {
          List<dynamic> seancesData = data['seances'];
          List<Reservation> reservations = [];
          
          for (var json in seancesData) {
            try {
              // Gérer les sièges qui peuvent être une liste ou une chaîne séparée par des virgules
              List<String> sieges = [];
              if (json['sieges'] is List) {
                sieges = (json['sieges'] as List).map((s) => s.toString()).toList();
              } else if (json['sieges'] is String) {
                sieges = (json['sieges'] as String).split(',').map((s) => s.trim()).toList();
              }
              
              // Créer l'objet Film
              final film = Film(
                id: 0,
                titre: json['film_titre']?.toString() ?? 'Titre inconnu',
                description: 'Description non disponible',
                affiche: 'https://via.placeholder.com/300x450?text=Aucune+affiche',
                ageMinimum: 0,
                coupDeCoeur: false,
                createdAt: DateTime.now(),
              );
              
              // Créer l'objet Seance
              final seance = Seance(
                id: 0,
                filmId: 0,
                salleId: 0,
                date: DateTime.parse(json['date']),
                heureDebut: json['heure_debut'],
                heureFin: '23:59:59',
                qualite: 'HD',
                prix: 0.0,
                salleNumero: json['salle_numero']?.toString(),
                cinemaNom: json['cinema_nom']?.toString(),
                film: film,
              );
              
              // Créer et ajouter la réservation
              final reservation = Reservation(
                id: int.parse(json['id'].toString()),
                utilisateurId: utilisateurId,
                seanceId: 0,
                dateReservation: DateTime.now(),
                sieges: sieges,
                montantTotal: (json['prix_total'] is num) 
                    ? (json['prix_total'] as num).toDouble() 
                    : double.tryParse(json['prix_total']?.toString() ?? '0') ?? 0.0,
                statut: json['statut']?.toString() ?? 'valide',
                seance: seance,
                film: film,
              );
              
              reservations.add(reservation);
            } catch (e) {
              print('Erreur lors du traitement d\'une réservation: $e');
              print('Données problématiques: $json');
            }
          }
          
          return reservations;
        } else {
          throw Exception(data['message'] ?? 'Format de réponse inattendu');
        }
      } else {
        throw Exception('Échec du chargement des séances: ${response.statusCode}');
      }
    } catch (e) {
      print('❌ Erreur lors de la récupération des séances: $e');
      if (e is http.ClientException) {
        print('❌ Détails de l\'erreur HTTP: ${e.message}');
        print('❌ URI: ${e.uri}');
      }
      rethrow;
    } finally {
      client.close();
    }
  }

  // Récupérer les détails d'un film
  static Future<Film> getFilm(int filmId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/endpoints/film_details.php?id=$filmId'),
        headers: await _getHeaders(),
      );

      final data = _handleResponse(response);
      return Film.fromJson(data);
    } catch (e) {
      print('Erreur lors de la récupération du film: $e');
      rethrow;
    }
  }

  // Récupérer les détails d'une séance
  static Future<Seance> getSeance(int seanceId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/endpoints/seance_details.php?id=$seanceId'),
        headers: await _getHeaders(),
      );

      final data = _handleResponse(response);
      return Seance.fromJson(data);
    } catch (e) {
      print('Erreur lors de la récupération de la séance: $e');
      rethrow;
    }
  }

  // Récupérer les sièges réservés pour une séance
  static Future<List<String>> getSiegesReserves(int seanceId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/endpoints/sieges_reserves.php?seance_id=$seanceId'),
        headers: await _getHeaders(),
      );

      final data = _handleResponse(response);
      return List<String>.from(data['sieges'] ?? []);
    } catch (e) {
      print('Erreur lors de la récupération des sièges réservés: $e');
      return [];
    }
  }

  // Générer un QR code pour une réservation
  static Future<String> genererQrCode(int reservationId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/endpoints/generer_qrcode.php?reservation_id=$reservationId'),
        headers: await _getHeaders(),
      );

      final data = _handleResponse(response);
      return data['qr_code_url'];
    } catch (e) {
      print('Erreur lors de la génération du QR code: $e');
      rethrow;
    }
  }

  // Valider un billet avec QR code
  static Future<bool> validerBillet(String qrData) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/endpoints/valider_billet.php'),
        headers: await _getHeaders(),
        body: jsonEncode({'qr_data': qrData}),
      );

      final data = _handleResponse(response);
      return data['valide'] == true;
    } catch (e) {
      print('Erreur lors de la validation du billet: $e');
      return false;
    }
  }
}
