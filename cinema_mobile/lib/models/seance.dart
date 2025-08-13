import 'package:cinema_mobile/models/film.dart';

class Seance {
  final int id;
  final int filmId;
  final int salleId;
  final DateTime date;
  final String heureDebut;
  final String heureFin;
  final String qualite;
  final double prix;
  
  // Champs optionnels qui peuvent être chargés avec des jointures
  final String? salleNumero;
  final String? cinemaNom;
  final Film? film;
  final List<String>? siegesReserves;

  Seance({
    required this.id,
    required this.filmId,
    required this.salleId,
    required this.date,
    required this.heureDebut,
    required this.heureFin,
    required this.qualite,
    required this.prix,
    this.salleNumero,
    this.cinemaNom,
    this.film,
    this.siegesReserves,
  });

  factory Seance.fromJson(Map<String, dynamic> json) {
    return Seance(
      id: json['id'] as int,
      filmId: json['film_id'] as int,
      salleId: json['salle_id'] as int,
      date: DateTime.parse(json['date'] as String),
      heureDebut: json['heure_debut'] as String,
      heureFin: json['heure_fin'] as String,
      qualite: json['qualite'] as String? ?? '',
      prix: (json['prix'] as num).toDouble(),
      salleNumero: json['salle_numero'] as String?,
      cinemaNom: json['cinema'] as String?,
      film: json['film'] != null ? Film.fromJson(json['film']) : null,
      siegesReserves: json['sieges'] != null 
          ? List<String>.from(json['sieges']) 
          : null,
    );
  }

  // Pour la réponse de l'API utilisateur_seances.php
  factory Seance.fromReservationJson(Map<String, dynamic> json) {
    return Seance(
      id: int.parse(json['id_seance'].toString()),
      filmId: 0, // Non fourni dans la réponse
      salleId: 0, // Non fourni dans la réponse
      date: DateTime.parse(json['jour_projection'] as String),
      heureDebut: json['debut'] as String,
      heureFin: json['fin'] as String,
      qualite: '', // Non fourni dans la réponse
      prix: 0.0, // Non fourni dans la réponse
      salleNumero: json['salle']?.toString(),
      film: Film(
        id: 0, // Non fourni dans la réponse
        titre: json['nom_film'] as String? ?? 'Film inconnu',
        description: '',
        affiche: json['affiche'] as String? ?? '',
        ageMinimum: 0,
        coupDeCoeur: false,
        createdAt: DateTime.now(),
      ),
      siegesReserves: json['sieges'] != null 
          ? List<String>.from(json['sieges']) 
          : [],
    );
  }
  
  // Getters pour le formatage des dates et heures
  String get dateFormattee {
    return '${_deuxChiffres(date.day)}/${_deuxChiffres(date.month)}/${date.year}';
  }
  
  String get heureDebutFormattee {
    return heureDebut.length >= 5 ? heureDebut.substring(0, 5) : heureDebut;
  }
  
  String get heureFinFormattee {
    return heureFin.length >= 5 ? heureFin.substring(0, 5) : heureFin;
  }
  
  String _deuxChiffres(int nombre) {
    return nombre < 10 ? '0$nombre' : nombre.toString();
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'film_id': filmId,
      'salle_id': salleId,
      'date': date.toIso8601String().split('T')[0],
      'heure_debut': heureDebut,
      'heure_fin': heureFin,
      'qualite': qualite,
      'prix': prix,
      if (salleNumero != null) 'salle_numero': salleNumero,
      if (cinemaNom != null) 'cinema': cinemaNom,
      if (film != null) 'film': film!.toJson(),
      if (siegesReserves != null) 'sieges': siegesReserves,
    };
  }



  // Vérifie si la séance est aujourd'hui
  bool get estAujourdhui {
    final maintenant = DateTime.now();
    return date.year == maintenant.year &&
        date.month == maintenant.month &&
        date.day == maintenant.day;
  }

  // Vérifie si la séance est à venir (y compris aujourd'hui)
  bool get estAVenir {
    final maintenant = DateTime.now();
    final dateSeance = DateTime(date.year, date.month, date.day);
    final dateAujourdhui = DateTime(maintenant.year, maintenant.month, maintenant.day);
    return !dateSeance.isBefore(dateAujourdhui);
  }
}
