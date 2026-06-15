<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de notes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none !important; }
        }
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2><i class="bi bi-file-text"></i> Bulletin de notes</h2>
        <div>
            <button onclick="window.print()" class="btn btn-primary btn-sm-custom"><i class="bi bi-printer"></i> Imprimer</button>
            <a href="<?= base_url('etudiants') ?>" class="btn btn-secondary btn-sm-custom"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
    </div>

    <!-- Messages flash -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $this->session->flashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <!-- Informations étudiant -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <i class="bi bi-person"></i> Informations de l'étudiant
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 text-center">
                    <?php if(!empty($etudiant['photo']) && $etudiant['photo'] != 'default.png'): ?>
                        <img src="<?= base_url('uploads/etudiants/'.$etudiant['photo']) ?>" width="80" class="rounded-circle">
                    <?php else: ?>
                        <i class="bi bi-person-circle fs-1 text-secondary"></i>
                    <?php endif; ?>
                </div>
                <div class="col-md-10">
                    <h3><?= strtoupper($etudiant['nom']) ?> <?= ucfirst($etudiant['prenom']) ?></h3>
                    <p><strong>Matricule :</strong> <?= $etudiant['matricule'] ?? 'Non défini' ?></p>
                    <p><strong>Classe :</strong> <?= $classe_actuelle['libelle'] ?? 'Non inscrit' ?></p>
                    <p><strong>Email :</strong> <?= $etudiant['mail'] ?> | <strong>Tél :</strong> <?= $etudiant['telephone'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton Ajouter une note -->
    <div class="mb-3 no-print">
        <a href="<?= base_url('note/ajouter_note/'.$etudiant['id']) ?>" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter une note
        </a>
    </div>

    <!-- Filtre année -->
    <div class="card mb-4 no-print">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-funnel"></i> Filtrer par année
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="annee_id" class="form-select">
                        <option value="">-- Toutes les années --</option>
                        <?php foreach($annees as $a): ?>
                            <option value="<?= $a['id_annee'] ?>" <?= ($annee_selectionnee == $a['id_annee']) ? 'selected' : '' ?>><?= $a['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des notes -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <i class="bi bi-table"></i> Relevé de notes
        </div>
        <div class="card-body">
            <?php if(empty($notes)): ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Aucune note enregistrée.
                    <a href="<?= base_url('note/ajouter_note/'.$etudiant['id']) ?>" class="alert-link">Ajouter une note</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Matière</th><th>Coeff</th><th>CC</th><th>Examen</th><th>Finale</th><th>Pondérée</th>
                                <th class="no-print">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_coeff = 0;
                            $total_pond = 0;
                            foreach($notes as $n): 
                                $coeff = $n['coefficient'];
                                $finale = $n['note_finale'];
                                $pond = $coeff * $finale;
                                $total_coeff += $coeff;
                                $total_pond += $pond;
                            ?>
                            <tr>
                                <td><strong><?= $n['matiere_code'] ?></strong><br><small><?= $n['matiere_libelle'] ?></small></td>
                                <td class="text-center"><?= $coeff ?></td>
                                <td class="text-center"><?= $n['note_cc'] ?? '-' ?></td>
                                <td class="text-center"><?= $n['note_exam'] ?? '-' ?></td>
                                <td class="text-center"><span class="badge <?= $finale >= 10 ? 'bg-success' : 'bg-danger' ?>"><?= $finale ?></span></td>
                                <td class="text-center"><?= number_format($pond, 2) ?></td>
                                <td class="no-print">
                                    <a href="<?= base_url('note/modifier_note/'.$n['id_note']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <a href="<?= base_url('note/supprimer_note/'.$n['id_note'].'/'.$etudiant['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette note ?')"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <th colspan="4">Moyenne générale</th>
                                <th colspan="2">
                                    <?php $moyenne = $total_coeff > 0 ? $total_pond / $total_coeff : 0; ?>
                                    <span class="badge <?= $moyenne >= 10 ? 'bg-success' : 'bg-danger' ?> fs-6 p-2"><?= number_format($moyenne, 2) ?> / 20</span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="alert alert-info mt-3">
                    <strong>Appréciation :</strong>
                    <?php if($moyenne >= 16): ?>Excellent !
                    <?php elseif($moyenne >= 14): ?>Très bien !
                    <?php elseif($moyenne >= 12): ?>Assez bien !
                    <?php elseif($moyenne >= 10): ?>Passable
                    <?php else: ?>Insuffisant, des efforts sont nécessaires
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>