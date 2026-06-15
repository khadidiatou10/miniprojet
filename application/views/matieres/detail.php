<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche de la Matière</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-book"></i> Fiche de la Matière</h1>
            <div>
                <a href="<?= base_url('matiere') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
                <a href="<?= base_url('matiere/edit_form/'.$matiere['id_matiere']) ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
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

        <!-- Informations de la matière -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5><i class="fas fa-info-circle"></i> Informations de la matière</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-code"></i> Code :</strong> <span class="badge bg-primary"><?= $matiere['code'] ?></span></p>
                        <p><strong><i class="fas fa-tag"></i> Libellé :</strong> <?= $matiere['libelle'] ?></p>
                        <p><strong><i class="fas fa-calculator"></i> Coefficient :</strong> <?= $matiere['coefficient'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-clock"></i> Volume horaire :</strong> <?= $matiere['volume_horaire'] ?> heures</p>
                        <p><strong><i class="fas fa-align-left"></i> Description :</strong><br><?= nl2br($matiere['description'] ?: 'Aucune description') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affectations (Professeurs et Classes) -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-chalkboard-user"></i> Professeurs affectés à cette matière</h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalAffectation">
                    <i class="fas fa-plus"></i> Affecter un professeur
                </button>
            </div>
            <div class="card-body">
                <?php if(empty($affectations)): ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Aucun professeur affecté à cette matière.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Professeur</th>
                                    <th>Spécialité</th>
                                    <th>Classe</th>
                                    <th>Année scolaire</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($affectations as $affect): ?>
                                    <tr>
                                        <td>
                                            <strong><?= strtoupper($affect['nom']) ?> <?= ucfirst($affect['prenom']) ?></strong><br>
                                            <small><?= $affect['email'] ?></small>
                                         </td>
                                        <td><?= $affect['specialite'] ?: '-' ?> </td>
                                        <td>
                                            <?= $affect['classe_code'] ?> - <?= $affect['classe_libelle'] ?><br>
                                            <small>Niveau: <?= $affect['niveau'] ?></small>
                                         </td>
                                        <td><?= $affect['annee_libelle'] ?: 'Non définie' ?> </td>
                                        <td>
                                            <a href="<?= base_url('matiere/supprimer_affectation/'.$affect['id_affectation'].'/'.$matiere['id_matiere']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette affectation ?')">
                                                <i class="fas fa-trash"></i>
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

    <!-- Modal Ajouter Affectation -->
    <div class="modal fade" id="modalAffectation" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Affecter un professeur</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('matiere/ajouter_affectation') ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="id_matiere" value="<?= $matiere['id_matiere'] ?>">

                        <div class="mb-3">
                            <label for="id_professeur" class="form-label">Professeur <span class="text-danger">*</span></label>
                            <select name="id_professeur" id="id_professeur" class="form-select" required>
                                <option value="">-- Sélectionner un professeur --</option>
                                <?php foreach($professeurs as $professeur): ?>
                                    <option value="<?= $professeur['id_professeur'] ?>">
                                        <?= strtoupper($professeur['nom']) ?> <?= ucfirst($professeur['prenom']) ?> - <?= $professeur['specialite'] ?>
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
                                        <?= $classe['code'] ?> - <?= $classe['libelle'] ?> (<?= $classe['niveau'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="annee_scolaire_id" class="form-label">Année scolaire</label>
                            <select name="annee_scolaire_id" id="annee_scolaire_id" class="form-select">
                                <option value="">-- Année en cours --</option>
                                <?php foreach($annees as $annee): ?>
                                    <option value="<?= $annee['id_annee'] ?>" <?= ($annee['actif'] == 1) ? 'selected' : '' ?>>
                                        <?= $annee['libelle'] ?> <?= ($annee['actif'] == 1) ? '(Active)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter l'affectation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>