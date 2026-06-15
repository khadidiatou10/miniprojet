<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Paiements</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; white-space: nowrap; }
        .statut-paye { background-color: #28a745; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .statut-partiel { background-color: #ffc107; color: #000; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .statut-impaye { background-color: #dc3545; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-credit-card"></i> Gestion des Paiements</h2>
        <div>
            <a href="<?= base_url('paiement/impayes') ?>" class="btn btn-danger btn-sm-custom">
                <i class="bi bi-exclamation-triangle"></i> Impayés
            </a>
            <a href="<?= base_url('paiement/frais') ?>" class="btn btn-info btn-sm-custom">
                <i class="bi bi-calculator"></i> Configurer frais
            </a>
            <a href="<?= base_url('paiement/form') ?>" class="btn btn-primary btn-sm-custom">
                <i class="bi bi-plus-circle"></i> Nouveau paiement
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

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-funnel"></i> Filtrer
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Classe</label>
                    <select name="classe_id" class="form-select">
                        <option value="">-- Toutes --</option>
                        <?php foreach($classes as $c): ?>
                            <option value="<?= $c['id_class'] ?>" <?= ($classe_selectionnee == $c['id_class']) ? 'selected' : '' ?>><?= $c['code'] ?> - <?= $c['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">-- Tous --</option>
                        <option value="paye" <?= ($statut_selectionne == 'paye') ? 'selected' : '' ?>>Payé</option>
                        <option value="partiel" <?= ($statut_selectionne == 'partiel') ? 'selected' : '' ?>>Partiel</option>
                        <option value="impaye" <?= ($statut_selectionne == 'impaye') ? 'selected' : '' ?>>Impayé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Année scolaire</label>
                    <select name="annee_id" class="form-select">
                        <option value="">-- Année active --</option>
                        <?php foreach($annees as $a): ?>
                            <option value="<?= $a['id_annee'] ?>" <?= ($annee_selectionnee == $a['id_annee']) ? 'selected' : '' ?>><?= $a['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white text-center">
                <div class="card-body"><h5>Total inscrits</h5><h3><?= $total_inscrits ?></h3></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white text-center">
                <div class="card-body"><h5>Payé</h5><h3><?= $total_paye ?></h3></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark text-center">
                <div class="card-body"><h5>Partiel</h5><h3><?= $total_partiel ?></h3></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white text-center">
                <div class="card-body"><h5>Impayé</h5><h3><?= $total_impayes ?></h3></div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <strong>Montant total dû :</strong> <?= number_format($montant_total_du, 0, ',', ' ') ?> FCFA<br>
                    <strong>Montant total payé :</strong> <?= number_format($montant_total_paye, 0, ',', ' ') ?> FCFA<br>
                    <strong>Taux de recouvrement :</strong> 
                    <div class="progress mt-2" style="height: 25px;">
                        <div class="progress-bar bg-success" style="width: <?= $taux_recouvrement ?>%"><?= $taux_recouvrement ?>%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des étudiants -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-people"></i> Situation financière des étudiants
        </div>
        <div class="card-body">
            <?php if(empty($etudiants)): ?>
                <div class="alert alert-info">Aucun étudiant trouvé.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Matricule</th><th>Nom & Prénom</th><th>Classe</th>
                                <th>Total dû</th><th>Payé</th><th>Reste</th><th>Statut</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($etudiants as $e): ?>
                            <tr>
                                <td><?= $e['matricule'] ?? 'E00'.$e['id_etudiant'] ?></td>
                                <td><strong><?= strtoupper($e['nom']) ?></strong> <?= ucfirst($e['prenom']) ?></td>
                                <td><?= $e['classe_code'] ?> - <?= $e['classe_libelle'] ?></td>
                                <td><?= number_format($e['montant_total'], 0, ',', ' ') ?> FCFA</td>
                                <td><?= number_format($e['montant_paye'], 0, ',', ' ') ?> FCFA</td>
                                <td class="text-danger fw-bold"><?= number_format($e['reste'], 0, ',', ' ') ?> FCFA</td>
                                <td>
                                    <?php if($e['statut_paiement'] == 'paye'): ?>
                                        <span class="statut-paye"><i class="bi bi-check-circle"></i> Payé</span>
                                    <?php elseif($e['statut_paiement'] == 'partiel'): ?>
                                        <span class="statut-partiel"><i class="bi bi-exclamation-circle"></i> Partiel</span>
                                    <?php else: ?>
                                        <span class="statut-impaye"><i class="bi bi-x-circle"></i> Impayé</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('paiement/historique/'.$e['id_etudiant']) ?>" class="btn btn-sm btn-info"><i class="bi bi-clock-history"></i></a>
                                    <a href="<?= base_url('paiement/form/'.$e['id_etudiant']) ?>" class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i></a>
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