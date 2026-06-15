<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emploi du temps - <?= $professeur['nom'] ?> <?= $professeur['prenom'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            .table { font-size: 10px; }
        }
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; }
        .seance-cours { background-color: #e3f2fd; }
        .seance-td { background-color: #e8f5e9; }
        .seance-tp { background-color: #fff3e0; }
        .emploi-table th, .emploi-table td { 
            vertical-align: top; 
            padding: 10px;
            min-width: 180px;
        }
        .heure-col { background-color: #f8f9fa; font-weight: bold; width: 100px; }
    </style>
</head>
<body>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h2><i class="bi bi-calendar-week"></i> Emploi du temps - <?= strtoupper($professeur['nom']) ?> <?= ucfirst($professeur['prenom']) ?></h2>
        <div>
            <button onclick="window.print()" class="btn btn-primary btn-sm-custom"><i class="bi bi-printer"></i> Imprimer</button>
            <a href="<?= base_url('emploiDuTemps') ?>" class="btn btn-secondary btn-sm-custom"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
    </div>

    <!-- Infos professeur -->
    <div class="alert alert-info">
        <div class="row">
            <div class="col-md-4"><strong>Spécialité :</strong> <?= $professeur['specialite'] ?: 'Non renseignée' ?></div>
            <div class="col-md-4"><strong>Email :</strong> <?= $professeur['email'] ?></div>
            <div class="col-md-4"><strong>Téléphone :</strong> <?= $professeur['telephone'] ?: 'Non renseigné' ?></div>
        </div>
    </div>

    <!-- Filtre année -->
    <div class="card mb-4 no-print">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-funnel"></i> Filtrer par année scolaire
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="annee_id" class="form-select">
                        <option value="">-- Toutes les années --</option>
                        <?php foreach($annees as $a): ?>
                            <option value="<?= $a['id_annee'] ?>" <?= ($annee_id == $a['id_annee']) ? 'selected' : '' ?>><?= $a['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Grille emploi du temps -->
    <div class="table-responsive">
        <table class="table table-bordered emploi-table">
            <thead class="table-dark">
                <tr>
                    <th class="heure-col">Horaire</th>
                    <?php foreach($jours as $jour): ?>
                        <th class="text-center"><?= $jour ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($creneaux as $heure_debut => $libelle): ?>
                    <tr>
                        <td class="heure-col text-center fw-bold"><?= $libelle ?></td>
                        <?php foreach($jours as $jour): ?>
                            <td class="<?= isset($grille[$jour][$heure_debut]) ? 'seance-' . $grille[$jour][$heure_debut]['type_cours'] : '' ?>">
                                <?php if(isset($grille[$jour][$heure_debut])): 
                                    $s = $grille[$jour][$heure_debut];
                                ?>
                                    <div class="fw-bold"><?= $s['matiere_code'] ?> - <?= $s['matiere_libelle'] ?></div>
                                    <div><i class="bi bi-building"></i> <?= $s['classe_code'] ?> - <?= $s['classe_libelle'] ?></div>
                                    <div><i class="bi bi-door-closed"></i> Salle <?= $s['salle'] ?></div>
                                    <div><small><?= date('H:i', strtotime($s['heure_debut'])) ?> - <?= date('H:i', strtotime($s['heure_fin'])) ?></small></div>
                                    <div class="mt-2 no-print">
                                        <a href="<?= base_url('emploiDuTemps/form/'.$s['id_seance']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="<?= base_url('emploiDuTemps/supprimer/'.$s['id_seance']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette séance ?')"><i class="bi bi-trash"></i></a>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>