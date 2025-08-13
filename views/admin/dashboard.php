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
                    <h3>Gestion des films</h3>
                    <p>Ajoutez et gérez vos films facilement</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="public/images/carousel2.jpg" alt="Film 2">
                <div class="carousel-caption">
                    <h3>Planification des séances</h3>
                    <p>Organisez les projections de vos films</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="public/images/carousel3.jpg" alt="Film 3">
                <div class="carousel-caption">
                    <h3>Gestion des avis</h3>
                    <p>Modérez les commentaires des spectateurs</p>
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
    
    <h1 class="section-title" style="margin: 2rem 0 1.5rem 0;">PANNEAU DE GESTION</h1>
    
    <script>
    function remplirFilm(film) {
        // Remplir les champs du formulaire
        document.getElementById('film_id').value = film.id;
        document.getElementById('film_titre').value = film.titre;
        document.getElementById('film_description').value = film.description || '';
        document.getElementById('film_age_minimum').value = film.age_minimum;
        document.getElementById('film_affiche').value = film.affiche || '';
        document.getElementById('film_coup_de_coeur').checked = film.coup_de_coeur == 1;
        
        // Décocher tous les genres
        document.querySelectorAll('input[name="genres[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Cocher les genres du film
        if (film.genres && film.genres.length > 0) {
            film.genres.forEach(genre => {
                const checkbox = document.getElementById(`genre_${genre.id}`);
                if (checkbox) checkbox.checked = true;
            });
        }
        
        // Changer le texte des boutons pour la modification
        document.querySelector('button[name="ajouter_film"]').style.display = 'none';
        document.querySelector('button[name="modifier_film"]').style.display = 'inline-block';
    }
    </script>

    <script>
    // Fonction pour réinitialiser le formulaire de film
    function resetFilmForm() {
        document.getElementById('film_id').value = '';
        document.getElementById('film_titre').value = '';
        document.getElementById('film_description').value = '';
        document.getElementById('film_age_minimum').value = '';
        document.getElementById('film_affiche').value = '';
        document.getElementById('film_coup_de_coeur').checked = false;
        
        // Décocher toutes les cases de genre
        document.querySelectorAll('input[name="genres[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Réafficher le bouton Ajouter et cacher le bouton Modifier
        document.querySelector('button[name="ajouter_film"]').style.display = 'inline-block';
        document.querySelector('button[name="modifier_film"]').style.display = 'none';
    }
    </script>

    <section>
        <h2 style="margin-bottom:0.7em;">🎬 Films</h2>
        <form method="post" enctype="multipart/form-data" style="margin-bottom:2em;">
            <input type="hidden" name="film_id" id="film_id">
            <label>Titre :</label>
            <input type="text" name="titre" id="film_titre" required>
            <label>Description :</label>
            <textarea name="description" id="film_description" required></textarea>
            <label>Âge minimum :</label>
            <input type="number" name="age_minimum" id="film_age_minimum" required>
            <label>Affiche (URL ou upload) :</label>
            <input type="text" name="affiche" id="film_affiche" placeholder="https://...">
            
            <label>Genres :</label>
            <div class="genres-container">
                <?php foreach ($genres as $genre): ?>
                    <label class="genre-checkbox">
                        <input type="checkbox" name="genres[]" value="<?= $genre['id'] ?>" id="genre_<?= $genre['id'] ?>">
                        <?= htmlspecialchars($genre['nom']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
            
            <label>Coup de cœur :</label>
            <input type="checkbox" name="coup_de_coeur" id="film_coup_de_coeur">
            <button class="cinema-btn" type="submit" name="ajouter_film">Ajouter</button>
            <button class="cinema-btn" type="submit" name="modifier_film" style="display:none;">Modifier</button>
            <button type="button" class="cinema-btn" onclick="resetFilmForm()" style="background-color: #6c757d;">Annuler</button>
        </form>
        <!-- Affichage des films en cards -->
        <div class="cinema-cards">
            <?php foreach ($films as $film): ?>
                <div class="cinema-card">
                    <!-- Affiche du film -->
                    <?php 
                    $imagePath = $film['affiche'] ? 
                        (strpos($film['affiche'], 'http') === 0 ? 
                            $film['affiche'] : 
                            'public/images/' . ltrim($film['affiche'], '/')) : 
                        'https://via.placeholder.com/260x340?text=Aucune+affiche';
                    ?>
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="Affiche du film <?= htmlspecialchars($film['titre']) ?>">
                    <div class="cinema-card-content">
                        <div class="cinema-card-title"><?= htmlspecialchars($film['titre']) ?></div>
                        <div class="cinema-card-desc"><?= nl2br(htmlspecialchars($film['description'] ?? '')) ?></div>
                        <div class="cinema-card-age">Âge minimum : <?= $film['age_minimum'] ?> ans</div>
                        <?php if (!empty($film['coup_de_coeur'])): ?>
                            <div class="cinema-card-coeur">❤️ Coup de cœur</div>
                        <?php endif; ?>
                        <div class="cinema-card-actions">
                            <button class="cinema-btn" type="button" onclick='remplirFilm(<?= json_encode($film) ?>)'>✏️ Modifier</button>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                                <button class="cinema-btn" name="supprimer_film" onclick="return confirm('Supprimer ce film ?')">❌ Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <script>
    function remplirFilm(film) {
        // Remplir les champs du formulaire
        document.getElementById('film_id').value = film.id;
        document.getElementById('film_titre').value = film.titre;
        document.getElementById('film_description').value = film.description || '';
        document.getElementById('film_age_minimum').value = film.age_minimum;
        document.getElementById('film_affiche').value = film.affiche || '';
        document.getElementById('film_coup_de_coeur').checked = film.coup_de_coeur == 1;
        
        // Décocher tous les genres
        document.querySelectorAll('input[name="genres[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Cocher les genres du film
        if (film.genres && film.genres.length > 0) {
            film.genres.forEach(genre => {
                const checkbox = document.getElementById(`genre_${genre.id}`);
                if (checkbox) checkbox.checked = true;
            });
        }
        
        // Changer le texte des boutons pour la modification
        document.querySelector('button[name="ajouter_film"]').style.display = 'none';
        document.querySelector('button[name="modifier_film"]').style.display = 'inline-block';
    }
    </script>

    <section>
        <h2>📅 Séances</h2>
        <form method="post" style="margin-bottom:1em;">
            <input type="hidden" name="seance_id" id="seance_id">
            <label>Film :</label>
            <select name="film_id" id="seance_film_id" required>
                <?php foreach ($films as $film): ?>
                    <option value="<?= $film['id'] ?>"><?= htmlspecialchars($film['titre']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Cinéma :</label>
            <select name="cinema_id" id="seance_cinema_id" required>
                <?php foreach ($cinemas as $cinema): ?>
                    <option value="<?= $cinema['id'] ?>"><?= htmlspecialchars($cinema['nom']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Salle :</label>
            <select name="salle_id" id="seance_salle_id" required>
                <?php foreach ($salles as $salle): ?>
                    <option value="<?= $salle['id'] ?>"><?= $salle['numero'] ?></option>
                <?php endforeach; ?>
            </select>
            <label>Date :</label>
            <input type="date" name="date" id="seance_date" required>
            <label>Heure début :</label>
            <input type="time" name="heure_debut" id="seance_heure_debut" required>
            <label>Heure fin :</label>
            <input type="time" name="heure_fin" id="seance_heure_fin" required>
            <label>Qualité :</label>
            <input type="text" name="qualite" id="seance_qualite" required>
            <label>Prix (€) :</label>
            <input type="number" name="prix" id="seance_prix" step="0.01" required>
            <button type="submit" name="ajouter_seance">Ajouter</button>
            <button type="submit" name="modifier_seance">Modifier</button>
        </form>
        <table border="1">
            <tr><th>Film</th><th>Cinéma</th><th>Salle</th><th>Date</th><th>Heure début</th><th>Heure fin</th><th>Qualité</th><th>Prix</th><th>Actions</th></tr>
            <?php foreach ($seances as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['film']) ?></td>
                    <td><?= htmlspecialchars($s['cinema']) ?></td>
                    <td><?= $s['salle'] ?></td>
                    <td><?= $s['date'] ?></td>
                    <td><?= $s['heure_debut'] ?></td>
                    <td><?= $s['heure_fin'] ?></td>
                    <td><?= htmlspecialchars($s['qualite']) ?></td>
                    <td><?= $s['prix'] ?> €</td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" onclick='remplirSeance(<?= json_encode($s) ?>)'>✏️</button>
                            <form method="post">
                                <input type="hidden" name="seance_id" value="<?= $s['id'] ?>">
                                <button type="submit" name="supprimer_seance" onclick="return confirm('Supprimer cette séance ?')">❌</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
    <script>
    function remplirSeance(s) {
        document.getElementById('seance_id').value = s.id;
        document.getElementById('seance_film_id').value = s.film_id;
        document.getElementById('seance_cinema_id').value = s.cinema_id;
        document.getElementById('seance_salle_id').value = s.salle_id;
        document.getElementById('seance_date').value = s.date;
        document.getElementById('seance_heure_debut').value = s.heure_debut;
        document.getElementById('seance_heure_fin').value = s.heure_fin;
        document.getElementById('seance_qualite').value = s.qualite;
        document.getElementById('seance_prix').value = s.prix;
    }
    </script>

    <section>
        <h2>💬 Avis à valider</h2>
        <table border="1">
            <tr><th>Film</th><th>Utilisateur</th><th>Note</th><th>Commentaire</th><th>Actions</th></tr>
            <?php foreach ($avis as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['titre']) ?></td>
                    <td><?= htmlspecialchars($a['prenom']) ?> <?= htmlspecialchars($a['nom']) ?></td>
                    <td><?= $a['note'] ?>/5</td>
                    <td><?= nl2br(htmlspecialchars($a['commentaire'])) ?></td>
                    <td>
                        <div class="action-buttons">
                            <form method="post">
                                <button type="submit" name="valider_avis" value="<?= $a['id'] ?>">✅</button>
                            </form>
                            <form method="post">
                                <button type="submit" name="supprimer_avis" value="<?= $a['id'] ?>">❌</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
    <section>
        <h2>💬 Avis validés</h2>
        <table border="1">
            <tr><th>Film</th><th>Utilisateur</th><th>Note</th><th>Commentaire</th><th>Actions</th></tr>
            <?php foreach ($avis_valides as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['titre']) ?></td>
                    <td><?= htmlspecialchars($a['prenom']) ?> <?= htmlspecialchars($a['nom']) ?></td>
                    <td><?= $a['note'] ?>/5</td>
                    <td><?= nl2br(htmlspecialchars($a['commentaire'])) ?></td>
                    <td>
                        <div class="action-buttons single-button">
                            <form method="post">
                                <button type="submit" name="supprimer_avis" value="<?= $a['id'] ?>">❌</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <section>
        <h2>👤 Gestion des employés</h2>
        <form id="employe-form" method="post" style="margin-bottom:1em;">
            <input type="hidden" name="employe_id" id="employe_id">
            <label>Prénom :</label>
            <input type="text" name="prenom" id="employe_prenom" required>
            <label>Nom :</label>
            <input type="text" name="nom" id="employe_nom" required>
            <label>Email :</label>
            <input type="email" name="email" id="employe_email" required>
            <label>Nom d'utilisateur :</label>
            <input type="text" name="username" id="employe_username" required>
            <label>Mot de passe :</label>
            <input type="password" name="mot_de_passe" id="employe_mot_de_passe" required>
            <label>Rôle :</label>
            <select name="role" id="employe_role" required>
                <option value="employe">Employé</option>
                <option value="admin">Administrateur</option>
            </select>
            <button type="submit" name="ajouter_employe">Ajouter</button>
            <button type="submit" name="modifier_employe">Modifier</button>
        </form>
        
        <table border="1">
            <tr><th>ID</th><th>Prénom</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr>
            <?php foreach ($employes as $employe): ?>
            <tr>
                <td><?= $employe['id'] ?></td>
                <td><?= htmlspecialchars($employe['prenom']) ?></td>
                <td><?= htmlspecialchars($employe['nom']) ?></td>
                <td><?= htmlspecialchars($employe['email']) ?></td>
                <td><?= ucfirst(htmlspecialchars($employe['role'])) ?></td>
                <td>
                    <div class="action-buttons">
                        <button type="button" onclick='remplirEmploye(<?= json_encode($employe) ?>)'>✏️</button>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="employe_id" value="<?= $employe['id'] ?>">
                            <button type="submit" name="supprimer_employe" onclick="return confirm('Supprimer cet employé ?')">❌</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
    <script>
    function remplirEmploye(e) {
        document.getElementById('employe_id').value = e.id;
        document.getElementById('employe_prenom').value = e.prenom;
        document.getElementById('employe_nom').value = e.nom;
        document.getElementById('employe_email').value = e.email;
        document.getElementById('employe_role').value = e.role;
    }
    </script>
    <section>
        <h2>🪑 Gestion des salles</h2>
        <form method="post" style="margin-bottom:1em;">
            <input type="hidden" name="id" id="salle_id">
            <label>Numéro :</label>
            <input type="number" name="numero" id="numero" required>
            <label>Capacité :</label>
            <input type="number" name="capacite" id="capacite" required>
            <label>Qualité projection :</label>
            <input type="text" name="qualite_projection" id="qualite_projection" required>
            <label>Cinéma :</label>
            <select name="cinema_id" id="cinema_id" required>
                <?php foreach ($cinemas as $cinema): ?>
                    <option value="<?= $cinema['id'] ?>"><?= htmlspecialchars($cinema['nom']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="ajouter_salle" class="cinema-btn">Ajouter</button>
            <button type="submit" name="modifier_salle" class="cinema-btn">Modifier</button>
        </form>

        <table border="1">
            <tr><th>ID</th><th>Numéro</th><th>Capacité</th><th>Qualité</th><th>Cinéma</th><th>Actions</th></tr>
            <?php foreach ($salles as $salle): ?>
            <tr>
                <td><?= $salle['id'] ?></td>
                <td><?= $salle['numero'] ?></td>
                <td><?= $salle['capacite'] ?></td>
                <td><?= $salle['qualite_projection'] ?></td>
                <td><?= htmlspecialchars($salle['cinema_nom']) ?></td>
                <td>
                    <div class="action-buttons">
                        <button type="button" onclick="remplirFormulaire(<?= htmlspecialchars(json_encode($salle)) ?>)">✏️</button>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $salle['id'] ?>">
                            <button type="submit" name="supprimer_salle" onclick="return confirm('Supprimer cette salle ?')">❌</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
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
function remplirFormulaire(salle) {
    document.getElementById('salle_id').value = salle.id;
    document.getElementById('numero').value = salle.numero;
    document.getElementById('capacite').value = salle.capacite;
    document.getElementById('qualite_projection').value = salle.qualite_projection;
    document.getElementById('cinema_id').value = salle.cinema_id;
}
</script>
    


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
