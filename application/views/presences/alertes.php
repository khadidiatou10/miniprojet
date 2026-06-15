<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Alertes d'absence</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-exclamation-triangle"></i> Alertes d'absence</h2>
        <a href="<?= base_url('presence') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <i class="bi bi-sliders2"></i> Seuil d'alerte
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Nombre d'absences non justifiées</label>
                    <input type="number" name="seuil" class="form-control" value="<?= $seuil ?>" min="1">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-warning">Appliquer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-danger text-white">
            <i class="bi bi-people"></i> Étudiants dépassant le seuil (> <?= $seuil ?> absences non justifiées)
        </div>
        <div class="card-body">
            <?php if(empty($alertes)): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> Aucun étudiant ne dépasse le seuil d'absence.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Matricule</th><th>Nom & Prénom</th><th>Classe</th><th>Absences non justifiées</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($alertes as $a): ?>
                            <tr>
                                <td><?= $a['matricule'] ?? 'Non défini' ?></td>
                                <td><strong><?= strtoupper($a['nom']) ?></strong> <?= ucfirst($a['prenom']) ?></td>
                                <td><?= $a['classe_libelle'] ?></td>
                                <td><span class="badge bg-danger fs-6"><?= $a['absences_non_justifiees'] ?></span></td>
                                <td>
                                    <a href="<?= base_url('presence/historique/'.$a['id']) ?>" class="btn btn-sm btn-info"><i class="bi bi-clock-history"></i> Historique</a>
                                    <a href="mailto:<?= $a['mail'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-envelope"></i> Envoyer alerte</a>
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