<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Classe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">

    <a href="<?php echo base_url('classe/index'); ?>" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Retour à la liste
    </a>

    <!-- Infos classe -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4><i class="bi bi-building"></i> <?php echo htmlspecialchars($classe['libelle']); ?></h4>
            <a href="<?php echo base_url('classe/edit_form/'.$classe['id_class']); ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>Code :</strong> <?php echo htmlspecialchars($classe['code']); ?></div>
                <div class="col-md-3"><strong>Niveau :</strong> <?php echo htmlspecialchars($classe['niveau']); ?></div>
                <div class="col-md-3"><strong>Capacité :</strong> <?php echo $classe['capacite'] ?? '-'; ?></div>
                <div class="col-md-3"><strong>Inscrits :</strong>
                    <span class="badge bg-primary"><?php echo count($etudiants); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste étudiants inscrits -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5><i class="bi bi-people-fill"></i> Étudiants inscrits</h5>
        </div>
        <div class="card-body">
            <?php if (empty($etudiants)): ?>
                <p class="text-muted text-center">Aucun étudiant inscrit dans cette classe.</p>
            <?php else: ?>
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Mail</th>
                            <th>Téléphone</th>
                            <th>Date inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($etudiants as $e): ?>
                        <tr>
                            <td>
                                <?php $photo = !empty($e['photo']) ? base_url('uploads/etudiants/'.$e['photo']) : base_url('uploads/etudiants/default.png'); ?>
                                <img src="<?php echo $photo; ?>" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                            </td>
                            <td><?php echo htmlspecialchars($e['nom']); ?></td>
                            <td><?php echo htmlspecialchars($e['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($e['mail']); ?></td>
                            <td><?php echo htmlspecialchars($e['telephone']); ?></td>
                            <td><?php echo $e['date_inscription']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>