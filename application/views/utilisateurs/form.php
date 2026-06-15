<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $utilisateur ? 'Modifier' : 'Ajouter' ?> un utilisateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><i class="bi bi-person-plus"></i> <?= $utilisateur ? 'Modifier' : 'Ajouter' ?> un utilisateur</h4>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= $utilisateur ? base_url('utilisateur/modifier/'.$utilisateur['id']) : base_url('utilisateur/enregistrer') ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="mb-3">
                            <label for="nom_utilisateur" class="form-label">Nom d'utilisateur <span class="text-danger">*</span></label>
                            <input type="text" name="nom_utilisateur" id="nom_utilisateur" class="form-control" 
                                   value="<?= $utilisateur ? $utilisateur['nom_utilisateur'] : '' ?>" 
                                   <?= $utilisateur ? 'readonly' : 'required' ?>>
                            <?php if($utilisateur): ?>
                                <small class="text-muted">Le nom d'utilisateur ne peut pas être modifié</small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" 
                                   value="<?= $utilisateur ? $utilisateur['email'] : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <?php foreach($roles as $key => $role): ?>
                                    <option value="<?= $key ?>" <?= ($utilisateur && $utilisateur['role'] == $key) ? 'selected' : '' ?>>
                                        <?= $role ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nom_complet" class="form-label">Nom complet</label>
                            <input type="text" name="nom_complet" id="nom_complet" class="form-control" 
                                   value="<?= $utilisateur ? $utilisateur['nom_complet'] : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" name="telephone" id="telephone" class="form-control" 
                                   value="<?= $utilisateur ? $utilisateur['telephone'] : '' ?>">
                        </div>

                        <?php if(!$utilisateur): ?>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" required>
                                <small class="text-muted">Minimum 6 caractères</small>
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Laisser vide pour ne pas changer">
                                <small class="text-muted">Minimum 6 caractères</small>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url('utilisateur') ?>" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary ms-2">
                                <i class="bi bi-save"></i> <?= $utilisateur ? 'Modifier' : 'Enregistrer' ?>
                            </button>
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