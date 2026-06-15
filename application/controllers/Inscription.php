<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inscription extends CI_Controller {
    
    public function __construct() {
    parent::__construct();
    
    // Votre code existant...
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->model('M_inscription');
    $this->load->model('M_classe');
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
    
    // Point 24 : Lister les étudiants inscrits dans une classe
    public function index() {
        $classe_id = $this->input->get('classe_id');
        $annee_id = $this->input->get('annee_id');
        
        // Récupérer toutes les classes et années pour les filtres
        $data['classes'] = $this->db->get('classe')->result_array();
        $data['annees'] = $this->db->get('annee_scolaire')->result_array();
        $data['classe_selectionnee'] = $classe_id;
        $data['annee_selectionnee'] = $annee_id;
        
        if($classe_id) {
            // Requête avec filtre
            $sql = "SELECT i.*, 
                    e.nom, e.prenom, e.mail, e.telephone, e.photo,
                    c.code, c.libelle as classe_libelle, c.niveau,
                    a.libelle as annee_libelle
                    FROM inscription i
                    LEFT JOIN etudiant e ON e.id = i.id_etudiant
                    LEFT JOIN classe c ON c.id_class = i.id_classe
                    LEFT JOIN annee_scolaire a ON a.id_annee = i.annee_scolaire_id
                    WHERE i.id_classe = $classe_id";
            
            if($annee_id) {
                $sql .= " AND i.annee_scolaire_id = $annee_id";
            }
            
            $sql .= " AND i.statut = 'actif' ORDER BY e.nom ASC";
            
            $data['inscriptions'] = $this->db->query($sql)->result_array();
        } else {
            // Toutes les inscriptions actives
            $sql = "SELECT i.*, 
                    e.nom, e.prenom, e.mail, e.telephone, e.photo,
                    c.code, c.libelle as classe_libelle, c.niveau,
                    a.libelle as annee_libelle
                    FROM inscription i
                    LEFT JOIN etudiant e ON e.id = i.id_etudiant
                    LEFT JOIN classe c ON c.id_class = i.id_classe
                    LEFT JOIN annee_scolaire a ON a.id_annee = i.annee_scolaire_id
                    WHERE i.statut = 'actif'
                    ORDER BY i.id_inscription DESC";
            
            $data['inscriptions'] = $this->db->query($sql)->result_array();
        }
        
        $this->load->view('inscription/list', $data);
    }
    
    // Point 23 : Formulaire d'inscription
    public function form() {
        $data['etudiants'] = $this->db->get('etudiant')->result_array();
        $data['classes'] = $this->db->get('classe')->result_array();
        $data['annees'] = $this->db->get('annee_scolaire')->result_array();
        
        $this->load->view('inscription/form', $data);
    }
    
    // Point 23 + 27 : Enregistrer avec vérification double inscription
    public function enregistrer() {
        $id_etudiant = $this->input->post('id_etudiant');
        $id_classe = $this->input->post('id_classe');
        $annee_scolaire_id = $this->input->post('annee_scolaire_id');
        $date_inscription = $this->input->post('date_inscription');
        
        // Point 27 : Vérifier la double inscription (même étudiant, même classe, même année)
        $check = $this->db->where('id_etudiant', $id_etudiant)
                         ->where('id_classe', $id_classe)
                         ->where('annee_scolaire_id', $annee_scolaire_id)
                         ->where('statut', 'actif')
                         ->get('inscription')
                         ->num_rows();
        
        if($check > 0) {
            $this->session->set_flashdata('error', 'Cet étudiant est déjà inscrit dans cette classe pour cette année scolaire !');
            redirect('inscription/form');
            return;
        }
        
        $data = array(
            'id_etudiant' => $id_etudiant,
            'id_classe' => $id_classe,
            'annee_scolaire_id' => $annee_scolaire_id,
            'date_inscription' => $date_inscription,
            'statut' => 'actif'
        );
        
        if($this->db->insert('inscription', $data)) {
            $this->session->set_flashdata('success', 'Inscription ajoutée avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'inscription');
        }
        
        redirect('inscription');
    }
    
    // Point 26 : Désinscrire un étudiant (passer statut à 'inactif')
    public function desinscrire($id) {
        // On ne supprime pas, on passe le statut à 'inactif'
        $this->db->where('id_inscription', $id)->update('inscription', array('statut' => 'inactif'));
        $this->session->set_flashdata('success', 'Étudiant désinscrit avec succès !');
        redirect('inscription');
    }
    
    // Point 25 : Formulaire de modification
    public function edit_form($id) {
        // Récupérer l'inscription avec toutes les infos
        $sql = "SELECT i.*, 
                e.nom, e.prenom, e.mail, e.telephone, e.photo,
                c.code, c.libelle as classe_libelle, c.niveau, c.id_class,
                a.libelle as annee_libelle, a.id_annee
                FROM inscription i
                LEFT JOIN etudiant e ON e.id = i.id_etudiant
                LEFT JOIN classe c ON c.id_class = i.id_classe
                LEFT JOIN annee_scolaire a ON a.id_annee = i.annee_scolaire_id
                WHERE i.id_inscription = $id AND i.statut = 'actif'";
        
        $data['inscription'] = $this->db->query($sql)->row_array();
        
        if(!$data['inscription']) {
            $this->session->set_flashdata('error', 'Inscription introuvable');
            redirect('inscription');
        }
        
        $data['etudiants'] = $this->db->get('etudiant')->result_array();
        $data['classes'] = $this->db->get('classe')->result_array();
        $data['annees'] = $this->db->get('annee_scolaire')->result_array();
        
        $this->load->view('inscription/edit', $data);
    }
    
    // Point 25 : Modifier l'inscription (changer de classe)
    public function modifier($id) {
        $id_classe = $this->input->post('id_classe');
        $annee_scolaire_id = $this->input->post('annee_scolaire_id');
        $date_inscription = $this->input->post('date_inscription');
        
        // Récupérer l'inscription actuelle
        $inscription = $this->db->where('id_inscription', $id)->get('inscription')->row_array();
        
        if(!$inscription) {
            $this->session->set_flashdata('error', 'Inscription introuvable');
            redirect('inscription');
        }
        
        // Point 27 : Vérifier la double inscription (en excluant l'actuelle)
        $check = $this->db->where('id_etudiant', $inscription['id_etudiant'])
                         ->where('id_classe', $id_classe)
                         ->where('annee_scolaire_id', $annee_scolaire_id)
                         ->where('statut', 'actif')
                         ->where('id_inscription !=', $id)
                         ->get('inscription')
                         ->num_rows();
        
        if($check > 0) {
            $this->session->set_flashdata('error', 'Cet étudiant est déjà inscrit dans cette classe pour cette année scolaire !');
            redirect('inscription/edit_form/'.$id);
            return;
        }
        
        $data = array(
            'id_classe' => $id_classe,
            'annee_scolaire_id' => $annee_scolaire_id,
            'date_inscription' => $date_inscription
        );
        
        $this->db->where('id_inscription', $id)->update('inscription', $data);
        $this->session->set_flashdata('success', 'Inscription modifiée avec succès !');
        redirect('inscription');
    }
    
    // Point 24 bis : Afficher les étudiants d'une classe spécifique (vue détaillée)
    public function etudiants_par_classe($id_classe, $annee_id = null) {
        $sql = "SELECT i.*, 
                e.nom, e.prenom, e.mail, e.telephone, e.photo, e.sexe, e.adresse,
                c.code, c.libelle as classe_libelle, c.niveau, c.capacite,
                a.libelle as annee_libelle
                FROM inscription i
                LEFT JOIN etudiant e ON e.id = i.id_etudiant
                LEFT JOIN classe c ON c.id_class = i.id_classe
                LEFT JOIN annee_scolaire a ON a.id_annee = i.annee_scolaire_id
                WHERE i.id_classe = $id_classe AND i.statut = 'actif'";
        
        if($annee_id) {
            $sql .= " AND i.annee_scolaire_id = $annee_id";
        }
        
        $sql .= " ORDER BY e.nom ASC";
        
        $data['inscriptions'] = $this->db->query($sql)->result_array();
        $data['classe'] = $this->db->where('id_class', $id_classe)->get('classe')->row_array();
        
        $this->load->view('inscription/etudiants_par_classe', $data);
    }
    
    // Réactiver une inscription désinscrite
    public function reactiver($id) {
        $this->db->where('id_inscription', $id)->update('inscription', array('statut' => 'actif'));
        $this->session->set_flashdata('success', 'Inscription réactivée avec succès !');
        redirect('inscription');
    }
    
    // Lister les inscriptions inactives (désinscrits)
    public function inactifs() {
        $sql = "SELECT i.*, 
                e.nom, e.prenom, e.mail, e.telephone,
                c.code, c.libelle as classe_libelle,
                a.libelle as annee_libelle
                FROM inscription i
                LEFT JOIN etudiant e ON e.id = i.id_etudiant
                LEFT JOIN classe c ON c.id_class = i.id_classe
                LEFT JOIN annee_scolaire a ON a.id_annee = i.annee_scolaire_id
                WHERE i.statut = 'inactif'
                ORDER BY i.id_inscription DESC";
        
        $data['inscriptions'] = $this->db->query($sql)->result_array();
        $data['classes'] = $this->db->get('classe')->result_array();
        $data['annees'] = $this->db->get('annee_scolaire')->result_array();
        $data['classe_selectionnee'] = null;
        $data['annee_selectionnee'] = null;
        $data['show_inactifs'] = true;
        
        $this->load->view('inscription/list', $data);
    }
}
?>