<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_paiement extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Récupérer toutes les classes
    public function get_all_classes() {
        $query = $this->db->order_by('libelle', 'ASC')->get('classe');
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
    
    // Récupérer les frais d'une classe
    public function get_frais_classe($classe_id, $annee_id = null) {
        $this->db->where('id_classe', $classe_id);
        if($annee_id) {
            $this->db->where('annee_scolaire_id', $annee_id);
        }
        $query = $this->db->get('frais_scolaires');
        return $query->row_array();
    }
    
    // Définir les frais pour une classe
    public function set_frais_classe($data) {
        $this->db->where('id_classe', $data['id_classe']);
        $this->db->where('annee_scolaire_id', $data['annee_scolaire_id']);
        $existing = $this->db->get('frais_scolaires')->row_array();
        
        if($existing) {
            $this->db->where('id_frais', $existing['id_frais']);
            return $this->db->update('frais_scolaires', $data);
        } else {
            return $this->db->insert('frais_scolaires', $data);
        }
    }
    
    // Récupérer tous les étudiants avec leur statut de paiement (Point 51)
    public function get_etudiants_statut_paiement($classe_id = null, $statut = null, $annee_id = null) {
        if(!$annee_id) {
            $active = $this->get_annee_active();
            $annee_id = $active['id_annee'] ?? null;
        }
        
        $sql = "SELECT i.id_etudiant, i.id_classe, i.id_inscription,
                i.montant_total, i.montant_paye,
                e.nom, e.prenom, e.matricule, e.mail, e.telephone,
                c.code as classe_code, c.libelle as classe_libelle,
                (i.montant_total - i.montant_paye) as reste,
                CASE 
                    WHEN i.montant_paye >= i.montant_total THEN 'paye'
                    WHEN i.montant_paye > 0 THEN 'partiel'
                    ELSE 'impaye'
                END as statut_paiement
                FROM inscription i
                JOIN etudiant e ON e.id = i.id_etudiant
                JOIN classe c ON c.id_class = i.id_classe
                WHERE i.statut = 'actif' AND i.annee_scolaire_id = $annee_id";
        
        if($classe_id) {
            $sql .= " AND i.id_classe = $classe_id";
        }
        
        if($statut == 'paye') {
            $sql .= " HAVING statut_paiement = 'paye'";
        } elseif($statut == 'partiel') {
            $sql .= " HAVING statut_paiement = 'partiel'";
        } elseif($statut == 'impaye') {
            $sql .= " HAVING statut_paiement = 'impaye'";
        }
        
        $sql .= " ORDER BY reste DESC, e.nom ASC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    // Récupérer les étudiants impayés pour relance (Point 52)
    public function get_impayes($classe_id = null, $annee_id = null) {
        return $this->get_etudiants_statut_paiement($classe_id, 'impaye', $annee_id);
    }
    
    // Récupérer un étudiant par son ID
    public function get_etudiant($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('etudiant');
        return $query->row_array();
    }
    
    // Récupérer l'inscription d'un étudiant
    public function get_inscription_etudiant($id_etudiant, $annee_id = null) {
        if(!$annee_id) {
            $active = $this->get_annee_active();
            $annee_id = $active['id_annee'] ?? null;
        }
        
        $this->db->where('id_etudiant', $id_etudiant);
        $this->db->where('annee_scolaire_id', $annee_id);
        $this->db->where('statut', 'actif');
        $query = $this->db->get('inscription');
        return $query->row_array();
    }
    
    // Récupérer une classe par son ID
    public function get_classe($id) {
        $this->db->where('id_class', $id);
        $query = $this->db->get('classe');
        return $query->row_array();
    }
    
    // Point 49 : Enregistrer un paiement
    public function enregistrer_paiement($data) {
        // Insérer le paiement
        $insert = $this->db->insert('paiement', $data);
        
        if($insert) {
            // Mettre à jour le montant payé dans l'inscription
            $inscription = $this->get_inscription_etudiant($data['id_etudiant'], $data['annee_scolaire_id']);
            
            if($inscription) {
                $nouveau_montant_paye = $inscription['montant_paye'] + $data['montant'];
                $this->db->where('id_inscription', $inscription['id_inscription']);
                $this->db->update('inscription', array('montant_paye' => $nouveau_montant_paye));
            }
            
            return true;
        }
        
        return false;
    }
    
    // Point 50 : Historique des paiements d'un étudiant
    public function get_historique_paiements($id_etudiant, $annee_id = null) {
        $sql = "SELECT p.*, 
                c.code as classe_code, c.libelle as classe_libelle,
                a.libelle as annee_libelle
                FROM paiement p
                JOIN classe c ON c.id_class = p.id_classe
                JOIN annee_scolaire a ON a.id_annee = p.annee_scolaire_id
                WHERE p.id_etudiant = $id_etudiant";
        
        if($annee_id) {
            $sql .= " AND p.annee_scolaire_id = $annee_id";
        }
        
        $sql .= " ORDER BY p.date_paiement DESC, p.id_paiement DESC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    // Récupérer un paiement par son ID
    public function get_paiement($id) {
        $this->db->where('id_paiement', $id);
        $query = $this->db->get('paiement');
        return $query->row_array();
    }
    
    // Récupérer le total payé par un étudiant
    public function get_total_paye($id_etudiant, $annee_id = null) {
        $this->db->select_sum('montant');
        $this->db->where('id_etudiant', $id_etudiant);
        if($annee_id) {
            $this->db->where('annee_scolaire_id', $annee_id);
        }
        $query = $this->db->get('paiement');
        return $query->row_array()['montant'] ?? 0;
    }
    
    // Modes de paiement disponibles
    public function get_modes_paiement() {
        return array(
            'especes' => 'Espèces',
            'cheque' => 'Chèque',
            'carte' => 'Carte bancaire',
            'virement' => 'Virement',
            'mobile_money' => 'Mobile Money'
        );
    }
    
    // Types de paiement disponibles
    public function get_types_paiement() {
        return array(
            'inscription' => 'Frais d\'inscription',
            'mensualite' => 'Mensualité',
            'autre' => 'Autre'
        );
    }
    
    // Mois de l'année
    public function get_mois() {
        return array(
            'Octobre', 'Novembre', 'Décembre', 'Janvier', 'Février',
            'Mars', 'Avril', 'Mai', 'Juin', 'Juillet'
        );
    }
}
?>