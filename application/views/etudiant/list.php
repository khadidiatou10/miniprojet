<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Étudiants</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; white-space: nowrap; }
        .actions-cell { min-width: 280px; white-space: nowrap; }
        .user-info { background-color: #f8f9fa; padding: 5px 12px; border-radius: 20px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-people-fill"></i> Liste des Étudiants</h2>
        <div class="d-flex align-items-center">
            <div class="user-info me-3">
                <i class="bi bi-person-circle"></i> 
                <strong><?= $this->session->userdata('nom_complet') ?: $this->session->userdata('username') ?></strong>
                <span class="badge bg-secondary ms-1"><?= $this->session->userdata('role') ?></span>
            </div>
            <a href="<?= base_url('auth/logout') ?>" class="btn btn-danger btn-sm me-2" onclick="return confirm('Déconnexion ?')">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
            <a href="<?= base_url('etudiants/form'); ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Ajouter
            </a>
        </div>
    </div>

    <!-- Messages flash -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $this->session->flashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= $this->session->flashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <!-- Barre de recherche -->
    <form method="get" action="<?= base_url('etudiants/index'); ?>" class="mb-3">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Rechercher..." value="<?= htmlspecialchars($recherche ?? ''); ?>">
            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Rechercher</button>
            <?php if (!empty($recherche)): ?><a href="<?= base_url('etudiants/index'); ?>" class="btn btn-outline-danger"><i class="bi bi-x"></i> Effacer</a><?php endif; ?>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr><th>Photo</th><th>Nom</th><th>Prénom</th><th>Sexe</th><th>Mail</th><th>Téléphone</th><th>Date Naissance</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($etudiants)): ?>
                    <tr><td colspan="8" class="text-center text-muted">Aucun étudiant trouvé.</td></tr>
                <?php else: foreach ($etudiants as $e): ?>
                <tr>
                    <td><img src="<?= !empty($e['photo']) ? base_url('uploads/etudiants/'.$e['photo']) : base_url('uploads/etudiants/default.png'); ?>" class="rounded-circle" width="45" height="45" style="object-fit:cover;"></td>
                    <td><?= htmlspecialchars($e['nom']); ?></td>
                    <td><?= htmlspecialchars($e['prenom']); ?></td>
                    <td><?= htmlspecialchars($e['sexe'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($e['mail']); ?></td>
                    <td><?= htmlspecialchars($e['telephone']); ?></td>
                    <td><?= $e['date_naissance']; ?></td>
                    <td class="actions-cell">
                        <a href="<?= base_url('note/notes_etudiant/'.$e['id']); ?>" class="btn btn-sm btn-info btn-sm-custom"><i class="bi bi-file-text"></i> Notes</a>
                        <a href="<?= base_url('etudiants/detail/'.$e['id']); ?>" class="btn btn-sm" style="background-color:#00BCD4; color:white;"><i class="bi bi-eye"></i> Détails</a>
                        <a href="<?= base_url('etudiants/edit_form/'.$e['id']); ?>" class="btn btn-sm btn-warning btn-sm-custom"><i class="bi bi-pencil"></i> Modifier</a>
                        <a href="<?= base_url('etudiants/delete_confirm/'.$e['id']); ?>" class="btn btn-sm" style="background-color:#E91E63; color:white;" onclick="return confirm('Supprimer ?')"><i class="bi bi-trash"></i> Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <nav><?= $pagination; ?></nav>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>