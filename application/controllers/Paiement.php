<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paiement extends CI_Controller {
    
    public function __construct() {
    parent::__construct();
    
    // Votre code existant...
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->model('M_paiement');
    $this->load->database();
    
    // ✅ AJOUTER CE FILTRE DE RÔLE
    if(!$this->session->userdata('user_id')) {
        redirect('auth/login');
    }
    
    $role = $this->session->userdata('role');
    if($role != 'admin' && $role != 'secretaire') {
        show_error('Accès non autorisé.', 403);
    }
}
    
    // Point 51 : Lister les étudiants avec leur statut de paiement
    public function index() {
        $classe_id = $this->input->get('classe_id');
        $statut = $this->input->get('statut');
        $annee_id = $this->input->get('annee_id');
        
        if(!$annee_id) {
            $active = $this->M_paiement->get_annee_active();
            $annee_id = $active['id_annee'] ?? null;
        }
        
        $data['classes'] = $this->M_paiement->get_all_classes();
        $data['annees'] = $this->M_paiement->get_all_annees();
        $data['classe_selectionnee'] = $classe_id;
        $data['annee_selectionnee'] = $annee_id;
        $data['statut_selectionne'] = $statut;
        $data['etudiants'] = $this->M_paiement->get_etudiants_statut_paiement($classe_id, $statut, $annee_id);
        
        // Calcul des totaux
        $total_inscrits = count($data['etudiants']);
        $total_impayes = 0;
        $total_partiel = 0;
        $total_paye = 0;
        $montant_total_du = 0;
        $montant_total_paye = 0;
        
        foreach($data['etudiants'] as $e) {
            if($e['statut_paiement'] == 'impaye') $total_impayes++;
            if($e['statut_paiement'] == 'partiel') $total_partiel++;
            if($e['statut_paiement'] == 'paye') $total_paye++;
            $montant_total_du += $e['montant_total'];
            $montant_total_paye += $e['montant_paye'];
        }
        
        $data['total_inscrits'] = $total_inscrits;
        $data['total_impayes'] = $total_impayes;
        $data['total_partiel'] = $total_partiel;
        $data['total_paye'] = $total_paye;
        $data['montant_total_du'] = $montant_total_du;
        $data['montant_total_paye'] = $montant_total_paye;
        $data['taux_recouvrement'] = $montant_total_du > 0 ? round(($montant_total_paye / $montant_total_du) * 100, 2) : 0;
        
        $this->load->view('paiements/index', $data);
    }
    
    // Point 52 : Filtrer les impayés pour relance
    public function impayes() {
        $classe_id = $this->input->get('classe_id');
        $annee_id = $this->input->get('annee_id');
        
        if(!$annee_id) {
            $active = $this->M_paiement->get_annee_active();
            $annee_id = $active['id_annee'] ?? null;
        }
        
        $data['classes'] = $this->M_paiement->get_all_classes();
        $data['annees'] = $this->M_paiement->get_all_annees();
        $data['classe_selectionnee'] = $classe_id;
        $data['annee_selectionnee'] = $annee_id;
        $data['impayes'] = $this->M_paiement->get_impayes($classe_id, $annee_id);
        
        $this->load->view('paiements/impayes', $data);
    }
    
    // Point 49 : Formulaire d'enregistrement d'un paiement
    public function form($id_etudiant = null) {
        if($id_etudiant) {
            $data['etudiant'] = $this->M_paiement->get_etudiant($id_etudiant);
            if(!$data['etudiant']) {
                $this->session->set_flashdata('error', 'Étudiant introuvable');
                redirect('paiement');
                return;
            }
            $data['inscription'] = $this->M_paiement->get_inscription_etudiant($id_etudiant);
            $data['frais'] = $this->M_paiement->get_frais_classe($data['inscription']['id_classe'], $data['inscription']['annee_scolaire_id']);
        } else {
            $data['etudiant'] = null;
            $data['inscription'] = null;
            $data['frais'] = null;
        }
        
        $data['classes'] = $this->M_paiement->get_all_classes();
        $data['annees'] = $this->M_paiement->get_all_annees();
        $data['modes'] = $this->M_paiement->get_modes_paiement();
        $data['types'] = $this->M_paiement->get_types_paiement();
        $data['mois'] = $this->M_paiement->get_mois();
        $data['annee_active'] = $this->M_paiement->get_annee_active();
        
        $this->load->view('paiements/form', $data);
    }
    
    // Point 49 : Enregistrer un paiement
    public function enregistrer() {
        $this->form_validation->set_rules('id_etudiant', 'Étudiant', 'required');
        $this->form_validation->set_rules('montant', 'Montant', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('mode_paiement', 'Mode de paiement', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('paiement/form');
            return;
        }
        
        $id_etudiant = $this->input->post('id_etudiant');
        $inscription = $this->M_paiement->get_inscription_etudiant($id_etudiant, $this->input->post('annee_scolaire_id'));
        
        $data = array(
            'id_etudiant' => $id_etudiant,
            'id_classe' => $inscription['id_classe'],
            'annee_scolaire_id' => $this->input->post('annee_scolaire_id'),
            'montant' => $this->input->post('montant'),
            'date_paiement' => $this->input->post('date_paiement'),
            'mode_paiement' => $this->input->post('mode_paiement'),
            'reference' => $this->input->post('reference'),
            'type_paiement' => $this->input->post('type_paiement'),
            'mois' => $this->input->post('mois'),
            'commentaire' => $this->input->post('commentaire'),
            'date_saisie' => date('Y-m-d H:i:s')
        );
        
        if($this->M_paiement->enregistrer_paiement($data)) {
            $this->session->set_flashdata('success', 'Paiement enregistré avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'enregistrement');
        }
        
        redirect('paiement');
    }
    
    // Point 50 : Historique des paiements d'un étudiant
    public function historique($id_etudiant) {
        $data['etudiant'] = $this->M_paiement->get_etudiant($id_etudiant);
        
        if(!$data['etudiant']) {
            $this->session->set_flashdata('error', 'Étudiant introuvable');
            redirect('paiement');
            return;
        }
        
        $data['annees'] = $this->M_paiement->get_all_annees();
        $annee_id = $this->input->get('annee_id');
        $data['annee_selectionnee'] = $annee_id;
        $data['paiements'] = $this->M_paiement->get_historique_paiements($id_etudiant, $annee_id);
        $data['inscription'] = $this->M_paiement->get_inscription_etudiant($id_etudiant, $annee_id);
        $data['total_paye'] = $this->M_paiement->get_total_paye($id_etudiant, $annee_id);
        $data['types'] = $this->M_paiement->get_types_paiement();
        
        if($data['inscription']) {
            $data['reste'] = $data['inscription']['montant_total'] - $data['total_paye'];
            $data['statut'] = $data['reste'] <= 0 ? 'paye' : ($data['total_paye'] > 0 ? 'partiel' : 'impaye');
        }
        
        $this->load->view('paiements/historique', $data);
    }
    
    // Point 53 : Générer un reçu de paiement (vue imprimable)
    public function recu($id_paiement) {
        $data['paiement'] = $this->M_paiement->get_paiement($id_paiement);
        
        if(!$data['paiement']) {
            $this->session->set_flashdata('error', 'Paiement introuvable');
            redirect('paiement');
            return;
        }
        
        $data['etudiant'] = $this->M_paiement->get_etudiant($data['paiement']['id_etudiant']);
        $data['classe'] = $this->M_paiement->get_classe($data['paiement']['id_classe']);
        
        // Récupérer l'année scolaire
        $this->db->where('id_annee', $data['paiement']['annee_scolaire_id']);
        $data['annee'] = $this->db->get('annee_scolaire')->row_array();
        
        // Récupérer le mode de paiement en texte
        $modes = $this->M_paiement->get_modes_paiement();
        $data['mode_texte'] = $modes[$data['paiement']['mode_paiement']] ?? $data['paiement']['mode_paiement'];
        
        $data['types'] = $this->M_paiement->get_types_paiement();
        $data['montant_lettres'] = $this->num2words($data['paiement']['montant']);
        
        $this->load->view('paiements/recu', $data);
    }
    
    // Point 51 bis : Détail des frais par classe
    public function frais() {
        $classe_id = $this->input->get('classe_id');
        $annee_id = $this->input->get('annee_id');
        
        if(!$annee_id) {
            $active = $this->M_paiement->get_annee_active();
            $annee_id = $active['id_annee'] ?? null;
        }
        
        $data['classes'] = $this->M_paiement->get_all_classes();
        $data['annees'] = $this->M_paiement->get_all_annees();
        $data['classe_selectionnee'] = $classe_id;
        $data['annee_selectionnee'] = $annee_id;
        
        if($classe_id) {
            $data['frais'] = $this->M_paiement->get_frais_classe($classe_id, $annee_id);
            $data['classe'] = $this->M_paiement->get_classe($classe_id);
        }
        
        if($this->input->post()) {
            $frais_data = array(
                'id_classe' => $this->input->post('id_classe'),
                'annee_scolaire_id' => $this->input->post('annee_scolaire_id'),
                'montant_total' => $this->input->post('montant_total'),
                'montant_inscription' => $this->input->post('montant_inscription'),
                'mensualite' => $this->input->post('mensualite'),
                'nb_mensualites' => $this->input->post('nb_mensualites') ?: 10
            );
            
            if($this->M_paiement->set_frais_classe($frais_data)) {
                $this->session->set_flashdata('success', 'Frais scolaires configurés avec succès !');
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la configuration');
            }
            redirect('paiement/frais?classe_id='.$classe_id.'&annee_id='.$annee_id);
        }
        
        $this->load->view('paiements/frais', $data);
    }
    
    // Convertir un nombre en lettres (pour le reçu)
    public function num2words($number) {
        $number = intval($number);
        $units = array('', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf');
        $tens = array('', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingt', 'quatre-vingt-dix');
        
        if($number == 0) return 'zéro';
        
        $words = array();
        
        if($number >= 1000) {
            $milliers = intval($number / 1000);
            if($milliers == 1) {
                $words[] = 'mille';
            } else {
                $words[] = $this->num2words($milliers) . ' mille';
            }
            $number %= 1000;
        }
        
        if($number >= 100) {
            $centaines = intval($number / 100);
            if($centaines == 1) {
                $words[] = 'cent';
            } else {
                $words[] = $units[$centaines] . ' cent';
            }
            $number %= 100;
            if($number > 0) {
                $words[] = '';
            }
        }
        
        if($number > 0) {
            if($number < 20) {
                $words[] = $units[$number];
            } else {
                $dizaine = intval($number / 10);
                $unite = $number % 10;
                if($dizaine == 7 || $dizaine == 9) {
                    $words[] = $tens[$dizaine-1] . '-' . $units[$unite+10];
                } else {
                    $words[] = $tens[$dizaine] . ($unite ? '-' . $units[$unite] : '');
                }
            }
        }
        
        $result = implode(' ', $words);
        $result = str_replace('  ', ' ', $result);
        
        // Accords
        if(substr($result, -4) == 'cent') {
            $result = str_replace('cent', 'cents', $result);
        }
        
        return trim($result);
    }
}
?>