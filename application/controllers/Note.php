<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Note extends CI_Controller {
    
   public function __construct() {
    parent::__construct();
    
    // Votre code existant...
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->model('M_note');
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
    
    // Page d'accueil des notes
    public function index() {
        $data['classes'] = $this->M_note->get_all_classes();
        $data['matieres'] = $this->M_note->get_all_matieres();
        $data['annees'] = $this->M_note->get_all_annees();
        $data['annee_active'] = $this->M_note->get_annee_active();
        
        $this->load->view('notes/index', $data);
    }
    
    // Saisie des notes par classe (tableau)
    public function saisie() {
        $classe_id = $this->input->get('classe_id');
        $matiere_id = $this->input->get('matiere_id');
        $annee_id = $this->input->get('annee_id');
        
        if(!$classe_id || !$matiere_id) {
            $this->session->set_flashdata('error', 'Veuillez sélectionner une classe et une matière');
            redirect('note');
            return;
        }
        
        $data['classe'] = $this->M_note->get_classe($classe_id);
        $data['matiere'] = $this->M_note->get_matiere($matiere_id);
        $data['etudiants'] = $this->M_note->get_etudiants_par_classe($classe_id, $annee_id);
        $data['annee_id'] = $annee_id;
        
        foreach($data['etudiants'] as &$etudiant) {
            $note = $this->M_note->get_note_etudiant_matiere($etudiant['id_etudiant'], $matiere_id, $annee_id);
            $etudiant['note_cc'] = $note ? $note['note_cc'] : '';
            $etudiant['note_exam'] = $note ? $note['note_exam'] : '';
            $etudiant['note_finale'] = $note ? $note['note_finale'] : '';
        }
        
        $this->load->view('notes/saisie', $data);
    }
    
    // Enregistrer les notes du tableau
    public function enregistrer() {
        $classe_id = $this->input->post('classe_id');
        $matiere_id = $this->input->post('matiere_id');
        $annee_id = $this->input->post('annee_id');
        $etudiants = $this->input->post('etudiants');
        
        if(!$etudiants) {
            $this->session->set_flashdata('error', 'Aucune donnée reçue');
            redirect('note/saisie?classe_id='.$classe_id.'&matiere_id='.$matiere_id.'&annee_id='.$annee_id);
            return;
        }
        
        $saved = 0;
        foreach($etudiants as $id_etudiant => $notes) {
            $note_cc = !empty($notes['note_cc']) ? $notes['note_cc'] : null;
            $note_exam = !empty($notes['note_exam']) ? $notes['note_exam'] : null;
            
            if($note_cc !== null || $note_exam !== null) {
                $data = array(
                    'id_etudiant' => $id_etudiant,
                    'id_matiere' => $matiere_id,
                    'id_classe' => $classe_id,
                    'annee_scolaire_id' => $annee_id,
                    'note_cc' => $note_cc,
                    'note_exam' => $note_exam,
                    'date_saisie' => date('Y-m-d H:i:s')
                );
                
                if($this->M_note->sauvegarder_note($data)) {
                    $saved++;
                }
            }
        }
        
        $this->session->set_flashdata('success', $saved . ' note(s) enregistrée(s) avec succès !');
        redirect('note/saisie?classe_id='.$classe_id.'&matiere_id='.$matiere_id.'&annee_id='.$annee_id);
    }
    
    // ========== NOUVEAU : Gestion des notes par étudiant ==========
    
    // Voir le bulletin d'un étudiant
    public function notes_etudiant($id_etudiant) {
        $data['etudiant'] = $this->M_note->get_etudiant($id_etudiant);
        
        if(!$data['etudiant']) {
            $this->session->set_flashdata('error', 'Étudiant introuvable');
            redirect('etudiants');
            return;
        }
        
        // Récupérer la classe actuelle
        $sql = "SELECT c.id_class, c.code, c.libelle, c.niveau
                FROM inscription i
                JOIN classe c ON c.id_class = i.id_classe
                WHERE i.id_etudiant = $id_etudiant AND i.statut = 'actif'
                ORDER BY i.id_inscription DESC LIMIT 1";
        $query = $this->db->query($sql);
        $data['classe_actuelle'] = $query->row_array();
        
        $data['annees'] = $this->M_note->get_all_annees();
        $annee_id = $this->input->get('annee_id');
        $data['annee_selectionnee'] = $annee_id;
        $data['notes'] = $this->M_note->get_notes_etudiant($id_etudiant, $annee_id);
        $data['moyenne_generale'] = $this->M_note->get_moyenne_etudiant($id_etudiant, $annee_id);
        
        $this->load->view('notes/notes_etudiant', $data);
    }
    
    // Formulaire ajouter une note pour un étudiant
    public function ajouter_note($id_etudiant) {
        $data['etudiant'] = $this->M_note->get_etudiant($id_etudiant);
        
        if(!$data['etudiant']) {
            $this->session->set_flashdata('error', 'Étudiant introuvable');
            redirect('etudiants');
            return;
        }
        
        $data['matieres'] = $this->M_note->get_all_matieres();
        $data['classes'] = $this->M_note->get_all_classes();
        $data['annees'] = $this->M_note->get_all_annees();
        
        $this->load->view('notes/ajouter_note', $data);
    }
    
    // Enregistrer une note pour un étudiant
    public function enregistrer_note_unique() {
        $id_etudiant = $this->input->post('id_etudiant');
        $note_cc = $this->input->post('note_cc');
        $note_exam = $this->input->post('note_exam');
        $note_finale = ($note_cc * 0.4) + ($note_exam * 0.6);
        
        $data = array(
            'id_etudiant' => $id_etudiant,
            'id_matiere' => $this->input->post('id_matiere'),
            'id_classe' => $this->input->post('id_classe'),
            'annee_scolaire_id' => $this->input->post('annee_scolaire_id'),
            'note_cc' => $note_cc,
            'note_exam' => $note_exam,
            'note_finale' => $note_finale,
            'date_saisie' => date('Y-m-d H:i:s')
        );
        
        // Vérifier si la note existe déjà
        $this->db->where('id_etudiant', $id_etudiant);
        $this->db->where('id_matiere', $data['id_matiere']);
        $this->db->where('id_classe', $data['id_classe']);
        $this->db->where('annee_scolaire_id', $data['annee_scolaire_id']);
        $existing = $this->db->get('note')->row_array();
        
        if($existing) {
            $this->db->where('id_note', $existing['id_note']);
            $this->db->update('note', $data);
            $this->session->set_flashdata('success', 'Note modifiée avec succès !');
        } else {
            $this->db->insert('note', $data);
            $this->session->set_flashdata('success', 'Note ajoutée avec succès !');
        }
        
        redirect('note/notes_etudiant/'.$id_etudiant);
    }
    
    // Modifier une note
    public function modifier_note($id_note) {
        $this->db->where('id_note', $id_note);
        $note = $this->db->get('note')->row_array();
        
        if(!$note) {
            $this->session->set_flashdata('error', 'Note introuvable');
            redirect('note');
            return;
        }
        
        if($this->input->post()) {
            $note_cc = $this->input->post('note_cc');
            $note_exam = $this->input->post('note_exam');
            $note_finale = ($note_cc * 0.4) + ($note_exam * 0.6);
            
            $data = array(
                'note_cc' => $note_cc,
                'note_exam' => $note_exam,
                'note_finale' => $note_finale,
                'date_saisie' => date('Y-m-d H:i:s')
            );
            
            $this->db->where('id_note', $id_note);
            $this->db->update('note', $data);
            $this->session->set_flashdata('success', 'Note modifiée avec succès !');
            redirect('note/notes_etudiant/'.$note['id_etudiant']);
        }
        
        $data['note'] = $note;
        $data['matiere'] = $this->M_note->get_matiere($note['id_matiere']);
        $data['etudiant'] = $this->M_note->get_etudiant($note['id_etudiant']);
        
        $this->load->view('notes/modifier_note', $data);
    }
    
    // Supprimer une note
    public function supprimer_note($id_note, $id_etudiant) {
        $this->db->where('id_note', $id_note);
        $this->db->delete('note');
        $this->session->set_flashdata('success', 'Note supprimée avec succès !');
        redirect('note/notes_etudiant/'.$id_etudiant);
    }
    
    // Classement des étudiants
    public function classement() {
        $classe_id = $this->input->get('classe_id');
        $annee_id = $this->input->get('annee_id');
        
        $data['classes'] = $this->M_note->get_all_classes();
        $data['annees'] = $this->M_note->get_all_annees();
        $data['classe_selectionnee'] = $classe_id;
        $data['annee_selectionnee'] = $annee_id;
        
        if($classe_id) {
            $data['classe'] = $this->M_note->get_classe($classe_id);
            $data['classement'] = $this->M_note->get_classement_classe($classe_id, $annee_id);
            
            if(!empty($data['classement'])) {
                $moyennes = array_column($data['classement'], 'moyenne');
                $data['moyenne_classe'] = round(array_sum($moyennes) / count($moyennes), 2);
                $data['max_classe'] = max($moyennes);
                $data['min_classe'] = min($moyennes);
            } else {
                $data['moyenne_classe'] = 0;
                $data['max_classe'] = 0;
                $data['min_classe'] = 0;
            }
        }
        
        $this->load->view('notes/classement', $data);
    }
}
?>