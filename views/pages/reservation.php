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
    <main>
    <!-- Carrousel d'images -->
    <div class="carousel-container">
        <div class="carousel-slide">
            <div class="carousel-item">
                <img src="public/images/carousel1.jpg" alt="Film 1">
                <div class="carousel-caption">
                    <h3>Nouveauté</h3>
                    <p>Découvrez notre dernier film</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="public/images/carousel2.jpg" alt="Film 2">
                <div class="carousel-caption">
                    <h3>Bientôt</h3>
                    <p>Prochainement au cinéma</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="public/images/carousel3.jpg" alt="Film 3">
                <div class="carousel-caption">
                    <h3>Événement</h3>
                    <p>Séances spéciales cette semaine</p>
                </div>
            </div>
        </div>
        <button class="carousel-control prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="carousel-control next" onclick="moveSlide(1)">&#10095;</button>
        <div class="carousel-indicators">
            <span class="indicator active" onclick="goToSlide(0)"></span>
            <span class="indicator" onclick="goToSlide(1)"></span>
            <span class="indicator" onclick="goToSlide(2)"></span>
        </div>
    </div>

    <h1 class="section-title">Réserver une séance</h1>
    <form method="get" action="index.php">
        <input type="hidden" name="page" value="reservation">

        <label for="cinema">Choisissez un cinéma :</label>
        <select name="cinema" id="cinema" onchange="this.form.submit()">
            <option value="">-- Sélectionnez --</option>
            <?php foreach ($cinemas as $cinema): ?>
                <option value="<?= $cinema['id'] ?>" <?= ($selectedCinema == $cinema['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cinema['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if (!empty($films)): ?>
            <label for="film">Choisissez un film :</label>
            <select name="film" id="film" onchange="this.form.submit()">
                <option value="">-- Sélectionnez --</option>
                <?php foreach ($films as $film): ?>
                    <option value="<?= $film['id'] ?>" <?= ($selectedFilm == $film['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($film['titre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <?php if (!empty($seances)): ?>
            <h2>Séances disponibles</h2>
            <ul class="seances-list">
                <?php 
                $currentDate = null;
                foreach ($seances as $seance): 
                    $seanceDate = new DateTime($seance['date']);
                    $formattedDate = $seanceDate->format('l d F Y');
                    if ($currentDate !== $formattedDate): 
                        $currentDate = $formattedDate;
                ?>
                    <h3 class="seance-date"><?= ucfirst($formattedDate) ?></h3>
                <?php endif; ?>
                    <li class="seance-item">
                        <div class="seance-info">
                            <span class="seance-time"><?= date('H:i', strtotime($seance['heure_debut'])) ?> - <?= date('H:i', strtotime($seance['heure_fin'])) ?></span>
                            <span class="seance-details">
                                Salle <?= $seance['salle_numero'] ?> | 
                                <?= $seance['qualite'] ?> | 
                                <?= number_format($seance['prix'], 2) ?> €
                            </span>
                        </div>
                        <a href="index.php?page=reservation&cinema=<?= $selectedCinema ?>&film=<?= $selectedFilm ?>&seance=<?= $seance['id'] ?>" class="btn-choose">Choisir</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <style>
                .seances-list {
                    list-style: none;
                    padding: 0;
                    margin: 20px 0;
                }
                .seance-date {
                    color: #e50914;
                    margin: 20px 0 10px;
                    font-size: 1.2em;
                    border-bottom: 1px solid #444;
                    padding-bottom: 5px;
                }
                .seance-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 12px 15px;
                    margin-bottom: 8px;
                    background-color: #1a1a1a;
                    border-radius: 4px;
                    transition: background-color 0.3s;
                }
                .seance-item:hover {
                    background-color: #2a2a2a;
                }
                .seance-info {
                    display: flex;
                    flex-direction: column;
                }
                .seance-time {
                    font-weight: bold;
                    margin-bottom: 5px;
                    color: #ffffff;
                }
                .seance-details {
                    color: #e0e0e0;
                    font-size: 0.9em;
                }
                .btn-choose {
                    background-color: #e50914;
                    color: white;
                    padding: 8px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: background-color 0.3s;
                }
                .btn-choose:hover {
                    background-color: #f40612;
                }
            </style>
        <?php elseif ($selectedFilm): ?>
            <p>Aucune séance disponible pour ce film dans ce cinéma.</p>
        <?php endif; ?>
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

<?php include 'views/layout/footer.php'; ?>

<script>
// Fonctionnalité du carrousel
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-item');
const indicators = document.querySelectorAll('.indicator');
const totalSlides = slides.length;

function updateCarousel() {
    const slideWidth = 100 / totalSlides;
    document.querySelector('.carousel-slide').style.transform = `translateX(-${currentSlide * slideWidth}%)`;
    
    // Mise à jour des indicateurs
    indicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentSlide);
    });
}

function moveSlide(direction) {
    currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
    updateCarousel();
}

function goToSlide(slideIndex) {
    currentSlide = slideIndex;
    updateCarousel();
}

// Défilement automatique
let slideInterval = setInterval(() => moveSlide(1), 5000);

// Arrêter le défilement automatique quand la souris est sur le carrousel
document.querySelector('.carousel-container').addEventListener('mouseenter', () => {
    clearInterval(slideInterval);
});

// Reprendre le défilement automatique quand la souris quitte le carrousel
document.querySelector('.carousel-container').addEventListener('mouseleave', () => {
    slideInterval = setInterval(() => moveSlide(1), 5000);
});

// Initialisation du carrousel
document.addEventListener('DOMContentLoaded', () => {
    updateCarousel();
});
</script>
