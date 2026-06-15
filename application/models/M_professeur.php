<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_professeur extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Récupérer tous les professeurs
    public function get_all_professeurs() {
        $query = $this->db->where('statut', 'actif')
                         ->order_by('nom', 'ASC')
                         ->get('professeur');
        return $query->result_array();
    }
    
    // Récupérer un professeur par son ID
    public function get_professeur($id) {
        $this->db->where('id_professeur', $id);
        $query = $this->db->get('professeur');
        return $query->row_array();
    }
    
    // Ajouter un professeur
    public function ajouter($data) {
        return $this->db->insert('professeur', $data);
    }
    
    // Modifier un professeur
    public function modifier($id, $data) {
        $this->db->where('id_professeur', $id);
        return $this->db->update('professeur', $data);
    }
    
    // Supprimer un professeur (soft delete - passer statut inactif)
    public function supprimer($id) {
        $this->db->where('id_professeur', $id);
        return $this->db->update('professeur', array('statut' => 'inactif'));
    }
    
    // Supprimer définitivement (si pas d'affectation)
    public function supprimer_definitivement($id) {
        $this->db->where('id_professeur', $id);
        return $this->db->delete('professeur');
    }
    
    // Vérifier si un professeur a des affectations
    public function a_des_affectations($id_professeur) {
        $this->db->where('id_professeur', $id_professeur);
        $query = $this->db->get('affectation');
        return $query->num_rows() > 0;
    }
    
    // Récupérer les affectations d'un professeur (matières et classes)
    public function get_affectations($id_professeur) {
        $sql = "SELECT a.*, 
                m.code as matiere_code, m.libelle as matiere_libelle, m.coefficient, m.volume_horaire,
                c.code as classe_code, c.libelle as classe_libelle, c.niveau,
                an.libelle as annee_libelle
                FROM affectation a
                LEFT JOIN matiere m ON m.id_matiere = a.id_matiere
                LEFT JOIN classe c ON c.id_class = a.id_classe
                LEFT JOIN annee_scolaire an ON an.id_annee = a.annee_scolaire_id
                WHERE a.id_professeur = $id_professeur
                ORDER BY an.libelle DESC, c.libelle ASC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    // Récupérer toutes les matières pour le formulaire d'affectation
    public function get_all_matieres() {
        $query = $this->db->select('id_matiere, code, libelle, coefficient, volume_horaire')
                         ->order_by('libelle', 'ASC')
                         ->get('matiere');
        return $query->result_array();
    }
    
    // Récupérer toutes les classes pour le formulaire d'affectation
    public function get_all_classes() {
        $query = $this->db->select('id_class, code, libelle, niveau')
                         ->order_by('libelle', 'ASC')
                         ->get('classe');
        return $query->result_array();
    }
    
    // Récupérer toutes les années scolaires
    public function get_all_annees() {
        $query = $this->db->order_by('libelle', 'DESC')->get('annee_scolaire');
        return $query->result_array();
    }
    
    // Ajouter une affectation
    public function ajouter_affectation($data) {
        return $this->db->insert('affectation', $data);
    }
    
    // Supprimer une affectation
    public function supprimer_affectation($id_affectation) {
        $this->db->where('id_affectation', $id_affectation);
        return $this->db->delete('affectation');
    }
    
    // Compter le nombre de professeurs
    public function compter() {
        return $this->db->where('statut', 'actif')->count_all_results('professeur');
    }
    
    // Vérifier si une matière existe
    public function matiere_exists($id) {
        $this->db->where('id_matiere', $id);
        $query = $this->db->get('matiere');
        return $query->num_rows() > 0;
    }
    
    // Vérifier si une classe existe
    public function classe_exists($id) {
        $this->db->where('id_class', $id);
        $query = $this->db->get('classe');
        return $query->num_rows() > 0;
    }
}
?>