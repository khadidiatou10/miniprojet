<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Étudiant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4" style="max-width: 700px;">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h4><i class="bi bi-pencil-square"></i> Modifier l'Étudiant</h4>
        </div>
        <div class="card-body">

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>

            <form action="<?php echo base_url('etudiants/save_update/'.$etudiant['id']); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                       value="<?php echo $this->security->get_csrf_hash(); ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($etudiant['nom']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="prenom" class="form-control" value="<?php echo htmlspecialchars($etudiant['prenom']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sexe</label>
                        <select name="sexe" class="form-select">
                            <option value="">-- Choisir --</option>
                            <option value="M" <?php echo ($etudiant['sexe'] == 'M') ? 'selected' : ''; ?>>Masculin</option>
                            <option value="F" <?php echo ($etudiant['sexe'] == 'F') ? 'selected' : ''; ?>>Féminin</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" name="date_naissance" class="form-control" value="<?php echo $etudiant['date_naissance']; ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" value="<?php echo htmlspecialchars($etudiant['adresse'] ?? ''); ?>">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="mail" class="form-control" value="<?php echo htmlspecialchars($etudiant['mail']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="<?php echo htmlspecialchars($etudiant['telephone']); ?>">
                    </div>
                </div>

                <!-- Photo actuelle + upload nouvelle -->
                <div class="mb-3">
                    <label class="form-label">Photo actuelle</label><br>
                    <?php
                        $photo = !empty($etudiant['photo']) ? base_url('uploads/etudiants/'.$etudiant['photo']) : base_url('uploads/etudiants/default.png');
                    ?>
                    <img src="<?php echo $photo; ?>" alt="photo actuelle" class="rounded mb-2"
                         style="max-height:100px; max-width:100px; object-fit:cover;">

                    <label class="form-label d-block">Changer la photo <small class="text-muted">(jpg/png, max 2 Mo)</small></label>
                    <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png"
                           onchange="previewPhoto(this)">
                    <div class="mt-2">
                        <img id="preview" src="#" alt="Aperçu" class="rounded"
                             style="display:none; max-height:100px; max-width:100px; object-fit:cover;">
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?php echo base_url('etudiants/index'); ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="bi bi-save"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewPhoto(input) {
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>