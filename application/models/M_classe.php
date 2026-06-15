<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_classe extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // ── LISTE AVEC NOMBRE D'INSCRITS ──────────────────────────────────────
    public function liste_avec_inscrits() {
        $this->db->select('c.*, COUNT(i.id_inscription) as nb_inscrits');
        $this->db->from('classe c');
        $this->db->join('inscription i', 'i.id_classe = c.id_class', 'left');
        $this->db->group_by('c.id_class');
        $this->db->order_by('c.niveau', 'ASC');
        return $this->db->get()->result_array();
    }

    // ── LISTE SIMPLE ──────────────────────────────────────────────────────
    public function liste() {
        return $this->db->get('classe')->result_array();
    }

    // ── AJOUTER ───────────────────────────────────────────────────────────
    public function ajouter($data) {
        return $this->db->insert('classe', $data);
    }

    // ── RÉCUPÉRER UNE CLASSE ──────────────────────────────────────────────
    public function get_classe($id) {
        $this->db->where('id_class', $id);
        return $this->db->get('classe')->row_array();
    }

    // ── METTRE À JOUR ─────────────────────────────────────────────────────
    public function update_classe($id, $data) {
        $this->db->where('id_class', $id);
        return $this->db->update('classe', $data);
    }

    // ── COMPTER LES INSCRITS ──────────────────────────────────────────────
    public function compte_inscrits($id_classe) {
        $this->db->where('id_classe', $id_classe);
        return $this->db->count_all_results('inscription');
    }

    // ── SUPPRIMER ─────────────────────────────────────────────────────────
    public function supprimer($id) {
        $this->db->where('id_class', $id);
        return $this->db->delete('classe');
    }

    // ── ÉTUDIANTS INSCRITS DANS UNE CLASSE ───────────────────────────────
    public function get_etudiants($id_classe) {
        $this->db->select('e.*, i.date_inscription');
        $this->db->from('etudiant e');
        $this->db->join('inscription i', 'i.id_etudiant = e.id');
        $this->db->where('i.id_classe', $id_classe);
        $this->db->order_by('e.nom', 'ASC');
        return $this->db->get()->result_array();
    }

    // ── LISTE ANNÉES SCOLAIRES ────────────────────────────────────────────
    public function liste_annees() {
        return $this->db->get('annee_scolaire')->result_array();
    }
}