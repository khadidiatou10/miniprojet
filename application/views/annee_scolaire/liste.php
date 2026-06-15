<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Années Scolaires</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar3"></i> Années Scolaires</h2>
        <a href="<?php echo base_url('annee_scolaire/form'); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Ajouter une année
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Libellé</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($annees)): ?>
                <tr><td colspan="6" class="text-center text-muted">Aucune année scolaire trouvée.</td></tr>
            <?php else: ?>
                <?php foreach ($annees as $a): ?>
                <tr class="<?php echo $a['actif'] ? 'table-success' : ''; ?>">
                    <td><?php echo $a['id_annee']; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($a['libelle']); ?></strong>
                        <?php if ($a['actif']): ?>
                            <span class="badge bg-success ms-2"><i class="bi bi-check-circle"></i> Active</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $a['date_debut']; ?></td>
                    <td><?php echo $a['date_fin']; ?></td>
                    <td>
                        <?php if ($a['actif']): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$a['actif']): ?>
                            <a href="<?php echo base_url('annee_scolaire/activer/'.$a['id_annee']); ?>" 
                               class="btn btn-success btn-sm" title="Activer">
                                <i class="bi bi-toggle-on"></i> Activer
                            </a>
                        <?php else: ?>
                            <a href="<?php echo base_url('annee_scolaire/desactiver/'.$a['id_annee']); ?>" 
                               class="btn btn-secondary btn-sm" title="Désactiver">
                                <i class="bi bi-toggle-off"></i> Désactiver
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo base_url('annee_scolaire/edit_form/'.$a['id_annee']); ?>" 
                           class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <a href="<?php echo base_url('annee_scolaire/delete_confirm/'.$a['id_annee']); ?>" 
                           class="btn btn-sm" style="background-color:#E91E63; color:white;">
                            <i class="bi bi-trash"></i> Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>