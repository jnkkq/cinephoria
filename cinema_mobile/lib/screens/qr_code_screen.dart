import 'package:flutter/material.dart';
import 'package:qr_flutter/qr_flutter.dart';
import 'package:cinema_mobile/models/reservation.dart';

class QrCodeScreen extends StatelessWidget {
  final Reservation reservation;

  const QrCodeScreen({
    Key? key,
    required this.reservation,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    // Création des données du QR code
    final qrData = {
      'reservation_id': reservation.id,
      'seance_id': reservation.seanceId,
      'utilisateur_id': reservation.utilisateurId,
      'date_validation': DateTime.now().toIso8601String(),
    };

    final qrDataString = qrData.entries
        .map((e) => '${e.key}=${e.value}')
        .join('&');

    return Scaffold(
      appBar: AppBar(
        title: const Text('Mon billet'),
        centerTitle: true,
        backgroundColor: Colors.black,
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () {
              Navigator.pushNamedAndRemoveUntil(
                context,
                '/login',
                (route) => false,
              );
            },
            tooltip: 'Déconnexion',
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // En-tête
            const Text(
              'Votre billet',
              style: TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
              ),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 10),
            Text(
              'Présentez ce QR code à l\'entrée de la salle',
              style: Theme.of(context).textTheme.bodyMedium,
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 30),
            
            // Carte du film
            Card(
              elevation: 4,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Titre du film
                    Text(
                      reservation.seance?.film?.titre ?? 'Film inconnu',
                      style: const TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 16),
                    
                    // Détails de la séance
                    _buildDetailRow(
                      icon: Icons.calendar_today,
                      text: reservation.seance?.dateFormattee ?? 'Date inconnue',
                    ),
                    const SizedBox(height: 8),
                    _buildDetailRow(
                      icon: Icons.access_time,
                      text: '${reservation.seance?.heureDebutFormattee} - ${reservation.seance?.heureFinFormattee}',
                    ),
                    const SizedBox(height: 8),
                    _buildDetailRow(
                      icon: Icons.meeting_room,
                      text: 'Salle ${reservation.seance?.salleNumero ?? '?'}',
                    ),
                    const SizedBox(height: 8),
                    _buildDetailRow(
                      icon: Icons.event_seat,
                      text: 'Siège(s): ${reservation.sieges.join(', ')}',
                    ),
                  ],
                ),
              ),
            ),
            
            const SizedBox(height: 30),
            
            // QR Code
            Center(
              child: QrImageView(
                data: qrDataString,
                version: QrVersions.auto,
                size: 200.0,
                backgroundColor: Colors.white,
                eyeStyle: const QrEyeStyle(
                  eyeShape: QrEyeShape.square,
                  color: Colors.black,
                ),
                dataModuleStyle: const QrDataModuleStyle(
                  dataModuleShape: QrDataModuleShape.square,
                  color: Colors.black,
                ),
              ),
            ),
            
            const SizedBox(height: 20),
            
            // Numéro de réservation
            Text(
              'Réservation n°${reservation.id}',
              style: const TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w500,
                color: Colors.grey,
              ),
              textAlign: TextAlign.center,
            ),
            
            const SizedBox(height: 30),
            
            // Instructions
            Card(
              color: Colors.grey[100],
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Instructions :',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        color: Colors.black87,
                      ),
                    ),
                    SizedBox(height: 8),
                    Text(
                      '• Présentez ce QR code à l\'entrée de la salle\n'
                      '• Assurez-vous d\'arriver au moins 15 minutes avant le début de la séance\n'
                      '• Ayez une pièce d\'identité en cas de contrôle',
                      style: TextStyle(fontSize: 14),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
  
  Widget _buildDetailRow({required IconData icon, required String text}) {
    return Row(
      children: [
        Icon(icon, size: 20, color: Colors.grey[600]),
        const SizedBox(width: 12),
        Text(
          text,
          style: const TextStyle(fontSize: 16),
        ),
      ],
    );
  }
}
