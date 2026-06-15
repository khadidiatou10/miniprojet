<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dashboard extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Point 64 : Nombre total d'étudiants
    public function get_total_etudiants() {
        return $this->db->count_all('etudiant');
    }
    
    // Point 64 : Nombre total de classes
    public function get_total_classes() {
        return $this->db->count_all('classe');
    }
    
    // Point 64 : Nombre total de professeurs actifs
    public function get_total_professeurs() {
        $this->db->where('statut', 'actif');
        return $this->db->count_all_results('professeur');
    }
    
    // Point 64 : Nombre total de matières
    public function get_total_matieres() {
        return $this->db->count_all('matiere');
    }
    
    // Point 64 : Nombre total d'inscriptions actives
    public function get_total_inscriptions() {
        $this->db->where('statut', 'actif');
        return $this->db->count_all_results('inscription');
    }
    
    // Point 64 : Nombre total de paiements
    public function get_total_paiements() {
        $query = $this->db->select_sum('montant')->get('paiement');
        $result = $query->row_array();
        return $result['montant'] ?? 0;
    }
    
    // Point 65 : 5 derniers étudiants inscrits
    public function get_derniers_etudiants($limit = 5) {
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('etudiant');
        return $query->result_array();
    }
    
    // Point 66 : Taux de paiement global (pour camembert)
    public function get_taux_paiement() {
        $annee_active = $this->get_annee_active();
        $annee_id = $annee_active ? $annee_active['id_annee'] : null;
        
        $sql = "SELECT 
                    SUM(i.montant_total) as total_du,
                    SUM(i.montant_paye) as total_paye
                FROM inscription i
                WHERE i.statut = 'actif'";
        
        if($annee_id) {
            $sql .= " AND i.annee_scolaire_id = $annee_id";
        }
        
        $query = $this->db->query($sql);
        $result = $query->row_array();
        
        $total_du = $result['total_du'] ?? 0;
        $total_paye = $result['total_paye'] ?? 0;
        $reste = $total_du - $total_paye;
        
        return array(
            'paye' => $total_paye,
            'reste' => $reste,
            'total' => $total_du,
            'pourcentage_paye' => $total_du > 0 ? round(($total_paye / $total_du) * 100, 2) : 0
        );
    }
    
    // Point 67 : Top 5 étudiants par moyenne générale
    public function get_top_etudiants($limit = 5) {
        $annee_active = $this->get_annee_active();
        $annee_id = $annee_active ? $annee_active['id_annee'] : null;
        
        $sql = "SELECT e.id, e.nom, e.prenom, e.matricule, e.photo,
                SUM(n.note_finale * m.coefficient) / SUM(m.coefficient) as moyenne
                FROM note n
                JOIN etudiant e ON e.id = n.id_etudiant
                JOIN matiere m ON m.id_matiere = n.id_matiere
                WHERE n.note_finale IS NOT NULL";
        
        if($annee_id) {
            $sql .= " AND n.annee_scolaire_id = $annee_id";
        }
        
        $sql .= " GROUP BY e.id, e.nom, e.prenom, e.matricule, e.photo
                  HAVING moyenne > 0
                  ORDER BY moyenne DESC
                  LIMIT $limit";
        
        $query = $this->db->query($sql);
        $resultats = $query->result_array();
        
        foreach($resultats as &$r) {
            $r['moyenne'] = round($r['moyenne'], 2);
        }
        
        return $resultats;
    }
    
    // Point 68 : Emploi du temps de la semaine en cours pour une classe (ou résumé)
    public function get_emploi_semaine_cours($classe_id = null) {
        $jour_semaine = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
        $aujourdhui = new DateTime();
        $numero_semaine = $aujourdhui->format('W');
        
        $sql = "SELECT e.*, 
                m.code as matiere_code, m.libelle as matiere_libelle,
                p.nom as professeur_nom, p.prenom as professeur_prenom,
                c.code as classe_code, c.libelle as classe_libelle
                FROM emploi_du_temps e
                JOIN matiere m ON m.id_matiere = e.id_matiere
                JOIN professeur p ON p.id_professeur = e.id_professeur
                JOIN classe c ON c.id_class = e.id_classe
                WHERE (e.semaine IS NULL OR e.semaine = $numero_semaine OR e.semaine = 0)";
        
        if($classe_id) {
            $sql .= " AND e.id_classe = $classe_id";
        }
        
        $sql .= " ORDER BY FIELD(e.jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'), e.heure_debut";
        
        $query = $this->db->query($sql);
        $seances = $query->result_array();
        
        // Organiser par jour
        $emploi = array();
        foreach($jour_semaine as $jour) {
            $emploi[$jour] = array();
        }
        
        foreach($seances as $s) {
            if(isset($emploi[$s['jour']])) {
                $emploi[$s['jour']][] = $s;
            }
        }
        
        return $emploi;
    }
    
    // Récupérer l'année active
    public function get_annee_active() {
        $this->db->where('actif', 1);
        $query = $this->db->get('annee_scolaire');
        return $query->row_array();
    }
    
    // Récupérer toutes les classes pour le filtre
    public function get_all_classes() {
        $query = $this->db->order_by('libelle', 'ASC')->get('classe');
        return $query->result_array();
    }
    
    // Point 68 : Résumé des prochains cours (pour le dashboard)
    public function get_prochains_cours($limit = 5) {
        $aujourdhui = date('Y-m-d');
        $jour_semaine = date('l', strtotime($aujourdhui));
        $jour_fr = array(
            'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'
        );
        $jour_actuel = $jour_fr[$jour_semaine] ?? 'Lundi';
        
        $heure_actuelle = date('H:i:s');
        
        $sql = "SELECT e.*, 
                m.code as matiere_code, m.libelle as matiere_libelle,
                p.nom as professeur_nom, p.prenom as professeur_prenom,
                c.code as classe_code, c.libelle as classe_libelle
                FROM emploi_du_temps e
                JOIN matiere m ON m.id_matiere = e.id_matiere
                JOIN professeur p ON p.id_professeur = e.id_professeur
                JOIN classe c ON c.id_class = e.id_classe
                WHERE (e.jour = '$jour_actuel' AND e.heure_debut >= '$heure_actuelle')
                ORDER BY e.heure_debut ASC
                LIMIT $limit";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
?>