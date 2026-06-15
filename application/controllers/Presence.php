<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presence extends CI_Controller {
    
    public function __construct() {
    parent::__construct();
    
    // Votre code existant...
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->model('M_presence');
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
    
    // Point 44 : Page principale - choix classe, matière, date
    public function index() {
        $data['classes'] = $this->M_presence->get_all_classes();
        $data['matieres'] = $this->M_presence->get_all_matieres();
        
        $this->load->view('presences/index', $data);
    }
    
    // Point 44 : Formulaire de saisie des présences (liste avec cases à cocher)
    public function saisie() {
        $classe_id = $this->input->get('classe_id');
        $matiere_id = $this->input->get('matiere_id');
        $date_seance = $this->input->get('date_seance');
        
        if(!$classe_id || !$matiere_id || !$date_seance) {
            $this->session->set_flashdata('error', 'Veuillez sélectionner une classe, une matière et une date');
            redirect('presence');
            return;
        }
        
        $data['classe'] = $this->M_presence->get_classe($classe_id);
        $data['matiere'] = $this->M_presence->get_matiere($matiere_id);
        $data['date_seance'] = $date_seance;
        $data['etudiants'] = $this->M_presence->get_etudiants_par_classe($classe_id);
        
        // Récupérer les présences existantes pour cette séance
        $data['presences_existantes'] = $this->M_presence->get_presences_seance($classe_id, $matiere_id, $date_seance);
        
        $this->load->view('presences/saisie', $data);
    }
    
    // Point 44 : Enregistrer les présences
    public function enregistrer() {
        $classe_id = $this->input->post('classe_id');
        $matiere_id = $this->input->post('matiere_id');
        $date_seance = $this->input->post('date_seance');
        $presences = $this->input->post('present');
        $justifies = $this->input->post('justifie');
        $commentaires = $this->input->post('commentaire');
        
        if(!$presences) {
            $presences = array();
        }
        if(!$justifies) {
            $justifies = array();
        }
        
        $saved = $this->M_presence->sauvegarder_presences($classe_id, $matiere_id, $date_seance, $presences, $justifies, $commentaires);
        
        $this->session->set_flashdata('success', $saved . ' présence(s) enregistrée(s) avec succès !');
        redirect('presence');
    }
    
    // Point 45 : Historique des présences d'un étudiant
    public function historique($id_etudiant) {
        $data['etudiant'] = $this->M_presence->get_etudiant($id_etudiant);
        
        if(!$data['etudiant']) {
            $this->session->set_flashdata('error', 'Étudiant introuvable');
            redirect('etudiants');
            return;
        }
        
        $data['historique'] = $this->M_presence->get_historique_etudiant($id_etudiant);
        $data['statistiques'] = $this->M_presence->get_taux_absenteisme_etudiant($id_etudiant);
        
        $this->load->view('presences/historique', $data);
    }
    
    // Point 46 : Taux d'absentéisme par classe
    public function absenteisme_classe() {
        $classe_id = $this->input->get('classe_id');
        
        $data['classes'] = $this->M_presence->get_all_classes();
        $data['classe_selectionnee'] = $classe_id;
        
        if($classe_id) {
            $data['classe'] = $this->M_presence->get_classe($classe_id);
            $data['statistiques'] = $this->M_presence->get_taux_absenteisme_classe($classe_id);
            
            // Calcul de la moyenne de la classe
            $total_taux = 0;
            $nb_etudiants = count($data['statistiques']);
            foreach($data['statistiques'] as $s) {
                $total_taux += $s['taux_absence'];
            }
            $data['moyenne_classe'] = $nb_etudiants > 0 ? round($total_taux / $nb_etudiants, 2) : 0;
        }
        
        $this->load->view('presences/absenteisme_classe', $data);
    }
    
    // Point 47 : Alertes pour étudiants dépassant le seuil d'absence
    public function alertes() {
        $seuil = $this->input->get('seuil') ?: 3;
        $data['seuil'] = $seuil;
        $data['alertes'] = $this->M_presence->get_alertes_absences($seuil);
        
        $this->load->view('presences/alertes', $data);
    }
    
    // Point 48 : Modifier une présence
    public function modifier($id_presence) {
        $data['presence'] = $this->M_presence->get_presence($id_presence);
        
        if(!$data['presence']) {
            $this->session->set_flashdata('error', 'Présence introuvable');
            redirect('presence');
            return;
        }
        
        $data['etudiant'] = $this->M_presence->get_etudiant($data['presence']['id_etudiant']);
        $data['classe'] = $this->M_presence->get_classe($data['presence']['id_classe']);
        $data['matiere'] = $this->M_presence->get_matiere($data['presence']['id_matiere']);
        
        if($this->input->post()) {
            $update_data = array(
                'present' => $this->input->post('present') ? 1 : 0,
                'justifie' => $this->input->post('justifie') ? 1 : 0,
                'commentaire' => $this->input->post('commentaire'),
                'date_saisie' => date('Y-m-d H:i:s')
            );
            
            if($this->M_presence->modifier_presence($id_presence, $update_data)) {
                $this->session->set_flashdata('success', 'Présence modifiée avec succès !');
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la modification');
            }
            
            redirect('presence/historique/'.$data['presence']['id_etudiant']);
        }
        
        $this->load->view('presences/modifier', $data);
    }
    
    // Point 45 bis : Lien depuis la liste des étudiants
    public function lien_historique($id_etudiant) {
        redirect('presence/historique/'.$id_etudiant);
    }
}
?>