<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4><i class="fas fa-edit"></i> Modifier l'Inscription</h4>
                    </div>
                    <div class="card-body">
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-user"></i> Étudiant :</strong> 
                                    <?= isset($inscription['nom']) ? strtoupper($inscription['nom']) : '' ?> 
                                    <?= isset($inscription['prenom']) ? ucfirst($inscription['prenom']) : '' ?>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-calendar"></i> Date inscription :</strong> 
                                    <?= isset($inscription['date_inscription']) ? date('d/m/Y', strtotime($inscription['date_inscription'])) : '' ?>
                                </div>
                            </div>
                        </div>

                        <form action="<?= base_url('inscription/modifier/'.$inscription['id_inscription']) ?>" method="post">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                            
                            <input type="hidden" name="id_etudiant" value="<?= $inscription['id_etudiant'] ?>">

                            <div class="mb-3">
                                <label for="id_classe" class="form-label">Classe <span class="text-danger">*</span></label>
                                <select name="id_classe" id="id_classe" class="form-select" required>
                                    <option value="">-- Sélectionner une classe --</option>
                                    <?php foreach($classes as $classe): ?>
                                        <option value="<?= $classe['id_class'] ?>" <?= (isset($inscription['id_classe']) && $inscription['id_classe'] == $classe['id_class']) ? 'selected' : '' ?>>
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
                                        <option value="<?= $annee['id_annee'] ?>" <?= (isset($inscription['annee_scolaire_id']) && $inscription['annee_scolaire_id'] == $annee['id_annee']) ? 'selected' : '' ?>>
                                            <?= $annee['libelle'] ?> <?= ($annee['actif'] == 1) ? '(Active)' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="date_inscription" class="form-label">Date d'inscription</label>
                                <input type="date" name="date_inscription" id="date_inscription" class="form-control" value="<?= isset($inscription['date_inscription']) ? $inscription['date_inscription'] : date('Y-m-d') ?>" required>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="<?= base_url('inscription') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Mettre à jour
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