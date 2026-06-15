<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saisie des Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-edit"></i> Saisie des Notes</h1>
            <a href="<?= base_url('note') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
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

        <!-- Informations -->
        <div class="alert alert-info">
            <div class="row">
                <div class="col-md-6">
                    <strong><i class="fas fa-chalkboard"></i> Classe :</strong> <?= $classe['code'] ?> - <?= $classe['libelle'] ?>
                </div>
                <div class="col-md-6">
                    <strong><i class="fas fa-book"></i> Matière :</strong> <?= $matiere['code'] ?> - <?= $matiere['libelle'] ?> (Coeff: <?= $matiere['coefficient'] ?>)
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <strong><i class="fas fa-calculator"></i> Formule :</strong> Note Finale = (CC × 0.4) + (Examen × 0.6)
                </div>
                <div class="col-md-6">
                    <strong><i class="fas fa-users"></i> Étudiants :</strong> <?= count($etudiants) ?>
                </div>
            </div>
        </div>

        <!-- Formulaire de saisie -->
        <form method="POST" action="<?= base_url('note/enregistrer') ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="classe_id" value="<?= $classe['id_class'] ?>">
            <input type="hidden" name="matiere_id" value="<?= $matiere['id_matiere'] ?>">
            <input type="hidden" name="annee_id" value="<?= $annee_id ?>">

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-table"></i> Tableau des notes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Matricule</th>
                                    <th>Nom & Prénom</th>
                                    <th>Note CC (sur 20)<br><small>Coeff 40%</small></th>
                                    <th>Note Examen (sur 20)<br><small>Coeff 60%</small></th>
                                    <th>Note Finale<br><small>Calculée</small></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach($etudiants as $etudiant): ?>
                                    <tr>
                                        <td class="text-center"><?= $i++ ?></td>
                                        <td><?= $etudiant['matricule'] ?? 'E00'.$etudiant['id'] ?></td>
                                        <td><strong><?= strtoupper($etudiant['nom']) ?></strong> <?= ucfirst($etudiant['prenom']) ?></td>
                                        <td>
                                            <input type="number" name="etudiants[<?= $etudiant['id_etudiant'] ?>][note_cc]" 
                                                   class="form-control note-cc" 
                                                   value="<?= $etudiant['note_cc'] ?>" 
                                                   step="0.25" min="0" max="20"
                                                   placeholder="0-20">
                                        </td>
                                        <td>
                                            <input type="number" name="etudiants[<?= $etudiant['id_etudiant'] ?>][note_exam]" 
                                                   class="form-control note-exam" 
                                                   value="<?= $etudiant['note_exam'] ?>" 
                                                   step="0.25" min="0" max="20"
                                                   placeholder="0-20">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control note-finale bg-light" 
                                                   value="<?= $etudiant['note_finale'] ?>" readonly>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer toutes les notes
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Calcul automatique de la note finale (Point 40)
        document.querySelectorAll('.note-cc, .note-exam').forEach(function(input) {
            input.addEventListener('input', function() {
                var row = this.closest('tr');
                var cc = parseFloat(row.querySelector('.note-cc').value) || 0;
                var exam = parseFloat(row.querySelector('.note-exam').value) || 0;
                var finale = (cc * 0.4) + (exam * 0.6);
                row.querySelector('.note-finale').value = finale.toFixed(2);
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>