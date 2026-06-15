<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller']            = 'Auth/login';
$route['404_override']                  = '';
$route['translate_uri_dashes']          = FALSE;

// ── AUTH ──────────────────────────────────────────────────────────────────
$route['login']                         = 'Auth/login';
$route['logout']                        = 'Auth/logout';

// ── CLASSES ───────────────────────────────────────────────────────────────
$route['classe']                        = 'Classe/index';
$route['classe/index']                  = 'Classe/index';
$route['classe/form_class']             = 'Classe/form_class';
$route['classe/enrigistrement']         = 'Classe/enrigistrement';
$route['classe/detail/(:num)']          = 'Classe/detail/$1';
$route['classe/edit_form/(:num)']       = 'Classe/edit_form/$1';
$route['classe/save_update/(:num)']     = 'Classe/save_update/$1';
$route['classe/delete_confirm/(:num)']  = 'Classe/delete_confirm/$1';
$route['classe/delete_now/(:num)']      = 'Classe/delete_now/$1';

// ── ÉTUDIANTS ─────────────────────────────────────────────────────────────
$route['etudiants']                         = 'Etudiants/index';
$route['etudiants/index']                   = 'Etudiants/index';
$route['etudiants/index/(:num)']            = 'Etudiants/index/$1';
$route['etudiants/form']                    = 'Etudiants/form';
$route['etudiants/enregistrement']          = 'Etudiants/enregistrement';
$route['etudiants/detail/(:num)']           = 'Etudiants/detail/$1';
$route['etudiants/edit_form/(:num)']        = 'Etudiants/edit_form/$1';
$route['etudiants/save_update/(:num)']      = 'Etudiants/save_update/$1';
$route['etudiants/delete_confirm/(:num)']   = 'Etudiants/delete_confirm/$1';
$route['etudiants/delete/(:num)']           = 'Etudiants/delete/$1';

// ── ANNÉES SCOLAIRES ──────────────────────────────────────────────────────
$route['annee_scolaire']                        = 'AnneeScolaire/index';
$route['annee_scolaire/index']                  = 'AnneeScolaire/index';
$route['annee_scolaire/form']                   = 'AnneeScolaire/form';
$route['annee_scolaire/enregistrement']         = 'AnneeScolaire/enregistrement';
$route['annee_scolaire/edit_form/(:num)']       = 'AnneeScolaire/edit_form/$1';
$route['annee_scolaire/save_update/(:num)']     = 'AnneeScolaire/save_update/$1';
$route['annee_scolaire/delete_confirm/(:num)']  = 'AnneeScolaire/delete_confirm/$1';
$route['annee_scolaire/delete_now/(:num)']      = 'AnneeScolaire/delete_now/$1';
$route['annee_scolaire/activer/(:num)']         = 'AnneeScolaire/activer/$1';
$route['annee_scolaire/desactiver/(:num)']      = 'AnneeScolaire/desactiver/$1';