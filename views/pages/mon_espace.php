<?php 
// Inclure les fichiers nécessaires
include 'views/layout/head.php'; 
include 'views/layout/header.php'; 
?>

<div class="page-wrapper">
    <!-- Contenu principal -->
    <main class="cinema-container" style="margin: 0 auto; max-width: 1200px; padding: 0 15px;">
        <style>
            /* Styles pour les réservations */
            .profile-hero {
                background: none;
                padding: 2rem 0;
                color: #333;
            }

            .profile-container {
                background: #fff;
                padding: 2rem;
                border-radius: 0;
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                border: 1px solid #eee;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .profile-header {
                text-align: center;
                margin-bottom: 2rem;
            }

            .profile-header h1 {
                font-size: 2.2rem;
                color: #000;
                margin: 0 0 1rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                position: relative;
                display: inline-block;
            }

            .profile-header h1::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 60px;
                height: 3px;
                background: #e50914;
            }

            .reservations-section {
                margin-top: 2rem;
            }

            .section-title {
                font-size: 1.4rem;
                color: #333;
                margin-bottom: 1.5rem;
                padding-bottom: 0.75rem;
                border-bottom: 2px solid #f0f0f0;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .section-title i {
                color: #e50914;
            }

            .reservations-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                gap: 1.5rem;
            }

            .reservation-card {
                background: #fff;
                border: 1px solid #f0f0f0;
                border-radius: 0;
                overflow: hidden;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .reservation-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .reservation-header {
                background: #000;
                color: #fff;
                padding: 1rem;
                border-bottom: 3px solid #e50914;
            }

            .movie-title {
                margin: 0;
                font-size: 1.1rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .reservation-details {
                padding: 1.25rem;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .detail {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 0.5rem;
            }

            .detail i {
                color: #e50914;
                font-size: 1rem;
                width: 20px;
                text-align: center;
            }

            .detail-label {
                font-size: 0.75rem;
                color: #777;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 0.15rem;
            }

            .detail-value {
                font-size: 0.9rem;
                font-weight: 500;
                color: #333;
            }

            .price {
                color: #e50914;
                font-weight: 600;
            }

            .no-reservations {
                text-align: center;
                padding: 2.5rem 1rem;
                background: #f9f9f9;
                border: 2px dashed #eee;
                border-radius: 0;
            }

            .no-reservations i {
                font-size: 2.5rem;
                color: #e50914;
                margin-bottom: 1rem;
                opacity: 0.8;
            }

            .no-reservations p {
                font-size: 1rem;
                color: #666;
                margin-bottom: 1.5rem;
            }

            .btn-films {
                display: inline-block;
                background: #e50914;
                color: white;
                padding: 0.6rem 1.5rem;
                border-radius: 0;
                text-decoration: none;
                font-weight: 500;
                text-transform: uppercase;
                font-size: 0.85rem;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
            }

            .btn-films:hover {
                background: #f40612;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(229, 9, 20, 0.2);
                color: white;
            }

            @media (max-width: 1024px) {
                .reservations-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 768px) {
                .profile-container {
                    padding: 1.5rem;
                }
                
                .reservations-grid {
                    grid-template-columns: 1fr;
                }
                
                .reservation-details {
                    grid-template-columns: 1fr 1fr;
                }
                
                .profile-header h1 {
                    font-size: 1.8rem;
                }
            }

            @media (max-width: 480px) {
                .reservation-details {
                    grid-template-columns: 1fr;
                }
                
                .profile-header h1 {
                    font-size: 1.6rem;
                }
            }
        </style>

        <div class="profile-hero">
            <div class="profile-container">
                <div class="profile-header">
                    <h1>Mon Espace</h1>
                </div>
                
                <div class="reservations-section">
                    <h2 class="section-title"><i class="fas fa-ticket-alt"></i> Mes réservations</h2>
                    
                    <?php if (!empty($reservations)): ?>
                        <div class="reservations-grid">
                            <?php foreach ($reservations as $resa): ?>
                                <div class="reservation-card">
                                    <div class="reservation-header">
                                        <h3 class="movie-title"><?= htmlspecialchars($resa['titre']) ?></h3>
                                    </div>
                                    <div class="reservation-details">
                                        <div class="detail">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div>
                                                <div class="detail-label">Cinéma</div>
                                                <div class="detail-value"><?= htmlspecialchars($resa['cinema_nom']) ?></div>
                                            </div>
                                        </div>
                                        <div class="detail">
                                            <i class="fas fa-door-open"></i>
                                            <div>
                                                <div class="detail-label">Salle</div>
                                                <div class="detail-value"><?= $resa['salle_numero'] ?></div>
                                            </div>
                                        </div>
                                        <div class="detail">
                                            <i class="far fa-calendar-alt"></i>
                                            <div>
                                                <div class="detail-label">Date</div>
                                                <div class="detail-value"><?= date('d/m/Y', strtotime($resa['date'])) ?></div>
                                            </div>
                                        </div>
                                        <div class="detail">
                                            <i class="far fa-clock"></i>
                                            <div>
                                                <div class="detail-label">Horaire</div>
                                                <div class="detail-value"><?= substr($resa['heure_debut'], 0, 5) ?></div>
                                            </div>
                                        </div>
                                        <div class="detail">
                                            <i class="fas fa-chair"></i>
                                            <div>
                                                <div class="detail-label">Places</div>
                                                <div class="detail-value">
                                                    <?= $resa['nombre_places'] ?>
                                                    <?php if (!empty($resa['sieges'])): ?>
                                                        <div class="seat-numbers" style="margin-top: 5px; font-size: 0.8em; color: #e50914; font-weight: bold;">
                                                            Sièges: <?= implode(', ', $resa['sieges']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="detail">
                                            <i class="fas fa-euro-sign"></i>
                                            <div>
                                                <div class="detail-label">Total</div>
                                                <div class="detail-value price"><?= number_format($resa['total_prix'], 2, ',', ' ') ?> €</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-reservations">
                            <i class="fas fa-ticket-alt"></i>
                            <p>Vous n'avez pas encore effectué de réservation.</p>
                            <a href="index.php?page=films" class="btn-films">Voir les films à l'affiche</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'views/layout/footer.php'; ?>
