<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Etudiants extends CI_Controller {

    public function __construct() {
    parent::__construct();
    
    // Votre code existant...
    $this->load->model('M_etudiant');
    $this->load->helper('url');
    $this->load->library('session');
    $this->load->library('pagination');
    $this->load->library('upload');
    
    // ✅ AJOUTER CE FILTRE DE RÔLE
    if(!$this->session->userdata('user_id')) {
        redirect('auth/login');
    }
    
    $role = $this->session->userdata('role');
    if($role != 'admin' && $role != 'secretaire') {
        show_error('Accès non autorisé. Seuls l\'administrateur et le secrétaire peuvent accéder à cette section.', 403);
    }
}

    // ── 1. LISTE + RECHERCHE + PAGINATION ─────────────────────────────────
    public function index() {
        // ✅ $_GET direct au lieu de $this->input->get()
        $recherche = isset($_GET['q']) ? trim($_GET['q']) : NULL;
        $par_page  = 10;
        $page      = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $total             = $this->M_etudiant->compte($recherche);
        $data['etudiants'] = $this->M_etudiant->liste($recherche, $par_page, $page);
        $data['recherche'] = $recherche;

        $config['base_url']      = base_url('etudiants/index/');
        $config['total_rows']    = $total;
        $config['per_page']      = $par_page;
        $config['uri_segment']   = 3;
        $config['full_tag_open']  = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']  = '</span></li>';
        $config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']  = '</span></li>';
        $config['next_link']      = 'Suivant &raquo;';
        $config['prev_link']      = '&laquo; Précédent';
        $config['next_tag_open']  = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close'] = '</span></li>';
        $config['prev_tag_open']  = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close'] = '</span></li>';

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('etudiant/list', $data);
    }

    // ── 2. FORMULAIRE AJOUT ────────────────────────────────────────────────
    public function form() {
        $this->load->view('etudiant/form');
    }

    // ── 3. ENREGISTREMENT AVEC PHOTO ──────────────────────────────────────
    public function enregistrement() {
        $photo = 'default.png';

        if (!empty($_FILES['photo']['name'])) {
            $config_upload = array(
                'upload_path'   => './uploads/etudiants/',
                'allowed_types' => 'jpg|jpeg|png',
                'max_size'      => 2048,
                'encrypt_name'  => TRUE,
            );

            if (!is_dir('./uploads/etudiants/')) {
                mkdir('./uploads/etudiants/', 0777, TRUE);
            }

            $this->upload->initialize($config_upload);

            if ($this->upload->do_upload('photo')) {
                $photo = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('etudiants/form');
                return;
            }
        }

        $data = array(
            'nom'            => $this->input->post('nom'),
            'prenom'         => $this->input->post('prenom'),
            'mail'           => $this->input->post('mail'),
            'telephone'      => $this->input->post('telephone'),
            'date_naissance' => $this->input->post('date_naissance'),
            'sexe'           => $this->input->post('sexe'),
            'adresse'        => $this->input->post('adresse'),
            'photo'          => $photo,
        );

        $this->M_etudiant->ajouter($data);
        $this->session->set_flashdata('success', 'Étudiant ajouté avec succès !');
        redirect('etudiants/index');
    }

    // ── 4. FICHE DÉTAIL ────────────────────────────────────────────────────
    public function detail($id) {
        $data['etudiant'] = $this->M_etudiant->get_etudiant($id);
        if (!$data['etudiant']) {
            $this->session->set_flashdata('error', 'Étudiant introuvable');
            redirect('etudiants/index');
        }
        $data['classes'] = $this->M_etudiant->get_classes($id);
        $data['notes']   = $this->M_etudiant->get_notes($id);
        $this->load->view('etudiant/detail', $data);
    }

    // ── 5. FORMULAIRE MODIFICATION ─────────────────────────────────────────
    public function edit_form($id) {
        $data['etudiant'] = $this->M_etudiant->get_etudiant($id);
        $this->load->view('etudiant/edit_form', $data);
    }

    // ── 6. MISE À JOUR AVEC PHOTO ──────────────────────────────────────────
    public function save_update($id) {
        $etudiant_actuel = $this->M_etudiant->get_etudiant($id);
        $photo = $etudiant_actuel['photo'];

        if (!empty($_FILES['photo']['name'])) {
            $config_upload = array(
                'upload_path'   => './uploads/etudiants/',
                'allowed_types' => 'jpg|jpeg|png',
                'max_size'      => 2048,
                'encrypt_name'  => TRUE,
            );

            if (!is_dir('./uploads/etudiants/')) {
                mkdir('./uploads/etudiants/', 0777, TRUE);
            }

            $this->upload->initialize($config_upload);

            if ($this->upload->do_upload('photo')) {
                $photo = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('etudiants/edit_form/' . $id);
                return;
            }
        }

        $data = array(
            'nom'            => $this->input->post('nom'),
            'prenom'         => $this->input->post('prenom'),
            'mail'           => $this->input->post('mail'),
            'telephone'      => $this->input->post('telephone'),
            'date_naissance' => $this->input->post('date_naissance'),
            'sexe'           => $this->input->post('sexe'),
            'adresse'        => $this->input->post('adresse'),
            'photo'          => $photo,
        );

        $this->M_etudiant->update_etudiant($id, $data);
        $this->session->set_flashdata('success', 'Étudiant modifié avec succès !');
        redirect('etudiants/index');
    }

    // ── 7. CONFIRMATION SUPPRESSION ────────────────────────────────────────
    public function delete_confirm($id) {
        $data['etudiant'] = $this->M_etudiant->get_etudiant($id);
        if (!$data['etudiant']) {
            $this->session->set_flashdata('error', 'Étudiant introuvable');
            redirect('etudiants/index');
        }
        $this->load->view('etudiant/delete_forme', $data);
    }

    // ── 8. SUPPRESSION RÉELLE ──────────────────────────────────────────────
    public function delete($id) {
        $this->M_etudiant->delete_etudiant($id);
        $this->session->set_flashdata('success', 'Étudiant supprimé avec succès !');
        redirect('etudiants/index');
    }
    
    
}
