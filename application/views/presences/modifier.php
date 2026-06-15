<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une présence</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4><i class="bi bi-pencil"></i> Modifier la présence</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Étudiant :</strong> <?= strtoupper($etudiant['nom']) ?> <?= ucfirst($etudiant['prenom']) ?><br>
                        <strong>Classe :</strong> <?= $classe['code'] ?> - <?= $classe['libelle'] ?><br>
                        <strong>Matière :</strong> <?= $matiere['code'] ?> - <?= $matiere['libelle'] ?><br>
                        <strong>Date :</strong> <?= date('d/m/Y', strtotime($presence['date_seance'])) ?>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="present" class="form-check-input" id="present" value="1" <?= $presence['present'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="present">Présent</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="justifie" class="form-check-input" id="justifie" value="1" <?= $presence['justifie'] ? 'checked' : '' ?> <?= $presence['present'] ? 'disabled' : '' ?>>
                                <label class="form-check-label" for="justifie">Absence justifiée</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="commentaire" class="form-label">Commentaire</label>
                            <textarea name="commentaire" id="commentaire" class="form-control" rows="2"><?= htmlspecialchars($presence['commentaire']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url('presence/historique/'.$etudiant['id']) ?>" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-warning ms-2">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const presentCheckbox = document.getElementById('present');
    const justifieCheckbox = document.getElementById('justifie');
    
    presentCheckbox.addEventListener('change', function() {
        if(this.checked) {
            justifieCheckbox.disabled = true;
            justifieCheckbox.checked = false;
        } else {
            justifieCheckbox.disabled = false;
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>