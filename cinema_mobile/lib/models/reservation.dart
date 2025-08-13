// Nécessaire pour éviter les erreurs de dépendance circulaire
import 'package:cinema_mobile/models/seance.dart';
import 'package:cinema_mobile/models/film.dart';

class Reservation {
  final int id;
  final int utilisateurId;
  final int seanceId;
  final DateTime dateReservation;
  final List<String> sieges;
  final double montantTotal;
  final String statut;
  
  // Champs optionnels qui peuvent être chargés avec des jointures
  final Seance? seance;
  final Film? film;

  Reservation({
    required this.id,
    required this.utilisateurId,
    required this.seanceId,
    required this.dateReservation,
    required this.sieges,
    required this.montantTotal,
    this.statut = 'confirmee',
    this.seance,
    this.film,
  });

  factory Reservation.fromJson(Map<String, dynamic> json) {
    // Gérer les sièges qui peuvent être une liste ou une chaîne séparée par des virgules
    List<String> siegesList = [];
    if (json['sieges'] is List) {
      siegesList = List<String>.from(json['sieges']);
    } else if (json['sieges'] is String) {
      siegesList = (json['sieges'] as String).split(',').map((s) => s.trim()).toList();
    }
    
    return Reservation(
      id: json['id'] as int,
      utilisateurId: json['utilisateur_id'] as int,
      seanceId: json['seance_id'] as int,
      dateReservation: DateTime.parse(json['date_reservation'] as String),
      sieges: siegesList,
      montantTotal: (json['montant_total'] is num) 
          ? (json['montant_total'] as num).toDouble() 
          : double.tryParse(json['montant_total']?.toString() ?? '0') ?? 0.0,
      statut: json['statut'] as String? ?? 'confirmee',
      seance: json['seance'] != null ? Seance.fromJson(json['seance']) : null,
      film: json['film'] != null ? Film.fromJson(json['film']) : null,
    );
  }

  // Pour la réponse de l'API utilisateur_seances.php
  factory Reservation.fromSeanceJson(Map<String, dynamic> json) {
    // Gérer les sièges qui peuvent être une liste ou une chaîne séparée par des virgules
    List<String> siegesList = [];
    if (json['sieges'] is List) {
      siegesList = List<String>.from(json['sieges']);
    } else if (json['sieges'] is String) {
      siegesList = (json['sieges'] as String).split(',').map((s) => s.trim()).toList();
    }
    
    return Reservation(
      id: int.parse(json['id'].toString()),
      utilisateurId: 0, // Non fourni dans la réponse
      seanceId: int.parse(json['seance_id']?.toString() ?? '0'),
      dateReservation: DateTime.now(), // Non fourni dans la réponse
      sieges: siegesList,
      montantTotal: 0.0, // Non fourni dans la réponse
      seance: Seance.fromReservationJson(json),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'utilisateur_id': utilisateurId,
      'seance_id': seanceId,
      'date_reservation': dateReservation.toIso8601String(),
      'sieges': sieges,
      'montant_total': montantTotal,
      'statut': statut,
      if (seance != null) 'seance': seance!.toJson(),
      if (film != null) 'film': film!.toJson(),
    };
  }

  // Pour l'affichage
  String get dateReservationFormattee {
    return '${_deuxChiffres(dateReservation.day)}/${_deuxChiffres(dateReservation.month)}/${dateReservation.year}';
  }

  String _deuxChiffres(int nombre) {
    return nombre < 10 ? '0$nombre' : nombre.toString();
  }

  // Vérifie si la réservation est pour aujourd'hui
  bool get estAujourdhui {
    final maintenant = DateTime.now();
    return dateReservation.year == maintenant.year &&
        dateReservation.month == maintenant.month &&
        dateReservation.day == maintenant.day;
  }

  // Vérifie si la réservation est à venir (y compris aujourd'hui)
  bool get estAVenir {
    final maintenant = DateTime.now();
    final dateReservationSansHeure = DateTime(
      dateReservation.year, 
      dateReservation.month, 
      dateReservation.day
    );
    final aujourdhuiSansHeure = DateTime(maintenant.year, maintenant.month, maintenant.day);
    return !dateReservationSansHeure.isBefore(aujourdhuiSansHeure);
  }
}

