<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmer la suppression</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="container mt-5">
    <div class="card border-danger shadow" style="max-width:500px; margin:auto;">
        <div class="card-header bg-danger text-white">
            <h4><i class="bi bi-exclamation-triangle-fill"></i> Confirmer la suppression</h4>
        </div>
        <div class="card-body text-center">
            <p class="fs-5">Voulez-vous vraiment supprimer l'année scolaire</p>
            <p><strong><?php echo htmlspecialchars($annee['libelle']); ?></strong> ?</p>
            <p class="text-muted">Impossible si des classes sont liées à cette année.</p>

            <form action="<?php echo base_url('annee_scolaire/delete_now/'.$annee['id_annee']); ?>" method="post">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <a href="<?php echo base_url('annee_scolaire/index'); ?>" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </div>
            </form>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>