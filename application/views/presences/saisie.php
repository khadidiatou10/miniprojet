<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saisie des Présences</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .present-checkbox {
            width: 20px !important;
            height: 20px !important;
            cursor: pointer !important;
        }
        .justifie-checkbox {
            width: 18px !important;
            height: 18px !important;
            cursor: pointer !important;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-calendar-check"></i> Saisie des Présences</h2>
        <a href="<?= base_url('presence') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <!-- Informations séance -->
    <div class="alert alert-info">
        <div class="row">
            <div class="col-md-4"><strong>Classe :</strong> <?= $classe['code'] ?> - <?= $classe['libelle'] ?></div>
            <div class="col-md-4"><strong>Matière :</strong> <?= $matiere['code'] ?> - <?= $matiere['libelle'] ?></div>
            <div class="col-md-4"><strong>Date :</strong> <?= date('d/m/Y', strtotime($date_seance)) ?></div>
        </div>
    </div>

    <?php if(empty($etudiants)): ?>
        <div class="alert alert-danger text-center">
            <i class="bi bi-exclamation-triangle"></i> 
            <strong>Aucun étudiant inscrit dans cette classe !</strong>
            <br><br>
            <a href="<?= base_url('inscription/form') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Inscrire des étudiants
            </a>
        </div>
    <?php else: ?>
        <form method="POST" action="<?= base_url('presence/enregistrer') ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="classe_id" value="<?= $classe['id_class'] ?>">
            <input type="hidden" name="matiere_id" value="<?= $matiere['id_matiere'] ?>">
            <input type="hidden" name="date_seance" value="<?= $date_seance ?>">

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-people"></i> Liste des étudiants
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Photo</th>
                                    <th>Matricule</th>
                                    <th>Nom & Prénom</th>
                                    <th class="text-center">Présent</th>
                                    <th class="text-center">Absence justifiée</th>
                                    <th>Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($etudiants as $e): ?>
                                <?php 
                                    $existing = isset($presences_existantes[$e['id_etudiant']]) ? $presences_existantes[$e['id_etudiant']] : null;
                                    $present = $existing ? $existing['present'] : 1;
                                    $justifie = $existing ? $existing['justifie'] : 0;
                                    $commentaire = $existing ? $existing['commentaire'] : '';
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if(!empty($e['photo']) && $e['photo'] != 'default.png'): ?>
                                            <img src="<?= base_url('uploads/etudiants/'.$e['photo']) ?>" width="40" class="rounded-circle">
                                        <?php else: ?>
                                            <i class="bi bi-person-circle fs-2 text-secondary"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $e['matricule'] ?? 'E00'.$e['id'] ?></td>
                                    <td><strong><?= strtoupper($e['nom']) ?></strong> <?= ucfirst($e['prenom']) ?></td>
                                    <!-- ✅ CASE À COCHER PRÉSENT -->
                                    <td class="text-center">
                                        <input type="checkbox" 
                                               name="present[<?= $e['id_etudiant'] ?>]" 
                                               class="present-checkbox" 
                                               value="1" 
                                               <?= $present ? 'checked' : '' ?>>
                                        <span class="ms-2 badge <?= $present ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $present ? 'Présent' : 'Absent' ?>
                                        </span>
                                    </td>
                                    <!-- ✅ CASE JUSTIFIÉ -->
                                    <td class="text-center">
                                        <input type="checkbox" 
                                               name="justifie[<?= $e['id_etudiant'] ?>]" 
                                               class="justifie-checkbox" 
                                               value="1" 
                                               <?= $justifie ? 'checked' : '' ?> 
                                               <?= $present ? 'disabled' : '' ?>>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="commentaire[<?= $e['id_etudiant'] ?>]" 
                                               class="form-control form-control-sm" 
                                               placeholder="Motif (ex: maladie, retard...)"
                                               value="<?= htmlspecialchars($commentaire) ?>"
                                               <?= $present ? 'disabled' : '' ?>>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer les présences
                    </button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
    // Gestion des cases à cocher
    document.querySelectorAll('.present-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var row = this.closest('tr');
            var justifie = row.querySelector('.justifie-checkbox');
            var commentaire = row.querySelector('input[type="text"]');
            var badge = this.nextElementSibling;
            
            if(this.checked) {
                // Présent
                justifie.disabled = true;
                justifie.checked = false;
                commentaire.disabled = true;
                commentaire.value = '';
                badge.className = 'ms-2 badge bg-success';
                badge.innerText = 'Présent';
            } else {
                // Absent
                justifie.disabled = false;
                commentaire.disabled = false;
                badge.className = 'ms-2 badge bg-secondary';
                badge.innerText = 'Absent';
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>