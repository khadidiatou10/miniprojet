<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Classement des étudiants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-trophy"></i> Classement des étudiants</h1>
            <div>
                <a href="<?= base_url('note') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
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

        <!-- Formulaire de sélection -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5><i class="fas fa-filter"></i> Sélectionner une classe</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="<?= base_url('note/classement') ?>" class="row g-3">
                    <div class="col-md-5">
                        <label for="classe_id" class="form-label">Classe</label>
                        <select name="classe_id" id="classe_id" class="form-select">
                            <option value="">-- Sélectionner une classe --</option>
                            <?php if(isset($classes) && !empty($classes)): ?>
                                <?php foreach($classes as $classe): ?>
                                    <option value="<?= $classe['id_class'] ?>" <?= (isset($classe_selectionnee) && $classe_selectionnee == $classe['id_class']) ? 'selected' : '' ?>>
                                        <?= $classe['code'] ?> - <?= $classe['libelle'] ?> (<?= $classe['niveau'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="annee_id" class="form-label">Année scolaire</label>
                        <select name="annee_id" id="annee_id" class="form-select">
                            <option value="">-- Année active --</option>
                            <?php if(isset($annees) && !empty($annees)): ?>
                                <?php foreach($annees as $annee): ?>
                                    <option value="<?= $annee['id_annee'] ?>" <?= (isset($annee_selectionnee) && $annee_selectionnee == $annee['id_annee']) ? 'selected' : '' ?>>
                                        <?= $annee['libelle'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Voir classement
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if(isset($classe_selectionnee) && $classe_selectionnee && isset($classement)): ?>
            <!-- Statistiques de la classe -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-chart-line"></i> Moyenne de la classe</h5>
                            <h3><?= isset($moyenne_classe) ? $moyenne_classe : '-' ?> / 20</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-arrow-up"></i> Meilleure moyenne</h5>
                            <h3><?= isset($max_classe) ? $max_classe : '-' ?> / 20</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-dark">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-arrow-down"></i> Moins bonne moyenne</h5>
                            <h3><?= isset($min_classe) ? $min_classe : '-' ?> / 20</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classement -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5><i class="fas fa-trophy"></i> Classement - <?= isset($classe) ? $classe['libelle'] : '' ?></h5>
                </div>
                <div class="card-body">
                    <?php if(empty($classement)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Aucune note enregistrée pour cette classe.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Rang</th>
                                        <th>Matricule</th>
                                        <th>Nom & Prénom</th>
                                        <th>Moyenne générale</th>
                                        <th>Mention</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($classement as $etudiant_data): ?>
                                        <?php 
                                            $moyenne = isset($etudiant_data['moyenne']) ? $etudiant_data['moyenne'] : 0;
                                            if($moyenne >= 16) $mention = 'Très bien';
                                            elseif($moyenne >= 14) $mention = 'Bien';
                                            elseif($moyenne >= 12) $mention = 'Assez bien';
                                            elseif($moyenne >= 10) $mention = 'Passable';
                                            else $mention = 'Insuffisant';
                                            
                                            $medal = '';
                                            if(isset($etudiant_data['rang']) && $etudiant_data['rang'] == 1) $medal = '🥇';
                                            elseif(isset($etudiant_data['rang']) && $etudiant_data['rang'] == 2) $medal = '🥈';
                                            elseif(isset($etudiant_data['rang']) && $etudiant_data['rang'] == 3) $medal = '🥉';
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge <?= (isset($etudiant_data['rang']) && $etudiant_data['rang'] <= 3) ? 'bg-warning' : 'bg-secondary' ?> fs-6">
                                                    <?= $medal ?> #<?= isset($etudiant_data['rang']) ? $etudiant_data['rang'] : '-' ?>
                                                </span>
                                              </td>
                                            <td><?= isset($etudiant_data['matricule']) ? $etudiant_data['matricule'] : '-' ?></td>
                                            <td>
                                                <strong><?= isset($etudiant_data['nom']) ? strtoupper($etudiant_data['nom']) : '' ?></strong> 
                                                <?= isset($etudiant_data['prenom']) ? ucfirst($etudiant_data['prenom']) : '' ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge <?= $moyenne >= 10 ? 'bg-success' : 'bg-danger' ?> fs-6">
                                                    <?= $moyenne ?> / 20
                                                </span>
                                            </td>
                                            <td><?= $mention ?></td>
                                            <td>
                                                <a href="<?= base_url('note/notes_etudiant/'.$etudiant_data['id']) ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Voir détails
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif(isset($classe_selectionnee) && $classe_selectionnee): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> Veuillez sélectionner une classe pour voir le classement.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>