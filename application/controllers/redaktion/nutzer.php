<?php  !defined('BASEPATH') and exit('No direct script access allowed');


    require_once APPPATH . 'controllers/redaktion/adminpage_controller.php';

    class Nutzer extends Adminpage_controller {

        protected $sControllerName = '';
        protected $sTable = 'nutzer';
        protected $sUriUebersicht = 'redaktion/nutzeruebersicht/';

        public function __construct () {
            //Controller Name in Kleinbuchstaben
            $this->sControllerName = strtolower(__CLASS__);
            parent::__construct($this->sControllerName, true, true);

            // h1 in header.php
            $this->headerData['heading'] = 'Nutzer bearbeiten';
        }


        /**
         * name:        index
         *
         * shows a blank form
         *
         * @param       string $account    expect ('own' = Logged in User
         *                                         'other' = Other User)
         *
         * @author      parobri.ch
         * @date        20120719
         */
        public function index ($account = 'other') {

            //--------------------------------------//
            // Leeres Formular ausgeben
            //--------------------------------------//
            $data['vorname']      = '';
            $data['nachname']     = '';
            $data['emailadresse'] = '';
            $data['benutzername'] = '';
            $data['status']       = 'adm';


            $data['label']     = 'Persönliche Informationen';
            $data['heading']   = '<span class="add-on"><i class="icon-pencil"></i></span> Neuen Account erstellen';
            $data['back_link'] = base_url() . $this->sUriUebersicht;
            if ($account == 'own') {
                $data['back_link'] = base_url() . 'redaktion/home';
            }
            $data['paswd_text']  = 'Passwort*:';
            $data['submit_text'] = 'Neuen Account erstellen';

            //--------------------------------------//
            // erstellt einen neuen Benutzer
            //--------------------------------------//

            if ($this->input->post()) {

                //Check ob Form-Validierung korrekt
                if ($this->Model->validate_form() == false) {

                    //Wenn Validierung nicht okay, wird nochmals Methode index() aufgerufen und Formular wird nochmals angezeigt
                    parent::__renderAll($this->sControllerName, $data);
                } //Falls Validierung korrekt, wird der Eintrag in die DB geschrieben
                else {
                    // Daten auslesen
                    $aData = array('vorname'       => $this->input->post('vorname', true),
                                   'nachname'      => $this->input->post('nachname', true),
                                   'emailadresse'  => $this->input->post('emailadresse', true),
                                   'benutzername'  => $this->input->post('benutzername', true),
                        //passwort wird direkt in einen md5 string gewandelt
                                   'status'        => $this->input->post('status'),
                                   'passwort'      => md5($this->input->post('passwort', true))
                    );


                    // Datensatz eintragen
                    if ($this->Model->saveRecord($this->sTable, $aData)) {
                        // Eintrag wird bestätigt
                        $last_id = mysql_insert_id();
                        $this->show($last_id, '', 'new');
                    } // Falls der Eintrag bereits besteht wird nochmals das Formular angezeigt
                    // und eine Fehlermeldung ausgegeben
                    else {
                        $data['message'] = 'Eintrag wurde bereits gespeichert.';
                        parent::__renderAll($this->sControllerName, $data);
                    }
                }
            } else {

                parent::__renderAll($this->sControllerName, $data);
            }
        }


        /**
         * name:        edit
         *
         * shows a form with editable row-entries
         *
         * @param       integer  $id             User id
         * @param       integer  $current_page   of the overview pagination
         * @param       string   $account        expect ('own' = Logged in User /
         *                                              'other' = Other User)
         *
         * @author      parobri.ch
         * @date        20120719
         */
        public function edit ($id = '', $current_page = '', $account = 'edit') {

            //----------------------------------------------//
            // gespeicherten Eintrag auslesen und editieren
            //----------------------------------------------//

            // Eintrag wird herausgelesen
            if ($id != '') {
                // passender Eintrag holen
                $where_array = array('nutzer_id'=> $id);
                $data        = $this->Model->getRow($this->sTable, '', $where_array);

                $data['heading']     = '<span class="add-on"><i class="icon-edit"></i></span> Account editieren';
                $data['label']       = 'Persönliche Informationen';
                $data['paswd_hide']  = 'yes';
                $data['paswd_text']  = 'neues Passwort*:';
                $data['submit_text'] = 'Änderung speichern';
                $data['back_link']   = base_url() . $this->sUriUebersicht . 'index/' . $current_page;


                if ($account == 'own') {
                    $data['back_link'] = base_url() . 'redaktion/home';
                }

                if ($this->input->post()) {
                    // wenn ein neues Passwort eingegeben wurde, dieses mitvalidieren,
                    // ansonsten bestehendes Passwort verwenden
                    if ($this->input->post('passwort', true) != '') {
                        $passwort               = 'yes';
                        $aData['passwort'] = md5($this->input->post('passwort', true));
                    } else {
                        $passwort = 'no';
                    }


                    // Check ob Form-Validierung korrekt
                    // wenn ok, Eintrag editieren und speichern
                    if ($this->Model->validate_form($passwort) == false) {
                        // Wenn Validierung nicht ok, dann Formular zurückgeben
                        parent::__renderAll($this->sControllerName, $data);
                    } else {
                        $aData['vorname']       = $this->input->post('vorname', true);
                        $aData['nachname']      = $this->input->post('nachname', true);
                        $aData['emailadresse']  = $this->input->post('emailadresse', true);
                        $aData['status']        = $this->input->post('status');
                        $aData['benutzername']  = $this->input->post('benutzername', true);

                        $this->Model->saveRecord($this->sTable, $aData, $item_id = $id, $id_field_name = 'nutzer_id');

                        //Eintrag anzeigen $this->show_member
                        $this->show($id, $current_page, $account);

                        //wenn der editierte Benutzer der aktive Benuzter ist, dann wird die Session umgeschrieben
                        if ($id == $this->session->userdata('id')) {
                            $sess_update = array('username' => $this->input->post('benutzername', true));
                            $this->session->set_userdata($sess_update);
                        }
                    }
                } // Formular mit Values anzeigen


                else {
                    parent::__renderAll($this->sControllerName, $data);
                }
            } // wenn keine ID gewählt ist
            else {
                $this->index();
            }
        }


        /**
         * name:        show
         *
         * shows the current row-entries
         *
         * @param       integer  $id             User id
         * @param       integer  $current_page   of the overview pagination
         * @param       string   $account        expect ('own' = Logged in User /
         *                                              'show' = after overview-page /
         *                                              'edit' = after edit-page)
         *
         * @author      jQuery Validation Plugin
         * @date        20120719
         */
        public function show ($id = -1, $current_page = '', $account = 'show') {
            if ($id != -1) {
                //--------------------------------------//
                // zeigt den gespeicherten Eintrag an
                //--------------------------------------//

                // gewünschte Felder
                $fields      = 'nutzer_id, vorname, nachname, benutzername, emailadresse, status';
                $where_array = array('nutzer_id'=> $id);

                // Datensatz herauslesen
                $data                = $this->Model->getRow($this->sTable, $fields, $where_array);
                $data['label']       = 'Folgende Einträge wurden gespeichert';
                $data['back_text']   = 'zurück';
                $data['back_link']   = base_url() . $this->sUriUebersicht . 'index/' . $current_page;
                $data['submit_text'] = 'Eintrag bearbeiten';
                $data['submit_link'] = base_url() . 'redaktion/nutzer/edit/' . $id . '/' . $current_page;
                $data['heading']     = 'Accountinfos';

                if ($account == 'new') {
                    $data['heading'] = 'Der Account wurde erstellt!';
                }

                if ($account == 'edit') {
                    $data['heading'] = 'Der Eintrag wurde aktualisiert!';
                }

                if ($account == 'own') {
                    $data['heading']   = 'Der Eintrag wurde aktualisiert!';
                    $data['back_link'] = base_url() . 'redaktion/home';
                }

                if ($account == 'delete') {
                    $data['heading']     = 'Nutzer löschen:';
                    $data['label']       = 'Folgender Eintrag löschen?';
                    $data['submit_text'] = 'Eintrag löschen';
                    $data['submit_link'] = base_url() . 'redaktion/nutzer/delete/' . $id . '/' . $current_page . '/yes';
                    $data['back_text']   = 'abbrechen';
                }
                parent::__renderAll('nutzer_show', $data);
            } else {
                // keine id ausgewählt -> Fehlermessage anzeigen
                $data['msg']      = 'kein Nutzer ausgewählt';
                $data['link_txt'] = 'zurück';
                $data['link']     = base_url() . $this->sUriUebersicht;
                parent::__renderAll('message', $data);
            }
        }


        /**
         * name:        delete
         *
         * deletes the current row-entries
         *
         * @param       integer  $id            User id
         * @param       integer  $current_page  of the overview pagination
         * @param       string   $execute       delete definitivly: expect('no'
         *                                                                 'yes')
         *
         * @author      parobri.ch
         * @date        20120719
         */
        public function delete ($id = '', $current_page = '', $execute = 'no') {

            //--------------------------------------//
            // zeigt Löschen Formular an
            //--------------------------------------//

            if ($id != $this->session->userdata('id')) {

                if ($id != '' && $execute == 'no') {
                    $this->show($id, $current_page, 'delete');

                } elseif ($id != '' && $execute == 'yes') {

                    if ($this->Model->deleteRecord($this->sTable, $id, $this->sTable . '_id') === true) {
                        $data['msg']              = 'Nutzer gelöscht';
                        $data['link']             = base_url() . $this->sUriUebersicht;
                        $data['link_txt']         = 'zurück';
                        $this->headerData['meta'] = array('<meta http-equiv="refresh" content="2; URL=' .
                            base_url() . $this->sUriUebersicht . '">');
                        parent::__renderAll('message', $data);
                    }
                }
                else {
                    // keine id ausgewählt -> Fehlermeldung anzeigen
                    $data['heading']  = 'Nutzer löschen:';
                    $data['msg']      = 'kein Nutzer ausgewählt';
                    $data['link_txt'] = 'zurück';
                    $data['link']     = base_url() . $this->sUriUebersicht;
                    parent::__renderAll('message', $data);
                }
            } else {
                // aktiver Nutzer hat sich selbst ausgewählt -> Fehlermeldung anzeigen
                $data['heading']  = 'Achtung: Das sind Sie!';
                $data['msg']      = 'Sie können sich nicht selbst löschen.';
                $data['link_txt'] = 'zurück';
                $data['link']     = base_url() . $this->sUriUebersicht;
                parent::__renderAll('message', $data);
            }
        }


        /**
         * name:        register_email_exists
         *
         * ajax request, if email already exists
         *
         * @param
         *
         *
         * @author      jQuery Validation Plugin
         * @date        20120719
         */


        function register_email_exists () {
            if (array_key_exists('emailadresse', $_POST)) {
                if ($this->email_exists($this->input->post('emailadresse')) == TRUE) {
                    echo json_encode(FALSE);
                } else {
                    echo json_encode(TRUE);
                }
            }
        }


        /**
         * name:        email_exists
         *
         * check if email exists -> true or false
         *
         * @param
         *
         *
         * @author      jQuery Validation Plugin
         * @date        20120719
         */


        private function email_exists ($emailadresse) {
            $this->db->where('emailadresse', $emailadresse);
            $query = $this->db->get('bvs_nutzer');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }


        /**
         * name:        register_benutzer_exists
         *
         * ajax request, if benutzer exists
         *
         * @param
         *
         *
         * @author      jQuery Validation Plugin
         * @date        20120719
         */


        function register_benutzer_exists () {
            if (array_key_exists('benutzername', $_POST)) {
                if ($this->benutzer_exists($this->input->post('benutzername')) == TRUE) {
                    echo json_encode(FALSE);
                } else {
                    echo json_encode(TRUE);
                }
            }
        }


        /**
         * name:        benutzer_exists
         *
         * check if  benutzer exists -> true or false
         *
         * @param
         *
         *
         * @author      jQuery Validation Plugin
         * @date        20120719
         */
        private function benutzer_exists ($benutzer) {
            $this->db->where('benutzername', $benutzer);
            $query = $this->db->get('bvs_nutzer');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }


    }

/* End of file nutzer.php */
/* Location: ./buchverwaltung/application/controllers/redaktion/nutzer.php */