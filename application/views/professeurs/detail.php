<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche du Professeur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-chalkboard-user"></i> Fiche du Professeur</h1>
            <div>
                <a href="<?= base_url('professeur') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
                <a href="<?= base_url('professeur/edit_form/'.$professeur['id_professeur']) ?>" class="btn btn-warning">
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

        <!-- Informations personnelles -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5><i class="fas fa-user"></i> Informations personnelles</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        <?php if($professeur['photo'] != 'default.png' && file_exists('./uploads/professeurs/'.$professeur['photo'])): ?>
                            <img src="<?= base_url('uploads/professeurs/'.$professeur['photo']) ?>" width="120" height="120" class="rounded-circle" style="object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-user-circle fa-6x text-secondary"></i>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-user"></i> Nom complet :</strong> <?= strtoupper($professeur['nom']) ?> <?= ucfirst($professeur['prenom']) ?></p>
                                <p><strong><i class="fas fa-graduation-cap"></i> Spécialité :</strong> <?= $professeur['specialite'] ?: 'Non renseignée' ?></p>
                                <p><strong><i class="fas fa-calendar-alt"></i> Date d'embauche :</strong> <?= $professeur['date_embauche'] ? date('d/m/Y', strtotime($professeur['date_embauche'])) : 'Non renseignée' ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-envelope"></i> Email :</strong> <?= $professeur['email'] ?></p>
                                <p><strong><i class="fas fa-phone"></i> Téléphone :</strong> <?= $professeur['telephone'] ?: 'Non renseigné' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affectations (Matières et Classes) -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-chalkboard"></i> Matières et Classes affectées</h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalAffectation">
                    <i class="fas fa-plus"></i> Ajouter une affectation
                </button>
            </div>
            <div class="card-body">
                <?php if(empty($affectations)): ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Aucune affectation pour ce professeur.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Matière</th>
                                    <th>Classe</th>
                                    <th>Année scolaire</th>
                                    <th>Coefficient</th>
                                    <th>Volume horaire</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($affectations as $affect): ?>
                                    <tr>
                                        <td>
                                            <?php if(isset($affect['matiere_code'])): ?>
                                                <strong><?= $affect['matiere_code'] ?></strong><br>
                                            <?php endif; ?>
                                            <?= $affect['matiere_libelle'] ?>
                                        </td>
                                        <td>
                                            <?= $affect['classe_code'] ?> - <?= $affect['classe_libelle'] ?><br>
                                            <small>Niveau: <?= $affect['niveau'] ?></small>
                                        </td>
                                        <td><?= $affect['annee_libelle'] ?: 'Non définie' ?></td>
                                        <td><?= $affect['coefficient'] ?: 1 ?></td>
                                        <td><?= $affect['volume_horaire'] ?: '-' ?> h</td>
                                        <td>
                                            <a href="<?= base_url('professeur/supprimer_affectation/'.$affect['id_affectation'].'/'.$professeur['id_professeur']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette affectation ?')">
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
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Ajouter une affectation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('professeur/ajouter_affectation') ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="id_professeur" value="<?= $professeur['id_professeur'] ?>">

                        <div class="mb-3">
                            <label for="id_matiere" class="form-label">Matière <span class="text-danger">*</span></label>
                            <select name="id_matiere" id="id_matiere" class="form-select" required>
                                <option value="">-- Sélectionner une matière --</option>
                                <?php foreach($matieres as $matiere): ?>
                                    <option value="<?= $matiere['id_matiere'] ?>">
                                        <?= $matiere['code'] ?> - <?= $matiere['libelle'] ?> (Coeff: <?= $matiere['coefficient'] ?>)
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