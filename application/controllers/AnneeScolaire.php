<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AnneeScolaire extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_anneeScolaire');
        $this->load->helper('url');
        $this->load->library('session');
    }

    // ── 1. LISTE ──────────────────────────────────────────────────────────
    public function index() {
        $data['annees'] = $this->M_anneeScolaire->liste();
        $this->load->view('annee_scolaire/liste', $data);
    }

    // ── 2. FORMULAIRE AJOUT ───────────────────────────────────────────────
    public function form() {
        $this->load->view('annee_scolaire/form');
    }

    // ── 3. ENREGISTREMENT ─────────────────────────────────────────────────
    public function enregistrement() {
        $data = array(
            'libelle'     => $this->input->post('libelle'),
            'date_debut'  => $this->input->post('date_debut'),
            'date_fin'    => $this->input->post('date_fin'),
            'actif'       => 0,
        );
        $this->M_anneeScolaire->ajouter($data);
        $this->session->set_flashdata('success', 'Année scolaire ajoutée avec succès !');
        redirect('annee_scolaire/index');
    }

    // ── 4. FORMULAIRE MODIFICATION ────────────────────────────────────────
    public function edit_form($id) {
        $data['annee'] = $this->M_anneeScolaire->get_annee($id);
        if (!$data['annee']) {
            $this->session->set_flashdata('error', 'Année introuvable');
            redirect('annee_scolaire/index');
        }
        $this->load->view('annee_scolaire/edit_form', $data);
    }

    // ── 5. MISE À JOUR ────────────────────────────────────────────────────
    public function save_update($id) {
        $data = array(
            'libelle'    => $this->input->post('libelle'),
            'date_debut' => $this->input->post('date_debut'),
            'date_fin'   => $this->input->post('date_fin'),
        );
        $this->M_anneeScolaire->update_annee($id, $data);
        $this->session->set_flashdata('success', 'Année scolaire modifiée avec succès !');
        redirect('annee_scolaire/index');
    }

    // ── 6. CONFIRMATION SUPPRESSION ───────────────────────────────────────
    public function delete_confirm($id) {
        $data['annee'] = $this->M_anneeScolaire->get_annee($id);
        if (!$data['annee']) {
            $this->session->set_flashdata('error', 'Année introuvable');
            redirect('annee_scolaire/index');
        }
        $this->load->view('annee_scolaire/supprime_form', $data);
    }

    // ── 7. SUPPRESSION (si aucune donnée liée) ────────────────────────────
    public function delete_now($id) {
        $nb_classes = $this->M_anneeScolaire->compte_classes($id);
        if ($nb_classes > 0) {
            $this->session->set_flashdata('error', 'Impossible de supprimer : '.$nb_classes.' classe(s) liée(s) à cette année.');
        } else {
            $this->M_anneeScolaire->supprimer($id);
            $this->session->set_flashdata('success', 'Année scolaire supprimée avec succès !');
        }
        redirect('annee_scolaire/index');
    }

    // ── 8. ACTIVER UNE ANNÉE (désactive les autres) ───────────────────────
    public function activer($id) {
        $this->M_anneeScolaire->desactiver_toutes();
        $this->M_anneeScolaire->activer($id);
        $this->session->set_flashdata('success', 'Année scolaire activée avec succès !');
        redirect('annee_scolaire/index');
    }

    // ── 9. DÉSACTIVER UNE ANNÉE ───────────────────────────────────────────
    public function desactiver($id) {
        $this->M_anneeScolaire->desactiver($id);
        $this->session->set_flashdata('success', 'Année scolaire désactivée.');
        redirect('annee_scolaire/index');
    }
}