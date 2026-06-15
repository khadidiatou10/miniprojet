<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_anneeScolaire extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // ── LISTE ─────────────────────────────────────────────────────────────
    public function liste() {
        $this->db->order_by('date_debut', 'DESC');
        return $this->db->get('annee_scolaire')->result_array();
    }

    // ── AJOUTER ───────────────────────────────────────────────────────────
    public function ajouter($data) {
        return $this->db->insert('annee_scolaire', $data);
    }

    // ── RÉCUPÉRER UNE ANNÉE ───────────────────────────────────────────────
    public function get_annee($id) {
        $this->db->where('id_annee', $id);
        return $this->db->get('annee_scolaire')->row_array();
    }

    // ── METTRE À JOUR ─────────────────────────────────────────────────────
    public function update_annee($id, $data) {
        $this->db->where('id_annee', $id);
        return $this->db->update('annee_scolaire', $data);
    }

    // ── COMPTER CLASSES LIÉES ─────────────────────────────────────────────
    public function compte_classes($id_annee) {
        $this->db->where('id_annee', $id_annee);
        return $this->db->count_all_results('classe');
    }

    // ── SUPPRIMER ─────────────────────────────────────────────────────────
    public function supprimer($id) {
        $this->db->where('id_annee', $id);
        return $this->db->delete('annee_scolaire');
    }

    // ── DÉSACTIVER TOUTES ─────────────────────────────────────────────────
    public function desactiver_toutes() {
        return $this->db->update('annee_scolaire', array('actif' => 0));
    }

    // ── ACTIVER UNE ANNÉE ─────────────────────────────────────────────────
    public function activer($id) {
        $this->db->where('id_annee', $id);
        return $this->db->update('annee_scolaire', array('actif' => 1));
    }

    // ── DÉSACTIVER UNE ANNÉE ──────────────────────────────────────────────
    public function desactiver($id) {
        $this->db->where('id_annee', $id);
        return $this->db->update('annee_scolaire', array('actif' => 0));
    }
}