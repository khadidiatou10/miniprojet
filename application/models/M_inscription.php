<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_inscription extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Vérifier si l'étudiant est déjà inscrit dans la même classe et année
    public function verifier_double_inscription($etudiant_id, $classe_id, $annee_scolaire_id, $exclure_id = null) {
        $this->db->where('etudiant_id', $etudiant_id);
        $this->db->where('classe_id', $classe_id);
        $this->db->where('annee_scolaire_id', $annee_scolaire_id);
        $this->db->where('statut', 'actif');
        
        if($exclure_id) {
            $this->db->where('id !=', $exclure_id);
        }
        
        $query = $this->db->get('inscription');  // ← corrigé
        return $query->num_rows() > 0;
    }
    
    // Ajouter une inscription
    public function ajouter($data) {
        return $this->db->insert('inscription', $data);  // ← corrigé
    }
    
    // Modifier une inscription
    public function modifier($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('inscription', $data);  // ← corrigé
    }
    
    // Désinscrire (passer statut à inactif)
    public function desinscrire($id) {
        $this->db->where('id', $id);
        return $this->db->update('inscription', array('statut' => 'inactif'));  // ← corrigé
    }
    
    // Récupérer une inscription par son ID
    public function get_inscription($id) {
        $this->db->select('i.*, e.nom, e.prenom, e.mail, e.telephone, e.photo, c.libelle as classe_libelle, c.code, a.libelle as annee_libelle');
        $this->db->from('inscription i');  // ← corrigé
        $this->db->join('etudiant e', 'e.id = i.etudiant_id');
        $this->db->join('classe c', 'c.id = i.classe_id');
        $this->db->join('annee_scolaire a', 'a.id_annee = i.annee_scolaire_id');
        $this->db->where('i.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    // Lister tous les étudiants inscrits dans une classe pour une année donnée
    public function get_etudiants_par_classe($classe_id, $annee_scolaire_id = null) {
        $this->db->select('i.*, e.nom, e.prenom, e.mail, e.telephone, e.photo, e.sexe, e.adresse, c.libelle as classe_libelle, c.code, c.niveau, c.capacite, a.libelle as annee_libelle');
        $this->db->from('inscription i');  // ← corrigé
        $this->db->join('etudiant e', 'e.id = i.etudiant_id');
        $this->db->join('classe c', 'c.id = i.classe_id');
        $this->db->join('annee_scolaire a', 'a.id_annee = i.annee_scolaire_id');
        $this->db->where('i.classe_id', $classe_id);
        $this->db->where('i.statut', 'actif');
        
        if($annee_scolaire_id) {
            $this->db->where('i.annee_scolaire_id', $annee_scolaire_id);
        }
        
        $this->db->order_by('e.nom', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    // Récupérer toutes les classes pour le formulaire
    public function get_all_classes() {
        $query = $this->db->get('classe');
        return $query->result_array();
    }
    
    // Récupérer une classe par son ID
    public function get_classe_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('classe');
        return $query->row_array();
    }
    
    // Récupérer toutes les années scolaires
    public function get_all_annees() {
        $query = $this->db->get('annee_scolaire');
        return $query->result_array();
    }
    
    // Récupérer tous les étudiants pour le formulaire
    public function get_all_etudiants() {
        $query = $this->db->get('etudiant');
        return $query->result_array();
    }
    
    // Récupérer l'année scolaire active
    public function get_annee_active() {
        $this->db->where('actif', 1);
        $query = $this->db->get('annee_scolaire');
        return $query->row_array();
    }
    
    // Récupérer une année scolaire par son ID
    public function get_annee_by_id($id) {
        $this->db->where('id_annee', $id);
        $query = $this->db->get('annee_scolaire');
        return $query->row_array();
    }
    
    // Compter les inscriptions par classe
    public function compter_inscriptions_par_classe($classe_id, $annee_scolaire_id = null) {
        $this->db->where('classe_id', $classe_id);
        $this->db->where('statut', 'actif');
        if($annee_scolaire_id) {
            $this->db->where('annee_scolaire_id', $annee_scolaire_id);
        }
        return $this->db->count_all_results('inscription');  // ← corrigé
    }
    
    // Récupérer toutes les inscriptions d'un étudiant
    public function get_inscriptions_par_etudiant($etudiant_id) {
        $this->db->select('i.*, c.libelle as classe_libelle, c.code, a.libelle as annee_libelle');
        $this->db->from('inscription i');  // ← corrigé
        $this->db->join('classe c', 'c.id = i.classe_id');
        $this->db->join('annee_scolaire a', 'a.id_annee = i.annee_scolaire_id');
        $this->db->where('i.etudiant_id', $etudiant_id);
        $this->db->order_by('i.date_inscription', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    // Vérifier si une classe a des inscriptions
    public function classe_a_des_inscriptions($classe_id) {
        $this->db->where('classe_id', $classe_id);
        $query = $this->db->get('inscription');  // ← corrigé
        return $query->num_rows() > 0;
    }
    
    // Vérifier si un étudiant a des inscriptions
    public function etudiant_a_des_inscriptions($etudiant_id) {
        $this->db->where('etudiant_id', $etudiant_id);
        $query = $this->db->get('inscription');  // ← corrigé
        return $query->num_rows() > 0;
    }
}
?>