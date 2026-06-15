<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmploiDuTemps extends CI_Controller {
    
    public function __construct() {
    parent::__construct();
    
    // Votre code existant...
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->model('M_emploi_du_temps');
    $this->load->database();
    
    // ✅ AJOUTER CE FILTRE DE RÔLE
    if(!$this->session->userdata('user_id')) {
        redirect('auth/login');
    }
    
    $role = $this->session->userdata('role');
    if($role != 'admin' && $role != 'secretaire' && $role != 'professeur') {
        show_error('Accès non autorisé.', 403);
    }
}
    
    // Point 55 : Page d'accueil - choix classe ou professeur
    public function index() {
        $data['classes'] = $this->M_emploi_du_temps->get_all_classes();
        $data['professeurs'] = $this->M_emploi_du_temps->get_all_professeurs();
        $data['annees'] = $this->M_emploi_du_temps->get_all_annees();
        $data['annee_active'] = $this->M_emploi_du_temps->get_annee_active();
        
        $this->load->view('emploi_du_temps/index', $data);
    }
    
    // Point 55 : Afficher l'emploi du temps d'une classe (grille hebdomadaire)
    public function classe($classe_id = null) {
        $classe_id = $classe_id ?: $this->input->get('classe_id');
        $annee_id = $this->input->get('annee_id');
        
        if(!$classe_id) {
            $this->session->set_flashdata('error', 'Veuillez sélectionner une classe');
            redirect('emploiDuTemps');
            return;
        }
        
        $data['classe'] = $this->M_emploi_du_temps->get_classe($classe_id);
        if(!$data['classe']) {
            $this->session->set_flashdata('error', 'Classe introuvable');
            redirect('emploiDuTemps');
            return;
        }
        
        $data['annee_id'] = $annee_id;
        $data['annees'] = $this->M_emploi_du_temps->get_all_annees();
        $data['jours'] = $this->M_emploi_du_temps->get_jours();
        $data['creneaux'] = $this->M_emploi_du_temps->get_creneaux();
        
        // Récupérer l'emploi du temps
        $seances = $this->M_emploi_du_temps->get_emploi_classe($classe_id, $annee_id);
        
        // Organiser les données en grille
        $grille = array();
        foreach($data['jours'] as $jour) {
            foreach($data['creneaux'] as $heure => $libelle) {
                $grille[$jour][$heure] = null;
            }
        }
        
        foreach($seances as $s) {
            $heure_debut = $s['heure_debut'];
            if(isset($grille[$s['jour']][$heure_debut])) {
                $grille[$s['jour']][$heure_debut] = $s;
            }
        }
        
        $data['grille'] = $grille;
        
        $this->load->view('emploi_du_temps/grille', $data);
    }
    
    // Point 56 : Afficher l'emploi du temps d'un professeur (grille hebdomadaire)
    public function professeur($professeur_id = null) {
        $professeur_id = $professeur_id ?: $this->input->get('professeur_id');
        $annee_id = $this->input->get('annee_id');
        
        if(!$professeur_id) {
            $this->session->set_flashdata('error', 'Veuillez sélectionner un professeur');
            redirect('emploiDuTemps');
            return;
        }
        
        $data['professeur'] = $this->M_emploi_du_temps->get_professeur($professeur_id);
        if(!$data['professeur']) {
            $this->session->set_flashdata('error', 'Professeur introuvable');
            redirect('emploiDuTemps');
            return;
        }
        
        $data['annee_id'] = $annee_id;
        $data['annees'] = $this->M_emploi_du_temps->get_all_annees();
        $data['jours'] = $this->M_emploi_du_temps->get_jours();
        $data['creneaux'] = $this->M_emploi_du_temps->get_creneaux();
        
        // Récupérer l'emploi du temps
        $seances = $this->M_emploi_du_temps->get_emploi_professeur($professeur_id, $annee_id);
        
        // Organiser les données en grille
        $grille = array();
        foreach($data['jours'] as $jour) {
            foreach($data['creneaux'] as $heure => $libelle) {
                $grille[$jour][$heure] = null;
            }
        }
        
        foreach($seances as $s) {
            $heure_debut = $s['heure_debut'];
            if(isset($grille[$s['jour']][$heure_debut])) {
                $grille[$s['jour']][$heure_debut] = $s;
            }
        }
        
        $data['grille'] = $grille;
        
        $this->load->view('emploi_du_temps/professeur', $data);
    }
    
    // Point 54 + 57 : Formulaire d'ajout/modification d'une séance
    public function form($id_seance = null) {
        if($id_seance) {
            $data['seance'] = $this->M_emploi_du_temps->get_seance($id_seance);
            if(!$data['seance']) {
                $this->session->set_flashdata('error', 'Séance introuvable');
                redirect('emploiDuTemps');
                return;
            }
            $data['title'] = 'Modifier une séance';
        } else {
            $data['seance'] = null;
            $data['title'] = 'Ajouter une séance';
        }
        
        $data['classes'] = $this->M_emploi_du_temps->get_all_classes();
        $data['matieres'] = $this->M_emploi_du_temps->get_all_matieres();
        $data['professeurs'] = $this->M_emploi_du_temps->get_all_professeurs();
        $data['annees'] = $this->M_emploi_du_temps->get_all_annees();
        $data['jours'] = $this->M_emploi_du_temps->get_jours();
        $data['creneaux'] = $this->M_emploi_du_temps->get_creneaux();
        $data['types_cours'] = $this->M_emploi_du_temps->get_types_cours();
        $data['annee_active'] = $this->M_emploi_du_temps->get_annee_active();
        
        $this->load->view('emploi_du_temps/form', $data);
    }
    
    // Point 54 : Enregistrer une séance
    public function enregistrer() {
        $this->form_validation->set_rules('id_classe', 'Classe', 'required');
        $this->form_validation->set_rules('id_matiere', 'Matière', 'required');
        $this->form_validation->set_rules('id_professeur', 'Professeur', 'required');
        $this->form_validation->set_rules('jour', 'Jour', 'required');
        $this->form_validation->set_rules('heure_debut', 'Heure de début', 'required');
        $this->form_validation->set_rules('heure_fin', 'Heure de fin', 'required');
        $this->form_validation->set_rules('salle', 'Salle', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('emploiDuTemps/form');
            return;
        }
        
        $data = array(
            'id_classe' => $this->input->post('id_classe'),
            'id_matiere' => $this->input->post('id_matiere'),
            'id_professeur' => $this->input->post('id_professeur'),
            'jour' => $this->input->post('jour'),
            'heure_debut' => $this->input->post('heure_debut'),
            'heure_fin' => $this->input->post('heure_fin'),
            'salle' => strtoupper($this->input->post('salle')),
            'type_cours' => $this->input->post('type_cours'),
            'annee_scolaire_id' => $this->input->post('annee_scolaire_id')
        );
        
        // Point 58 : Vérifier les conflits
        $conflits = $this->M_emploi_du_temps->verifier_conflit($data);
        
        if($conflits['salle']) {
            $this->session->set_flashdata('error', 'Conflit : Cette salle est déjà occupée à ce créneau !');
            redirect('emploiDuTemps/form');
            return;
        }
        
        if($conflits['professeur']) {
            $this->session->set_flashdata('error', 'Conflit : Ce professeur a déjà un cours à ce créneau !');
            redirect('emploiDuTemps/form');
            return;
        }
        
        if($conflits['classe']) {
            $this->session->set_flashdata('error', 'Conflit : Cette classe a déjà un cours à ce créneau !');
            redirect('emploiDuTemps/form');
            return;
        }
        
        if($this->M_emploi_du_temps->ajouter_seance($data)) {
            $this->session->set_flashdata('success', 'Séance ajoutée avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout');
        }
        
        redirect('emploiDuTemps');
    }
    
    // Point 57 : Modifier une séance
    public function modifier($id_seance) {
        $this->form_validation->set_rules('id_classe', 'Classe', 'required');
        $this->form_validation->set_rules('id_matiere', 'Matière', 'required');
        $this->form_validation->set_rules('id_professeur', 'Professeur', 'required');
        $this->form_validation->set_rules('jour', 'Jour', 'required');
        $this->form_validation->set_rules('heure_debut', 'Heure de début', 'required');
        $this->form_validation->set_rules('heure_fin', 'Heure de fin', 'required');
        $this->form_validation->set_rules('salle', 'Salle', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('emploiDuTemps/form/'.$id_seance);
            return;
        }
        
        $data = array(
            'id_classe' => $this->input->post('id_classe'),
            'id_matiere' => $this->input->post('id_matiere'),
            'id_professeur' => $this->input->post('id_professeur'),
            'jour' => $this->input->post('jour'),
            'heure_debut' => $this->input->post('heure_debut'),
            'heure_fin' => $this->input->post('heure_fin'),
            'salle' => strtoupper($this->input->post('salle')),
            'type_cours' => $this->input->post('type_cours'),
            'annee_scolaire_id' => $this->input->post('annee_scolaire_id')
        );
        
        // Point 58 : Vérifier les conflits (en excluant la séance actuelle)
        $conflits = $this->M_emploi_du_temps->verifier_conflit($data, $id_seance);
        
        if($conflits['salle']) {
            $this->session->set_flashdata('error', 'Conflit : Cette salle est déjà occupée à ce créneau !');
            redirect('emploiDuTemps/form/'.$id_seance);
            return;
        }
        
        if($conflits['professeur']) {
            $this->session->set_flashdata('error', 'Conflit : Ce professeur a déjà un cours à ce créneau !');
            redirect('emploiDuTemps/form/'.$id_seance);
            return;
        }
        
        if($conflits['classe']) {
            $this->session->set_flashdata('error', 'Conflit : Cette classe a déjà un cours à ce créneau !');
            redirect('emploiDuTemps/form/'.$id_seance);
            return;
        }
        
        if($this->M_emploi_du_temps->modifier_seance($id_seance, $data)) {
            $this->session->set_flashdata('success', 'Séance modifiée avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la modification');
        }
        
        redirect('emploiDuTemps');
    }
    
    // Point 57 : Supprimer une séance
    public function supprimer($id_seance) {
        $seance = $this->M_emploi_du_temps->get_seance($id_seance);
        
        if(!$seance) {
            $this->session->set_flashdata('error', 'Séance introuvable');
            redirect('emploiDuTemps');
            return;
        }
        
        if($this->M_emploi_du_temps->supprimer_seance($id_seance)) {
            $this->session->set_flashdata('success', 'Séance supprimée avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression');
        }
        
        redirect('emploiDuTemps');
    }
}
?>