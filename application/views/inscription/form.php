<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4><i class="fas fa-plus-circle"></i> Nouvelle Inscription</h4>
                    </div>
                    <div class="card-body">
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('inscription/enregistrer') ?>" method="post">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                            <div class="mb-3">
                                <label for="id_etudiant" class="form-label">Étudiant <span class="text-danger">*</span></label>
                                <select name="id_etudiant" id="id_etudiant" class="form-select" required>
                                    <option value="">-- Sélectionner un étudiant --</option>
                                    <?php foreach($etudiants as $etudiant): ?>
                                        <option value="<?= $etudiant['id'] ?>">
                                            <?= strtoupper($etudiant['nom']) ?> <?= ucfirst($etudiant['prenom']) ?> - <?= $etudiant['mail'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="id_classe" class="form-label">Classe <span class="text-danger">*</span></label>
                                <select name="id_classe" id="id_classe" class="form-select" required>
                                    <option value="">-- Sélectionner une classe --</option>
                                    <?php foreach($classes as $classe): ?>
                                        <option value="<?= $classe['id_class'] ?>">
                                            <?= $classe['code'] ?> - <?= $classe['libelle'] ?> (Niveau: <?= $classe['niveau'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="annee_scolaire_id" class="form-label">Année scolaire <span class="text-danger">*</span></label>
                                <select name="annee_scolaire_id" id="annee_scolaire_id" class="form-select" required>
                                    <option value="">-- Sélectionner une année --</option>
                                    <?php foreach($annees as $annee): ?>
                                        <option value="<?= $annee['id_annee'] ?>" <?= ($annee['actif'] == 1) ? 'selected' : '' ?>>
                                            <?= $annee['libelle'] ?> <?= ($annee['actif'] == 1) ? '(Active)' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="date_inscription" class="form-label">Date d'inscription <span class="text-danger">*</span></label>
                                <input type="date" name="date_inscription" id="date_inscription" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="<?= base_url('inscription') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Enregistrer l'inscription
                                </button>
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