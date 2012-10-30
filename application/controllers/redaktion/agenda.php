<?php  !defined('BASEPATH') and exit('No direct script access allowed');


    require_once APPPATH . 'controllers/redaktion/adminpage_controller.php';

    class Agenda extends Adminpage_controller {

        protected $sControllerName = '';
        protected $sTable = 'agenda';
        protected $sUriUebersicht = 'redaktion/agendauebersicht/';


        public function __construct () {
            //Controller Name in Kleinbuchstaben
            $this->sControllerName = strtolower(__CLASS__);
            parent::__construct($this->sControllerName, true, true);

            // h1 in header.php
            $this->headerData['heading'] = 'Agenda bearbeiten';
        }

        /**
         * name:        index
         *
         * shows a blank form
         *
         * @author      parobri.ch
         * @date        20120719
         */


        public function index ($account = 'other') {

            //--------------------------------------//
            // Leeres Formular ausgeben
            //--------------------------------------//
            $data['datum']        = '';
            $data['zeit']         = '';
            $data['titel']        = '';
            $data['ort']          = '';
            $data['beschreibung'] = '';
            $data['website']      = '';
            $data ['bild']        = '';
            $data['agendabild']   = 'Bild hochladen';

            $data['message']     = '';
            $data['heading']     = '<span class="add-on"><i class="icon-edit"></i></span> Termin bearbeiten:';
            $data['back_link']   = base_url() . $this->sUriUebersicht;
            $data['submit_text'] = 'Termin erstellen';

            // Bildmasse und Typ werden definiert
            $data['bildmasse'] = $aBildUploadConfig = array('upload_path'   => './media/redaktion/images/' . $this->sTable . '/',
                                                            'allowed_types' => 'gif|jpg|png|jpeg',
                                                            'max_size'      => '2500',
                                                            'max_width'     => '1024',
                                                            'max_height'    => '1024',
                                                            'file_name'     => time() . '.jpg'
                                                        );

            //--------------------------------------//
            // erstellt einen neuen Termin
            //--------------------------------------//
            if ($this->input->post()) {

                //Bild Upload wird gestartet
                //$image_name = $this->Model->doUpload();


                //Check ob Form-Validierung korrekt => server
                if ($this->Model->validate_form() == false) {

                    //Wenn Validierung nicht okay, wird nochmals Methode index() aufgerufen und Formular wird nochmals angezeigt
                    parent::__renderAll($this->sControllerName, $data);
                } //Falls Validierung korrekt, wird der Eintrag in die DB geschrieben
                else {

                    $array = explode('.', $this->input->post('datum', true));
                    $datum = $array[2] . '-' . $array[1] . '-' . $array[0];

                    // Daten auslesen
                    $aData = array('datum'            => $datum,
                                   'zeit'             => $this->input->post('zeit', true),
                                   'titel'            => $this->input->post('titel', true),
                                   'ort'              => $this->input->post('ort', true),
                                   'beschreibung'     => $this->input->post('beschreibung', true),
                                   'website'          => $this->input->post('website', true),
                    );


                    // Wenn ein neues Bild im Upload ist
                    if (isset($_FILES['userfile']['tmp_name']) && $_FILES['userfile']['tmp_name'] != '') {

                        // Upload durchführen: wenn ok ist Bildname im $return_upload sonst die Fehlermeldung
                        $return_upload = $this->Model->doImageUpload('', 'agenda_id', 'agenda', 'bild', $aBildUploadConfig);

                        // Wenn return_upload ein Fehler zurückgibt
                        if (strstr($return_upload, 'Fehler')) {
                            $data['message']    = strip_tags($this->upload->display_errors());
                            $data['uploaderror']= 'Fehler';
                            parent::__renderAll($this->sControllerName, $data);
                        }
                        // Wenn return_upload den Bildnamen zurückgibt
                        else {
                            $aData['bild'] = $return_upload;
                        }
                    }
                    // Wenn kein Fehler angezeigt wird, dann Einträge in der Tabelle speichern
                    if ($data['message'] == '') {
                        $this->Model->saveRecord($this->sTable, $aData);
                        $last_id = mysql_insert_id();
                        redirect(base_url() . 'redaktion/agenda/show/' . $last_id);
                    }
                }

            }
            // Datensatz eintragen
            else {
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

                $where_array = array('agenda_id'=> $id);
                $data        = $this->Model->getRow($this->sTable, '', $where_array);

                $data['message']     = '';
                $data['heading']     = '<span class="add-on"><i class="icon-edit"></i></span> Termin bearbeiten ';
                $data['submit_text'] = 'Änderung speichern';
                $data['back_link']   = base_url() . $this->sUriUebersicht . 'index/' . $current_page;
                $data['agendabild'] = 'Bild hochladen';

                // Bildmasse und Typ werden definiert
                $data['bildmasse'] = $aBildUploadConfig = array('upload_path'   => './media/redaktion/images/' . $this->sTable . '/',
                                                                'allowed_types' => 'gif|jpg|png|jpeg',
                                                                'max_size'      => '2500',
                                                                'max_width'     => '1024',
                                                                'max_height'    => '1024',
                                                                'file_name'     => time() . '.jpg'
                                                            );

                if ($this->input->post('submit')) {

                    //$image_name = $this->Model->doUpload($id);
                    // Check ob Form-Validierung korrekt
                    // wenn ok, Eintrag editieren und speichern
                    if ($this->Model->validate_form() == false) {
                        // Wenn Validierung nicht ok, dann Formular zurückgeben
                        parent::__renderAll($this->sControllerName, $data);
                    } else {

                        $array = explode('.', $this->input->post('datum', true));
                        $datum = $array[2] . '-' . $array[1] . '-' . $array[0];

                        $aData = array('datum'          => $datum,
                                       'zeit'           => $this->input->post('zeit', true),
                                       'titel'          => $this->input->post('titel', true),
                                       'ort'            => $this->input->post('ort', true),
                                       'beschreibung'   => $this->input->post('beschreibung', true),
                                       'website'        => $this->input->post('website', true)
                        );
                        // Wenn ein neues Bild im Upload ist
                        if (isset($_FILES['userfile']['tmp_name']) && $_FILES['userfile']['tmp_name'] != '') {

                            // Upload durchführen: wenn ok ist Bildname im $return_upload sonst die Fehlermeldung
                            $return_upload = $this->Model->doImageUpload($id, 'agenda_id', 'agenda', 'bild', $aBildUploadConfig);

                            // Wenn return_upload ein Fehler zurückgibt
                            if (strstr($return_upload, 'Fehler')) {
                                $data['message'] = $this->upload->display_errors();
                                parent::__renderAll($this->sControllerName, $data);
                            } // Wenn return_upload den Bildnamen zurückgibt
                            else {
                                $aData['bild'] = $return_upload;
                            }
                        }
                        if ($data['message'] == '') {
                            $this->Model->saveRecord($this->sTable, $aData, $item_id = $id, $id_field_name = 'agenda_id');
                            redirect(base_url() . 'redaktion/agenda/show/' . $id . '/' . $current_page . '/' . $account);
                        }

                        //wenn der editierte Benutzer der aktive Benuzter ist, dann wird die Session umgeschrieben
                        if ($id == $this->session->userdata('id')) {
                            $sess_update = array('username' => $this->input->post('benutzername', true));
                            $this->session->set_userdata($sess_update);

                        }
                    }
                }
                // Formular mit Values anzeigen
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
         * @author      parobri.ch
         * @date        20120719
         */
        public function show ($id = -1, $current_page = '', $account = 'show') {
            if ($id != -1) {
                //--------------------------------------//
                // zeigt den gespeicherten Eintrag an
                //--------------------------------------//

                // gewünschte Felder
                $fields      = 'agenda_id, datum, zeit, titel, ort, beschreibung, website, bild';
                $where_array = array('agenda_id'=> $id);

                // Datensatz herauslesen
                // $image_data             = $this->upload->data();
                $data = $this->Model->getRow($this->sTable, $fields, $where_array);
                //$data['label']          = 'Folgende Einträge wurden gespeichert';
                $data['back_text']   = 'zur Übersicht';
                $data['back_link']   = base_url() . $this->sUriUebersicht . 'index/' . $current_page;
                $data['submit_text'] = 'Eintrag bearbeiten';
                $data['submit_link'] = base_url() . 'redaktion/agenda/edit/' . $id . '/' . $current_page;
                $data['heading']     = '<span class="add-on"><i class="icon-eye-open"></i></span> Termininfos';
                //$data['uploadInfo']     = $image_data;
                //$data['thumbnail_name'] = $image_data['raw_name'] . '_thumb' . $image_data['file_ext'];


                if ($account == 'new') {
                    $data['heading'] = 'Der Termin wurde erstellt!';
                }

                if ($account == 'edit') {
                    $data['heading'] = 'Der Eintrag wurde aktualisiert!';
                }

                if ($account == 'delete') {
                    $data['heading']     = '<span class="add-on"><i class="icon-trash" alt="löschen" title="löschen"></i></span> Termin löschen';
                    $data['label']       = 'Diesen Termin löschen?';
                    $data['submit_text'] = 'Termin löschen';
                    $data['submit_link'] = base_url() . 'redaktion/agenda/delete/' . $id . '/' . $current_page . '/yes';
                    $data['back_text']   = 'abbrechen';
                }

                parent::__renderAll('agenda_show', $data);
            } else {
                // keine id ausgewählt -> Fehlermessage anzeigen
                $data['msg']      = 'keinen Termin ausgewählt';
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


            if ($id != '' && $execute == 'no') {
                $this->show($id, $current_page, 'delete');

            }
            elseif ($id != '' && $execute == 'yes') {

                //check ob Bild vorhanden
                $fields      = 'bild';
                $where_array = array('agenda_id'=> $id);
                $data        = $this->Model->getRow($this->sTable, $fields, $where_array);
                //Falls Bild vorhanden ->Bild löschen
                if ($data['bild'] != '') {
                    $this->Model->deleteImage($id, $sIdname = 'agenda_id', $sTable = 'agenda');
                }
                if ($this->Model->deleteRecord($this->sTable, $id, $this->sTable . '_id') === true) {
                    $data['msg']              = 'Termin gelöscht';
                    $data['link']             = base_url() . $this->sUriUebersicht;
                    $data['link_txt']         = 'zurück';
                    $this->headerData['meta'] = array('<meta http-equiv="refresh" content="2; URL=' .
                        base_url() . $this->sUriUebersicht . '">');
                    parent::__renderAll('message', $data);
                }
            } else {
                // keine id ausgewählt -> Fehlermeldung anzeigen
                $data['heading']  = 'Termin löschen:';
                $data['msg']      = 'keinen Termin ausgewählt';
                $data['link_txt'] = 'zurück';
                $data['link']     = base_url() . $this->sUriUebersicht;
                parent::__renderAll('message', $data);
            }

        }


        /**
         * name:        deleteImage
         *
         * delete image
         *
         * @param       integer  $id            User id
         * @param       integer  $current_page  of the overview pagination
         * @param
         *
         *
         * @author      parobri.ch
         * @date        20120719
         */
        public function deleteImage ($agenda_id = '', $current_page = '') {

            if ($agenda_id != '') {
                $this->Model->deleteImage($agenda_id, 'agenda_id', 'agenda');
                redirect(base_url() . 'redaktion/agenda/edit/' . $agenda_id . '/' . $current_page);
            }
        }


    }

/* End of file agenda.php */
/* Location: ./buchverwaltung/application/controllers/redaktion/agenda.php */