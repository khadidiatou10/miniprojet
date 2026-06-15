<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_note extends CI_Model {
    
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
    
    // Récupérer les étudiants d'une classe
    public function get_etudiants_par_classe($classe_id, $annee_id = null) {
        $sql = "SELECT i.id_etudiant, e.id, e.nom, e.prenom, e.photo, e.matricule
                FROM inscription i
                JOIN etudiant e ON e.id = i.id_etudiant
                WHERE i.id_classe = $classe_id AND i.statut = 'actif'";
        
        if($annee_id) {
            $sql .= " AND i.annee_scolaire_id = $annee_id";
        }
        
        $sql .= " ORDER BY e.nom ASC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    // Récupérer les notes d'un étudiant pour une matière spécifique
    public function get_note_etudiant_matiere($id_etudiant, $id_matiere, $annee_id = null) {
        $this->db->where('id_etudiant', $id_etudiant);
        $this->db->where('id_matiere', $id_matiere);
        if($annee_id) {
            $this->db->where('annee_scolaire_id', $annee_id);
        }
        $query = $this->db->get('note');
        return $query->row_array();
    }
    
    // Sauvegarder une note
    public function sauvegarder_note($data) {
        $note_cc = !empty($data['note_cc']) ? floatval($data['note_cc']) : null;
        $note_exam = !empty($data['note_exam']) ? floatval($data['note_exam']) : null;
        
        if($note_cc !== null && $note_exam !== null) {
            $data['note_finale'] = ($note_cc * 0.4) + ($note_exam * 0.6);
        } else {
            $data['note_finale'] = null;
        }
        
        $this->db->where('id_etudiant', $data['id_etudiant']);
        $this->db->where('id_matiere', $data['id_matiere']);
        $this->db->where('id_classe', $data['id_classe']);
        $this->db->where('annee_scolaire_id', $data['annee_scolaire_id']);
        $existing = $this->db->get('note')->row_array();
        
        if($existing) {
            $this->db->where('id_note', $existing['id_note']);
            return $this->db->update('note', $data);
        } else {
            return $this->db->insert('note', $data);
        }
    }
    
    // Notes d'un étudiant
    public function get_notes_etudiant($id_etudiant, $annee_id = null) {
        $sql = "SELECT n.*, 
                m.code as matiere_code, m.libelle as matiere_libelle, m.coefficient,
                c.libelle as classe_libelle
                FROM note n
                JOIN matiere m ON m.id_matiere = n.id_matiere
                JOIN classe c ON c.id_class = n.id_classe
                WHERE n.id_etudiant = $id_etudiant";
        
        if($annee_id) {
            $sql .= " AND n.annee_scolaire_id = $annee_id";
        }
        
        $sql .= " ORDER BY m.libelle ASC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    // Moyenne d'un étudiant
    public function get_moyenne_etudiant($id_etudiant, $annee_id = null) {
        $sql = "SELECT SUM(n.note_finale * m.coefficient) / SUM(m.coefficient) as moyenne
                FROM note n
                JOIN matiere m ON m.id_matiere = n.id_matiere
                WHERE n.id_etudiant = $id_etudiant AND n.note_finale IS NOT NULL";
        
        if($annee_id) {
            $sql .= " AND n.annee_scolaire_id = $annee_id";
        }
        
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result['moyenne'] ? round($result['moyenne'], 2) : 0;
    }
    
    // Moyennes par classe
    public function get_moyennes_par_classe($classe_id, $annee_id = null) {
        $sql = "SELECT e.id, e.nom, e.prenom, e.matricule,
                SUM(n.note_finale * m.coefficient) / SUM(m.coefficient) as moyenne
                FROM note n
                JOIN etudiant e ON e.id = n.id_etudiant
                JOIN matiere m ON m.id_matiere = n.id_matiere
                WHERE n.id_classe = $classe_id AND n.note_finale IS NOT NULL";
        
        if($annee_id) {
            $sql .= " AND n.annee_scolaire_id = $annee_id";
        }
        
        $sql .= " GROUP BY e.id, e.nom, e.prenom, e.matricule
                  ORDER BY moyenne DESC";
        
        $query = $this->db->query($sql);
        $resultats = $query->result_array();
        
        $rang = 1;
        foreach($resultats as &$etudiant) {
            $etudiant['rang'] = $rang++;
            $etudiant['moyenne'] = round($etudiant['moyenne'], 2);
        }
        
        return $resultats;
    }
    
    public function get_classement_classe($classe_id, $annee_id = null) {
        return $this->get_moyennes_par_classe($classe_id, $annee_id);
    }
    
    public function get_classe($id) {
        $this->db->where('id_class', $id);
        $query = $this->db->get('classe');
        return $query->row_array();
    }
    
    public function get_etudiant($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('etudiant');
        return $query->row_array();
    }
    
    public function get_matiere($id) {
        $this->db->where('id_matiere', $id);
        $query = $this->db->get('matiere');
        return $query->row_array();
    }

    // ========== METHODES POUR LE MODULE BULLETIN ==========

// Point 59 : Récupérer les notes d'un étudiant pour une période donnée
public function get_notes_bulletin($id_etudiant, $periode = 'annuel', $annee_id = null) {
    // Périodes : S1 (Septembre à Décembre), S2 (Janvier à Juin), annuel (toute l'année)
    $sql = "SELECT n.*, 
            m.code as matiere_code, m.libelle as matiere_libelle, m.coefficient,
            c.libelle as classe_libelle
            FROM note n
            JOIN matiere m ON m.id_matiere = n.id_matiere
            JOIN classe c ON c.id_class = n.id_classe
            WHERE n.id_etudiant = $id_etudiant AND n.note_finale IS NOT NULL";
    
    if($periode == 'S1') {
        $sql .= " AND MONTH(n.date_saisie) BETWEEN 9 AND 12";
    } elseif($periode == 'S2') {
        $sql .= " AND MONTH(n.date_saisie) BETWEEN 1 AND 6";
    }
    
    if($annee_id) {
        $sql .= " AND n.annee_scolaire_id = $annee_id";
    }
    
    $sql .= " ORDER BY m.libelle ASC";
    
    $query = $this->db->query($sql);
    return $query->result_array();
}

// Point 60 : Calculer le rang de l'étudiant dans sa classe
public function get_rang_etudiant($id_etudiant, $classe_id, $annee_id = null) {
    $classement = $this->get_moyennes_par_classe($classe_id, $annee_id);
    
    $rang = 0;
    foreach($classement as $c) {
        $rang++;
        if($c['id'] == $id_etudiant) {
            return $rang;
        }
    }
    return 0;
}

// Point 60 : Obtenir la mention en fonction de la moyenne
public function get_mention($moyenne) {
    if($moyenne >= 16) return 'Très bien';
    if($moyenne >= 14) return 'Bien';
    if($moyenne >= 12) return 'Assez bien';
    if($moyenne >= 10) return 'Passable';
    return 'Insuffisant';
}

// Point 61 : Statistiques de la classe (moyenne, min, max)
public function get_statistiques_classe($classe_id, $annee_id = null) {
    $moyennes = $this->get_moyennes_par_classe($classe_id, $annee_id);
    
    if(empty($moyennes)) {
        return array('moyenne_classe' => 0, 'min_classe' => 0, 'max_classe' => 0, 'nb_etudiants' => 0);
    }
    
    $notes = array_column($moyennes, 'moyenne');
    
    return array(
        'moyenne_classe' => round(array_sum($notes) / count($notes), 2),
        'min_classe' => min($notes),
        'max_classe' => max($notes),
        'nb_etudiants' => count($notes)
    );
}

// Point 62 + 63 : Récupérer toutes les données pour le bulletin
public function get_donnees_bulletin($id_etudiant, $periode = 'annuel', $annee_id = null) {
    // Informations étudiant
    $etudiant = $this->get_etudiant($id_etudiant);
    
    // Classe actuelle
    $sql = "SELECT i.id_classe, c.code, c.libelle, c.niveau
            FROM inscription i
            JOIN classe c ON c.id_class = i.id_classe
            WHERE i.id_etudiant = $id_etudiant AND i.statut = 'actif'";
    if($annee_id) {
        $sql .= " AND i.annee_scolaire_id = $annee_id";
    }
    $sql .= " LIMIT 1";
    $classe = $this->db->query($sql)->row_array();
    
    // Notes
    $notes = $this->get_notes_bulletin($id_etudiant, $periode, $annee_id);
    
    // Calculs
    $total_coefficients = 0;
    $total_pondere = 0;
    foreach($notes as $note) {
        $coeff = $note['coefficient'];
        $note_finale = $note['note_finale'];
        $total_coefficients += $coeff;
        $total_pondere += $coeff * $note_finale;
    }
    $moyenne_generale = $total_coefficients > 0 ? round($total_pondere / $total_coefficients, 2) : 0;
    
    // Rang
    $rang = $classe ? $this->get_rang_etudiant($id_etudiant, $classe['id_classe'], $annee_id) : 0;
    
    // Mention
    $mention = $this->get_mention($moyenne_generale);
    
    // Statistiques classe
    $stats = $classe ? $this->get_statistiques_classe($classe['id_classe'], $annee_id) : array();
    
    // Année scolaire
    $annee = null;
    if($annee_id) {
        $this->db->where('id_annee', $annee_id);
        $annee = $this->db->get('annee_scolaire')->row_array();
    } else {
        $annee = $this->get_annee_active();
    }
    
    return array(
        'etudiant' => $etudiant,
        'classe' => $classe,
        'notes' => $notes,
        'moyenne_generale' => $moyenne_generale,
        'rang' => $rang,
        'mention' => $mention,
        'statistiques_classe' => $stats,
        'annee' => $annee,
        'periode' => $periode
    );
}
}

?>