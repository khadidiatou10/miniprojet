<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Inscriptions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-sm-custom {
            padding: 4px 8px;
            font-size: 12px;
            margin: 2px;
            white-space: nowrap;
        }
        .actions-cell {
            min-width: 220px;
            white-space: nowrap;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge-actif {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        .badge-inactif {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-journal-bookmark-fill"></i> Gestion des Inscriptions</h2>
        <div>
            <?php if(isset($show_inactifs) && $show_inactifs): ?>
                <a href="<?= base_url('inscription') ?>" class="btn btn-info btn-sm-custom">
                    <i class="bi bi-person-check"></i> Voir actifs
                </a>
            <?php else: ?>
                <a href="<?= base_url('inscription/inactifs') ?>" class="btn btn-secondary btn-sm-custom">
                    <i class="bi bi-person-x"></i> Voir désinscrits
                </a>
            <?php endif; ?>
            <a href="<?= base_url('inscription/form') ?>" class="btn btn-primary btn-sm-custom">
                <i class="bi bi-plus-circle"></i> Ajouter
            </a>
        </div>
    </div>

    <!-- Messages flash -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filtres -->
    <?php if(!isset($show_inactifs) || !$show_inactifs): ?>
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-funnel"></i> Filtrer par classe et année
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('inscription') ?>" class="row g-3">
                <div class="col-md-5">
                    <label for="classe_id" class="form-label">Classe</label>
                    <select name="classe_id" id="classe_id" class="form-select">
                        <option value="">-- Toutes les classes --</option>
                        <?php if(isset($classes) && !empty($classes)): ?>
                            <?php foreach($classes as $classe): ?>
                                <option value="<?= $classe['id_class'] ?>" <?= (isset($classe_selectionnee) && $classe_selectionnee == $classe['id_class']) ? 'selected' : '' ?>>
                                    <?= $classe['code'] ?> - <?= $classe['libelle'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="annee_id" class="form-label">Année scolaire</label>
                    <select name="annee_id" id="annee_id" class="form-select">
                        <option value="">-- Toutes les années --</option>
                        <?php if(isset($annees) && !empty($annees)): ?>
                            <?php foreach($annees as $annee): ?>
                                <option value="<?= $annee['id_annee'] ?>" <?= (isset($annee_selectionnee) && $annee_selectionnee == $annee['id_annee']) ? 'selected' : '' ?>>
                                    <?= $annee['libelle'] ?> <?= ($annee['actif'] == 1) ? '(Active)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Liste des inscriptions -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-list"></i> 
            <?= (isset($show_inactifs) && $show_inactifs) ? 'Étudiants désinscrits' : 'Liste des inscriptions actives' ?>
            <span class="badge bg-light text-dark ms-2"><?= count($inscriptions) ?> inscription(s)</span>
        </div>
        <div class="card-body">
            <?php if(empty($inscriptions)): ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Aucune inscription trouvée.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Photo</th>
                                <th>Matricule</th>
                                <th>Nom & Prénom</th>
                                <th>Classe</th>
                                <th>Année scolaire</th>
                                <th>Date inscription</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($inscriptions as $insc): ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if(!empty($insc['photo']) && $insc['photo'] != 'default.png' && file_exists('./uploads/etudiants/'.$insc['photo'])): ?>
                                            <img src="<?= base_url('uploads/etudiants/'.$insc['photo']) ?>" width="45" height="45" class="rounded-circle" style="object-fit:cover;">
                                        <?php else: ?>
                                            <i class="bi bi-person-circle fs-1 text-secondary"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= $insc['matricule'] ?? 'E00'.$insc['id_etudiant'] ?></span>
                                     </td>
                                    <td>
                                        <strong><?= strtoupper($insc['nom']) ?></strong> <?= ucfirst($insc['prenom']) ?>
                                     </td>
                                    <td><?= $insc['classe_libelle'] ?? $insc['classe_libelle'] ?> </td>
                                    <td><?= $insc['annee_libelle'] ?? 'Non définie' ?> </td>
                                    <td><?= date('d/m/Y', strtotime($insc['date_inscription'])) ?> </td>
                                    <td class="text-center">
                                        <?php if(isset($insc['statut']) && $insc['statut'] == 'actif'): ?>
                                            <span class="badge-actif"><i class="bi bi-check-circle"></i> Actif</span>
                                        <?php else: ?>
                                            <span class="badge-inactif"><i class="bi bi-x-circle"></i> Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions-cell">
                                        <?php if(isset($show_inactifs) && $show_inactifs): ?>
                                            <a href="<?= base_url('inscription/reactiver/'.$insc['id_inscription']) ?>" class="btn btn-sm btn-success btn-sm-custom" onclick="return confirm('Réactiver cette inscription ?')">
                                                <i class="bi bi-arrow-repeat"></i> Réactiver
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('inscription/edit_form/'.$insc['id_inscription']) ?>" class="btn btn-sm btn-warning btn-sm-custom" title="Modifier">
                                                <i class="bi bi-pencil"></i> Modifier
                                            </a>
                                            <a href="<?= base_url('inscription/desinscrire/'.$insc['id_inscription']) ?>" class="btn btn-sm btn-sm-custom" style="background-color:#E91E63; color:white;" title="Désinscrire" onclick="return confirm('Confirmer la désinscription de <?= $insc['prenom'] ?> <?= $insc['nom'] ?> ?')">
                                                <i class="bi bi-person-x"></i> Désinscrire
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