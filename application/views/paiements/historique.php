<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des paiements</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-clock-history"></i> Historique des paiements</h2>
        <div>
            <a href="<?= base_url('paiement/form/'.$etudiant['id']) ?>" class="btn btn-success btn-sm-custom">
                <i class="bi bi-plus-circle"></i> Nouveau paiement
            </a>
            <a href="<?= base_url('paiement') ?>" class="btn btn-secondary btn-sm-custom">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Messages flash -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

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
                    <p><strong>Matricule :</strong> <?= $etudiant['matricule'] ?? 'Non défini' ?> | <strong>Email :</strong> <?= $etudiant['mail'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Situation financière -->
    <?php if($inscription): ?>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white text-center">
                <div class="card-body"><h5>Total dû</h5><h3><?= number_format($inscription['montant_total'], 0, ',', ' ') ?> FCFA</h3></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white text-center">
                <div class="card-body"><h5>Total payé</h5><h3><?= number_format($total_paye, 0, ',', ' ') ?> FCFA</h3></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white text-center">
                <div class="card-body"><h5>Reste à payer</h5><h3><?= number_format($reste, 0, ',', ' ') ?> FCFA</h3></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Filtre année -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-funnel"></i> Filtrer par année
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="annee_id" class="form-select">
                        <option value="">-- Toutes les années --</option>
                        <?php foreach($annees as $a): ?>
                            <option value="<?= $a['id_annee'] ?>" <?= ($annee_selectionnee == $a['id_annee']) ? 'selected' : '' ?>><?= $a['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des paiements -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-list"></i> Détail des paiements
        </div>
        <div class="card-body">
            <?php if(empty($paiements)): ?>
                <div class="alert alert-info">Aucun paiement enregistré.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th><th>Montant</th><th>Mode</th><th>Référence</th><th>Type</th><th>Mois</th><th>Commentaire</th><th>Reçu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($paiements as $p): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($p['date_paiement'])) ?></td>
                                <td><?= number_format($p['montant'], 0, ',', ' ') ?> FCFA</td>
                                <td><?= $p['mode_paiement'] ?></td>
                                <td><?= $p['reference'] ?: '-' ?></td>
                                <td><?= $types[$p['type_paiement']] ?? $p['type_paiement'] ?></td>
                                <td><?= $p['mois'] ?: '-' ?></td>
                                <td><?= $p['commentaire'] ?: '-' ?></td>
                                <td>
                                    <a href="<?= base_url('paiement/recu/'.$p['id_paiement']) ?>" class="btn btn-sm btn-primary" target="_blank">
                                        <i class="bi bi-printer"></i> Reçu
                                    </a>
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