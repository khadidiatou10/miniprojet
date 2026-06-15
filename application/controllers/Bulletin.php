<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bulletin extends CI_Controller {
    
    public function __construct() {
    parent::__construct();
    
    // Votre code existant...
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('session');
    $this->load->model('M_note');
    $this->load->database();
    
    // ✅ AJOUTER CE FILTRE DE RÔLE
    if(!$this->session->userdata('user_id')) {
        redirect('auth/login');
    }
    
    $role = $this->session->userdata('role');
    if($role != 'admin' && $role != 'secretaire' && $role != 'professeur') {
        show_error('Accès non autorisé.', 403);
    }
}
    
    // Point 59 : Page de choix de l'étudiant et de la période
    public function index() {
        // Récupérer tous les étudiants inscrits
        $sql = "SELECT DISTINCT e.id, e.nom, e.prenom, e.matricule
                FROM etudiant e
                JOIN inscription i ON i.id_etudiant = e.id
                WHERE i.statut = 'actif'
                ORDER BY e.nom ASC";
        $data['etudiants'] = $this->db->query($sql)->result_array();
        
        $data['annees'] = $this->M_note->get_all_annees();
        $data['periodes'] = array(
            'annuel' => 'Annuel',
            'S1' => 'Premier semestre (S1)',
            'S2' => 'Deuxième semestre (S2)'
        );
        
        $this->load->view('bulletins/choix', $data);
    }
    
    // Point 59 + 60 + 61 + 62 : Générer le bulletin
    public function generer() {
        $id_etudiant = $this->input->get('id_etudiant');
        $periode = $this->input->get('periode') ?: 'annuel';
        $annee_id = $this->input->get('annee_id');
        
        if(!$id_etudiant) {
            $this->session->set_flashdata('error', 'Veuillez sélectionner un étudiant');
            redirect('bulletin');
            return;
        }
        
        $data = $this->M_note->get_donnees_bulletin($id_etudiant, $periode, $annee_id);
        
        if(!$data['etudiant']) {
            $this->session->set_flashdata('error', 'Étudiant introuvable');
            redirect('bulletin');
            return;
        }
        
        $this->load->view('bulletins/bulletin', $data);
    }
    
    // Point 63 : Générer le bulletin en PDF (optionnel - TCPDF)
    public function pdf($id_etudiant, $periode = 'annuel', $annee_id = null) {
        // Vérifier si TCPDF est installé
        if(!file_exists(APPPATH . 'third_party/tcpdf/tcpdf.php')) {
            show_error('TCPDF non installé. Veuillez installer la librairie TCPDF dans application/third_party/tcpdf/');
            return;
        }
        
        require_once(APPPATH . 'third_party/tcpdf/tcpdf.php');
        
        $data = $this->M_note->get_donnees_bulletin($id_etudiant, $periode, $annee_id);
        
        if(!$data['etudiant']) {
            $this->session->set_flashdata('error', 'Étudiant introuvable');
            redirect('bulletin');
            return;
        }
        
        // Création du PDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('Système de Gestion Scolaire');
        $pdf->SetAuthor('Établissement Scolaire');
        $pdf->SetTitle('Bulletin - ' . $data['etudiant']['nom'] . ' ' . $data['etudiant']['prenom']);
        $pdf->SetSubject('Bulletin scolaire');
        
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        
        // Générer le HTML pour le PDF
        $html = $this->load->view('bulletins/bulletin_pdf', $data, true);
        
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('bulletin_' . $data['etudiant']['matricule'] . '.pdf', 'I');
    }
}
?>