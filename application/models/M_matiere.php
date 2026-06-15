<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_matiere extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Point 33 : Récupérer toutes les matières
    public function get_all_matieres() {
        $query = $this->db->order_by('libelle', 'ASC')->get('matiere');
        return $query->result_array();
    }
    
    // Récupérer une matière par son ID
    public function get_matiere($id) {
        $this->db->where('id_matiere', $id);
        $query = $this->db->get('matiere');
        return $query->row_array();
    }
    
    // Point 34 : Ajouter une matière
    public function ajouter($data) {
        return $this->db->insert('matiere', $data);
    }
    
    // Point 35 : Modifier une matière
    public function modifier($id, $data) {
        $this->db->where('id_matiere', $id);
        return $this->db->update('matiere', $data);
    }
    
    // Point 36 : Supprimer une matière (vérifier si non affectée d'abord)
    public function supprimer($id) {
        $this->db->where('id_matiere', $id);
        return $this->db->delete('matiere');
    }
    
    // Point 36 : Vérifier si une matière est affectée à des professeurs ou des notes
    public function est_affectee($id_matiere) {
        // Vérifier dans les affectations
        $this->db->where('id_matiere', $id_matiere);
        $affectations = $this->db->get('affectation')->num_rows();
        
        if($affectations > 0) {
            return true;
        }
        
        // Vérifier dans les notes
        $this->db->where('id_matiere', $id_matiere);
        $notes = $this->db->get('note')->num_rows();
        
        if($notes > 0) {
            return true;
        }
        
        return false;
    }
    
    // Point 37 : Récupérer tous les professeurs pour l'affectation
    public function get_all_professeurs() {
        $query = $this->db->where('statut', 'actif')
                         ->order_by('nom', 'ASC')
                         ->get('professeur');
        return $query->result_array();
    }
    
    // Point 37 : Récupérer toutes les classes pour l'affectation
    public function get_all_classes() {
        $query = $this->db->order_by('libelle', 'ASC')->get('classe');
        return $query->result_array();
    }
    
    // Point 37 : Récupérer toutes les années scolaires
    public function get_all_annees() {
        $query = $this->db->order_by('libelle', 'DESC')->get('annee_scolaire');
        return $query->result_array();
    }
    
    // Point 37 : Récupérer les affectations d'une matière
    public function get_affectations_par_matiere($id_matiere) {
        $sql = "SELECT a.*, 
                p.nom, p.prenom, p.email, p.specialite,
                c.code as classe_code, c.libelle as classe_libelle, c.niveau,
                an.libelle as annee_libelle
                FROM affectation a
                LEFT JOIN professeur p ON p.id_professeur = a.id_professeur
                LEFT JOIN classe c ON c.id_class = a.id_classe
                LEFT JOIN annee_scolaire an ON an.id_annee = a.annee_scolaire_id
                WHERE a.id_matiere = $id_matiere
                ORDER BY an.libelle DESC, c.libelle ASC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    // Point 37 : Ajouter une affectation (professeur à matière dans une classe)
    public function ajouter_affectation($data) {
        // Vérifier si l'affectation existe déjà
        $this->db->where('id_professeur', $data['id_professeur']);
        $this->db->where('id_matiere', $data['id_matiere']);
        $this->db->where('id_classe', $data['id_classe']);
        $this->db->where('annee_scolaire_id', $data['annee_scolaire_id']);
        $existing = $this->db->get('affectation')->num_rows();
        
        if($existing > 0) {
            return false; // Affectation déjà existante
        }
        
        return $this->db->insert('affectation', $data);
    }
    
    // Point 37 : Supprimer une affectation
    public function supprimer_affectation($id_affectation) {
        $this->db->where('id_affectation', $id_affectation);
        return $this->db->delete('affectation');
    }
    
    // Compter le nombre de matières
    public function compter() {
        return $this->db->count_all_results('matiere');
    }
    
    // Vérifier si un code de matière existe déjà
    public function code_existe($code, $exclure_id = null) {
        $this->db->where('code', $code);
        if($exclure_id) {
            $this->db->where('id_matiere !=', $exclure_id);
        }
        $query = $this->db->get('matiere');
        return $query->num_rows() > 0;
    }
}
?>