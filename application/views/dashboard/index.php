<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card { transition: transform 0.2s; cursor: pointer; }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-icon { font-size: 1.8rem; }
        .stat-card h2 { font-size: 1.6rem; margin-bottom: 0; }
        .stat-card h6 { font-size: 0.75rem; }
        .chart-container { max-width: 250px; margin: 0 auto; }
        .card-header { padding: 8px 12px; }
        .card-body { padding: 12px; }
        .table-sm td, .table-sm th { padding: 6px; font-size: 0.8rem; }
        .btn-sm-custom { padding: 2px 6px; font-size: 0.7rem; }
        .user-info { background-color: #f8f9fa; padding: 5px 12px; border-radius: 20px; }
    </style>
</head>
<body>
<div class="container-fluid mt-3">
    <!-- En-tête avec déconnexion -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-speedometer2"></i> Tableau de bord</h3>
        <div class="d-flex align-items-center">
            <div class="user-info me-3">
                <i class="bi bi-person-circle"></i> 
                <strong><?= $this->session->userdata('nom_complet') ?: $this->session->userdata('username') ?></strong>
                <span class="badge bg-secondary ms-1"><?= $this->session->userdata('role') ?></span>
            </div>
            <a href="<?= base_url('auth/logout') ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment vous déconnecter ?')">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
        </div>
    </div>

    <!-- Point 64 : Statistiques générales -->
    <div class="row mb-3">
        <div class="col-md-3 col-6">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><h6 class="card-title mb-0">Étudiants</h6><h2 class="mb-0"><?= $total_etudiants ?></h2></div>
                        <i class="bi bi-people-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card bg-success text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><h6 class="card-title mb-0">Classes</h6><h2 class="mb-0"><?= $total_classes ?></h2></div>
                        <i class="bi bi-building stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card bg-info text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><h6 class="card-title mb-0">Professeurs</h6><h2 class="mb-0"><?= $total_professeurs ?></h2></div>
                        <i class="bi bi-person-badge stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card bg-warning text-dark">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><h6 class="card-title mb-0">Matières</h6><h2 class="mb-0"><?= $total_matieres ?></h2></div>
                        <i class="bi bi-book stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4 col-6">
            <div class="card stat-card bg-secondary text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><h6 class="card-title mb-0">Inscriptions</h6><h2 class="mb-0"><?= $total_inscriptions ?></h2></div>
                        <i class="bi bi-journal-bookmark-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="card stat-card bg-dark text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><h6 class="card-title mb-0">Total encaissé</h6><h5 class="mb-0"><?= number_format($total_paiements / 1000, 0, ',', ' ') ?> K FCFA</h5></div>
                        <i class="bi bi-cash-stack stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="card stat-card bg-danger text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><h6 class="card-title mb-0">Taux paiement</h6><h2 class="mb-0"><?= $taux_paiement['pourcentage_paye'] ?>%</h2></div>
                        <i class="bi bi-pie-chart stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Graphique camembert -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-primary text-white py-1"><small><i class="bi bi-pie-chart"></i> Paiements</small></div>
                <div class="card-body text-center py-2">
                    <div class="chart-container"><canvas id="paiementChart" width="200" height="200"></canvas></div>
                    <div class="row mt-2">
                        <div class="col-6"><span class="badge bg-success">Payé</span> <small><?= number_format($taux_paiement['paye'] / 1000, 0, ',', ' ') ?> K FCFA</small></div>
                        <div class="col-6"><span class="badge bg-danger">Reste</span> <small><?= number_format($taux_paiement['reste'] / 1000, 0, ',', ' ') ?> K FCFA</small></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 5 étudiants -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-success text-white py-1"><small><i class="bi bi-trophy"></i> Top 5 étudiants</small></div>
                <div class="card-body py-1">
                    <?php if(empty($top_etudiants)): ?>
                        <div class="alert alert-info py-1 small">Aucune note</div>
                    <?php else: ?>
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-dark"><tr><th>#</th><th>Étudiant</th><th>Moy.</th></tr></thead>
                            <tbody>
                                <?php $rang = 1; foreach($top_etudiants as $e): ?>
                                <tr>
                                    <td class="text-center"><?= $rang == 1 ? '🥇' : ($rang == 2 ? '🥈' : ($rang == 3 ? '🥉' : $rang)) ?></td>
                                    <td><small><?= strtoupper(substr($e['nom'], 0, 8)) ?> <?= ucfirst(substr($e['prenom'], 0, 8)) ?></small></td>
                                    <td class="text-center"><span class="badge bg-primary"><?= $e['moyenne'] ?></span></td>
                                </tr>
                                <?php $rang++; endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Prochains cours -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark py-1"><small><i class="bi bi-calendar-event"></i> Cours à venir</small></div>
                <div class="card-body py-1">
                    <?php if(empty($prochains_cours)): ?>
                        <div class="alert alert-info py-1 small">Aucun cours</div>
                    <?php else: ?>
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-dark"><tr><th>Horaire</th><th>Matière</th><th>Salle</th></tr></thead>
                            <tbody>
                                <?php foreach($prochains_cours as $c): ?>
                                <tr>
                                    <td><small><?= date('H:i', strtotime($c['heure_debut'])) ?></small></td>
                                    <td><small><?= $c['matiere_code'] ?></small></td>
                                    <td><small><?= $c['salle'] ?></small></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Derniers étudiants inscrits -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-info text-white py-1"><small><i class="bi bi-person-plus"></i> Derniers inscrits</small></div>
                <div class="card-body py-1">
                    <?php if(empty($derniers_etudiants)): ?>
                        <div class="alert alert-info py-1 small">Aucun étudiant</div>
                    <?php else: ?>
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-dark"><tr><th>Étudiant</th><th>Matricule</th></tr></thead>
                            <tbody>
                                <?php foreach($derniers_etudiants as $e): ?>
                                <tr>
                                    <td><small><strong><?= strtoupper(substr($e['nom'], 0, 10)) ?></strong> <?= ucfirst(substr($e['prenom'], 0, 10)) ?></small></td>
                                    <td><small><?= $e['matricule'] ?? 'E00'.$e['id'] ?></small></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Liens rapides -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-dark text-white py-1"><small><i class="bi bi-link-45deg"></i> Accès rapide</small></div>
                <div class="card-body py-2">
                    <div class="row text-center">
                        <div class="col-4"><a href="<?= base_url('etudiants') ?>" class="btn btn-outline-primary btn-sm w-100 mb-1">👨‍🎓 Étudiants</a></div>
                        <div class="col-4"><a href="<?= base_url('professeur') ?>" class="btn btn-outline-success btn-sm w-100 mb-1">👨‍🏫 Profs</a></div>
                        <div class="col-4"><a href="<?= base_url('note') ?>" class="btn btn-outline-info btn-sm w-100 mb-1">📝 Notes</a></div>
                        <div class="col-4"><a href="<?= base_url('paiement') ?>" class="btn btn-outline-warning btn-sm w-100 mb-1">💰 Paiements</a></div>
                        <div class="col-4"><a href="<?= base_url('presence') ?>" class="btn btn-outline-secondary btn-sm w-100 mb-1">📋 Présences</a></div>
                        <div class="col-4"><a href="<?= base_url('emploiDuTemps') ?>" class="btn btn-outline-dark btn-sm w-100 mb-1">📅 EDT</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('paiementChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Payé', 'Reste'],
            datasets: [{
                data: [<?= $taux_paiement['paye'] ?>, <?= $taux_paiement['reste'] ?>],
                backgroundColor: ['#28a745', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } },
                tooltip: { callbacks: { label: function(context) {
                    let value = context.raw;
                    let total = <?= $taux_paiement['total'] ?>;
                    let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                    return context.label + ': ' + (value/1000).toFixed(0) + 'K FCFA (' + percentage + '%)';
                } } }
            }
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>