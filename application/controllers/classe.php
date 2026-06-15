<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classe extends CI_Controller {

    public function __construct() {
    parent::__construct();
    
    // Votre code existant...
    $this->load->model('M_classe');
    $this->load->helper('url');
    $this->load->library('session');
    
    // ✅ AJOUTER CE FILTRE DE RÔLE
    if(!$this->session->userdata('user_id')) {
        redirect('auth/login');
    }
    
    $role = $this->session->userdata('role');
    if($role != 'admin' && $role != 'secretaire') {
        show_error('Accès non autorisé.', 403);
    }
}
    // ── 1. LISTE DES CLASSES + NOMBRE D'INSCRITS ──────────────────────────
    public function index() {
        $data['classes'] = $this->M_classe->liste_avec_inscrits();
        $this->load->view('classe/liste_classe', $data);
    }

    // ── 2. FORMULAIRE AJOUT ───────────────────────────────────────────────
    public function form_class() {
        $data['annees'] = $this->M_classe->liste_annees();
        $this->load->view('classe/form_classe', $data);
    }

    // ── 3. ENREGISTREMENT ─────────────────────────────────────────────────
    public function enrigistrement() {
        $data = array(
            'code'     => $this->input->post('code'),
            'libelle'  => $this->input->post('libelle'),
            'niveau'   => $this->input->post('niveau'),
            'capacite' => $this->input->post('capacite'),
            'id_annee' => $this->input->post('id_annee'),
        );
        $this->M_classe->ajouter($data);
        $this->session->set_flashdata('success', 'Classe ajoutée avec succès !');
        redirect('classe/index');
    }

    // ── 4. FORMULAIRE MODIFICATION ────────────────────────────────────────
    public function edit_form($id) {
        $data['classe'] = $this->M_classe->get_classe($id);
        $data['annees'] = $this->M_classe->liste_annees();
        $this->load->view('classe/modif_form', $data);
    }

    // ── 5. MISE À JOUR ────────────────────────────────────────────────────
    public function save_update($id) {
        $data = array(
            'code'     => $this->input->post('code'),
            'libelle'  => $this->input->post('libelle'),
            'niveau'   => $this->input->post('niveau'),
            'capacite' => $this->input->post('capacite'),
            'id_annee' => $this->input->post('id_annee'),
        );
        $this->M_classe->update_classe($id, $data);
        $this->session->set_flashdata('success', 'Classe modifiée avec succès !');
        redirect('classe/index');
    }

    // ── 6. CONFIRMATION SUPPRESSION ───────────────────────────────────────
    public function delete_confirm($id) {
        $data['classe'] = $this->M_classe->get_classe($id);
        if (!$data['classe']) {
            $this->session->set_flashdata('error', 'Classe introuvable');
            redirect('classe/index');
        }
        $this->load->view('classe/supprime_form', $data);
    }

    // ── 7. SUPPRESSION (uniquement si aucun inscrit) ──────────────────────
    public function delete_now($id) {
        $nb_inscrits = $this->M_classe->compte_inscrits($id);
        if ($nb_inscrits > 0) {
            $this->session->set_flashdata('error', 'Impossible de supprimer : '.$nb_inscrits.' étudiant(s) inscrit(s) dans cette classe.');
        } else {
            $this->M_classe->supprimer($id);
            $this->session->set_flashdata('success', 'Classe supprimée avec succès !');
        }
        redirect('classe/index');
    }

    // ── 8. FICHE DÉTAIL + LISTE ÉTUDIANTS INSCRITS ────────────────────────
    public function detail($id) {
        $data['classe'] = $this->M_classe->get_classe($id);
        if (!$data['classe']) {
            $this->session->set_flashdata('error', 'Classe introuvable');
            redirect('classe/index');
        }
        $data['etudiants'] = $this->M_classe->get_etudiants($id);
        $this->load->view('classe/detail_classe', $data);
    }

    // ── 9. LISTE DES ÉTUDIANTS D'UNE CLASSE (POUR LE BOUTON "Élèves") ─────
    public function etudiants_par_classe($id) {
        // Récupérer les informations de la classe
        $data['classe'] = $this->M_classe->get_classe($id);
        
        if (!$data['classe']) {
            $this->session->set_flashdata('error', 'Classe introuvable');
            redirect('classe/index');
        }
        
        // Récupérer les étudiants inscrits dans cette classe
        $data['etudiants'] = $this->M_classe->get_etudiants($id);
        
        // Charger la vue
        $this->load->view('classe/etudiants_par_classe', $data);
    }
}
?>