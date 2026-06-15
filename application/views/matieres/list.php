<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Matières</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; white-space: nowrap; }
        .actions-cell { min-width: 280px; white-space: nowrap; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-book"></i> Liste des Matières</h2>
        <a href="<?php echo base_url('matiere/form'); ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Ajouter</a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo $this->session->flashdata('success'); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?php echo $this->session->flashdata('error'); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr><th>Code</th><th>Libellé</th><th>Coefficient</th><th>Volume horaire</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($matieres)): ?><tr><td colspan="5" class="text-center">Aucune matière</td></tr>
                <?php else: foreach ($matieres as $m): ?>
                <tr>
                    <td><span class="badge bg-info"><?php echo htmlspecialchars($m['code']); ?></span></td>
                    <td><?php echo htmlspecialchars($m['libelle']); ?></td>
                    <td><?php echo $m['coefficient']; ?></td>
                    <td><?php echo $m['volume_horaire']; ?> h</td>
                    <td class="actions-cell">
                        <a href="<?php echo base_url('matiere/detail/'.$m['id_matiere']); ?>" class="btn btn-sm btn-sm-custom" style="background-color:#00BCD4; color:white;"><i class="bi bi-eye"></i> Détails</a>
                        <a href="<?php echo base_url('matiere/edit_form/'.$m['id_matiere']); ?>" class="btn btn-sm btn-warning btn-sm-custom"><i class="bi bi-pencil"></i> Modifier</a>
                        <a href="<?php echo base_url('matiere/supprimer/'.$m['id_matiere']); ?>" class="btn btn-sm btn-sm-custom" style="background-color:#E91E63; color:white;" onclick="return confirm('Supprimer ?')"><i class="bi bi-trash"></i> Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>