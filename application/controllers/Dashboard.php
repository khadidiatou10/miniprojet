<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('M_dashboard');
        $this->load->database();
        
        // Vérifier que l'utilisateur est connecté (Point 73)
        if(!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }
    
    public function index() {
        // Statistiques selon le rôle
        $role = $this->session->userdata('role');
        
        // Point 64 : Statistiques générales (accessibles à tous)
        $data['total_etudiants'] = $this->M_dashboard->get_total_etudiants();
        $data['total_classes'] = $this->M_dashboard->get_total_classes();
        $data['total_professeurs'] = $this->M_dashboard->get_total_professeurs();
        $data['total_matieres'] = $this->M_dashboard->get_total_matieres();
        $data['total_inscriptions'] = $this->M_dashboard->get_total_inscriptions();
        $data['total_paiements'] = $this->M_dashboard->get_total_paiements();
        
        $data['derniers_etudiants'] = $this->M_dashboard->get_derniers_etudiants(5);
        $data['taux_paiement'] = $this->M_dashboard->get_taux_paiement();
        $data['top_etudiants'] = $this->M_dashboard->get_top_etudiants(5);
        $data['prochains_cours'] = $this->M_dashboard->get_prochains_cours(5);
        $data['classes'] = $this->M_dashboard->get_all_classes();
        $data['annee_active'] = $this->M_dashboard->get_annee_active();
        $data['role'] = $role;
        
        $this->load->view('dashboard/index', $data);
    }
}
?>