<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Générer un bulletin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><i class="bi bi-file-text"></i> Générer un bulletin scolaire</h4>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                    <?php endif; ?>

                    <form method="GET" action="<?= base_url('bulletin/generer') ?>" target="_blank">
                        <div class="mb-3">
                            <label for="id_etudiant" class="form-label">Étudiant <span class="text-danger">*</span></label>
                            <select name="id_etudiant" id="id_etudiant" class="form-select" required>
                                <option value="">-- Sélectionner un étudiant --</option>
                                <?php foreach($etudiants as $e): ?>
                                    <option value="<?= $e['id'] ?>">
                                        <?= strtoupper($e['nom']) ?> <?= ucfirst($e['prenom']) ?> - <?= $e['matricule'] ?? 'E00'.$e['id'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="periode" class="form-label">Période</label>
                                <select name="periode" id="periode" class="form-select">
                                    <?php foreach($periodes as $key => $periode): ?>
                                        <option value="<?= $key ?>"><?= $periode ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="annee_id" class="form-label">Année scolaire</label>
                                <select name="annee_id" id="annee_id" class="form-select">
                                    <option value="">-- Année active --</option>
                                    <?php foreach($annees as $a): ?>
                                        <option value="<?= $a['id_annee'] ?>" <?= ($a['actif'] == 1) ? 'selected' : '' ?>><?= $a['libelle'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-file-text"></i> Générer le bulletin
                            </button>
                        </div>
                    </form>

                    <hr>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Informations :</strong>
                        <ul class="mb-0 mt-2">
                            <li>Le bulletin s'ouvre dans un nouvel onglet</li>
                            <li>Utilisez la fonction d'impression du navigateur pour imprimer</li>
                            <li>Les moyennes sont calculées avec les coefficients</li>
                            <li>Le rang est déterminé par rapport à la classe</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>