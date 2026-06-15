<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Enregistrer un paiement</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4><i class="bi bi-plus-circle"></i> Enregistrer un paiement</h4>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= base_url('paiement/enregistrer') ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="mb-3">
                            <label for="id_etudiant" class="form-label">Étudiant <span class="text-danger">*</span></label>
                            <select name="id_etudiant" id="id_etudiant" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <?php if($etudiant): ?>
                                    <option value="<?= $etudiant['id'] ?>" selected><?= strtoupper($etudiant['nom']) ?> <?= ucfirst($etudiant['prenom']) ?> (<?= $etudiant['matricule'] ?? 'E00'.$etudiant['id'] ?>)</option>
                                <?php else: ?>
                                    <?php
                                    // Récupérer tous les étudiants inscrits
                                    $sql = "SELECT e.id, e.nom, e.prenom, e.matricule, c.libelle as classe
                                            FROM inscription i
                                            JOIN etudiant e ON e.id = i.id_etudiant
                                            JOIN classe c ON c.id_class = i.id_classe
                                            WHERE i.statut = 'actif'
                                            ORDER BY e.nom ASC";
                                    $etudiants = $this->db->query($sql)->result_array();
                                    foreach($etudiants as $e): ?>
                                        <option value="<?= $e['id'] ?>"><?= strtoupper($e['nom']) ?> <?= ucfirst($e['prenom']) ?> - <?= $e['classe'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="annee_scolaire_id" class="form-label">Année scolaire</label>
                                <select name="annee_scolaire_id" id="annee_scolaire_id" class="form-select">
                                    <?php foreach($annees as $a): ?>
                                        <option value="<?= $a['id_annee'] ?>" <?= ($annee_active && $a['id_annee'] == $annee_active['id_annee']) ? 'selected' : '' ?>><?= $a['libelle'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_paiement" class="form-label">Date de paiement <span class="text-danger">*</span></label>
                                <input type="date" name="date_paiement" id="date_paiement" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="montant" class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" name="montant" id="montant" class="form-control" step="1000" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mode_paiement" class="form-label">Mode de paiement <span class="text-danger">*</span></label>
                                <select name="mode_paiement" id="mode_paiement" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach($modes as $key => $mode): ?>
                                        <option value="<?= $key ?>"><?= $mode ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type_paiement" class="form-label">Type de paiement</label>
                                <select name="type_paiement" id="type_paiement" class="form-select">
                                    <?php foreach($types as $key => $type): ?>
                                        <option value="<?= $key ?>"><?= $type ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mois" class="form-label">Mois (pour mensualité)</label>
                                <select name="mois" id="mois" class="form-select">
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach($mois as $m): ?>
                                        <option value="<?= $m ?>"><?= $m ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reference" class="form-label">Référence (chèque, transaction, etc.)</label>
                            <input type="text" name="reference" id="reference" class="form-control" placeholder="Ex: CHQ001, REF123...">
                        </div>

                        <div class="mb-3">
                            <label for="commentaire" class="form-label">Commentaire</label>
                            <textarea name="commentaire" id="commentaire" class="form-control" rows="2" placeholder="Informations complémentaires..."></textarea>
                        </div>

                        <?php if($inscription): ?>
                            <div class="alert alert-info">
                                <strong>Situation actuelle :</strong><br>
                                Montant total dû : <?= number_format($inscription['montant_total'], 0, ',', ' ') ?> FCFA<br>
                                Déjà payé : <?= number_format($inscription['montant_paye'], 0, ',', ' ') ?> FCFA<br>
                                Reste à payer : <span class="fw-bold text-danger"><?= number_format($inscription['montant_total'] - $inscription['montant_paye'], 0, ',', ' ') ?> FCFA</span>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url('paiement') ?>" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success ms-2"><i class="bi bi-save"></i> Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>