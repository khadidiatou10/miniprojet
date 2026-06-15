<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-edit"></i> Gestion des Notes</h1>
            <div>
                <a href="<?= base_url('note/classement') ?>" class="btn btn-info">
                    <i class="fas fa-trophy"></i> Classement
                </a>
            </div>
        </div>

        <!-- Messages flash -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Formulaire de sélection pour saisie des notes -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5><i class="fas fa-pen"></i> Saisir des notes</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="<?= base_url('note/saisie') ?>" class="row g-3">
                    <div class="col-md-4">
                        <label for="classe_id" class="form-label">Classe <span class="text-danger">*</span></label>
                        <select name="classe_id" id="classe_id" class="form-select" required>
                            <option value="">-- Sélectionner une classe --</option>
                            <?php foreach($classes as $classe): ?>
                                <option value="<?= $classe['id_class'] ?>">
                                    <?= $classe['code'] ?> - <?= $classe['libelle'] ?> (<?= $classe['niveau'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="matiere_id" class="form-label">Matière <span class="text-danger">*</span></label>
                        <select name="matiere_id" id="matiere_id" class="form-select" required>
                            <option value="">-- Sélectionner une matière --</option>
                            <?php foreach($matieres as $matiere): ?>
                                <option value="<?= $matiere['id_matiere'] ?>">
                                    <?= $matiere['code'] ?> - <?= $matiere['libelle'] ?> (Coeff: <?= $matiere['coefficient'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="annee_id" class="form-label">Année scolaire</label>
                        <select name="annee_id" id="annee_id" class="form-select">
                            <option value="">-- Année active --</option>
                            <?php foreach($annees as $annee): ?>
                                <option value="<?= $annee['id_annee'] ?>" <?= ($annee['actif'] == 1) ? 'selected' : '' ?>>
                                    <?= $annee['libelle'] ?> <?= ($annee['actif'] == 1) ? '(Active)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section : Consulter les notes d'un étudiant -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5><i class="fas fa-user-graduate"></i> Consulter les notes d'un étudiant</h5>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                <p>Accédez au bulletin scolaire complet d'un étudiant</p>
                <a href="<?= base_url('etudiants') ?>" class="btn btn-success btn-lg">
                    <i class="fas fa-search"></i> Choisir un étudiant
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>