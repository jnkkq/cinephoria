<?php 
$showAdvancedLink = true; // Pour masquer le lien dans le header
include 'views/layout/head.php'; 
include 'views/layout/header.php'; 
?>

<style>
:root {
    --primary-color: #4e73df;
    --secondary-color: #1cc88a;
    --dark-color: #5a5c69;
    --light-color: #f8f9fc;
}

body { 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.dashboard-container {
    max-width: 1200px;
    margin: 2rem auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    flex: 1;
}

.chart-container {
    position: relative;
    height: 500px;
    margin: 30px 0;
}

.header {
    text-align: center;
    margin-bottom: 30px;
    color: #2c3e50;
}

.last-update {
    text-align: right;
    color: #7f8c8d;
    font-size: 0.9em;
    margin-bottom: 20px;
}

.loading {
    text-align: center;
    padding: 50px;
    font-size: 1.2em;
    color: #7f8c8d;
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .user-menu {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>

<!-- Contenu principal -->
<main class="container-fluid py-4">
    <div class="dashboard-container">
        <div class="header">
            <h1>Tableau de bord des réservations</h1>
            <p class="lead">Statistiques des 7 derniers jours</p>
        </div>
        
        <div class="last-update" id="lastUpdate">Chargement des données...</div>
        
        <div class="chart-container">
            <canvas id="reservationsChart"></canvas>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Film</th>
                        <th class="text-end">Nombre de réservations</th>
                    </tr>
                </thead>
                <tbody id="reservationsTableBody">
                    <tr>
                        <td colspan="2" class="text-center">Chargement des données...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du bouton d'actualisation
    document.getElementById('refreshBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.reload();
    });

    // Fonction pour formater la date
    function formatDate(dateString) {
        const options = { 
            year: 'numeric', 
            month: '2-digit', 
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        };
        return new Date(dateString).toLocaleDateString('fr-FR', options);
    }

    // Charger les données depuis l'API
    fetch('http://localhost/cine-web-mobile-bureautique-main/api/reservations/derniers_jours.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateDashboard(data.data);
                document.getElementById('lastUpdate').textContent = `Dernière mise à jour : ${new Date().toLocaleString('fr-FR')}`;
            } else {
                throw new Error(data.error || 'Erreur lors du chargement des données');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('reservationsTableBody').innerHTML = `
                <tr>
                    <td colspan="2" class="text-center text-danger">
                        Erreur lors du chargement des données: ${error.message}
                    </td>
                </tr>`;
        });

    // Mettre à jour le tableau de bord avec les données
    function updateDashboard(reservationsData) {
        const ctx = document.getElementById('reservationsChart').getContext('2d');
        const labels = [];
        const data = [];
        const backgroundColors = [];
        
        // Trier les films par nombre de réservations (du plus élevé au plus bas)
        const sortedData = [...reservationsData].sort((a, b) => b.total_reservations - a.total_reservations);
        
        // Préparer les données pour le graphique
        sortedData.forEach((item, index) => {
            // Utiliser item.titre au lieu de item.film_titre
            labels.push(item.titre);
            data.push(item.total_reservations);
            // Générer une couleur aléatoire pour chaque segment
            const hue = (index * 137.508) % 360; // Utiliser l'angle d'or pour des couleurs distinctes
            backgroundColors.push(`hsl(${hue}, 70%, 60%)`);
        });
        
        // Créer le graphique
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Répartition des réservations par film',
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} réservations (${percentage}%)`;
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
        
        // Mettre à jour le tableau
        const tbody = document.getElementById('reservationsTableBody');
        tbody.innerHTML = '';
        
        if (sortedData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="2" class="text-center">Aucune réservation trouvée</td>
                </tr>`;
        } else {
            sortedData.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.titre}</td>
                    <td class="text-end">${item.total_reservations}</td>
                `;
                tbody.appendChild(row);
            });
        }
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>
