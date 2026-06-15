<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Année Scolaire</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4" style="max-width:600px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><i class="bi bi-calendar-plus"></i> Ajouter une Année Scolaire</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo base_url('annee_scolaire/enregistrement'); ?>" method="post">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                       value="<?php echo $this->security->get_csrf_hash(); ?>">

                <div class="mb-3">
                    <label class="form-label">Libellé <span class="text-danger">*</span></label>
                    <input type="text" name="libelle" class="form-control" 
                           placeholder="Ex: 2025-2026" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date de début <span class="text-danger">*</span></label>
                    <input type="date" name="date_debut" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date de fin <span class="text-danger">*</span></label>
                    <input type="date" name="date_fin" class="form-control" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?php echo base_url('annee_scolaire/index'); ?>" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>