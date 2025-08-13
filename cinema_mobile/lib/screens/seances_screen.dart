import 'package:flutter/material.dart';
import 'package:cinema_mobile/services/api_service.dart';
import 'package:cinema_mobile/models/reservation.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'package:intl/intl.dart';
import 'qr_code_screen.dart';

class SeancesScreen extends StatefulWidget {
  final int utilisateurId;

  const SeancesScreen({Key? key, required this.utilisateurId}) : super(key: key);

  @override
  _SeancesScreenState createState() => _SeancesScreenState();
}

class _SeancesScreenState extends State<SeancesScreen> {
  late Future<List<Reservation>> _futureReservations;
  late final DateFormat _dateFormat;
  late final DateFormat _timeFormat;
  bool _isInitialized = false;

  @override
  void initState() {
    super.initState();
    _initializeDateFormatting();
    _futureReservations = ApiService.getSeancesUtilisateur(widget.utilisateurId);
  }

  Future<void> _initializeDateFormatting() async {
    await initializeDateFormatting('fr_FR', null);
    setState(() {
      _dateFormat = DateFormat('EEEE d MMMM y', 'fr_FR');
      _timeFormat = DateFormat('HH:mm', 'fr_FR');
      _isInitialized = true;
    });
  }

  // Méthode pour gérer la déconnexion
  void _deconnexion() {
    Navigator.pushReplacementNamed(context, '/login');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Mes séances'),
        centerTitle: true,
        backgroundColor: Colors.black,
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: _deconnexion,
            tooltip: 'Déconnexion',
          ),
        ],
      ),
      body: FutureBuilder<List<Reservation>>(
        future: _futureReservations,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(Icons.error_outline, color: Colors.red, size: 60),
                  const SizedBox(height: 16),
                  Text(
                    'Une erreur est survenue :(',
                    style: Theme.of(context).textTheme.titleLarge,
                  ),
                  const SizedBox(height: 8),
                  Text(
                    snapshot.error.toString(),
                    textAlign: TextAlign.center,
                    style: const TextStyle(color: Colors.red),
                  ),
                  const SizedBox(height: 16),
                  ElevatedButton(
                    onPressed: () {
                      setState(() {
                        _futureReservations = 
                            ApiService.getSeancesUtilisateur(widget.utilisateurId);
                      });
                    },
                    child: const Text('Réessayer'),
                  ),
                ],
              ),
            );
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(
              child: Text('Aucune séance à venir.'),
            );
          }

          final reservations = snapshot.data!;
          return ListView.builder(
            itemCount: reservations.length,
            itemBuilder: (context, index) {
              final reservation = reservations[index];
              final seance = reservation.seance!;
              final film = seance.film!;

              return Card(
                margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                elevation: 3,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
                child: InkWell(
                  onTap: () {
                    // Naviguer vers l'écran du QR code
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => QrCodeScreen(
                          reservation: reservation,
                        ),
                      ),
                    );
                  },
                  borderRadius: BorderRadius.circular(12),
                  child: Padding(
                    padding: const EdgeInsets.all(12.0),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Affiche du film
                        ClipRRect(
                          borderRadius: BorderRadius.circular(8),
                          child: Image.network(
                            film.affiche.isNotEmpty
                                ? film.affiche
                                : 'https://via.placeholder.com/80x120?text=No+Image',
                            width: 80,
                            height: 120,
                            fit: BoxFit.cover,
                            errorBuilder: (context, error, stackTrace) {
                              return Container(
                                width: 80,
                                height: 120,
                                color: Colors.grey[300],
                                child: const Icon(Icons.movie, size: 40),
                              );
                            },
                          ),
                        ),
                        const SizedBox(width: 16),
                        // Détails de la séance
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              // Titre du film
                              Text(
                                film.titre,
                                style: const TextStyle(
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                ),
                                maxLines: 2,
                                overflow: TextOverflow.ellipsis,
                              ),
                              const SizedBox(height: 4),
                              // Date et heure
                              Text(
                                '${_dateFormat.format(seance.date)} • ${seance.heureDebut}',
                                style: TextStyle(
                                  color: Colors.grey[600],
                                  fontSize: 14,
                                ),
                              ),
                              const SizedBox(height: 4),
                              // Salle
                              if (seance.salleNumero != null && seance.salleNumero!.isNotEmpty)
                                Row(
                                  children: [
                                    const Icon(Icons.meeting_room_outlined, size: 16, color: Colors.black87),
                                    const SizedBox(width: 4),
                                    Text(
                                      'Salle ${seance.salleNumero}',
                                      style: const TextStyle(fontSize: 14, color: Colors.black87),
                                    ),
                                  ],
                                ),
                              const SizedBox(height: 4),
                              // Sièges
                              if (reservation.sieges.isNotEmpty)
                                Wrap(
                                  spacing: 4,
                                  children: [
                                    const Icon(Icons.event_seat, size: 16, color: Colors.orange),
                                    const SizedBox(width: 4),
                                    Text(
                                      'Sièges: ${reservation.sieges.join(', ')}',
                                      style: const TextStyle(fontSize: 14),
                                      maxLines: 2,
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                  ],
                                ),
                              const SizedBox(height: 8),
                              // Prix et statut
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    '${reservation.montantTotal.toStringAsFixed(2)} €',
                                    style: const TextStyle(
                                      fontWeight: FontWeight.bold,
                                      color: Colors.green,
                                    ),
                                  ),
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                    decoration: BoxDecoration(
                                      color: reservation.statut == 'valide' ? Colors.green[100] : Colors.orange[100],
                                      borderRadius: BorderRadius.circular(12),
                                    ),
                                    child: Text(
                                      reservation.statut.toUpperCase(),
                                      style: TextStyle(
                                        color: reservation.statut == 'valide' ? Colors.green[800] : Colors.orange[800],
                                        fontSize: 12,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 4),
                              // Heure de début et fin
                              Row(
                                children: [
                                  const Icon(Icons.access_time, size: 16),
                                  const SizedBox(width: 4),
                                  Text(
                                    '${seance.heureDebutFormattee} - ${seance.heureFinFormattee}',
                                    style: const TextStyle(fontSize: 14),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 4),
                              // Salle
                              Row(
                                children: [
                                  const Icon(Icons.meeting_room, size: 16),
                                  const SizedBox(width: 4),
                                  Text(
                                    'Salle ${seance.salleNumero ?? '?'}',
                                    style: const TextStyle(fontSize: 14),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 4),
                              // Sièges
                              Row(
                                children: [
                                  const Icon(Icons.event_seat, size: 16),
                                  const SizedBox(width: 4),
                                  Text(
                                    reservation.sieges.join(', '),
                                    style: const TextStyle(fontSize: 14),
                                    maxLines: 1,
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                ],
                              ),
                            ],
                          ),
                        ),
                        // Icône de flèche
                        const Icon(Icons.chevron_right, color: Colors.grey),
                      ],
                    ),
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}
