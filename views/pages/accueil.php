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

    <section id="nouveaux-films">
        <h2 class="section-title">NOUVEAUTÉS DE LA SEMAINE</h2>
        <div class="films">
            <?php if ($films): ?>
                <?php foreach ($films as $film): ?>
                    <div class="film" onclick="window.location.href='index.php?page=reservation&film=<?= $film['id'] ?>'" style="cursor: pointer;">
                        <img src="public/images/<?= htmlspecialchars($film['affiche']) ?>" alt="<?= htmlspecialchars($film['titre']) ?>">
                        <h3><?= htmlspecialchars($film['titre']) ?></h3>
                        <p><?= htmlspecialchars($film['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun film trouvé pour mercredi dernier.</p>
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
