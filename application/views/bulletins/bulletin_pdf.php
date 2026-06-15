<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin - <?= strtoupper($etudiant['nom']) ?> <?= ucfirst($etudiant['prenom']) ?></title>
    <style>
        body { font-family: dejavusans, sans-serif; font-size: 10pt; margin: 20px; }
        .bulletin-header { text-align: center; border-bottom: 2px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
        h2 { margin: 0; }
        h4 { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; text-align: center; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .badge-success { color: #28a745; font-weight: bold; }
        .badge-danger { color: #dc3545; font-weight: bold; }
        .alert-success { color: #28a745; }
        .alert-info { color: #17a2b8; }
        .alert-warning { color: #ffc107; }
        .alert-danger { color: #dc3545; }
        .mt-4 { margin-top: 20px; }
        .mb-4 { margin-bottom: 20px; }
        .row { width: 100%; }
        .col-half { width: 48%; float: left; }
        .clearfix { clear: both; }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="bulletin-header">
        <h2>Établissement Scolaire</h2>
        <h4>Bulletin de notes - <?= $periode == 'annuel' ? 'Annuel' : ($periode == 'S1' ? 'Premier semestre' : 'Deuxième semestre') ?></h4>
        <p>Année scolaire : <?= $annee['libelle'] ?? 'Année en cours' ?></p>
    </div>

    <!-- Informations étudiant -->
    <table class="table-borderless">
        <tr>
            <td width="50%"><strong>Nom :</strong> <?= strtoupper($etudiant['nom']) ?></td>
            <td width="50%"><strong>Classe :</strong> <?= $classe['code'] ?> - <?= $classe['libelle'] ?></td>
        </tr>
        <tr>
            <td><strong>Prénom :</strong> <?= ucfirst($etudiant['prenom']) ?></td>
            <td><strong>Date naissance :</strong> <?= date('d/m/Y', strtotime($etudiant['date_naissance'])) ?></td>
        </tr>
        <tr>
            <td><strong>Matricule :</strong> <?= $etudiant['matricule'] ?? 'Non défini' ?></td>
            <td><strong>Email :</strong> <?= $etudiant['mail'] ?></td>
        </tr>
    </table>

    <!-- Tableau des notes -->
    <table>
        <thead>
            <tr>
                <th>Matière</th>
                <th class="text-center">Coeff</th>
                <th class="text-center">CC</th>
                <th class="text-center">Examen</th>
                <th class="text-center">Finale</th>
                <th class="text-center">Pondérée</th>
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
                $note_class = $finale >= 16 ? 'badge-success' : ($finale < 10 ? 'badge-danger' : '');
            ?>
                <tr>
                    <td><strong><?= $n['matiere_code'] ?></strong><br><?= $n['matiere_libelle'] ?></td>
                    <td class="text-center"><?= $coeff ?></td>
                    <td class="text-center"><?= number_format($n['note_cc'], 2) ?></td>
                    <td class="text-center"><?= number_format($n['note_exam'], 2) ?></td>
                    <td class="text-center <?= $note_class ?>"><?= number_format($finale, 2) ?></td>
                    <td class="text-center"><?= number_format($pond, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Moyenne générale</th>
                <th colspan="2">
                    <?php $moyenne = $total_coeff > 0 ? round($total_pond / $total_coeff, 2) : 0; ?>
                    <?= number_format($moyenne, 2) ?> / 20
                </th>
            </tr>
        </tfoot>
    </table>

    <!-- Résultats -->
    <div class="row">
        <div class="col-half">
            <strong>Rang :</strong> <?= $rang ?>ᵉ / <?= $statistiques_classe['nb_etudiants'] ?><br>
            <strong>Mention :</strong> <?= $mention ?><br>
            <strong>Moyenne de la classe :</strong> <?= $statistiques_classe['moyenne_classe'] ?> / 20
        </div>
        <div class="col-half">
            <strong>Meilleure note :</strong> <?= $statistiques_classe['max_classe'] ?> / 20<br>
            <strong>Moins bonne note :</strong> <?= $statistiques_classe['min_classe'] ?> / 20
        </div>
    </div>
    <div class="clearfix"></div>

    <!-- Appréciation -->
    <div class="mt-4">
        <strong>Appréciation :</strong><br>
        <?php if($moyenne >= 16): ?>
            <span class="alert-success">🏆 Excellent ! Félicitations pour vos résultats exceptionnels.</span>
        <?php elseif($moyenne >= 14): ?>
            <span class="alert-info">👍 Très bien ! De très bonnes performances.</span>
        <?php elseif($moyenne >= 12): ?>
            <span class="alert-info">📚 Assez bien ! Des résultats satisfaisants.</span>
        <?php elseif($moyenne >= 10): ?>
            <span class="alert-warning">⚠️ Passable ! Des efforts supplémentaires sont nécessaires.</span>
        <?php else: ?>
            <span class="alert-danger">🔴 Insuffisant ! Une remise en question s'impose.</span>
        <?php endif; ?>
    </div>

    <!-- Signature -->
    <div class="mt-4">
        <table class="table-borderless">
            <tr>
                <td width="50%">Fait à ____________, le <?= date('d/m/Y') ?></td>
                <td class="text-end">Le chef d'établissement<br><br><br>Signature</td>
            </tr>
        </table>
    </div>

    <!-- Pied de page -->
    <div class="text-center mt-4">
        <small>Document généré le <?= date('d/m/Y à H:i:s') ?></small>
    </div>
</body>
</html>