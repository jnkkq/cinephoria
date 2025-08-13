class Film {
  final int id;
  final String titre;
  final String description;
  final String affiche;
  final int ageMinimum;
  final bool coupDeCoeur;
  final DateTime createdAt;
  final double? moyenneNotes;

  Film({
    required this.id,
    required this.titre,
    required this.description,
    required this.affiche,
    required this.ageMinimum,
    required this.coupDeCoeur,
    required this.createdAt,
    this.moyenneNotes,
  });

  factory Film.fromJson(Map<String, dynamic> json) {
    return Film(
      id: json['id'] as int,
      titre: json['titre'] as String,
      description: json['description'] as String? ?? '',
      affiche: json['affiche'] as String? ?? '',
      ageMinimum: (json['age_minimum'] as int?) ?? 0,
      coupDeCoeur: (json['coup_de_coeur'] as int?) == 1,
      createdAt: json['created_at'] != null 
          ? DateTime.parse(json['created_at'] as String) 
          : DateTime.now(),
      moyenneNotes: json['moyenne'] != null 
          ? (json['moyenne'] is double 
              ? json['moyenne'] 
              : (json['moyenne'] as num).toDouble())
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'titre': titre,
      'description': description,
      'affiche': affiche,
      'age_minimum': ageMinimum,
      'coup_de_coeur': coupDeCoeur ? 1 : 0,
      'created_at': createdAt.toIso8601String(),
      'moyenne': moyenneNotes,
    };
  }
}
