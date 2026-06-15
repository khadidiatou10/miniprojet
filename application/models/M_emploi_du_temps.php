<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_emploi_du_temps extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Récupérer toutes les classes
    public function get_all_classes() {
        $query = $this->db->order_by('libelle', 'ASC')->get('classe');
        return $query->result_array();
    }
    
    // Récupérer toutes les matières
    public function get_all_matieres() {
        $query = $this->db->order_by('libelle', 'ASC')->get('matiere');
        return $query->result_array();
    }
    
    // Récupérer tous les professeurs
    public function get_all_professeurs() {
        $query = $this->db->where('statut', 'actif')->order_by('nom', 'ASC')->get('professeur');
        return $query->result_array();
    }
    
    // Récupérer toutes les années scolaires
    public function get_all_annees() {
        $query = $this->db->order_by('libelle', 'DESC')->get('annee_scolaire');
        return $query->result_array();
    }
    
    // Récupérer l'année active
    public function get_annee_active() {
        $this->db->where('actif', 1);
        $query = $this->db->get('annee_scolaire');
        return $query->row_array();
    }
    
    // Récupérer une classe par son ID
    public function get_classe($id) {
        $this->db->where('id_class', $id);
        $query = $this->db->get('classe');
        return $query->row_array();
    }
    
    // Récupérer un professeur par son ID
    public function get_professeur($id) {
        $this->db->where('id_professeur', $id);
        $query = $this->db->get('professeur');
        return $query->row_array();
    }
    
    // Récupérer une matière par son ID
    public function get_matiere($id) {
        $this->db->where('id_matiere', $id);
        $query = $this->db->get('matiere');
        return $query->row_array();
    }
    
    // Récupérer l'emploi du temps d'une classe
    public function get_emploi_classe($classe_id, $annee_id = null) {
        $sql = "SELECT e.*, 
                m.code as matiere_code, m.libelle as matiere_libelle,
                p.nom as professeur_nom, p.prenom as professeur_prenom
                FROM emploi_du_temps e
                JOIN matiere m ON m.id_matiere = e.id_matiere
                JOIN professeur p ON p.id_professeur = e.id_professeur
                WHERE e.id_classe = $classe_id";
        
        if($annee_id) {
            $sql .= " AND e.annee_scolaire_id = $annee_id";
        }
        
        $sql .= " ORDER BY FIELD(e.jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'), e.heure_debut";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    // Récupérer l'emploi du temps d'un professeur
    public function get_emploi_professeur($professeur_id, $annee_id = null) {
        $sql = "SELECT e.*, 
                m.code as matiere_code, m.libelle as matiere_libelle,
                c.code as classe_code, c.libelle as classe_libelle
                FROM emploi_du_temps e
                JOIN matiere m ON m.id_matiere = e.id_matiere
                JOIN classe c ON c.id_class = e.id_classe
                WHERE e.id_professeur = $professeur_id";
        
        if($annee_id) {
            $sql .= " AND e.annee_scolaire_id = $annee_id";
        }
        
        $sql .= " ORDER BY FIELD(e.jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'), e.heure_debut";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    // Récupérer une séance par son ID
    public function get_seance($id) {
        $this->db->where('id_seance', $id);
        $query = $this->db->get('emploi_du_temps');
        return $query->row_array();
    }
    
    // Point 54 : Ajouter une séance
    public function ajouter_seance($data) {
        return $this->db->insert('emploi_du_temps', $data);
    }
    
    // Point 57 : Modifier une séance
    public function modifier_seance($id, $data) {
        $this->db->where('id_seance', $id);
        return $this->db->update('emploi_du_temps', $data);
    }
    
    // Point 57 : Supprimer une séance
    public function supprimer_seance($id) {
        $this->db->where('id_seance', $id);
        return $this->db->delete('emploi_du_temps');
    }
    
    // Point 58 : Vérifier les conflits (même salle ou même professeur à la même heure)
    public function verifier_conflit($data, $exclure_id = null) {
        // Vérifier conflit de salle
        $this->db->where('salle', $data['salle']);
        $this->db->where('jour', $data['jour']);
        $this->db->where('heure_debut <', $data['heure_fin']);
        $this->db->where('heure_fin >', $data['heure_debut']);
        if($exclure_id) {
            $this->db->where('id_seance !=', $exclure_id);
        }
        $conflit_salle = $this->db->get('emploi_du_temps')->num_rows();
        
        // Vérifier conflit professeur
        $this->db->where('id_professeur', $data['id_professeur']);
        $this->db->where('jour', $data['jour']);
        $this->db->where('heure_debut <', $data['heure_fin']);
        $this->db->where('heure_fin >', $data['heure_debut']);
        if($exclure_id) {
            $this->db->where('id_seance !=', $exclure_id);
        }
        $conflit_professeur = $this->db->get('emploi_du_temps')->num_rows();
        
        // Vérifier conflit classe (même classe à la même heure)
        $this->db->where('id_classe', $data['id_classe']);
        $this->db->where('jour', $data['jour']);
        $this->db->where('heure_debut <', $data['heure_fin']);
        $this->db->where('heure_fin >', $data['heure_debut']);
        if($exclure_id) {
            $this->db->where('id_seance !=', $exclure_id);
        }
        $conflit_classe = $this->db->get('emploi_du_temps')->num_rows();
        
        return array(
            'salle' => $conflit_salle > 0,
            'professeur' => $conflit_professeur > 0,
            'classe' => $conflit_classe > 0
        );
    }
    
    // Jours de la semaine
    public function get_jours() {
        return array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
    }
    
    // Créneaux horaires prédéfinis
    public function get_creneaux() {
        return array(
            '08:00:00' => '08h00 - 10h00',
            '10:00:00' => '10h00 - 12h00',
            '12:00:00' => '12h00 - 14h00',
            '14:00:00' => '14h00 - 16h00',
            '16:00:00' => '16h00 - 18h00'
        );
    }
    
    // Types de cours
    public function get_types_cours() {
        return array(
            'cours' => 'Cours magistral',
            'td' => 'Travaux dirigés',
            'tp' => 'Travaux pratiques'
        );
    }
}
?>