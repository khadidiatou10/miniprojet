<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emploi du Temps</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; white-space: nowrap; }
        .card-link { cursor: pointer; transition: transform 0.2s; }
        .card-link:hover { transform: translateY(-5px); }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-calendar-week"></i> Emploi du Temps</h2>
        <a href="<?= base_url('emploiDuTemps/form') ?>" class="btn btn-primary btn-sm-custom">
            <i class="bi bi-plus-circle"></i> Ajouter une séance
        </a>
    </div>

    <!-- Messages flash -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $this->session->flashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= $this->session->flashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <!-- Sélection par classe -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-building"></i> Emploi du temps par classe
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('emploiDuTemps/classe') ?>" class="row g-3">
                <div class="col-md-5">
                    <select name="classe_id" class="form-select" required>
                        <option value="">-- Sélectionner une classe --</option>
                        <?php foreach($classes as $c): ?>
                            <option value="<?= $c['id_class'] ?>"><?= $c['code'] ?> - <?= $c['libelle'] ?> (<?= $c['niveau'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="annee_id" class="form-select">
                        <option value="">-- Année active --</option>
                        <?php foreach($annees as $a): ?>
                            <option value="<?= $a['id_annee'] ?>" <?= ($annee_active && $a['id_annee'] == $annee_active['id_annee']) ? 'selected' : '' ?>><?= $a['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-eye"></i> Voir l'emploi du temps</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sélection par professeur -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <i class="bi bi-person-badge"></i> Emploi du temps par professeur
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('emploiDuTemps/professeur') ?>" class="row g-3">
                <div class="col-md-5">
                    <select name="professeur_id" class="form-select" required>
                        <option value="">-- Sélectionner un professeur --</option>
                        <?php foreach($professeurs as $p): ?>
                            <option value="<?= $p['id_professeur'] ?>"><?= strtoupper($p['nom']) ?> <?= ucfirst($p['prenom']) ?> - <?= $p['specialite'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="annee_id" class="form-select">
                        <option value="">-- Année active --</option>
                        <?php foreach($annees as $a): ?>
                            <option value="<?= $a['id_annee'] ?>" <?= ($annee_active && $a['id_annee'] == $annee_active['id_annee']) ? 'selected' : '' ?>><?= $a['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-eye"></i> Voir l'emploi du temps</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liens rapides -->
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-light card-link" onclick="window.location.href='<?= base_url('emploiDuTemps/classe') ?>'">
                <div class="card-body text-center">
                    <i class="bi bi-building fs-1 text-primary"></i>
                    <h5 class="mt-2">Voir tous les emplois du temps par classe</h5>
                    <p class="text-muted">Consultez l'emploi du temps de chaque classe</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light card-link" onclick="window.location.href='<?= base_url('emploiDuTemps/professeur') ?>'">
                <div class="card-body text-center">
                    <i class="bi bi-person-badge fs-1 text-success"></i>
                    <h5 class="mt-2">Voir tous les emplois du temps par professeur</h5>
                    <p class="text-muted">Consultez l'emploi du temps de chaque professeur</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>