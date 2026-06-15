<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de paiement</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            .container { margin: 0; padding: 0; }
            .card { border: none !important; box-shadow: none !important; }
        }
        .recu-header { border-bottom: 2px solid #000; margin-bottom: 20px; }
        .recu-footer { border-top: 1px solid #ccc; margin-top: 30px; padding-top: 10px; }
        .montant { font-size: 24px; font-weight: bold; color: #28a745; }
    </style>
</head>
<body>
<div class="container mt-4">
    <!-- Boutons impression -->
    <div class="text-end mb-3 no-print">
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Imprimer</button>
        <a href="<?= base_url('paiement/historique/'.$etudiant['id']) ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <!-- Reçu -->
    <div class="card">
        <div class="card-body">
            <div class="text-center recu-header">
                <h2>Établissement Scolaire</h2>
                <h4>Reçu de paiement</h4>
                <p>N° REÇU : <?= str_pad($paiement['id_paiement'], 8, '0', STR_PAD_LEFT) ?></p>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <strong>Étudiant :</strong><br>
                    <?= strtoupper($etudiant['nom']) ?> <?= ucfirst($etudiant['prenom']) ?><br>
                    Matricule : <?= $etudiant['matricule'] ?? 'Non défini' ?><br>
                    Classe : <?= $classe['code'] ?> - <?= $classe['libelle'] ?>
                </div>
                <div class="col-md-6 text-end">
                    <strong>Date de paiement :</strong><br>
                    <?= date('d/m/Y', strtotime($paiement['date_paiement'])) ?><br>
                    <strong>Année scolaire :</strong><br>
                    <?= $annee['libelle'] ?? 'Non définie' ?>
                </div>
            </div>

            <table class="table table-bordered">
                <tr><th style="width: 40%;">Désignation</th><td><?= $types[$paiement['type_paiement']] ?? $paiement['type_paiement'] ?> <?= $paiement['mois'] ? '- '.$paiement['mois'] : '' ?></td></tr>
                <tr><th>Montant</th><td class="montant"><?= number_format($paiement['montant'], 0, ',', ' ') ?> FCFA</td></tr>
                <tr><th>Mode de paiement</th><td><?= $mode_texte ?></td></tr>
                <?php if($paiement['reference']): ?>
                <tr><th>Référence</th><td><?= $paiement['reference'] ?></td></tr>
                <?php endif; ?>
                <?php if($paiement['commentaire']): ?>
                <tr><th>Commentaire</th><td><?= $paiement['commentaire'] ?></td></tr>
                <?php endif; ?>
            </table>

            <div class="text-center recu-footer">
                <p>Montant arrêté à la somme de : <strong><?= strtoupper($this->num2words($paiement['montant'])) ?> Francs CFA</strong></p>
                <p>Signature et cachet</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>