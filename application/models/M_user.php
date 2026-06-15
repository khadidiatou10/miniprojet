<?php
class M_user extends CI_Model{
    public function __construct(){
        parent:: __construct(); 
        $this->load->database(); // important
    }
    public function get_user_by_username($username){
        return $this->db->get_where('utilisateur',['nom_utilisateur'=>$username])->row_array() ;
    }
}