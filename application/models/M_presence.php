<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_presence extends CI_Model {
    
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
    
    // Récupérer les étudiants d'une classe
    public function get_etudiants_par_classe($classe_id) {
        $sql = "SELECT i.id_etudiant, e.id, e.nom, e.prenom, e.matricule, e.photo
                FROM inscription i
                JOIN etudiant e ON e.id = i.id_etudiant
                WHERE i.id_classe = $classe_id AND i.statut = 'actif'
                ORDER BY e.nom ASC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    // Vérifier si une séance existe déjà
    public function seance_existe($classe_id, $matiere_id, $date_seance) {
        $this->db->where('id_classe', $classe_id);
        $this->db->where('id_matiere', $matiere_id);
        $this->db->where('date_seance', $date_seance);
        $query = $this->db->get('presence');
        return $query->num_rows() > 0;
    }
    
    // Récupérer les présences d'une séance
    public function get_presences_seance($classe_id, $matiere_id, $date_seance) {
        $this->db->where('id_classe', $classe_id);
        $this->db->where('id_matiere', $matiere_id);
        $this->db->where('date_seance', $date_seance);
        $query = $this->db->get('presence');
        $resultats = $query->result_array();
        
        // Transformer en tableau associatif [id_etudiant] = presence
        $presences = array();
        foreach($resultats as $p) {
            $presences[$p['id_etudiant']] = $p;
        }
        return $presences;
    }
    
    // Point 44 : Saisir les présences (enregistrement)
    public function sauvegarder_presences($classe_id, $matiere_id, $date_seance, $presences, $justifies = array(), $commentaires = array()) {
        // Supprimer les anciennes présences pour cette séance
        $this->db->where('id_classe', $classe_id);
        $this->db->where('id_matiere', $matiere_id);
        $this->db->where('date_seance', $date_seance);
        $this->db->delete('presence');
        
        // Insérer les nouvelles présences
        $saved = 0;
        foreach($presences as $id_etudiant => $present) {
            $justifie = isset($justifies[$id_etudiant]) ? 1 : 0;
            $commentaire = isset($commentaires[$id_etudiant]) ? $commentaires[$id_etudiant] : null;
            
            $data = array(
                'id_etudiant' => $id_etudiant,
                'id_classe' => $classe_id,
                'id_matiere' => $matiere_id,
                'date_seance' => $date_seance,
                'present' => $present ? 1 : 0,
                'justifie' => $justifie,
                'commentaire' => $commentaire,
                'date_saisie' => date('Y-m-d H:i:s')
            );
            
            if($this->db->insert('presence', $data)) {
                $saved++;
            }
        }
        return $saved;
    }
    
    // Point 45 : Historique des présences d'un étudiant
    public function get_historique_etudiant($id_etudiant, $date_debut = null, $date_fin = null) {
        $sql = "SELECT p.*, 
                c.code as classe_code, c.libelle as classe_libelle,
                m.code as matiere_code, m.libelle as matiere_libelle
                FROM presence p
                JOIN classe c ON c.id_class = p.id_classe
                JOIN matiere m ON m.id_matiere = p.id_matiere
                WHERE p.id_etudiant = $id_etudiant
                ORDER BY p.date_seance DESC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    // Point 46 : Taux d'absentéisme par étudiant
    public function get_taux_absenteisme_etudiant($id_etudiant) {
        $sql = "SELECT 
                    COUNT(*) as total_seances,
                    SUM(CASE WHEN present = 0 THEN 1 ELSE 0 END) as absences,
                    SUM(CASE WHEN present = 0 AND justifie = 1 THEN 1 ELSE 0 END) as absences_justifiees,
                    SUM(CASE WHEN present = 0 AND justifie = 0 THEN 1 ELSE 0 END) as absences_non_justifiees
                FROM presence
                WHERE id_etudiant = $id_etudiant";
        
        $query = $this->db->query($sql);
        $result = $query->row_array();
        
        if($result['total_seances'] > 0) {
            $result['taux_absence'] = round(($result['absences'] / $result['total_seances']) * 100, 2);
        } else {
            $result['taux_absence'] = 0;
        }
        
        return $result;
    }
    
    // Point 46 : Taux d'absentéisme par classe
    public function get_taux_absenteisme_classe($classe_id) {
        $sql = "SELECT 
                    e.id, e.nom, e.prenom, e.matricule,
                    COUNT(p.id_presence) as total_seances,
                    SUM(CASE WHEN p.present = 0 THEN 1 ELSE 0 END) as absences,
                    SUM(CASE WHEN p.present = 0 AND p.justifie = 1 THEN 1 ELSE 0 END) as absences_justifiees,
                    SUM(CASE WHEN p.present = 0 AND p.justifie = 0 THEN 1 ELSE 0 END) as absences_non_justifiees
                FROM etudiant e
                LEFT JOIN presence p ON p.id_etudiant = e.id
                LEFT JOIN inscription i ON i.id_etudiant = e.id
                WHERE i.id_classe = $classe_id AND i.statut = 'actif'
                GROUP BY e.id, e.nom, e.prenom, e.matricule
                ORDER BY absences DESC";
        
        $query = $this->db->query($sql);
        $resultats = $query->result_array();
        
        foreach($resultats as &$r) {
            if($r['total_seances'] > 0) {
                $r['taux_absence'] = round(($r['absences'] / $r['total_seances']) * 100, 2);
            } else {
                $r['taux_absence'] = 0;
            }
        }
        
        return $resultats;
    }
    
    // Point 47 : Générer les alertes pour étudiants dépassant le seuil d'absence
    public function get_alertes_absences($seuil = 3) {
        $sql = "SELECT 
                    e.id, e.nom, e.prenom, e.matricule, e.mail, e.telephone,
                    c.libelle as classe_libelle,
                    COUNT(p.id_presence) as total_seances,
                    SUM(CASE WHEN p.present = 0 AND p.justifie = 0 THEN 1 ELSE 0 END) as absences_non_justifiees
                FROM etudiant e
                LEFT JOIN presence p ON p.id_etudiant = e.id
                LEFT JOIN inscription i ON i.id_etudiant = e.id
                LEFT JOIN classe c ON c.id_class = i.id_classe
                WHERE i.statut = 'actif'
                GROUP BY e.id, e.nom, e.prenom, e.matricule, e.mail, e.telephone, c.libelle
                HAVING absences_non_justifiees >= $seuil
                ORDER BY absences_non_justifiees DESC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    // Point 48 : Modifier une présence
    public function modifier_presence($id_presence, $data) {
        $this->db->where('id_presence', $id_presence);
        return $this->db->update('presence', $data);
    }
    
    // Récupérer une présence par son ID
    public function get_presence($id_presence) {
        $this->db->where('id_presence', $id_presence);
        $query = $this->db->get('presence');
        return $query->row_array();
    }
    
    // Récupérer une classe par son ID
    public function get_classe($id) {
        $this->db->where('id_class', $id);
        $query = $this->db->get('classe');
        return $query->row_array();
    }
    
    // Récupérer une matière par son ID
    public function get_matiere($id) {
        $this->db->where('id_matiere', $id);
        $query = $this->db->get('matiere');
        return $query->row_array();
    }
    
    // Récupérer un étudiant par son ID
    public function get_etudiant($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('etudiant');
        return $query->row_array();
    }
    
    // Compter les absences non justifiées d'un étudiant
    public function compter_absences_non_justifiees($id_etudiant) {
        $this->db->where('id_etudiant', $id_etudiant);
        $this->db->where('present', 0);
        $this->db->where('justifie', 0);
        return $this->db->count_all_results('presence');
    }
}
?>