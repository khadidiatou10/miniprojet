<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Classes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-building"></i> Liste des Classes</h2>
        <a href="<?php echo base_url('classe/form_class'); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Ajouter une classe
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

    <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Libellé</th>
                <th>Niveau</th>
                <th>Capacité</th>
                <th>Inscrits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($classes)): ?>
                <tr><td colspan="7" class="text-center text-muted">Aucune classe trouvée.</td></tr>
            <?php else: ?>
                <?php foreach ($classes as $c): ?>
                <tr>
                    <td><?php echo $c['id_class']; ?></td>
                    <td><?php echo htmlspecialchars($c['code']); ?></td>
                    <td><?php echo htmlspecialchars($c['libelle']); ?></td>
                    <td><?php echo htmlspecialchars($c['niveau']); ?></td>
                    <td><?php echo $c['capacite'] ?? '-'; ?></td>
                    <td>
                        <span class="badge bg-<?php echo $c['nb_inscrits'] > 0 ? 'primary' : 'secondary'; ?>">
                            <?php echo $c['nb_inscrits']; ?> étudiant(s)
                        </span>
                    </td>
                    <td>
                        <a href="<?php echo base_url('classe/detail/'.$c['id_class']); ?>" class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i> Détails
                        </a>
                        <a href="<?php echo base_url('classe/edit_form/'.$c['id_class']); ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <a href="<?php echo base_url('classe/delete_confirm/'.$c['id_class']); ?>" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>