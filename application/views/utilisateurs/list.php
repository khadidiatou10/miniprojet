<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; white-space: nowrap; }
        .badge-admin { background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
        .badge-secretaire { background-color: #17a2b8; color: white; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
        .badge-professeur { background-color: #28a745; color: white; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
        .badge-actif { background-color: #28a745; color: white; padding: 3px 8px; border-radius: 15px; font-size: 10px; }
        .badge-inactif { background-color: #6c757d; color: white; padding: 3px 8px; border-radius: 15px; font-size: 10px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-people"></i> Gestion des utilisateurs</h2>
        <a href="<?= base_url('utilisateur/form') ?>" class="btn btn-primary btn-sm-custom">
            <i class="bi bi-plus-circle"></i> Ajouter un utilisateur
        </a>
    </div>

    <!-- Messages flash -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $this->session->flashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= $this->session->flashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-list"></i> Liste des utilisateurs
            <span class="badge bg-light text-dark ms-2"><?= count($utilisateurs) ?> utilisateur(s)</span>
        </div>
        <div class="card-body">
            <?php if(empty($utilisateurs)): ?>
                <div class="alert alert-info">Aucun utilisateur trouvé.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nom d'utilisateur</th>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($utilisateurs as $u): ?>
                            <tr>
                                <td><?= $u['id'] ?></td>
                                <td><strong><?= $u['nom_utilisateur'] ?></strong></td>
                                <td><?= $u['nom_complet'] ?: '-' ?></td>
                                <td><?= $u['email'] ?></td>
                                <td>
                                    <?php if($u['role'] == 'admin'): ?>
                                        <span class="badge-admin"><i class="bi bi-shield-lock"></i> Admin</span>
                                    <?php elseif($u['role'] == 'secretaire'): ?>
                                        <span class="badge-secretaire"><i class="bi bi-person"></i> Secrétaire</span>
                                    <?php else: ?>
                                        <span class="badge-professeur"><i class="bi bi-person-badge"></i> Professeur</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($u['est_actif'] == 1): ?>
                                        <span class="badge-actif">Actif</span>
                                    <?php else: ?>
                                        <span class="badge-inactif">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
                                <td>
                                    <a href="<?= base_url('utilisateur/edit_form/'.$u['id']) ?>" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if($u['est_actif'] == 1): ?>
                                        <a href="<?= base_url('utilisateur/supprimer/'.$u['id']) ?>" class="btn btn-sm btn-danger" title="Désactiver" onclick="return confirm('Désactiver cet utilisateur ?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('utilisateur/reactiver/'.$u['id']) ?>" class="btn btn-sm btn-success" title="Réactiver" onclick="return confirm('Réactiver cet utilisateur ?')">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>