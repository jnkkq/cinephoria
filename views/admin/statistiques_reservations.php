<?php require 'layout/head.php'; ?>

<style>
    .stat-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }
    
    .stat-card .card-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
        border: none;
    }
    
    .stat-card .card-header h4 {
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
    }
    
    .stat-card .card-body {
        padding: 2rem;
    }
    
    /* Conteneur du graphique */
    .chart-container {
        position: relative;
        height: 500px; /* Hauteur augmentée */
        width: 100%;
        margin: 0 auto;
    }
    
    /* Style spécifique pour le canvas du graphique */
    #reservationsChart {
        width: 100% !important;
        height: 100% !important;
        min-height: 500px;
    }
    
    .film-card {
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .film-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .film-header {
        background-color: #f8f9fc;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e3e6f0;
        border-radius: 10px 10px 0 0;
    }
    
    .film-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #5a5c69;
        margin: 0;
    }
    
    .reservation-count {
        font-size: 1.75rem;
        font-weight: 700;
        color: #4e73df;
    }
    
    .reservation-badge {
        font-size: 0.9rem;
        padding: 0.35rem 0.8rem;
        border-radius: 50px;
        background-color: #e3e6f0;
        color: #4e73df;
        font-weight: 600;
    }
    
    .reservation-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #eaecf4;
        transition: background-color 0.2s;
    }
    
    .reservation-item:last-child {
        border-bottom: none;
    }
    
    .reservation-item:hover {
        background-color: #f8f9fc;
    }
    
    .reservation-date {
        color: #5a5c69;
        font-weight: 500;
    }
    
    .reservation-user {
        color: #858796;
        font-size: 0.9rem;
    }
    
    .chart-container {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-top: 2rem;
    }
    
    .section-title {
        color: #4e73df;
        font-weight: 600;
        margin: 2.5rem 0 1.5rem 0;
        font-size: 1.5rem;
        position: relative;
        padding-bottom: 0.75rem;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #4e73df, #224abe);
        border-radius: 2px;
    }
    
    .no-data {
        text-align: center;
        padding: 3rem 1rem;
    }
    
    .no-data i {
        font-size: 4rem;
        color: #dddfeb;
        margin-bottom: 1.5rem;
    }
    
    .no-data h4 {
        color: #5a5c69;
        margin-bottom: 1rem;
    }
    
    .no-data p {
        color: #858796;
        max-width: 500px;
        margin: 0 auto 1.5rem;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tableau de bord des réservations</h1>
        <div class="d-flex align-items-center">
            <span class="badge bg-primary-soft text-primary px-3 py-2 me-3">
                <i class="fas fa-calendar-alt me-2"></i>
                <span id="date-range">Chargement...</span>
            </span>
            <button class="btn btn-primary" onclick="refreshData()">
                <i class="fas fa-sync-alt me-2"></i>Actualiser
            </button>
        </div>
    </div>
    
    <!-- Carte de résumé -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total des réservations (7j)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-reservations">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Films réservés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-films">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-film fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Moyenne par film</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="avg-per-movie">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Dernière mise à jour</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="last-update">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="stat-card card mb-4">
                <div class="card-header">
                    <h4><i class="fas fa-chart-pie me-2"></i>Répartition des réservations</h4>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container">
                        <canvas id="reservationsChart" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="stat-card card mb-4">
                <div class="card-header">
                    <h4><i class="fas fa-star me-2"></i>Top Films</h4>
                </div>
                <div class="card-body p-0" id="top-movies">
                    <div class="text-center py-5" id="top-movies-loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="stat-card card mb-4">
        <div class="card-header">
            <h4><i class="fas fa-list-ul me-2"></i>Détails des réservations par film</h4>
        </div>
        <div class="card-body">
            <div id="loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-3 text-muted">Chargement des données de réservation...</p>
            </div>
            
            <div id="stats-container" style="display: none;">
                <div id="films-container">
                    <!-- Les cartes de films seront insérées ici par JavaScript -->
                </div>
            </div>
            
            <div id="no-data" class="no-data" style="display: none;">
                <i class="far fa-calendar-times"></i>
                <h4>Aucune donnée disponible</h4>
                <p>Il n'y a aucune réservation enregistrée pour les 7 derniers jours.</p>
                <a href="index.php?page=admin_films" class="btn btn-primary">
                    <i class="fas fa-film me-2"></i>Voir les films
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Inclure Chart.js et Moment.js pour les dates -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/fr.js"></script>

<script>
// Configuration de la locale française pour moment.js
moment.locale('fr');

// Variables globales
let chartInstance = null;
let reservationsData = [];

// Couleurs prédéfinies pour les graphiques
const chartColors = [
    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
    '#5a5c69', '#858796', '#6f42c1', '#fd7e14', '#20c9a6'
];

// Formater une date en français
const formatDate = (dateString) => {
    return moment(dateString).format('DD MMM YYYY [à] HH:mm');
};

// Mettre à jour la plage de dates affichée
const updateDateRange = () => {
    const startDate = moment().subtract(7, 'days').format('DD MMMM YYYY');
    const endDate = moment().format('DD MMMM YYYY');
    document.getElementById('date-range').textContent = `${startDate} au ${endDate}`;
};

// Mettre à jour les cartes de résumé
const updateSummaryCards = (data) => {
    const totalReservations = data.reduce((sum, item) => sum + item.total_reservations, 0);
    const totalFilms = data.length;
    const avgPerMovie = totalFilms > 0 ? (totalReservations / totalFilms).toFixed(1) : 0;
    
    document.getElementById('total-reservations').textContent = totalReservations;
    document.getElementById('total-films').textContent = totalFilms;
    document.getElementById('avg-per-movie').textContent = avgPerMovie;
    document.getElementById('last-update').textContent = moment().format('DD/MM/YYYY [à] HH:mm');
};

// Créer le graphique de répartition
const createChart = (data) => {
    const labels = data.map(item => item.titre);
    const reservationsData = data.map(item => item.total_reservations);
    
    const ctx = document.getElementById('reservationsChart').getContext('2d');
    
    // Détruire le graphique existant s'il existe
    if (chartInstance) {
        chartInstance.destroy();
    }
    
    chartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: reservationsData,
                backgroundColor: chartColors.slice(0, data.length),
                borderWidth: 0,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 20,
                        font: {
                            size: 13
                        },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 14, weight: '600' },
                    bodyFont: { size: 13 },
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return ` ${label}: ${value} réservations (${percentage}%)`;
                        }
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
};

// Générer les cartes de films
const generateFilmCards = (data) => {
    const container = document.getElementById('films-container');
    container.innerHTML = '';
    
    if (data.length === 0) {
        document.getElementById('no-data').style.display = 'block';
        return;
    }
    
    data.forEach((item, index) => {
        const colorIndex = index % chartColors.length;
        const card = document.createElement('div');
        card.className = 'film-card';
        
        // Trier les réservations par date décroissante
        const sortedReservations = [...item.dernieres_reservations].sort((a, b) => 
            new Date(b.date) - new Date(a.date)
        );
        
        // Limiter à 5 réservations pour l'affichage
        const recentReservations = sortedReservations.slice(0, 5);
        
        card.innerHTML = `
            <div class="film-header d-flex justify-content-between align-items-center">
                <h3 class="film-title mb-0">${item.titre}</h3>
                <span class="reservation-badge">
                    <i class="fas fa-ticket-alt me-1"></i>
                    ${item.total_reservations} réservation${item.total_reservations > 1 ? 's' : ''}
                </span>
            </div>
            <div class="reservation-list">
                ${recentReservations.map(res => `
                    <div class="reservation-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="reservation-date">
                                <i class="far fa-calendar-alt me-2"></i>
                                ${formatDate(res.date)}
                            </div>
                            <div class="reservation-user text-muted small">
                                <i class="fas fa-user me-2"></i>
                                Utilisateur #${res.utilisateur_id}
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </div>
                `).join('')}
                ${item.total_reservations > 5 ? `
                    <div class="text-center py-2 small text-muted">
                        + ${item.total_reservations - 5} réservation${item.total_reservations - 5 > 1 ? 's' : ''} supplémentaires
                    </div>
                ` : ''}
            </div>
        `;
        
        container.appendChild(card);
    });
};

// Mettre à jour la section des meilleurs films
const updateTopMovies = (data) => {
    const topMoviesContainer = document.getElementById('top-movies');
    const loadingElement = document.getElementById('top-movies-loading');
    
    if (data.length === 0) {
        topMoviesContainer.innerHTML = `
            <div class="text-center p-4">
                <i class="fas fa-film fa-3x text-muted mb-3"></i>
                <p class="mb-0 text-muted">Aucune donnée disponible</p>
            </div>
        `;
        return;
    }
    
    // Trier par nombre de réservations et prendre les 5 premiers
    const topMovies = [...data]
        .sort((a, b) => b.total_reservations - a.total_reservations)
        .slice(0, 5);
    
    let html = '<div class="list-group list-group-flush">';
    
    topMovies.forEach((item, index) => {
        const colorIndex = index % chartColors.length;
        const width = (item.total_reservations / topMovies[0].total_reservations) * 100;
        
        html += `
            <div class="list-group-item border-0">
                <div class="d-flex align-items-center mb-1">
                    <div class="position-relative me-3" style="width: 36px; height: 36px; border-radius: 8px; background-color: ${chartColors[colorIndex]}25; display: flex; align-items: center; justify-content: center;">
                        <span class="text-primary fw-bold" style="color: ${chartColors[colorIndex]} !important;">${index + 1}</span>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0" style="font-size: 0.9rem;">${item.titre}</h6>
                        <small class="text-muted">${item.total_reservations} réservation${item.total_reservations > 1 ? 's' : ''}</small>
                    </div>
                </div>
                <div class="progress" style="height: 6px; background-color: #eaecf4; border-radius: 3px; overflow: hidden;">
                    <div class="progress-bar" role="progressbar" style="width: ${width}%; background-color: ${chartColors[colorIndex]};" aria-valuenow="${width}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    
    // Supprimer l'indicateur de chargement et ajouter le contenu
    loadingElement.remove();
    topMoviesContainer.innerHTML = html;
};

// Charger les données des réservations
const loadReservationsData = async () => {
    try {
        const response = await fetch('/api/reservations/derniers_jours.php');
        
        if (!response.ok) {
            throw new Error('Erreur lors de la récupération des données');
        }
        
        const data = await response.json();
        
        // Masquer le chargement
        document.getElementById('loading').style.display = 'none';
        
        if (data.success && data.data.length > 0) {
            // Afficher le conteneur principal
            document.getElementById('stats-container').style.display = 'block';
            
            // Mettre à jour les données globales
            reservationsData = data.data;
            
            // Mettre à jour l'interface
            updateSummaryCards(reservationsData);
            createChart(reservationsData);
            generateFilmCards(reservationsData);
            updateTopMovies(reservationsData);
        } else {
            // Aucune donnée disponible
            document.getElementById('no-data').style.display = 'block';
        }
    } catch (error) {
        console.error('Erreur:', error);
        document.getElementById('loading').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Une erreur est survenue lors du chargement des données.
                <div class="mt-2 small">${error.message}</div>
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt me-1"></i> Réessayer
                </button>
            </div>
        `;
    }
};

// Rafraîchir les données
const refreshData = () => {
    // Afficher l'indicateur de chargement
    document.getElementById('loading').style.display = 'block';
    document.getElementById('no-data').style.display = 'none';
    document.getElementById('stats-container').style.display = 'none';
    
    // Réinitialiser le conteneur des meilleurs films
    const topMoviesContainer = document.getElementById('top-movies');
    topMoviesContainer.innerHTML = `
        <div class="text-center py-5" id="top-movies-loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    `;
    
    // Recharger les données
    loadReservationsData();
};

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    // Mettre à jour la plage de dates
    updateDateRange();
    
    // Charger les données
    loadReservationsData();
    
    // Configurer le rafraîchissement automatique toutes les 5 minutes
    setInterval(refreshData, 5 * 60 * 1000);
});
</script>

<?php require 'layout/footer.php'; ?>
