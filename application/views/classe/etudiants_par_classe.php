<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Étudiants de la classe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; white-space: nowrap; }
        .actions-cell { min-width: 200px; white-space: nowrap; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-people-fill"></i> Étudiants - <?php echo $classe['libelle']; ?></h2>
        <a href="<?php echo base_url('classe/index'); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Informations de la classe -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <i class="bi bi-building"></i> Informations de la classe
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Code :</strong> <?php echo $classe['code']; ?>
                </div>
                <div class="col-md-3">
                    <strong>Libellé :</strong> <?php echo $classe['libelle']; ?>
                </div>
                <div class="col-md-3">
                    <strong>Niveau :</strong> <?php echo $classe['niveau']; ?>
                </div>
                <div class="col-md-3">
                    <strong>Capacité :</strong> <?php echo $classe['capacite']; ?> étudiants
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des étudiants -->
    <?php if (empty($etudiants)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Aucun étudiant inscrit dans cette classe.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Photo</th>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etudiants as $e): ?>
                    <tr>
                        <td class="text-center">
                            <?php if(!empty($e['photo']) && $e['photo'] != 'default.png' && file_exists('./uploads/etudiants/'.$e['photo'])): ?>
                                <img src="<?= base_url('uploads/etudiants/'.$e['photo']) ?>" width="45" height="45" class="rounded-circle" style="object-fit:cover;">
                            <?php else: ?>
                                <i class="bi bi-person-circle fs-1 text-secondary"></i>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $e['matricule'] ?? 'E00'.$e['id']; ?></td>
                        <td><?php echo strtoupper($e['nom']); ?></td>
                        <td><?php echo ucfirst($e['prenom']); ?></td>
                        <td><?php echo $e['mail']; ?></td>
                        <td><?php echo $e['telephone']; ?></td>
                        <td class="actions-cell">
                            <a href="<?php echo base_url('etudiants/detail/'.$e['id']); ?>" class="btn btn-sm btn-sm-custom" style="background-color:#00BCD4; color:white;">
                                <i class="bi bi-eye"></i> Détails
                            </a>
                            <a href="<?php echo base_url('note/notes_etudiant/'.$e['id']); ?>" class="btn btn-sm btn-info btn-sm-custom">
                                <i class="bi bi-file-text"></i> Notes
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Statistiques -->
        <div class="alert alert-secondary mt-3">
            <strong><i class="bi bi-bar-chart"></i> Statistiques :</strong>
            Total d'étudiants inscrits : <?php echo count($etudiants); ?>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>