<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utilisateur extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->database();
        
        // Vérifier que l'utilisateur est connecté
        if(!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // Vérifier le rôle (seul admin peut gérer les utilisateurs) - Point 73
        $user = $this->User_model->get_user($this->session->userdata('user_id'));
        if(!$user || $user['role'] != 'admin') {
            show_error('Accès non autorisé. Seul l\'administrateur peut gérer les utilisateurs.', 403);
        }
    }
    
    // Point 69 : Lister tous les utilisateurs
    public function index() {
        $data['utilisateurs'] = $this->User_model->get_all_users();
        $data['roles'] = $this->User_model->get_roles();
        
        $this->load->view('utilisateurs/list', $data);
    }
    
    // Point 70 : Formulaire d'ajout
    public function form() {
        $data['utilisateur'] = null;
        $data['roles'] = $this->User_model->get_roles();
        
        $this->load->view('utilisateurs/form', $data);
    }
    
    // Point 70 : Enregistrer un nouvel utilisateur
    public function enregistrer() {
        $this->form_validation->set_rules('nom_utilisateur', 'Nom d\'utilisateur', 'required|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Mot de passe', 'required|min_length[6]');
        $this->form_validation->set_rules('role', 'Rôle', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('utilisateur/form');
            return;
        }
        
        // Vérifier si nom d'utilisateur existe déjà
        if($this->User_model->username_existe($this->input->post('nom_utilisateur'))) {
            $this->session->set_flashdata('error', 'Ce nom d\'utilisateur existe déjà !');
            redirect('utilisateur/form');
            return;
        }
        
        // Vérifier si email existe déjà
        if($this->User_model->email_existe($this->input->post('email'))) {
            $this->session->set_flashdata('error', 'Cet email existe déjà !');
            redirect('utilisateur/form');
            return;
        }
        
        $data = array(
            'nom_utilisateur' => $this->input->post('nom_utilisateur'),
            'email' => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'role' => $this->input->post('role'),
            'nom_complet' => $this->input->post('nom_complet'),
            'telephone' => $this->input->post('telephone'),
            'est_actif' => 1,
            'date_creation' => date('Y-m-d H:i:s')
        );
        
        if($this->User_model->ajouter($data)) {
            $this->session->set_flashdata('success', 'Utilisateur ajouté avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout');
        }
        
        redirect('utilisateur');
    }
    
    // Point 71 : Formulaire de modification
    public function edit_form($id) {
        $data['utilisateur'] = $this->User_model->get_user($id);
        
        if(!$data['utilisateur']) {
            $this->session->set_flashdata('error', 'Utilisateur introuvable');
            redirect('utilisateur');
            return;
        }
        
        $data['roles'] = $this->User_model->get_roles();
        
        $this->load->view('utilisateurs/form', $data);
    }
    
    // Point 71 : Modifier un utilisateur
    public function modifier($id) {
        $utilisateur = $this->User_model->get_user($id);
        
        if(!$utilisateur) {
            $this->session->set_flashdata('error', 'Utilisateur introuvable');
            redirect('utilisateur');
            return;
        }
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('role', 'Rôle', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('utilisateur/edit_form/'.$id);
            return;
        }
        
        // Vérifier si email existe déjà (excluant l'utilisateur actuel)
        if($this->User_model->email_existe($this->input->post('email'), $id)) {
            $this->session->set_flashdata('error', 'Cet email existe déjà !');
            redirect('utilisateur/edit_form/'.$id);
            return;
        }
        
        $data = array(
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role'),
            'nom_complet' => $this->input->post('nom_complet'),
            'telephone' => $this->input->post('telephone')
        );
        
        // Si un nouveau mot de passe est fourni
        $new_password = $this->input->post('new_password');
        if(!empty($new_password)) {
            if(strlen($new_password) < 6) {
                $this->session->set_flashdata('error', 'Le mot de passe doit contenir au moins 6 caractères');
                redirect('utilisateur/edit_form/'.$id);
                return;
            }
            $data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
        }
        
        if($this->User_model->modifier($id, $data)) {
            $this->session->set_flashdata('success', 'Utilisateur modifié avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la modification');
        }
        
        redirect('utilisateur');
    }
    
    // Point 72 : Supprimer un utilisateur (sauf le dernier admin)
    public function supprimer($id) {
        $utilisateur = $this->User_model->get_user($id);
        
        if(!$utilisateur) {
            $this->session->set_flashdata('error', 'Utilisateur introuvable');
            redirect('utilisateur');
            return;
        }
        
        // Ne pas se supprimer soi-même
        if($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Vous ne pouvez pas supprimer votre propre compte !');
            redirect('utilisateur');
            return;
        }
        
        // Vérifier si c'est le dernier admin (Point 72)
        if($utilisateur['role'] == 'admin' && $this->User_model->est_dernier_admin($id)) {
            $this->session->set_flashdata('error', 'Impossible de supprimer le dernier administrateur !');
            redirect('utilisateur');
            return;
        }
        
        if($this->User_model->supprimer($id)) {
            $this->session->set_flashdata('success', 'Utilisateur désactivé avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression');
        }
        
        redirect('utilisateur');
    }
    
    // Réactiver un utilisateur désactivé
    public function reactiver($id) {
        $this->User_model->modifier($id, array('est_actif' => 1));
        $this->session->set_flashdata('success', 'Utilisateur réactivé avec succès !');
        redirect('utilisateur');
    }
}
?>