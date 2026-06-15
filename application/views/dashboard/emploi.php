<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emploi du temps de la semaine</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none !important; }
        }
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; }
        .emploi-table th, .emploi-table td { vertical-align: middle; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h2><i class="bi bi-calendar-week"></i> Emploi du temps de la semaine</h2>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <!-- Filtre par classe -->
    <div class="card mb-4 no-print">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-funnel"></i> Filtrer par classe
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="classe_id" class="form-select">
                        <option value="">-- Toutes les classes --</option>
                        <?php foreach($classes as $c): ?>
                            <option value="<?= $c['id_class'] ?>" <?= (isset($classe) && $classe && $classe['id_class'] == $c['id_class']) ? 'selected' : '' ?>>
                                <?= $c['code'] ?> - <?= $c['libelle'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <?php if(isset($classe) && $classe): ?>
        <div class="alert alert-info">Emploi du temps de la classe : <strong><?= $classe['code'] ?> - <?= $classe['libelle'] ?></strong></div>
    <?php endif; ?>

    <!-- Grille emploi du temps -->
    <div class="table-responsive">
        <table class="table table-bordered emploi-table">
            <thead class="table-dark">
                <tr>
                    <th>Jour</th>
                    <th>Horaire</th>
                    <th>Matière</th>
                    <th>Professeur</th>
                    <th>Salle</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                $has_content = false;
                foreach($jours as $jour):
                    if(isset($emploi[$jour]) && !empty($emploi[$jour])):
                        $has_content = true;
                        $first = true;
                        foreach($emploi[$jour] as $cours):
                ?>
                    <tr>
                        <?php if($first): ?>
                            <td rowspan="<?= count($emploi[$jour]) ?>" class="align-middle bg-light fw-bold"><?= $jour ?></td>
                        <?php endif; ?>
                        <td><?= date('H:i', strtotime($cours['heure_debut'])) ?> - <?= date('H:i', strtotime($cours['heure_fin'])) ?></td>
                        <td><?= $cours['matiere_code'] ?> - <?= $cours['matiere_libelle'] ?></td>
                        <td><?= $cours['professeur_nom'] ?> <?= $cours['professeur_prenom'] ?></td>
                        <td><?= $cours['salle'] ?></td>
                        <td>
                            <?php if($cours['type_cours'] == 'cours'): ?>
                                <span class="badge bg-info">Cours</span>
                            <?php elseif($cours['type_cours'] == 'td'): ?>
                                <span class="badge bg-success">TD</span>
                            <?php else: ?>
                                <span class="badge bg-warning">TP</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                        <?php 
                        $first = false;
                        endforeach; 
                    endif;
                endforeach;
                if(!$has_content):
                ?>
                <tr><td colspan="6" class="text-center text-muted">Aucun cours programmé cette semaine</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>