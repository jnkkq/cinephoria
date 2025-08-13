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

    <h1 class="section-title">Films à l'affiche</h1>
    <form method="get" action="index.php" class="filtres-form">
        <input type="hidden" name="page" value="films">

        <div class="filtre-groupe">
            <label for="cinema">Cinéma :</label>
            <select name="cinema" id="cinema" class="form-control">
                <option value="">-- Tous les cinémas --</option>
                <?php foreach ($cinemas as $cinema): ?>
                    <option value="<?= $cinema['id'] ?>" <?= ($cinema_id == $cinema['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cinema['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filtre-groupe">
            <label for="genre">Genre :</label>
            <select name="genre" id="genre" class="form-control">
                <option value="">-- Tous les genres --</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= $genre['id'] ?>" <?= ($genre_id == $genre['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($genre['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filtre-groupe">
            <label for="jour">Jour :</label>
            <input type="date" name="jour" id="jour" class="form-control" value="<?= htmlspecialchars($jour) ?>">
        </div>

        <div class="filtre-actions">
            <button type="submit" class="btn-filtre">Filtrer</button>
            <a href="?page=films" class="btn-reinitialiser">Réinitialiser</a>
        </div>
    </form>

    <section class="films-list">
        <h2>Résultats</h2>
        <div class="films">
            <?php if ($films): ?>
                <?php foreach ($films as $film): ?>
                    <div class="film">
                        <h3><?= htmlspecialchars($film['titre']) ?></h3>
                        <a href="index.php?page=reservation&film=<?= $film['id'] ?>" class="film-image-link">
                            <img src="public/images/<?= htmlspecialchars($film['affiche']) ?>">
                        </a>
                        
                        <div class="film-meta">
                            <?php if (!empty($film['genres'])): ?>
                            <div class="film-meta-item">
                                <i class="fas fa-tags"></i>
                                <span><?= implode(', ', array_map('htmlspecialchars', $film['genres'])) ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="film-meta-item">
                                <i class="fas fa-user-lock"></i>
                                <span>À partir de <?= $film['age_minimum'] ?> ans</span>
                            </div>
                            <div class="film-meta-item">
                                <i class="fas fa-star"></i>
                                <span><?= $film['moyenne'] ?? 'N/A' ?>/5</span>
                            </div>
                            <?php if ($film['coup_de_coeur']): ?>
                            <div class="film-coup-de-coeur active">
                                <div>
                                    <i class="fas fa-heart"></i>
                                    <span>Coup de cœur</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <p><?= htmlspecialchars($film['description']) ?></p>

                        <div class="film-actions" onclick="event.stopPropagation()">
                            <button class="btn-voir-avis" onclick="event.stopPropagation(); toggleAvis(this, <?= $film['id'] ?>); return false;">
                                <span>Voir les avis des spectateurs</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>

                        <div class="film-avis" id="avis-<?= $film['id'] ?>">
                            <div class="avis-conteneur">
                                <?php if (isset($_SESSION['utilisateur'])): ?>
                                    <form class="form-avis" method="post" action="index.php?page=avis">
                                        <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                                        
                                        <div class="form-group">
                                            <label for="note">Note (1 à 5) :</label>
                                            <input type="number" name="note" min="1" max="5" required class="form-control" style="width: 80px;">
                                        </div>
                                        
                                        <label for="commentaire">Votre avis</label>
                                        <textarea name="commentaire" placeholder="Partagez votre expérience..." required></textarea>
                                        
                                        <button type="submit">Publier l'avis</button>
                                    </form>
                                <?php else: ?>
                                    <div class="login-prompt" onclick="event.stopPropagation()">
                                        <i class="fas fa-info-circle"></i>
                                        <a href="index.php?page=login" onclick="event.stopPropagation()">Connectez-vous</a> pour laisser votre avis
                                    </div>
                                <?php endif; ?>

                                <div class="commentaires-section">
                                    <?php
                                    require_once 'models/Avis.php';
                                    $avisFilm = Avis::getPourFilm($pdo, $film['id']);
                                    
                                    if ($avisFilm): ?>
                                        <h4>Avis des spectateurs :</h4>
                                        <ul class="avis-liste">
                                            <?php foreach (array_slice($avisFilm, 0, 2) as $avis): ?>
                                                <li class="avis-item">
                                                    <span class="avis-auteur"><?= htmlspecialchars($avis['prenom']) ?> <?= htmlspecialchars($avis['nom']) ?></span>
                                                    <div class="avis-note">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <?php if ($i <= $avis['note']): ?>
                                                                <i class="fas fa-star"></i>
                                                            <?php else: ?>
                                                                <i class="far fa-star"></i>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <p class="avis-texte"><?= nl2br(htmlspecialchars($avis['commentaire'])) ?></p>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php if (count($avisFilm) > 2): ?>
                                            <p class="plus-d-avis">... et <?= count($avisFilm) - 2 ?> autres avis</p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="no-avis">Aucun avis pour le moment.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun film trouvé.</p>
            <?php endif; ?>
        </div>
    </section>
    </main>
    </div>

    <!-- Publicité à droite -->
    <aside class="advertisement right">
        <a href="#" target="_blank">
            <img src="https://targetemsecure.blob.core.windows.net/56d1a8d0-8ab1-45fa-831e-4cfe33a13514/8a9ab463-f0c1-4024-b1a6-22f1021cb76d.jpg" alt="Publicité">
        </a>
    </aside>
</div>

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

// Fonction pour les avis
function toggleAvis(button, filmId) {
    const avisSection = document.getElementById('avis-' + filmId);
    const icon = button.querySelector('i');
    
    if (avisSection.classList.contains('visible')) {
        avisSection.classList.remove('visible');
        button.querySelector('span').textContent = 'Voir les avis des spectateurs';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    } else {
        avisSection.classList.add('visible');
        button.querySelector('span').textContent = 'Masquer les avis';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
        
        // Vérifier si le contenu est défilable
        const conteneur = avisSection.querySelector('.avis-conteneur');
        if (conteneur.scrollHeight > conteneur.clientHeight) {
            conteneur.classList.add('scrollable');
        } else {
            conteneur.classList.remove('scrollable');
        }
    }
}

// Vérifier également au chargement de la page si le contenu est défilable
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.film-avis.visible .avis-conteneur').forEach(conteneur => {
        if (conteneur.scrollHeight > conteneur.clientHeight) {
            conteneur.classList.add('scrollable');
        }
    });
});
</script>

<?php include 'views/layout/footer.php'; ?>
