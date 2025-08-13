<?php include 'views/layout/head.php'; ?>
<?php include 'views/layout/header.php'; ?>

<div class="page-wrapper">
    <!-- Publicité à gauche -->
    <aside class="advertisement left">
        <a href="#" target="_blank">
            <img src="https://targetemsecure.blob.core.windows.net/56d1a8d0-8ab1-45fa-831e-4cfe33a13514/8a9ab463-f0c1-4024-b1a6-22f1021cb76d.jpg" alt="Publicité">
        </a>
    </aside>

    <!-- Contenu principal -->
    <div class="cinema-container">
        <main class="seat-selection-container">
            <h1 class="section-title">Choisissez vos sièges</h1>
            
            <div class="screen-container">
                <div class="screen">ÉCRAN</div>
            </div>
            
            <form method="post" action="index.php?page=valider_reservation" class="seat-form">
                <input type="hidden" name="seance_id" value="<?= htmlspecialchars($_GET['seance']) ?>">
                
                <div class="seats-grid">
                    <?php 
                    // Organiser les sièges en rangées (8 sièges par rangée)
                    $seatsPerRow = 8;
                    $totalSeats = count($sieges);
                    $rows = ceil($totalSeats / $seatsPerRow);
                    
                    for ($i = 0; $i < $rows; $i++): 
                        $start = $i * $seatsPerRow;
                        $end = min(($i + 1) * $seatsPerRow, $totalSeats);
                    ?>
                        <div class="seat-row">
                            <?php for ($j = $start; $j < $end; $j++): 
                                $siege = $sieges[$j];
                                $id = $siege['id'];
                                $numero = $siege['numero'];
                                $isAccessible = $siege['accessible'];
                                $isOccupe = in_array($id, $occupes);
                                $disabled = $isOccupe || $isAccessible ? 'disabled' : '';
                                $label = $isOccupe ? 'X' : ($isAccessible ? 'PMR' : $numero);
                                $seatClass = $isOccupe ? 'seat-occupied' : ($isAccessible ? 'seat-pmr' : 'seat-available');
                            ?>
                                <div class="seat-container">
                                    <input type="checkbox" 
                                           id="seat-<?= $id ?>" 
                                           name="sieges[]" 
                                           value="<?= $id ?>" 
                                           class="seat-checkbox"
                                           <?= $disabled ?>>
                                    <label for="seat-<?= $id ?>" class="seat <?= $seatClass ?>">
                                        <span class="seat-number"><?= $label ?></span>
                                    </label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>
                </div>
                
                <div class="seat-legend">
                    <div class="legend-item">
                        <div class="seat seat-available"></div>
                        <span>Disponible</span>
                    </div>
                    <div class="legend-item">
                        <div class="seat seat-pmr"></div>
                        <span>PMR</span>
                    </div>
                    <div class="legend-item">
                        <div class="seat seat-occupied"></div>
                        <span>Occupé</span>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="index.php?page=reservation&cinema=<?= $_GET['cinema'] ?? '' ?>&film=<?= $_GET['film'] ?? '' ?>" class="btn-cancel">Retour</a>
                    <?php if (!isset($_SESSION['utilisateur'])): ?>
                        <p class="login-prompt">🔐 <a href="index.php?page=login">Connectez-vous</a> pour réserver.</p>
                    <?php else: ?>
                        <button type="submit" class="btn-reserve">Valider ma réservation</button>
                    <?php endif; ?>
                </div>
            </form>
        </main>
    </div>

    <!-- Publicité à droite -->
    <aside class="advertisement right">
        <a href="#" target="_blank">
            <img src="https://targetemsecure.blob.core.windows.net/56d1a8d0-8ab1-45fa-831e-4cfe33a13514/8a9ab463-f0c1-4024-b1a6-22f1021cb76d.jpg" alt="Publicité">
        </a>
    </aside>
</div>

<style>
/* Styles pour la sélection des sièges */
.seat-selection-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem;
    color: #ffffff;
}

.section-title {
    color: #e50914;
    text-align: center;
    margin-bottom: 2rem;
    font-size: 2rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.screen-container {
    text-align: center;
    margin-bottom: 3rem;
    perspective: 1000px;
}

.screen {
    display: inline-block;
    background: #e50914;
    color: white;
    padding: 0.5rem 3rem;
    border-radius: 5px;
    margin-bottom: 2rem;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: relative;
    box-shadow: 0 5px 15px rgba(229, 9, 20, 0.3);
}

.screen::after {
    content: '';
    position: absolute;
    bottom: -15px;
    left: 20%;
    right: 20%;
    height: 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 0 0 50% 50%;
    filter: blur(5px);
}

.seats-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 3rem;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.seat-row {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.seat-container {
    position: relative;
}

.seat-checkbox {
    display: none;
}

.seat {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
    border: 2px solid transparent;
}

.seat-available {
    background-color: #2d3748;
    color: #ffffff;
}

.seat-available:hover {
    background-color: #4a5568;
    transform: scale(1.05);
}

.seat-checkbox:checked + .seat-available {
    background-color: #e50914;
    border-color: #ffffff;
}

.seat-pmr {
    background-color: #2c7a7b;
    color: #ffffff;
    cursor: not-allowed;
}

.seat-occupied {
    background-color: #4a5568;
    color: #a0aec0;
    cursor: not-allowed;
    opacity: 0.7;
}

.seat-number {
    font-size: 0.8rem;
    font-weight: bold;
    pointer-events: none;
}

.seat-legend {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    margin: 3rem 0;
    flex-wrap: wrap;
    padding: 1rem;
    background-color: rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    color: #ffffff;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    border: 1px solid #4a5568;
}

.legend-item .seat {
    width: 25px;
    height: 25px;
    cursor: default;
    border: 2px solid #ffffff;
}

.form-actions {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1.5rem;
    margin-top: 2rem;
}

.btn-reserve {
    background-color: #e50914;
    color: white;
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-reserve:hover {
    background-color: #f40612;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(229, 9, 20, 0.4);
}

.btn-reserve:disabled {
    background-color: #4a5568;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-cancel {
    background-color: transparent;
    color: #a0aec0;
    border: 1px solid #4a5568;
    padding: 0.8rem 2rem;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-cancel:hover {
    background-color: rgba(255, 255, 255, 0.05);
    color: #ffffff;
    border-color: #a0aec0;
}

.login-prompt {
    color: #a0aec0;
    margin: 0;
}

.login-prompt a {
    color: #e50914;
    text-decoration: none;
    font-weight: bold;
}

.login-prompt a:hover {
    text-decoration: underline;
}

/* Styles responsifs */
@media (max-width: 768px) {
    .seat {
        width: 30px;
        height: 30px;
        font-size: 0.7rem;
    }
    
    .seat-legend {
        gap: 1rem;
    }
    
    .seat-legend .seat {
        width: 15px;
        height: 15px;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-reserve, .btn-cancel {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php include 'views/layout/footer.php'; ?>
