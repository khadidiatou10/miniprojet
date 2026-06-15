<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Configuration des frais</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-calculator"></i> Configuration des frais scolaires</h2>
        <a href="<?= base_url('paiement') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">
            <i class="bi bi-gear"></i> Définir les frais par classe
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <select name="classe_id" class="form-select">
                        <option value="">-- Sélectionner une classe --</option>
                        <?php foreach($classes as $c): ?>
                            <option value="<?= $c['id_class'] ?>" <?= ($classe_selectionnee == $c['id_class']) ? 'selected' : '' ?>><?= $c['code'] ?> - <?= $c['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="annee_id" class="form-select">
                        <option value="">-- Année active --</option>
                        <?php foreach($annees as $a): ?>
                            <option value="<?= $a['id_annee'] ?>" <?= ($annee_selectionnee == $a['id_annee']) ? 'selected' : '' ?>><?= $a['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Charger</button>
                </div>
            </form>

            <?php if(isset($classe) && $classe): ?>
            <form method="POST">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="id_classe" value="<?= $classe['id_class'] ?>">
                <input type="hidden" name="annee_scolaire_id" value="<?= $annee_selectionnee ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="montant_total" class="form-label">Montant total annuel (FCFA)</label>
                        <input type="number" name="montant_total" id="montant_total" class="form-control" step="1000" value="<?= $frais['montant_total'] ?? 0 ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="montant_inscription" class="form-label">Frais d'inscription (FCFA)</label>
                        <input type="number" name="montant_inscription" id="montant_inscription" class="form-control" step="1000" value="<?= $frais['montant_inscription'] ?? 0 ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mensualite" class="form-label">Mensualité (FCFA)</label>
                        <input type="number" name="mensualite" id="mensualite" class="form-control" step="1000" value="<?= $frais['mensualite'] ?? 0 ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nb_mensualites" class="form-label">Nombre de mensualités</label>
                        <input type="number" name="nb_mensualites" id="nb_mensualites" class="form-control" value="<?= $frais['nb_mensualites'] ?? 10 ?>">
                    </div>
                </div>

                <div class="alert alert-info">
                    <strong>Calcul automatique :</strong> Total = <?= number_format(($frais['montant_inscription'] ?? 0) + (($frais['mensualite'] ?? 0) * ($frais['nb_mensualites'] ?? 10)), 0, ',', ' ') ?> FCFA
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>