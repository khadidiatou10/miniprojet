<!DOCTYPE html>
<html>
<head>
    <title>Supprimer un étudiant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="container mt-5">

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-danger shadow">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title mb-0">Confirmer la suppression</h3>
            </div>
            <div class="card-body">
                <p class="text-center fw-bold">Êtes-vous sûr de vouloir supprimer cet étudiant ?</p>
                
                <table class="table table-sm table-bordered mt-3">
                    <tr class="table-light">
                        <th width="40%">ID :</th>
                        <td><?php echo $etudiant['id']; ?></td>
                    </tr>
                    <tr>
                        <th>Nom :</th>
                        <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                    </tr>
                    <tr>
                        <th>Prénom :</th>
                        <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                    </tr>
                    <tr>
                        <th>Email :</th>
                        <td><?php echo htmlspecialchars($etudiant['mail']); ?></td>
                    </tr>
                </table>

                <div class="alert alert-warning py-2 mt-3">
                    <small><strong>Note :</strong> Cette action supprimera définitivement les données de la base.</small>
                </div>

                <!-- ✅ action corrigée : etudiants/delete -->
                <form action="<?php echo base_url('etudiants/delete/'.$etudiant['id']); ?>" method="post">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                           value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <div class="d-flex justify-content-between mt-4">
                        <!-- ✅ lien annuler corrigé -->
                        <a href="<?php echo base_url('etudiants/index'); ?>" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>