<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin - <?= strtoupper($etudiant['nom']) ?> <?= ucfirst($etudiant['prenom']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; font-size: 12px; }
            .container { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
            .card { border: none !important; box-shadow: none !important; }
            .card-header { background-color: #f8f9fa !important; border-bottom: 1px solid #000 !important; }
            .badge { border: 1px solid #000; }
        }
        .btn-sm-custom { padding: 4px 8px; font-size: 12px; margin: 2px; }
        .bulletin-header { border-bottom: 2px solid #000; margin-bottom: 20px; }
        .note-excellente { color: #28a745; font-weight: bold; }
        .note-insuffisante { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
<div class="container mt-4">
    <!-- Boutons d'action (non imprimables) -->
    <div class="text-end mb-3 no-print">
        <button onclick="window.print()" class="btn btn-primary btn-sm-custom">
            <i class="bi bi-printer"></i> Imprimer
        </button>
        <a href="<?= base_url('bulletin') ?>" class="btn btn-secondary btn-sm-custom">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        <?php if($etudiant && isset($etudiant['id'])): ?>
        <a href="<?= base_url('bulletin/pdf/'.$etudiant['id'].'/'.$periode.'/'.($annee['id_annee'] ?? '')) ?>" class="btn btn-danger btn-sm-custom" target="_blank">
            <i class="bi bi-file-pdf"></i> PDF
        </a>
        <?php endif; ?>
    </div>

    <!-- Bulletin -->
    <div class="card">
        <div class="card-body">
            <!-- En-tête du bulletin -->
            <div class="text-center bulletin-header">
                <h2>Établissement Scolaire</h2>
                <h4>Bulletin de notes - <?= $periode == 'annuel' ? 'Annuel' : ($periode == 'S1' ? 'Premier semestre' : 'Deuxième semestre') ?></h4>
                <p>Année scolaire : <?= isset($annee['libelle']) ? $annee['libelle'] : 'Année en cours' ?></p>
            </div>

            <!-- Informations étudiant -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr><th style="width: 120px;">Nom :</th><td><strong><?= isset($etudiant['nom']) ? strtoupper($etudiant['nom']) : '' ?></strong></td></tr>
                        <tr><th>Prénom :</th><td><?= isset($etudiant['prenom']) ? ucfirst($etudiant['prenom']) : '' ?></td></tr>
                        <tr><th>Matricule :</th><td><?= isset($etudiant['matricule']) ? $etudiant['matricule'] : 'Non défini' ?></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr><th style="width: 120px;">Classe :</th>
                        <td>
                            <?php if(isset($classe) && $classe && isset($classe['code'])): ?>
                                <strong><?= $classe['code'] ?> - <?= $classe['libelle'] ?></strong>
                            <?php else: ?>
                                <span class="text-warning">Non inscrit</span>
                            <?php endif; ?>
                         </td>
                        </tr>
                        <tr>
                            <th>Date naissance :</th>
                            <td><?= isset($etudiant['date_naissance']) ? date('d/m/Y', strtotime($etudiant['date_naissance'])) : 'Non renseignée' ?></td>
                        </tr>
                        <tr>
                            <th>Email :</th>
                            <td><?= isset($etudiant['mail']) ? $etudiant['mail'] : '' ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Tableau des notes -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Matière</th>
                            <th class="text-center">Coefficient</th>
                            <th class="text-center">Note CC<br><small>/20</small></th>
                            <th class="text-center">Note Examen<br><small>/20</small></th>
                            <th class="text-center">Note Finale<br><small>/20</small></th>
                            <th class="text-center">Note Pondérée</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_coeff = 0;
                        $total_pond = 0;
                        if(isset($notes) && !empty($notes)):
                        foreach($notes as $n): 
                            $coeff = isset($n['coefficient']) ? $n['coefficient'] : 1;
                            $finale = isset($n['note_finale']) ? $n['note_finale'] : 0;
                            $pond = $coeff * $finale;
                            $total_coeff += $coeff;
                            $total_pond += $pond;
                            $note_class = $finale >= 16 ? 'note-excellente' : ($finale < 10 ? 'note-insuffisante' : '');
                        ?>
                            <tr>
                                <td><strong><?= isset($n['matiere_code']) ? $n['matiere_code'] : '' ?></strong><br><small><?= isset($n['matiere_libelle']) ? $n['matiere_libelle'] : '' ?></small></td>
                                <td class="text-center"><?= $coeff ?></td>
                                <td class="text-center"><?= isset($n['note_cc']) ? number_format($n['note_cc'], 2) : '-' ?></td>
                                <td class="text-center"><?= isset($n['note_exam']) ? number_format($n['note_exam'], 2) : '-' ?></td>
                                <td class="text-center <?= $note_class ?>"><?= number_format($finale, 2) ?></td>
                                <td class="text-center"><?= number_format($pond, 2) ?></td>
                            </tr>
                        <?php endforeach; 
                        else: ?>
                            <tr><td colspan="6" class="text-center">Aucune note enregistrée pour cet étudiant</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th colspan="4">Moyenne générale</th>
                            <th colspan="2">
                                <?php $moyenne = $total_coeff > 0 ? round($total_pond / $total_coeff, 2) : 0; ?>
                                <span class="badge <?= $moyenne >= 10 ? 'bg-success' : 'bg-danger' ?> fs-6">
                                    <?= number_format($moyenne, 2) ?> / 20
                                </span>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Résultats -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5><i class="bi bi-trophy"></i> Résultats</h5>
                            <table class="table table-sm">
                                <tr><th>Rang dans la classe :</th>
                                    <td>
                                        <?php if(isset($rang) && $rang > 0 && isset($statistiques_classe['nb_etudiants'])): ?>
                                            <strong><?= $rang ?>ᵉ / <?= $statistiques_classe['nb_etudiants'] ?></strong>
                                        <?php else: ?>
                                            <span class="text-muted">Non classé</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr><th>Mention :</th><td><strong><?= isset($mention) ? $mention : '-' ?></strong></td></tr>
                                <tr><th>Moyenne de la classe :</th>
                                    <td><?= isset($statistiques_classe['moyenne_classe']) ? $statistiques_classe['moyenne_classe'] : '-' ?> / 20</td>
                                </tr>
                                <tr><th>Meilleure note :</th>
                                    <td><?= isset($statistiques_classe['max_classe']) ? $statistiques_classe['max_classe'] : '-' ?> / 20</td>
                                </tr>
                                <tr><th>Moins bonne note :</th>
                                    <td><?= isset($statistiques_classe['min_classe']) ? $statistiques_classe['min_classe'] : '-' ?> / 20</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5><i class="bi bi-bar-chart"></i> Appréciation</h5>
                            <?php if($moyenne >= 16): ?>
                                <div class="alert alert-success">🏆 Excellent ! Félicitations pour vos résultats exceptionnels.</div>
                            <?php elseif($moyenne >= 14): ?>
                                <div class="alert alert-info">👍 Très bien ! De très bonnes performances.</div>
                            <?php elseif($moyenne >= 12): ?>
                                <div class="alert alert-primary">📚 Assez bien ! Des résultats satisfaisants.</div>
                            <?php elseif($moyenne >= 10): ?>
                                <div class="alert alert-warning">⚠️ Passable ! Des efforts supplémentaires sont nécessaires.</div>
                            <?php else: ?>
                                <div class="alert alert-danger">🔴 Insuffisant ! Une remise en question s'impose.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signature -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <p>Fait à ____________, le <?= date('d/m/Y') ?></p>
                </div>
                <div class="col-md-6 text-end">
                    <p>Le chef d'établissement<br><br><br>Signature</p>
                </div>
            </div>

            <!-- Pied de page -->
            <div class="text-center mt-4 text-muted">
                <small>Document généré le <?= date('d/m/Y à H:i:s') ?></small>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>