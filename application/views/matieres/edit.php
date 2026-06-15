<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Matière</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4><i class="fas fa-edit"></i> Modifier la Matière</h4>
                    </div>
                    <div class="card-body">
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('matiere/modifier/'.$matiere['id_matiere']) ?>" method="post">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" id="code" class="form-control" value="<?= $matiere['code'] ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                                    <input type="text" name="libelle" id="libelle" class="form-control" value="<?= $matiere['libelle'] ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="coefficient" class="form-label">Coefficient <span class="text-danger">*</span></label>
                                    <input type="number" name="coefficient" id="coefficient" class="form-control" step="0.5" min="0.5" value="<?= $matiere['coefficient'] ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="volume_horaire" class="form-label">Volume horaire (heures) <span class="text-danger">*</span></label>
                                    <input type="number" name="volume_horaire" id="volume_horaire" class="form-control" min="1" value="<?= $matiere['volume_horaire'] ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3"><?= $matiere['description'] ?></textarea>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="<?= base_url('matiere') ?>" class="btn btn-secondary">
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