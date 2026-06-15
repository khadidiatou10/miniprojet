<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Classe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4" style="max-width:600px;">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h4><i class="bi bi-pencil-square"></i> Modifier la Classe</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo base_url('classe/save_update/'.$classe['id_class']); ?>" method="post">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                       value="<?php echo $this->security->get_csrf_hash(); ?>">

                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" value="<?php echo htmlspecialchars($classe['code']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Libellé</label>
                    <input type="text" name="libelle" class="form-control" value="<?php echo htmlspecialchars($classe['libelle']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Niveau</label>
                    <select name="niveau" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach (['L1','L2','L3','M1','M2'] as $niv): ?>
                            <option value="<?php echo $niv; ?>" <?php echo $classe['niveau'] == $niv ? 'selected' : ''; ?>>
                                <?php echo $niv; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Capacité</label>
                    <input type="number" name="capacite" class="form-control" value="<?php echo $classe['capacite'] ?? ''; ?>" min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Année Scolaire</label>
                    <select name="id_annee" class="form-select">
                        <option value="">-- Choisir --</option>
                        <?php foreach ($annees as $a): ?>
                            <option value="<?php echo $a['id_annee']; ?>"
                                <?php echo (isset($classe['id_annee']) && $classe['id_annee'] == $a['id_annee']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($a['libelle']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?php echo base_url('classe/index'); ?>" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-warning text-dark">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>