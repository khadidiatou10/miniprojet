<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><i class="bi bi-plus-circle"></i> <?= $title ?></h4>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= $seance ? base_url('emploiDuTemps/modifier/'.$seance['id_seance']) : base_url('emploiDuTemps/enregistrer') ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_classe" class="form-label">Classe <span class="text-danger">*</span></label>
                                <select name="id_classe" id="id_classe" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach($classes as $c): ?>
                                        <option value="<?= $c['id_class'] ?>" <?= ($seance && $seance['id_classe'] == $c['id_class']) ? 'selected' : '' ?>>
                                            <?= $c['code'] ?> - <?= $c['libelle'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="id_matiere" class="form-label">Matière <span class="text-danger">*</span></label>
                                <select name="id_matiere" id="id_matiere" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach($matieres as $m): ?>
                                        <option value="<?= $m['id_matiere'] ?>" <?= ($seance && $seance['id_matiere'] == $m['id_matiere']) ? 'selected' : '' ?>>
                                            <?= $m['code'] ?> - <?= $m['libelle'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_professeur" class="form-label">Professeur <span class="text-danger">*</span></label>
                                <select name="id_professeur" id="id_professeur" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach($professeurs as $p): ?>
                                        <option value="<?= $p['id_professeur'] ?>" <?= ($seance && $seance['id_professeur'] == $p['id_professeur']) ? 'selected' : '' ?>>
                                            <?= strtoupper($p['nom']) ?> <?= ucfirst($p['prenom']) ?> - <?= $p['specialite'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="type_cours" class="form-label">Type de cours</label>
                                <select name="type_cours" id="type_cours" class="form-select">
                                    <?php foreach($types_cours as $key => $type): ?>
                                        <option value="<?= $key ?>" <?= ($seance && $seance['type_cours'] == $key) ? 'selected' : '' ?>><?= $type ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="jour" class="form-label">Jour <span class="text-danger">*</span></label>
                                <select name="jour" id="jour" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach($jours as $j): ?>
                                        <option value="<?= $j ?>" <?= ($seance && $seance['jour'] == $j) ? 'selected' : '' ?>><?= $j ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="heure_debut" class="form-label">Heure de début <span class="text-danger">*</span></label>
                                <select name="heure_debut" id="heure_debut" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach($creneaux as $heure => $libelle): ?>
                                        <option value="<?= $heure ?>" <?= ($seance && $seance['heure_debut'] == $heure) ? 'selected' : '' ?>><?= $libelle ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="heure_fin" class="form-label">Heure de fin <span class="text-danger">*</span></label>
                                <input type="time" name="heure_fin" id="heure_fin" class="form-control" value="<?= $seance ? $seance['heure_fin'] : '12:00:00' ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="salle" class="form-label">Salle <span class="text-danger">*</span></label>
                                <input type="text" name="salle" id="salle" class="form-control" value="<?= $seance ? $seance['salle'] : '' ?>" required placeholder="Ex: A101, B202">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="annee_scolaire_id" class="form-label">Année scolaire</label>
                                <select name="annee_scolaire_id" id="annee_scolaire_id" class="form-select">
                                    <option value="">-- Année active --</option>
                                    <?php foreach($annees as $a): ?>
                                        <option value="<?= $a['id_annee'] ?>" <?= ($seance && $seance['annee_scolaire_id'] == $a['id_annee']) ? 'selected' : ($annee_active && $a['id_annee'] == $annee_active['id_annee'] ? 'selected' : '') ?>>
                                            <?= $a['libelle'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Règles de non-conflit :</strong>
                            <ul class="mb-0 mt-2">
                                <li>Un professeur ne peut pas avoir deux cours en même temps</li>
                                <li>Une salle ne peut pas être utilisée par deux cours en même temps</li>
                                <li>Une classe ne peut pas avoir deux cours en même temps</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url('emploiDuTemps') ?>" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-save"></i> Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>