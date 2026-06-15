<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Étudiants par Classe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="fas fa-users"></i> 
                Étudiants de la classe : 
                <span class="text-primary"><?= $classe_libelle ?? 'Non définie' ?></span>
            </h1>
            <div>
                <a href="<?= base_url('inscription') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour aux inscriptions
                </a>
                <a href="<?= base_url('inscription/form') ?>" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nouvelle inscription
                </a>
            </div>
        </div>

        <!-- Messages flash -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Informations sur la classe -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <i class="fas fa-code"></i> Code
                        </h5>
                        <h3><?= $classe_code ?? 'N/A' ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <i class="fas fa-graduation-cap"></i> Niveau
                        </h5>
                        <h3><?= $classe_niveau ?? 'N/A' ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <i class="fas fa-user-friends"></i> Capacité
                        </h5>
                        <h3><?= $classe_capacite ?? 'N/A' ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <i class="fas fa-user-check"></i> Inscrits
                        </h5>
                        <h3><?= count($inscriptions) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Année scolaire -->
        <div class="alert alert-secondary">
            <i class="fas fa-calendar-alt"></i> 
            <strong>Année scolaire :</strong> <?= $annee_libelle ?? 'Non définie' ?>
        </div>

        <!-- Liste des étudiants -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="fas fa-list"></i> Liste des étudiants inscrits</h5>
            </div>
            <div class="card-body">
                <?php if(empty($inscriptions)): ?>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Aucun étudiant n'est inscrit dans cette classe pour le moment.
                        <br>
                        <a href="<?= base_url('inscription/form') ?>" class="btn btn-sm btn-success mt-2">
                            <i class="fas fa-plus"></i> Ajouter une inscription
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Nom complet</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Date d'inscription</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach($inscriptions as $insc): ?>
                                    <tr>
                                        <td class="text-center"><?= $i++ ?></td>
                                        <td class="text-center">
                                            <?php if(!empty($insc['photo']) && $insc['photo'] != 'default.png' && file_exists('./uploads/etudiants/'.$insc['photo'])): ?>
                                                <img src="<?= base_url('uploads/etudiants/'.$insc['photo']) ?>" width="50" height="50" class="rounded-circle" style="object-fit: cover;">
                                            <?php else: ?>
                                                <i class="fas fa-user-circle fa-3x text-secondary"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?= strtoupper($insc['nom']) ?></strong> 
                                            <?= ucfirst($insc['prenom']) ?>
                                        </td>
                                        <td>
                                            <i class="fas fa-envelope"></i> <?= $insc['mail'] ?>
                                        </td>
                                        <td>
                                            <i class="fas fa-phone"></i> <?= $insc['telephone'] ?? 'Non renseigné' ?>
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar-day"></i> <?= date('d/m/Y', strtotime($insc['date_inscription'])) ?>
                                        </td>
                                        <td>
                                            <?php if($insc['statut'] == 'actif'): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('inscription/edit_form/'.$insc['id']) ?>" class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url('inscription/desinscrire/'.$insc['id']) ?>" class="btn btn-sm btn-danger" title="Désinscrire" onclick="return confirm('Confirmer la désinscription de <?= $insc['prenom'] ?> <?= $insc['nom'] ?> ?')">
                                                    <i class="fas fa-user-slash"></i>
                                                </a>
                                                <a href="<?= base_url('etudiants/detail/'.$insc['etudiant_id']) ?>" class="btn btn-sm btn-info" title="Voir fiche étudiant">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Statistiques -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="fas fa-chart-pie"></i> Répartition par sexe</h6>
                                    <?php 
                                        $hommes = 0;
                                        $femmes = 0;
                                        foreach($inscriptions as $insc) {
                                            // Vous devez avoir le champ sexe dans la table etudiants
                                            // Cette partie nécessite une jointure supplémentaire
                                        }
                                    ?>
                                    <p class="text-muted">(Fonctionnalité à compléter avec la jointure du sexe)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="fas fa-chart-line"></i> Taux de remplissage</h6>
                                    <?php 
                                        $taux = ($classe_capacite && $classe_capacite > 0) ? (count($inscriptions) / $classe_capacite) * 100 : 0;
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar <?= $taux > 90 ? 'bg-danger' : ($taux > 70 ? 'bg-warning' : 'bg-success') ?>" 
                                             style="width: <?= $taux ?>%">
                                            <?= round($taux, 1) ?>%
                                        </div>
                                    </div>
                                    <p class="mt-2 text-muted">
                                        <?= count($inscriptions) ?> / <?= $classe_capacite ?> places occupées
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>