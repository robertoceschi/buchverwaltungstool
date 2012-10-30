<?php  !defined('BASEPATH') and exit('No direct script access allowed');


    require_once APPPATH . 'controllers/redaktion/adminpage_controller.php';

    class Autor extends Adminpage_controller {

        protected $sControllerName = '';
        protected $sTable = 'autor';
        protected $sUriUebersicht = 'redaktion/autorenuebersicht/';

        public function __construct () {
            //Controller Name in Kleinbuchstaben
            $this->sControllerName = strtolower(__CLASS__);
            parent::__construct($this->sControllerName, true, true);

            // h1 in header.php
            $this->headerData['heading'] = 'Autor bearbeiten';
        }


        /**
         * name:        index
         *
         * shows a blank form
         *
         *
         *
         *
         * @author      parobri.ch
         * @date        20120719
         */
        public function index ($account = 'other') {

            //--------------------------------------//
            // Leeres Formular ausgeben
            //--------------------------------------//
            $data['vorname']   = '';
            $data['nachname']  = '';
            $data['biografie'] = '';
            $data['website']   = '';
            $data ['bild']     = '';

            $data['message']     = '';
            $data['heading']     = 'Neuer Autor:';
            $data['back_link']   = base_url() . $this->sUriUebersicht;
            $data['autorenbild'] = 'Bild hochladen';
            if($this->session->userdata('buchdata')){
                $buchdata = $this->session->userdata('buchdata');
                $data['back_link']   = base_url() . $buchdata['url'];
            }
            $data['submit_text'] = 'Sichern und weiter';

            // Bildmasse und Typ werden definiert
            $data['bildmasse'] = $aBildUploadConfig = array('upload_path'   => './media/redaktion/images/' . $this->sTable . '/',
                                                            'allowed_types' => 'gif|jpg|png|jpeg',
                                                            'max_size'      => '2500',
                                                            'max_width'     => '1024',
                                                            'max_height'    => '1024',
                                                            'file_name'     => time() . '.jpg'
                                                        );

            //--------------------------------------//
            // erstellt einen neuen Autor
            //--------------------------------------//

            if ($this->input->post()) {


                //Check ob Form-Validierung korrekt
                if ($this->Model->validate_form() == false) {
                    //Wenn Validierung nicht okay, wird nochmals Methode index() aufgerufen und Formular wird nochmals angezeigt
                    parent::__renderAll($this->sControllerName, $data);
                } //Falls Validierung korrekt, wird der Eintrag in die DB geschrieben
                else {
                    // Daten auslesen
                    $aData = array('vorname'        => $this->input->post('vorname', true),
                                   'nachname'       => $this->input->post('nachname', true),
                                   'biografie'      => $this->input->post('biografie', true),
                                   'website'        => $this->input->post('website', true)
                    );


                    // Wenn ein neues Bild im Upload ist
                    if (isset($_FILES['userfile']['tmp_name']) && $_FILES['userfile']['tmp_name'] != '') {

                        // Upload durchführen: wenn ok ist Bildname im $return_upload sonst die Fehlermeldung
                        $return_upload = $this->Model->doImageUpload('', 'autor_id', 'autor', 'bild', $aBildUploadConfig);

                        // Wenn return_upload ein Fehler zurückgibt
                        if (strstr($return_upload, 'Fehler')) {
                            $data['message'] = $this->upload->display_errors();
                            parent::__renderAll($this->sControllerName, $data);
                        } // Wenn return_upload den Bildnamen zurückgibt
                        else {
                            $aData['bild'] = $return_upload;
                        }
                    }
                    // Wenn kein Fehler angezeigt wird, dann Einträge in der Tabelle speichern
                    if ($data['message'] == '') {
                        $this->Model->saveRecord($this->sTable, $aData);
                        $last_id = mysql_insert_id();




                        // wenn Buch-Daten in der Session sind diese ergänzen und zurückleiten
                        if($this->session->userdata('buchdata')){
                            $buchdata            = $this->session->userdata('buchdata');
                            $buchdata['autor'][] = $last_id;
                            $this->session->set_userdata('buchdata', $buchdata);
                            $buchdata = $this->session->userdata('buchdata');
                            redirect(base_url() . $buchdata['url']);
                        }
                        else{
                            redirect(base_url() . 'redaktion/autor/show/' . $last_id);
                        }
                    }
                }
            } else {

                //$data['message'] = 'Eintrag wurde bereits gespeichert.';
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

            if ($id != '') {
                // passender Eintrag holen
                $where_array = array('autor_id'=> $id);
                $data        = $this->Model->getRow($this->sTable, '', $where_array);

                $data['message']     = '';
                $data['heading']     = '<span class="add-on"><i class="icon-edit"></i></span> Autor editieren';
                $data['autorenbild'] = 'Bild hochladen';
                $data['submit_text'] = 'Änderung speichern';
                $data['back_link']   = base_url() . $this->sUriUebersicht . 'index/' . $current_page;

                // Bildmasse und Typ werden definiert
                $data['bildmasse'] = $aBildUploadConfig = array('upload_path'   => './media/redaktion/images/' . $this->sTable . '/',
                                                                'allowed_types' => 'gif|jpg|png|jpeg',
                                                                'max_size'      => '2500',
                                                                'max_width'     => '1024',
                                                                'max_height'    => '1024',
                                                                'file_name'     => time() . '.jpg'
                                                            );

                //----------------------------------------------//
                // Post-Einträge werden verarbeitet
                //----------------------------------------------//

                if ($this->input->post('submit')) {

                    // wenn Validierungsfehler entstehen Formular zurückgeben
                    if ($this->Model->validate_form() == false) {
                        parent::__renderAll($this->sControllerName, $data);
                    } // Wenn Validierung ok dann weiterverarbeiten
                    else {
                        $aData = array('vorname'      => $this->input->post('vorname', true),
                                       'nachname'     => $this->input->post('nachname', true),
                                       'biografie'    => $this->input->post('biografie', true),
                                       'website'      => $this->input->post('website', true)
                        );

                        // Wenn ein neues Bild im Upload ist
                        if (isset($_FILES['userfile']['tmp_name']) && $_FILES['userfile']['tmp_name'] != '') {

                            // Upload durchführen: wenn ok ist Bildname im $return_upload sonst die Fehlermeldung
                            $return_upload = $this->Model->doImageUpload($id, 'autor_id', 'autor', 'bild', $aBildUploadConfig);

                            // Wenn return_upload ein Fehler zurückgibt
                            if (strstr($return_upload, 'Fehler')) {
                                $data['message']    = strip_tags($this->upload->display_errors());
                                $data['uploaderror']= 'Fehler';
                                parent::__renderAll($this->sControllerName, $data);
                            } // Wenn return_upload den Bildnamen zurückgibt
                            else {
                                $aData['bild'] = $return_upload;
                            }
                        }
                        // Wenn kein Fehler angezeigt wird, dann Einträge in der Tabelle speichern
                        if ($data['message'] == '') {
                            $this->Model->saveRecord($this->sTable, $aData, $item_id = $id, $id_field_name = 'autor_id');
                            redirect(base_url() . 'redaktion/autor/show/' . $id . '/' . $current_page . '/' . $account);
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
         * @author      parobri.ch
         * @date        20120719
         */
        public function show ($id = -1, $current_page = '', $account = 'show') {
            if ($id != -1) {
                //--------------------------------------//
                // zeigt den gespeicherten Eintrag an
                //--------------------------------------//

                // gewünschte Felder
                $fields      = 'autor_id, vorname, nachname, biografie, website, bild';
                $where_array = array('autor_id'=> $id);

                // Datensatz herauslesen

                $data = $this->Model->getRow($this->sTable, $fields, $where_array);
                //$data['label']       = 'Folgende Einträge wurden gespeichert';
                $data['back_text']   = 'zur Übersicht';
                $data['back_link']   = base_url() . $this->sUriUebersicht . 'index/' . $current_page;
                $data['submit_text'] = 'Eintrag bearbeiten';
                $data['submit_link'] = base_url() . 'redaktion/autor/edit/' . $id . '/' . $current_page;
                $data['heading']     = '<span class="add-on"><i class="icon-eye-open" alt="anzeigen" title="anzeigen"></i></span> Autoreninfos';


                if ($account == 'new') {
                    $data['heading'] = 'Der Autor wurde erstellt!';
                    // $data['bild'] = $this->input->post('bild');

                }

                if ($account == 'edit') {
                    $data['heading'] = 'Der Eintrag wurde aktualisiert!';
                }
                parent::__renderAll('autor_show', $data);
            } else {
                // keine id ausgewählt -> Fehlermessage anzeigen
                $data['msg']      = 'keinen Autor ausgewählt';
                $data['link_txt'] = 'zur Übersicht';
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
         * @param
         *
         *
         * @author      parobri.ch
         * @date        20120719
         */
        public function delete ($id = '', $current_page = '') {

            //--------------------------------------//
            // zeigt Löschen Formular an
            //--------------------------------------//

            if ($id != '') {

                // Wenn Posteintrag vorhanden
                if($this->input->post('objekt', true) && $this->Model->validate_form(1) == true){

                    //Falls Bild vorhanden ->Bild löschen
                    if ($this->Model->getOne('autor','bild','autor_id = ' . $id) !='') {
                        $this->Model->deleteImage($id, 'autor_id', 'autor');
                    }

                    if ($this->Model->deleteRecord($this->sTable, $id, $this->sTable . '_id') === true) {
                        $data['heading'] = '';
                        $data['msg']      = 'Autor gelöscht';
                        $data['link']     = base_url() . $this->sUriUebersicht;
                        $data['link_txt'] = 'zurück';


                        $this->headerData['meta'] = array('<meta http-equiv="refresh" content="1; URL=' .
                            base_url() . $this->sUriUebersicht . 'index/' . $current_page . '">');
                        parent::__renderAll('message', $data);
                    }
                }
                else{
                    $bucharr = $this->Model->getRows('buch', 'buch.buchtitel, buch.buch_id', array('buch_autor.autor_id' => $id), array('buch_autor' => 'buch_autor.buch_id = buch.buch_id'));
                    // Prüfen, ob Bucheinträge vom Löschvorgang betroffen sind
                    if(count($bucharr)>0){

                        $data['description'] = 'Folgender Eintrag kann momentan nicht gelöscht werden: ';
                        $data['back_txt']    = 'abbrechen';
                        $data['back_link']   = base_url() . $this->sUriUebersicht;
                        $data['delete']      = 'delete';
                        $nachname            = $this->Model->getOne('autor', 'nachname', 'autor_id =' . $id);
                        $vorname             = $this->Model->getOne('autor', 'vorname', 'autor_id =' . $id);
                        $data['objekt']      = $vorname . ' ' . $nachname;
                        $data['description2']= 'Löschen oder ändern Sie zuerst folgende Bücher:';

                        $data['bucharr'] = $bucharr;
                    }
                    else{

                        $data['submit_text'] = 'Eintrag löschen';
                        $data['submit_link'] = '';
                        $data['description'] = 'Folgender Eintrag löschen: ';
                        $data['back_txt']    = 'abbrechen';
                        $data['back_link']   = base_url() . $this->sUriUebersicht;
                        $data['delete']      = 'delete';
                        $nachname            = $this->Model->getOne('autor', 'nachname', 'autor_id =' . $id);
                        $vorname             = $this->Model->getOne('autor', 'vorname', 'autor_id =' . $id);
                        $data['objekt']      = $vorname . ' ' . $nachname;
                    }

                    $data['heading']     = '<span class="add-on"><i class="icon-trash" alt="löschen" title="löschen"></i></span> Autor löschen';
                    parent::__renderAll('loeschen', $data);
                }
            }
            else {
                // keine id ausgewählt -> Fehlermeldung anzeigen
                $data['heading']  = 'Autor löschen:';
                $data['msg']      = 'kein Autor ausgewählt';
                $data['linktxt'] = 'zurück';
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
        public function deleteImage ($autor_id = '', $current_page = '') {
            if ($autor_id != '') {
                $this->Model->deleteImage($autor_id, 'autor_id', 'autor');
                redirect(base_url() . 'redaktion/autor/edit/' . $autor_id . '/' . $current_page);
            }
        }
    }

/* End of file autor.php */
/* Location: ./buchverwaltung/application/controllers/redaktion/autor.php */