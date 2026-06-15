<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une note</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4><i class="bi bi-pencil"></i> Modifier la note</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Étudiant :</strong> <?= strtoupper($etudiant['nom']) ?> <?= ucfirst($etudiant['prenom']) ?><br>
                        <strong>Matière :</strong> <?= $matiere['code'] ?> - <?= $matiere['libelle'] ?>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="note_cc" class="form-label">Note CC</label>
                                <input type="number" name="note_cc" id="note_cc" class="form-control" step="0.25" min="0" max="20" value="<?= $note['note_cc'] ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="note_exam" class="form-label">Note Examen</label>
                                <input type="number" name="note_exam" id="note_exam" class="form-control" step="0.25" min="0" max="20" value="<?= $note['note_exam'] ?>" required>
                            </div>
                        </div>

                        <div class="alert alert-secondary">
                            <strong>Note finale :</strong> <span id="note_finale"><?= $note['note_finale'] ?></span> / 20
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url('note/notes_etudiant/'.$etudiant['id']) ?>" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-warning ms-2">Enregistrer</button>
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