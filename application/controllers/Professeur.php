<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Professeur extends CI_Controller {
    
    public function __construct() {
    parent::__construct();
    
    // Votre code existant...
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->library('upload');
    $this->load->model('M_professeur');
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
    // Point 28 : Lister les professeurs avec leur(s) spécialité(s)
    public function index() {
        $data['professeurs'] = $this->M_professeur->get_all_professeurs();
        $data['total'] = $this->M_professeur->compter();
        
        $this->load->view('professeurs/list', $data);
    }
    
    // Point 29 : Formulaire d'ajout
    public function form() {
        $this->load->view('professeurs/form');
    }
    
    // Point 29 : Enregistrer un nouveau professeur
    public function enregistrer() {
        // Validation
        $this->form_validation->set_rules('nom', 'Nom', 'required');
        $this->form_validation->set_rules('prenom', 'Prénom', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('telephone', 'Téléphone', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('professeur/form');
            return;
        }
        
        // Gestion de la photo
        $photo = 'default.png';
        if (!empty($_FILES['photo']['name'])) {
            $config = array(
                'upload_path' => './uploads/professeurs/',
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size' => 2048,
                'encrypt_name' => TRUE
            );
            
            if (!is_dir('./uploads/professeurs/')) {
                mkdir('./uploads/professeurs/', 0777, TRUE);
            }
            
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('photo')) {
                $photo = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('professeur/form');
                return;
            }
        }
        
        $data = array(
            'nom' => $this->input->post('nom'),
            'prenom' => $this->input->post('prenom'),
            'email' => $this->input->post('email'),
            'telephone' => $this->input->post('telephone'),
            'specialite' => $this->input->post('specialite'),
            'date_embauche' => $this->input->post('date_embauche'),
            'photo' => $photo,
            'statut' => 'actif'
        );
        
        if ($this->M_professeur->ajouter($data)) {
            $this->session->set_flashdata('success', 'Professeur ajouté avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout');
        }
        
        redirect('professeur');
    }
    
    // Point 30 : Formulaire de modification
    public function edit_form($id) {
        $data['professeur'] = $this->M_professeur->get_professeur($id);
        
        if (!$data['professeur']) {
            $this->session->set_flashdata('error', 'Professeur introuvable');
            redirect('professeur');
        }
        
        $this->load->view('professeurs/edit', $data);
    }
    
    // Point 30 : Modifier les informations d'un professeur
    public function modifier($id) {
        // Validation
        $this->form_validation->set_rules('nom', 'Nom', 'required');
        $this->form_validation->set_rules('prenom', 'Prénom', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('professeur/edit_form/'.$id);
            return;
        }
        
        $professeur_actuel = $this->M_professeur->get_professeur($id);
        $photo = $professeur_actuel['photo'];
        
        // Gestion de la photo
        if (!empty($_FILES['photo']['name'])) {
            $config = array(
                'upload_path' => './uploads/professeurs/',
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size' => 2048,
                'encrypt_name' => TRUE
            );
            
            if (!is_dir('./uploads/professeurs/')) {
                mkdir('./uploads/professeurs/', 0777, TRUE);
            }
            
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('photo')) {
                // Supprimer l'ancienne photo si ce n'est pas default.png
                if ($photo != 'default.png' && file_exists('./uploads/professeurs/'.$photo)) {
                    unlink('./uploads/professeurs/'.$photo);
                }
                $photo = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('professeur/edit_form/'.$id);
                return;
            }
        }
        
        $data = array(
            'nom' => $this->input->post('nom'),
            'prenom' => $this->input->post('prenom'),
            'email' => $this->input->post('email'),
            'telephone' => $this->input->post('telephone'),
            'specialite' => $this->input->post('specialite'),
            'date_embauche' => $this->input->post('date_embauche'),
            'photo' => $photo
        );
        
        if ($this->M_professeur->modifier($id, $data)) {
            $this->session->set_flashdata('success', 'Professeur modifié avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la modification');
        }
        
        redirect('professeur');
    }
    
    // Point 31 : Supprimer un professeur (uniquement si pas d'affectation active)
    public function supprimer($id) {
        $professeur = $this->M_professeur->get_professeur($id);
        
        if (!$professeur) {
            $this->session->set_flashdata('error', 'Professeur introuvable');
            redirect('professeur');
        }
        
        // Vérifier si le professeur a des affectations
        if ($this->M_professeur->a_des_affectations($id)) {
            $this->session->set_flashdata('error', 'Impossible de supprimer ce professeur car il a des affectations actives !');
            redirect('professeur');
        }
        
        // Supprimer la photo si ce n'est pas default.png
        if ($professeur['photo'] != 'default.png' && file_exists('./uploads/professeurs/'.$professeur['photo'])) {
            unlink('./uploads/professeurs/'.$professeur['photo']);
        }
        
        if ($this->M_professeur->supprimer_definitivement($id)) {
            $this->session->set_flashdata('success', 'Professeur supprimé avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression');
        }
        
        redirect('professeur');
    }
    
    // Point 32 : Afficher la fiche d'un professeur avec les matières et classes affectées
    public function detail($id) {
        $data['professeur'] = $this->M_professeur->get_professeur($id);
        
        if (!$data['professeur']) {
            $this->session->set_flashdata('error', 'Professeur introuvable');
            redirect('professeur');
        }
        
        $data['affectations'] = $this->M_professeur->get_affectations($id);
        $data['matieres'] = $this->M_professeur->get_all_matieres();
        $data['classes'] = $this->M_professeur->get_all_classes();
        $data['annees'] = $this->M_professeur->get_all_annees();
        
        $this->load->view('professeurs/detail', $data);
    }
    
    // Ajouter une affectation (matière + classe) à un professeur
    public function ajouter_affectation() {
        $id_professeur = $this->input->post('id_professeur');
        $id_matiere = $this->input->post('id_matiere');
        $id_classe = $this->input->post('id_classe');
        $annee_scolaire_id = $this->input->post('annee_scolaire_id');
        
        // Vérifier si la matière existe
        if (!$this->M_professeur->matiere_exists($id_matiere)) {
            $this->session->set_flashdata('error', 'Matière introuvable');
            redirect('professeur/detail/'.$id_professeur);
            return;
        }
        
        // Vérifier si la classe existe
        if (!$this->M_professeur->classe_exists($id_classe)) {
            $this->session->set_flashdata('error', 'Classe introuvable');
            redirect('professeur/detail/'.$id_professeur);
            return;
        }
        
        $data = array(
            'id_professeur' => $id_professeur,
            'id_matiere' => $id_matiere,
            'id_classe' => $id_classe,
            'annee_scolaire_id' => $annee_scolaire_id ?: null
        );
        
        if ($this->M_professeur->ajouter_affectation($data)) {
            $this->session->set_flashdata('success', 'Affectation ajoutée avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'affectation');
        }
        
        redirect('professeur/detail/'.$id_professeur);
    }
    
    // Supprimer une affectation
    public function supprimer_affectation($id_affectation, $id_professeur) {
        if ($this->M_professeur->supprimer_affectation($id_affectation)) {
            $this->session->set_flashdata('success', 'Affectation supprimée avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression');
        }
        
        redirect('professeur/detail/'.$id_professeur);
    }
}
?>