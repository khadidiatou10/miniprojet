<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une note</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4><i class="bi bi-plus-circle"></i> Ajouter une note</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Étudiant :</strong> <?= strtoupper($etudiant['nom']) ?> <?= ucfirst($etudiant['prenom']) ?>
                    </div>

                    <form method="POST" action="<?= base_url('note/enregistrer_note_unique') ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="id_etudiant" value="<?= $etudiant['id'] ?>">

                        <div class="mb-3">
                            <label for="id_matiere" class="form-label">Matière <span class="text-danger">*</span></label>
                            <select name="id_matiere" id="id_matiere" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <?php foreach($matieres as $m): ?>
                                    <option value="<?= $m['id_matiere'] ?>"><?= $m['code'] ?> - <?= $m['libelle'] ?> (Coeff: <?= $m['coefficient'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="id_classe" class="form-label">Classe <span class="text-danger">*</span></label>
                            <select name="id_classe" id="id_classe" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <?php foreach($classes as $c): ?>
                                    <option value="<?= $c['id_class'] ?>"><?= $c['code'] ?> - <?= $c['libelle'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="annee_scolaire_id" class="form-label">Année scolaire</label>
                            <select name="annee_scolaire_id" id="annee_scolaire_id" class="form-select">
                                <option value="">-- Année active --</option>
                                <?php foreach($annees as $a): ?>
                                    <option value="<?= $a['id_annee'] ?>" <?= $a['actif'] == 1 ? 'selected' : '' ?>><?= $a['libelle'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="note_cc" class="form-label">Note CC (sur 20)</label>
                                <input type="number" name="note_cc" id="note_cc" class="form-control" step="0.25" min="0" max="20" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="note_exam" class="form-label">Note Examen (sur 20)</label>
                                <input type="number" name="note_exam" id="note_exam" class="form-control" step="0.25" min="0" max="20" required>
                            </div>
                        </div>

                        <div class="alert alert-secondary">
                            <strong>Note finale calculée :</strong> <span id="note_finale">0.00</span> / 20
                            <br><small>Formule : (CC × 0.4) + (Examen × 0.6)</small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url('note/notes_etudiant/'.$etudiant['id']) ?>" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success ms-2">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function calculerFinale() {
        let cc = parseFloat(document.getElementById('note_cc').value) || 0;
        let exam = parseFloat(document.getElementById('note_exam').value) || 0;
        document.getElementById('note_finale').innerText = ((cc * 0.4) + (exam * 0.6)).toFixed(2);
    }
    document.getElementById('note_cc').addEventListener('input', calculerFinale);
    document.getElementById('note_exam').addEventListener('input', calculerFinale);
</script>
</body>
</html>