<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Présences</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; white-space: nowrap; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-calendar-check"></i> Gestion des Présences</h2>
        <div>
            <a href="<?= base_url('presence/absenteisme_classe') ?>" class="btn btn-info btn-sm-custom">
                <i class="bi bi-graph-up"></i> Statistiques
            </a>
            <a href="<?= base_url('presence/alertes') ?>" class="btn btn-warning btn-sm-custom">
                <i class="bi bi-exclamation-triangle"></i> Alertes
            </a>
        </div>
    </div>

    <!-- Messages flash -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $this->session->flashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= $this->session->flashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <!-- Formulaire de sélection -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-funnel"></i> Sélectionner la séance
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('presence/saisie') ?>" class="row g-3">
                <div class="col-md-4">
                    <label for="classe_id" class="form-label">Classe <span class="text-danger">*</span></label>
                    <select name="classe_id" id="classe_id" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach($classes as $c): ?>
                            <option value="<?= $c['id_class'] ?>"><?= $c['code'] ?> - <?= $c['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="matiere_id" class="form-label">Matière <span class="text-danger">*</span></label>
                    <select name="matiere_id" id="matiere_id" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach($matieres as $m): ?>
                            <option value="<?= $m['id_matiere'] ?>"><?= $m['code'] ?> - <?= $m['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_seance" class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date_seance" id="date_seance" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-play-fill"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liens rapides -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-person-video3 fs-1"></i>
                    <h5>Historique étudiant</h5>
                    <a href="<?= base_url('etudiants') ?>" class="btn btn-sm btn-primary">Voir les étudiants</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up fs-1"></i>
                    <h5>Statistiques par classe</h5>
                    <a href="<?= base_url('presence/absenteisme_classe') ?>" class="btn btn-sm btn-info">Voir stats</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                    <h5>Alertes absences</h5>
                    <a href="<?= base_url('presence/alertes') ?>" class="btn btn-sm btn-warning">Voir alertes</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>