<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('User_model');
        $this->load->library('session');
    } 
    
    public function login() {
        $this->load->view('auth/login');
    }
    
    public function verification() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->User_model->get_user_by_username($username);

        if ($user && password_verify($password, $user['password'])) {
            if($user['est_actif'] != 1) {
                $this->session->set_flashdata('error', 'Votre compte est désactivé. Contactez l\'administrateur.');
                redirect('auth/login');
                return;
            }
            
            $this->session->set_userdata('user_id', $user['id']);
            $this->session->set_userdata('role', $user['role']);
            $this->session->set_userdata('username', $user['nom_utilisateur']);
            $this->session->set_userdata('nom_complet', $user['nom_complet']);
            
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
            redirect('auth/login');
        }
    }
    
    // ✅ Méthode de déconnexion
    public function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Vous avez été déconnecté avec succès.');
        redirect('auth/login');
    }
}
?>