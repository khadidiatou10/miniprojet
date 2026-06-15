<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Étudiant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">

    <a href="<?php echo base_url('etudiants/index'); ?>" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Retour à la liste
    </a>

    <div class="row">
        <!-- Infos personnelles -->
        <div class="col-md-4">
            <div class="card shadow text-center p-3">
                <?php
                    $photo = !empty($etudiant['photo']) ? base_url('uploads/etudiants/'.$etudiant['photo']) : base_url('uploads/etudiants/default.png');
                ?>
                <img src="<?php echo $photo; ?>" alt="photo"
                     class="rounded-circle mx-auto mb-3"
                     style="width:130px; height:130px; object-fit:cover;">
                <h4><?php echo htmlspecialchars($etudiant['nom'].' '.$etudiant['prenom']); ?></h4>
                <p class="text-muted"><?php echo $etudiant['sexe'] == 'M' ? 'Masculin' : 'Féminin'; ?></p>
                <hr>
                <ul class="list-unstyled text-start">
                    <li><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($etudiant['mail']); ?></li>
                    <li><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($etudiant['telephone']); ?></li>
                    <li><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($etudiant['adresse'] ?? '-'); ?></li>
                    <li><i class="bi bi-calendar"></i> <?php echo $etudiant['date_naissance']; ?></li>
                </ul>
                <a href="<?php echo base_url('etudiants/edit_form/'.$etudiant['id']); ?>" class="btn btn-warning btn-sm mt-2">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
            </div>
        </div>

        <!-- Classes et Notes -->
        <div class="col-md-8">
            <!-- Classes inscrites -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5><i class="bi bi-building"></i> Classes inscrites</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($classes)): ?>
                        <p class="text-muted">Aucune classe associée.</p>
                    <?php else: ?>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr><th>Code</th><th>Libellé</th><th>Niveau</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($classes as $c): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($c['code']); ?></td>
                                    <td><?php echo htmlspecialchars($c['libelle']); ?></td>
                                    <td><?php echo htmlspecialchars($c['niveau']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notes -->
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5><i class="bi bi-journal-text"></i> Notes</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($notes)): ?>
                        <p class="text-muted">Aucune note enregistrée.</p>
                    <?php else: ?>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr><th>Matière</th><th>Note</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notes as $n): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($n['matiere']); ?></td>
                                    <td><span class="badge bg-<?php echo $n['note'] >= 10 ? 'success' : 'danger'; ?>">
                                        <?php echo $n['note']; ?>/20
                                    </span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>