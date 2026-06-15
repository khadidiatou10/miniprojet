<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Point 69 : Récupérer tous les utilisateurs
    public function get_all_users() {
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('utilisateur');
        return $query->result_array();
    }
    
    // Récupérer un utilisateur par son ID
    public function get_user($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('utilisateur');
        return $query->row_array();
    }
    
    // Récupérer un utilisateur par son nom (pour login)
    public function get_user_by_username($username) {
        $this->db->where('nom_utilisateur', $username);
        $query = $this->db->get('utilisateur');
        return $query->row_array();
    }
    
    // Vérifier si un email existe déjà
    public function email_existe($email, $exclure_id = null) {
        $this->db->where('email', $email);
        if($exclure_id) {
            $this->db->where('id !=', $exclure_id);
        }
        $query = $this->db->get('utilisateur');
        return $query->num_rows() > 0;
    }
    
    // Vérifier si un nom d'utilisateur existe déjà
    public function username_existe($username, $exclure_id = null) {
        $this->db->where('nom_utilisateur', $username);
        if($exclure_id) {
            $this->db->where('id !=', $exclure_id);
        }
        $query = $this->db->get('utilisateur');
        return $query->num_rows() > 0;
    }
    
    // Compter le nombre d'administrateurs (Point 72)
    public function count_admins() {
        $this->db->where('role', 'admin');
        $this->db->where('est_actif', 1);
        return $this->db->count_all_results('utilisateur');
    }
    
    // Point 70 : Ajouter un utilisateur
    public function ajouter($data) {
        return $this->db->insert('utilisateur', $data);
    }
    
    // Point 71 : Modifier un utilisateur
    public function modifier($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('utilisateur', $data);
    }
    
    // Point 72 : Supprimer un utilisateur (soft delete)
    public function supprimer($id) {
        $this->db->where('id', $id);
        return $this->db->update('utilisateur', array('est_actif' => 0));
    }
    
    // Supprimer définitivement (si besoin)
    public function supprimer_definitivement($id) {
        $this->db->where('id', $id);
        return $this->db->delete('utilisateur');
    }
    
    // Réinitialiser le mot de passe
    public function reset_password($id, $new_password_hash) {
        $this->db->where('id', $id);
        return $this->db->update('utilisateur', array('password' => $new_password_hash));
    }
    
    // Vérifier si l'utilisateur est le dernier admin (Point 72)
    public function est_dernier_admin($id) {
        $user = $this->get_user($id);
        if($user && $user['role'] == 'admin') {
            $nb_admins = $this->count_admins();
            return $nb_admins <= 1;
        }
        return false;
    }
    
    // Obtenir les rôles disponibles
    public function get_roles() {
        return array(
            'admin' => 'Administrateur',
            'secretaire' => 'Secrétaire',
            'professeur' => 'Professeur'
        );
    }
}
?>