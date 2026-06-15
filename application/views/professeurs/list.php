<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Professeurs</title>
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
        <h2><i class="bi bi-person-badge"></i> Liste des Professeurs</h2>
        <a href="<?php echo base_url('professeur/form'); ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Ajouter</a>
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
                <tr><th>Photo</th><th>Nom</th><th>Prénom</th><th>Spécialité</th><th>Email</th><th>Téléphone</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($professeurs)): ?><tr><td colspan="7" class="text-center">Aucun professeur</td></tr>
                <?php else: foreach ($professeurs as $p): ?>
                <tr>
                    <td><img src="<?php echo !empty($p['photo']) && $p['photo']!='default.png' ? base_url('uploads/professeurs/'.$p['photo']) : 'https://ui-avatars.com/api/?name='.urlencode($p['prenom'].'+'.$p['nom']).'&background=0D8ABC&color=fff&size=45'; ?>" class="rounded-circle" width="45" height="45"></td>
                    <td><?php echo htmlspecialchars($p['nom']); ?></td>
                    <td><?php echo htmlspecialchars($p['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($p['specialite'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($p['email']); ?></td>
                    <td><?php echo htmlspecialchars($p['telephone']); ?></td>
                    <td class="actions-cell">
                        <a href="<?php echo base_url('professeur/detail/'.$p['id_professeur']); ?>" class="btn btn-sm btn-sm-custom" style="background-color:#00BCD4; color:white;"><i class="bi bi-eye"></i> Détails</a>
                        <a href="<?php echo base_url('professeur/edit_form/'.$p['id_professeur']); ?>" class="btn btn-sm btn-warning btn-sm-custom"><i class="bi bi-pencil"></i> Modifier</a>
                        <a href="<?php echo base_url('professeur/supprimer/'.$p['id_professeur']); ?>" class="btn btn-sm btn-sm-custom" style="background-color:#E91E63; color:white;" onclick="return confirm('Supprimer ?')"><i class="bi bi-trash"></i> Supprimer</a>
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