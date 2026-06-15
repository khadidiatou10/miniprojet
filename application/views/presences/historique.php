<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des présences</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; }
        .badge-present { background-color: #28a745; }
        .badge-absent { background-color: #dc3545; }
        .badge-justifie { background-color: #ffc107; color: #000; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-clock-history"></i> Historique des présences</h2>
        <a href="<?= base_url('etudiants') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <!-- Infos étudiant -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <i class="bi bi-person"></i> Informations
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 text-center">
                    <?php if(!empty($etudiant['photo']) && $etudiant['photo'] != 'default.png'): ?>
                        <img src="<?= base_url('uploads/etudiants/'.$etudiant['photo']) ?>" width="70" class="rounded-circle">
                    <?php else: ?>
                        <i class="bi bi-person-circle fs-1 text-secondary"></i>
                    <?php endif; ?>
                </div>
                <div class="col-md-10">
                    <h3><?= strtoupper($etudiant['nom']) ?> <?= ucfirst($etudiant['prenom']) ?></h3>
                    <p><strong>Matricule :</strong> <?= $etudiant['matricule'] ?? 'Non défini' ?> | <strong>Email :</strong> <?= $etudiant['mail'] ?> | <strong>Tél :</strong> <?= $etudiant['telephone'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white text-center">
                <div class="card-body"><h5>Total séances</h5><h3><?= $statistiques['total_seances'] ?></h3></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white text-center">
                <div class="card-body"><h5>Présences</h5><h3><?= $statistiques['total_seances'] - $statistiques['absences'] ?></h3></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white text-center">
                <div class="card-body"><h5>Absences</h5><h3><?= $statistiques['absences'] ?></h3></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark text-center">
                <div class="card-body"><h5>Taux d'absence</h5><h3><?= $statistiques['taux_absence'] ?>%</h3></div>
            </div>
        </div>
    </div>

    <!-- Historique -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-list"></i> Détail des séances
        </div>
        <div class="card-body">
            <?php if(empty($historique)): ?>
                <div class="alert alert-info">Aucune présence enregistrée.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th><th>Classe</th><th>Matière</th><th>Statut</th><th>Commentaire</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($historique as $h): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($h['date_seance'])) ?></td>
                                <td><?= $h['classe_code'] ?> - <?= $h['classe_libelle'] ?></td>
                                <td><?= $h['matiere_code'] ?> - <?= $h['matiere_libelle'] ?></td>
                                <td>
                                    <?php if($h['present']): ?>
                                        <span class="badge badge-present"><i class="bi bi-check-circle"></i> Présent</span>
                                    <?php elseif($h['justifie']): ?>
                                        <span class="badge badge-justifie"><i class="bi bi-file-text"></i> Absence justifiée</span>
                                    <?php else: ?>
                                        <span class="badge badge-absent"><i class="bi bi-x-circle"></i> Absence non justifiée</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $h['commentaire'] ?: '-' ?></td>
                                <td>
                                    <a href="<?= base_url('presence/modifier/'.$h['id_presence']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>