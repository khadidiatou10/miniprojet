<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_etudiant extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // ── LISTE AVEC RECHERCHE ET PAGINATION ────────────────────────────────
    public function liste($recherche = NULL, $limite = 10, $offset = 0) {
        if (!empty($recherche)) {
            // ✅ LIKE SQL direct — fonctionne même avec plusieurs mots
            $r = $this->db->escape_like_str($recherche);
            $this->db->where("(nom LIKE '%{$r}%' OR prenom LIKE '%{$r}%')");
        }
        $this->db->limit($limite, $offset);
        $this->db->order_by('nom', 'ASC');
        return $this->db->get('etudiant')->result_array();
    }

    // ── COMPTER POUR PAGINATION ────────────────────────────────────────────
    public function compte($recherche = NULL) {
        if (!empty($recherche)) {
            $r = $this->db->escape_like_str($recherche);
            $this->db->where("(nom LIKE '%{$r}%' OR prenom LIKE '%{$r}%')");
        }
        return $this->db->count_all_results('etudiant');
    }

    // ── AJOUTER ────────────────────────────────────────────────────────────
    public function ajouter($data) {
        return $this->db->insert('etudiant', $data);
    }

    // ── RÉCUPÉRER UN ÉTUDIANT ──────────────────────────────────────────────
    public function get_etudiant($id) {
        $this->db->where('id', $id);
        return $this->db->get('etudiant')->row_array();
    }

    // ── METTRE À JOUR ──────────────────────────────────────────────────────
    public function update_etudiant($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('etudiant', $data);
    }

    // ── SUPPRIMER ──────────────────────────────────────────────────────────
    public function delete_etudiant($id) {
        $this->db->where('id', $id);
        return $this->db->delete('etudiant');
    }

    // ── CLASSES ASSOCIÉES (pour la fiche détail) ───────────────────────────
    public function get_classes($id_etudiant) {
        $this->db->select('c.*');
        $this->db->from('classe c');
        $this->db->join('inscription i', 'i.id_classe = c.id_class');
        $this->db->where('i.id_etudiant', $id_etudiant);
        return $this->db->get()->result_array();
    }

    // ── NOTES ASSOCIÉES (pour la fiche détail) ─────────────────────────────
    public function get_notes($id_etudiant) {
        $this->db->select('n.*, m.libelle as matiere');
        $this->db->from('note n');
        $this->db->join('matiere m', 'm.id_matiere = n.id_matiere', 'left');
        $this->db->where('n.id_etudiant', $id_etudiant);
        return $this->db->get()->result_array();
    }
}