<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matiere extends CI_Controller {
    
    public function __construct() {
    parent::__construct();
    
    // Votre code existant...
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->model('M_matiere');
    $this->load->database();
    
    // ✅ AJOUTER CE FILTRE DE RÔLE
    if(!$this->session->userdata('user_id')) {
        redirect('auth/login');
    }
    
    $role = $this->session->userdata('role');
    if($role != 'admin' && $role != 'secretaire') {
        show_error('Accès non autorisé.', 403);
    }
}
    
    // Point 33 : Lister toutes les matières
    public function index() {
        $data['matieres'] = $this->M_matiere->get_all_matieres();
        $data['total'] = $this->M_matiere->compter();
        
        $this->load->view('matieres/list', $data);
    }
    
    // Point 34 : Formulaire d'ajout
    public function form() {
        $this->load->view('matieres/form');
    }
    
    // Point 34 : Enregistrer une nouvelle matière
    public function enregistrer() {
        // Validation - CORRIGÉE (plus de alpha_numeric)
        $this->form_validation->set_rules('code', 'Code', 'required|trim');
        $this->form_validation->set_rules('libelle', 'Libellé', 'required|trim');
        $this->form_validation->set_rules('coefficient', 'Coefficient', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('volume_horaire', 'Volume horaire', 'required|numeric|greater_than[0]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('matiere/form');
            return;
        }
        
        // Vérifier si le code existe déjà
        if ($this->M_matiere->code_existe($this->input->post('code'))) {
            $this->session->set_flashdata('error', 'Ce code de matière existe déjà !');
            redirect('matiere/form');
            return;
        }
        
        $data = array(
            'code' => strtoupper($this->input->post('code')),
            'libelle' => $this->input->post('libelle'),
            'coefficient' => $this->input->post('coefficient'),
            'volume_horaire' => $this->input->post('volume_horaire'),
            'description' => $this->input->post('description')
        );
        
        if ($this->M_matiere->ajouter($data)) {
            $this->session->set_flashdata('success', 'Matière ajoutée avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout');
        }
        
        redirect('matiere');
    }
    
    // Point 35 : Formulaire de modification
    public function edit_form($id) {
        $data['matiere'] = $this->M_matiere->get_matiere($id);
        
        if (!$data['matiere']) {
            $this->session->set_flashdata('error', 'Matière introuvable');
            redirect('matiere');
        }
        
        $this->load->view('matieres/edit', $data);
    }
    
    // Point 35 : Modifier une matière
    public function modifier($id) {
        // Validation - CORRIGÉE (plus de alpha_numeric)
        $this->form_validation->set_rules('code', 'Code', 'required|trim');
        $this->form_validation->set_rules('libelle', 'Libellé', 'required|trim');
        $this->form_validation->set_rules('coefficient', 'Coefficient', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('volume_horaire', 'Volume horaire', 'required|numeric|greater_than[0]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('matiere/edit_form/'.$id);
            return;
        }
        
        // Vérifier si le code existe déjà (excluant la matière actuelle)
        if ($this->M_matiere->code_existe($this->input->post('code'), $id)) {
            $this->session->set_flashdata('error', 'Ce code de matière existe déjà !');
            redirect('matiere/edit_form/'.$id);
            return;
        }
        
        $data = array(
            'code' => strtoupper($this->input->post('code')),
            'libelle' => $this->input->post('libelle'),
            'coefficient' => $this->input->post('coefficient'),
            'volume_horaire' => $this->input->post('volume_horaire'),
            'description' => $this->input->post('description')
        );
        
        if ($this->M_matiere->modifier($id, $data)) {
            $this->session->set_flashdata('success', 'Matière modifiée avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la modification');
        }
        
        redirect('matiere');
    }
    
    // Point 36 : Supprimer une matière (si non affectée)
    public function supprimer($id) {
        $matiere = $this->M_matiere->get_matiere($id);
        
        if (!$matiere) {
            $this->session->set_flashdata('error', 'Matière introuvable');
            redirect('matiere');
        }
        
        // Vérifier si la matière est affectée
        if ($this->M_matiere->est_affectee($id)) {
            $this->session->set_flashdata('error', 'Impossible de supprimer cette matière car elle est utilisée dans des affectations ou des notes !');
            redirect('matiere');
        }
        
        if ($this->M_matiere->supprimer($id)) {
            $this->session->set_flashdata('success', 'Matière supprimée avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression');
        }
        
        redirect('matiere');
    }
    
    // Point 37 : Afficher la fiche d'une matière avec ses affectations
    public function detail($id) {
        $data['matiere'] = $this->M_matiere->get_matiere($id);
        
        if (!$data['matiere']) {
            $this->session->set_flashdata('error', 'Matière introuvable');
            redirect('matiere');
        }
        
        $data['affectations'] = $this->M_matiere->get_affectations_par_matiere($id);
        $data['professeurs'] = $this->M_matiere->get_all_professeurs();
        $data['classes'] = $this->M_matiere->get_all_classes();
        $data['annees'] = $this->M_matiere->get_all_annees();
        
        $this->load->view('matieres/detail', $data);
    }
    
    // Point 37 : Ajouter une affectation (professeur à matière dans une classe)
    public function ajouter_affectation() {
        $id_matiere = $this->input->post('id_matiere');
        
        $data = array(
            'id_professeur' => $this->input->post('id_professeur'),
            'id_matiere' => $id_matiere,
            'id_classe' => $this->input->post('id_classe'),
            'annee_scolaire_id' => $this->input->post('annee_scolaire_id') ?: null
        );
        
        if ($this->M_matiere->ajouter_affectation($data)) {
            $this->session->set_flashdata('success', 'Professeur affecté avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Cette affectation existe déjà ou une erreur est survenue !');
        }
        
        redirect('matiere/detail/'.$id_matiere);
    }
    
    // Point 37 : Supprimer une affectation
    public function supprimer_affectation($id_affectation, $id_matiere) {
        if ($this->M_matiere->supprimer_affectation($id_affectation)) {
            $this->session->set_flashdata('success', 'Affectation supprimée avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression');
        }
        
        redirect('matiere/detail/'.$id_matiere);
    }
}
?>