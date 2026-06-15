<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Étudiants impayés</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-exclamation-triangle text-danger"></i> Étudiants impayés</h2>
        <a href="<?= base_url('paiement') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="bi bi-funnel"></i> Filtrer
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Classe</label>
                    <select name="classe_id" class="form-select">
                        <option value="">-- Toutes --</option>
                        <?php foreach($classes as $c): ?>
                            <option value="<?= $c['id_class'] ?>" <?= ($classe_selectionnee == $c['id_class']) ? 'selected' : '' ?>><?= $c['code'] ?> - <?= $c['libelle'] ?></option>
                        <?php endforeach; ?>
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
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-danger w-100"><i class="bi bi-search"></i> Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des impayés -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-people"></i> Liste des étudiants impayés
            <span class="badge bg-danger ms-2"><?= count($impayes) ?> étudiant(s)</span>
        </div>
        <div class="card-body">
            <?php if(empty($impayes)): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> Aucun étudiant impayé trouvé.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Matricule</th><th>Nom & Prénom</th><th>Classe</th>
                                <th>Total dû</th><th>Payé</th><th>Reste</th><th>Contact</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($impayes as $e): ?>
                            <tr>
                                <td><?= $e['matricule'] ?? 'E00'.$e['id_etudiant'] ?></td>
                                <td><strong><?= strtoupper($e['nom']) ?></strong> <?= ucfirst($e['prenom']) ?></td>
                                <td><?= $e['classe_code'] ?> - <?= $e['classe_libelle'] ?></td>
                                <td><?= number_format($e['montant_total'], 0, ',', ' ') ?> FCFA</td>
                                <td><?= number_format($e['montant_paye'], 0, ',', ' ') ?> FCFA</td>
                                <td class="text-danger fw-bold"><?= number_format($e['reste'], 0, ',', ' ') ?> FCFA</td>
                                <td>
                                    <i class="bi bi-envelope"></i> <?= $e['mail'] ?><br>
                                    <i class="bi bi-phone"></i> <?= $e['telephone'] ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('paiement/historique/'.$e['id_etudiant']) ?>" class="btn btn-sm btn-info"><i class="bi bi-clock-history"></i></a>
                                    <a href="<?= base_url('paiement/form/'.$e['id_etudiant']) ?>" class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i></a>
                                    <a href="mailto:<?= $e['mail'] ?>?subject=Relance%20paiement%20frais%20scolaires&body=Bonjour,<br><br>Nous vous rappelons que votre situation financière présente un solde impayé de <?= number_format($e['reste'], 0, ',', ' ') ?> FCFA.<br><br>Cordialement." class="btn btn-sm btn-primary">
                                        <i class="bi bi-envelope-paper"></i> Relancer
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