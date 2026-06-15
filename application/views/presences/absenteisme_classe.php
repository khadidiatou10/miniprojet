<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Absentéisme par classe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; }
        .progress { height: 25px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-graph-up"></i> Absentéisme par classe</h2>
        <a href="<?= base_url('presence') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-funnel"></i> Sélectionner une classe
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="classe_id" class="form-select">
                        <option value="">-- Sélectionner --</option>
                        <?php foreach($classes as $c): ?>
                            <option value="<?= $c['id_class'] ?>" <?= ($classe_selectionnee == $c['id_class']) ? 'selected' : '' ?>><?= $c['code'] ?> - <?= $c['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Voir stats</button>
                </div>
            </form>
        </div>
    </div>

    <?php if($classe_selectionnee && isset($statistiques)): ?>
        <div class="alert alert-info">
            <strong>Classe :</strong> <?= $classe['code'] ?> - <?= $classe['libelle'] ?><br>
            <strong>Moyenne d'absentéisme de la classe :</strong> <span class="badge bg-primary fs-6"><?= $moyenne_classe ?>%</span>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-people"></i> Détail par étudiant
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Matricule</th><th>Nom & Prénom</th><th>Séances</th><th>Absences</th><th>Justifiées</th><th>Non justifiées</th><th>Taux absence</th><th>Barre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($statistiques as $s): ?>
                            <tr>
                                <td><?= $s['matricule'] ?? 'E00'.$s['id'] ?></td>
                                <td><strong><?= strtoupper($s['nom']) ?></strong> <?= ucfirst($s['prenom']) ?></td>
                                <td><?= $s['total_seances'] ?></td>
                                <td><span class="badge bg-danger"><?= $s['absences'] ?></span></td>
                                <td><span class="badge bg-warning text-dark"><?= $s['absences_justifiees'] ?></span></td>
                                <td><span class="badge bg-danger"><?= $s['absences_non_justifiees'] ?></span></td>
                                <td><?= $s['taux_absence'] ?>%</td>
                                <td style="width:150px">
                                    <div class="progress">
                                        <div class="progress-bar <?= $s['taux_absence'] > 20 ? 'bg-danger' : ($s['taux_absence'] > 10 ? 'bg-warning' : 'bg-success') ?>" style="width: <?= min($s['taux_absence'], 100) ?>%">
                                            <?= $s['taux_absence'] ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>